<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupController extends Controller
{
    public function index()
    {
        $backupPath = 'backups';
        
        // Pastikan folder ada di disk local
        if (!Storage::disk('local')->exists($backupPath)) {
            Storage::disk('local')->makeDirectory($backupPath);
        }

        // Ambil semua file di folder backups pada disk local
        $files = Storage::disk('local')->files($backupPath);
        $backups = [];

        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => $this->formatSize(Storage::disk('local')->size($file)),
                'date' => Carbon::createFromTimestamp(Storage::disk('local')->lastModified($file))->format('d M Y H:i:s'),
                'raw_date' => Storage::disk('local')->lastModified($file)
            ];
        }

        // Urutkan berdasarkan waktu (terbaru di atas)
        usort($backups, function($a, $b) {
            return $b['raw_date'] <=> $a['raw_date'];
        });

        return view('super-admin.backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbHost = config('database.connections.mysql.host');
            $dbPass = config('database.connections.mysql.password');
            $dbPort = config('database.connections.mysql.port', '3306');

            $timestamp   = Carbon::now()->format('Y-m-d-H-i-s');
            $sqlFilename = "db-backup-{$timestamp}.sql";
            $zipFilename = "backup-{$timestamp}.zip";

            if (!Storage::disk('local')->exists('backups')) {
                Storage::disk('local')->makeDirectory('backups');
            }

            $sqlPath = Storage::disk('local')->path('backups/' . $sqlFilename);
            $zipPath = Storage::disk('local')->path('backups/' . $zipFilename);

            // === Deteksi binary: utamakan mariadb-dump, fallback ke mysqldump ===
            $dumpBin = $this->findBinary(['mariadb-dump', 'mysqldump']);
            if (!$dumpBin) {
                throw new \Exception('Binary mysqldump / mariadb-dump tidak ditemukan di server.');
            }

            // Bangun perintah dump — stderr dibuang ke /dev/null agar warning tidak masuk ke file SQL
            $passPart = $dbPass ? "-p" . escapeshellarg($dbPass) : '';
            $cmd = sprintf(
                '%s -h %s -P %s -u %s %s %s > %s 2>/dev/null',
                escapeshellcmd($dumpBin),
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                $passPart,
                escapeshellarg($dbName),
                escapeshellarg($sqlPath)
            );

            exec($cmd, $cmdOut, $retCode);

            // Jika masih gagal atau file kosong, coba sekali lagi tanpa escapeshellcmd
            if ($retCode !== 0 || !file_exists($sqlPath) || filesize($sqlPath) === 0) {
                throw new \Exception('Gagal membuat dump database. Exit code: ' . $retCode);
            }

            // === Bersihkan warning MariaDB dari hasil dump ===
            $this->stripMariadbWarnings($sqlPath);

            // === Zip SQL + folder uploads ===
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('Gagal membuat file ZIP.');
            }
            $zip->addFile($sqlPath, $sqlFilename);
            $publicStorage = storage_path('app/public');
            if (is_dir($publicStorage)) {
                $this->addFolderToZip($publicStorage, $zip, 'files');
            }
            $zip->close();
            @unlink($sqlPath);

            return redirect()->back()->with('success', "Backup berhasil dibuat: {$zipFilename}");

        } catch (\Exception $e) {
            \Log::error('Backup Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal backup: ' . $e->getMessage());
        }
    }


    private function addFolderToZip($folder, &$zip, $zipSubFolder)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folder, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = $zipSubFolder . '/' . substr($filePath, strlen($folder) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    public function download($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        if (file_exists($path)) {
            return response()->download($path);
        }
        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    public function destroy($filename)
    {
        if (Storage::disk('local')->exists('backups/' . $filename)) {
            Storage::disk('local')->delete('backups/' . $filename);
            return redirect()->back()->with('success', 'Backup berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    public function restore($filename)
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbHost = config('database.connections.mysql.host');
            $dbPass = config('database.connections.mysql.password');
            $dbPort = config('database.connections.mysql.port', '3306');

            if (!Storage::disk('local')->exists('backups/' . $filename)) {
                throw new \Exception('File backup tidak ditemukan.');
            }

            $zipPath = Storage::disk('local')->path('backups/' . $filename);
            $backupDir = dirname($zipPath);

            // 1. Ekstrak SQL dari ZIP
            $zip = new \ZipArchive();
            $sqlFilename = null;
            if ($zip->open($zipPath) === TRUE) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $entryName = $zip->getNameIndex($i);
                    if (str_ends_with($entryName, '.sql')) {
                        $sqlFilename = basename($entryName);
                        // Ekstrak ke folder backups (bukan subdir)
                        copy("zip://{$zipPath}#{$entryName}", $backupDir . '/' . $sqlFilename);
                        break;
                    }
                }
                $zip->close();
            }

            if (!$sqlFilename) {
                throw new \Exception('File SQL tidak ditemukan di dalam paket ZIP.');
            }

            $sqlPath = $backupDir . DIRECTORY_SEPARATOR . $sqlFilename;

            // 2. Bersihkan warning lines dari MariaDB/mysqldump sebelum restore
            $this->stripMariadbWarnings($sqlPath);

            // 3. Deteksi binary restore: utamakan mariadb, fallback ke mysql
            $mysqlBin = $this->findBinary(['mariadb', 'mysql']);
            if (!$mysqlBin) {
                throw new \Exception('Binary mysql / mariadb tidak ditemukan di server.');
            }

            $passPart = $dbPass ? '-p' . escapeshellarg($dbPass) : '';
            $cmd = sprintf(
                '%s -h %s -P %s -u %s %s %s < %s 2>&1',
                escapeshellcmd($mysqlBin),
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                $passPart,
                escapeshellarg($dbName),
                escapeshellarg($sqlPath)
            );

            exec($cmd, $output, $retCode);
            @unlink($sqlPath);

            if ($retCode !== 0) {
                // Filter out warning-only outputs (non-fatal)
                $errors = array_filter($output, fn($l) => stripos($l, 'Warning') === false && stripos($l, 'Deprecated') === false && trim($l) !== '');
                if (!empty($errors)) {
                    throw new \Exception('Restore gagal: ' . implode("\n", $errors));
                }
            }

            return redirect()->back()->with('success', 'Database berhasil dipulihkan dari: ' . $filename);

        } catch (\Exception $e) {
            \Log::error('Restore Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Restore gagal: ' . $e->getMessage());
        }
    }


    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Cari binary yang tersedia dari daftar kandidat.
     * Kembalikan path lengkap atau false jika tidak ada.
     */
    private function findBinary(array $candidates): string|false
    {
        // Lokasi umum di cPanel/VPS/shared hosting
        $searchPaths = [
            '/usr/bin',
            '/usr/local/bin',
            '/usr/local/mariadb/bin',
            '/opt/plesk/mariadb/bin',
        ];

        foreach ($candidates as $bin) {
            // Cek apakah ada di PATH dengan which
            $which = trim(shell_exec("which {$bin} 2>/dev/null") ?? '');
            if ($which && file_exists($which)) {
                return $which;
            }
            // Fallback: cari manual di folder umum
            foreach ($searchPaths as $dir) {
                $full = $dir . '/' . $bin;
                if (file_exists($full) && is_executable($full)) {
                    return $full;
                }
            }
        }
        return false;
    }

    /**
     * Hapus baris-baris warning/deprecation dari MariaDB/mysqldump
     * agar file SQL bersih dan siap di-restore.
     */
    private function stripMariadbWarnings(string $filePath): void
    {
        if (!file_exists($filePath)) return;

        $lines   = file($filePath, FILE_IGNORE_NEW_LINES);
        $cleaned = [];

        foreach ($lines as $line) {
            // Buang baris yang mengandung warning dari mariadb-dump/mysqldump
            if (preg_match('/^(mysqldump|mariadb-dump|mysql|mariadb):\s*(Deprecated|Warning)/i', $line)) {
                continue;
            }
            // Buang baris "/*M!999999 enable the sandbox mode */" yang menyebabkan error syntax
            if (preg_match('/\/\*M!999999\s+enable the sandbox mode\s*\*\//', $line)) {
                continue;
            }
            $cleaned[] = $line;
        }

        file_put_contents($filePath, implode("\n", $cleaned));
    }
}
