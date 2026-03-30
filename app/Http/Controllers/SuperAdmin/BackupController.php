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
            // Pengaturan DB
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbHost = config('database.connections.mysql.host');
            $dbPass = config('database.connections.mysql.password');
            $dbPort = config('database.connections.mysql.port', '3306');
            
            $timestamp = Carbon::now()->format('Y-m-d-H-i-s');
            $sqlFilename = "db-backup-{$timestamp}.sql";
            $zipFilename = "backup-{$timestamp}.zip";
            
            // Gunakan disk local untuk manajemen path agar konsisten
            if (!Storage::disk('local')->exists('backups')) {
                Storage::disk('local')->makeDirectory('backups');
            }

            $sqlPath = Storage::disk('local')->path('backups/' . $sqlFilename);
            $zipPath = Storage::disk('local')->path('backups/' . $zipFilename);

            // 1. Eksekusi mysqldump
            $passwordPart = $dbPass ? "-p\"$dbPass\"" : "";
            
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $command = "mysqldump -h $dbHost -P $dbPort -u $dbUser $passwordPart $dbName > \"$sqlPath\"";
            } else {
                $command = "mysqldump -h $dbHost -P $dbPort -u $dbUser $passwordPart $dbName > '$sqlPath' 2>&1";
            }

            exec($command, $output, $returnVar);

            if ($returnVar !== 0 || !file_exists($sqlPath) || filesize($sqlPath) === 0) {
                $errorMsg = "Gagal dump database. ";
                if (!empty($output)) {
                    $errorMsg .= implode("\n", $output);
                } else {
                    $errorMsg .= "Pastikan mysqldump terinstall dan ada di dalam PATH.";
                }
                throw new \Exception($errorMsg);
            }

            // 2. Zip file SQL dan Folder Uploads
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
                $zip->addFile($sqlPath, $sqlFilename);
                
                $publicStorage = storage_path('app/public');
                if (file_exists($publicStorage)) {
                    $this->addFolderToZip($publicStorage, $zip, 'files');
                }
                
                $zip->close();
                @unlink($sqlPath);
            } else {
                throw new \Exception("Gagal membuat file ZIP.");
            }

            return redirect()->back()->with('success', 'Backup database & file berhasil dibuat: ' . $zipFilename);
            
        } catch (\Exception $e) {
            \Log::error("Backup Error: " . $e->getMessage());
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
            // Pengaturan DB
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbHost = config('database.connections.mysql.host');
            $dbPass = config('database.connections.mysql.password');
            $dbPort = config('database.connections.mysql.port', '3306');
            
            if (!Storage::disk('local')->exists('backups/' . $filename)) {
                throw new \Exception("File backup tidak ditemukan di disk local.");
            }

            $zipPath = Storage::disk('local')->path('backups/' . $filename);
            $backupDir = dirname($zipPath);


            // 1. Ekstrak file SQL dari ZIP
            $zip = new \ZipArchive();
            $sqlFilename = null;
            if ($zip->open($zipPath) === TRUE) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $entryName = $zip->getNameIndex($i);
                    if (str_ends_with($entryName, '.sql')) {
                        $sqlFilename = $entryName;
                        $zip->extractTo($backupDir, $entryName);
                        break;
                    }
                }
                $zip->close();
            }

            if (!$sqlFilename) {
                throw new \Exception("File SQL tidak ditemukan di dalam paket ZIP.");
            }

            $sqlPath = $backupDir . DIRECTORY_SEPARATOR . $sqlFilename;

            // 2. Eksekusi Restore
            $passwordPart = $dbPass ? "-p\"$dbPass\"" : "";
            
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $command = "mysql -h $dbHost -P $dbPort -u $dbUser $passwordPart $dbName < \"$sqlPath\"";
            } else {
                $command = "mysql -h $dbHost -P $dbPort -u $dbUser $passwordPart $dbName < '$sqlPath' 2>&1";
            }

            exec($command, $output, $returnVar);

            // Hapus file SQL hasil ekstrak
            @unlink($sqlPath);

            if ($returnVar !== 0) {
                throw new \Exception("Gagal restore. Pesan: " . (isset($output[0]) ? implode("\n", $output) : 'Unknown error'));
            }

            return redirect()->back()->with('success', 'Database berhasil dipulihkan dari: ' . $filename);

        } catch (\Exception $e) {
            \Log::error("Restore Error: " . $e->getMessage());
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
}
