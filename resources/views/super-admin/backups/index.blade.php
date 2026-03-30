@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size: 1.8rem;">
                    <i class="fas fa-database text-warning mr-2"></i> Database Backup
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <form action="{{ route('backups.create') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary shadow-sm font-weight-bold rounded-pill px-4">
                        <i class="fas fa-plus-circle mr-1"></i> Buat Backup Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="card-title font-weight-bold text-dark mb-0">Daftar Backup Terakhir</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="pl-4">Nama File</th>
                                <th>Ukuran</th>
                                <th>Waktu Backup</th>
                                <th class="text-center" width="300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($backups as $b)
                                <tr>
                                    <td class="pl-4 font-weight-bold text-primary">
                                        <i class="fas fa-file-archive text-warning mr-2"></i> {{ $b['name'] }}
                                    </td>
                                    <td><span class="badge badge-outline-secondary">{{ $b['size'] }}</span></td>
                                    <td>{{ $b['date'] }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('backups.download', $b['name']) }}" class="btn btn-sm btn-success shadow-sm" title="Download">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                            <form action="{{ route('backups.restore', $b['name']) }}" method="POST" class="d-inline" onsubmit="return confirm('PERINGATAN: Restore akan menimpa data saat ini. Lanjutkan?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger shadow-sm mx-1" title="Restore Data">
                                                    <i class="fas fa-history"></i> Restore
                                                </button>
                                            </form>
                                            <form action="{{ route('backups.destroy', $b['name']) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus file backup ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-secondary shadow-sm" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="fas fa-database fa-3x text-light mb-3"></i>
                                        <p class="text-muted">Belum ada file backup yang tersedia.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="alert alert-info shadow-sm border-0 mt-3">
            <h5><i class="icon fas fa-info-circle"></i> Catatan Penting</h5>
            <ul class="mb-0">
                <li>Backup berisi database sistem dan seluruh konfigurasi saat ini.</li>
                <li>Sangat disarankan untuk mendownload file backup ke penyimpanan luar (Flashdisk/Cloud) secara berkala.</li>
                <li>Gunakan fitur Restore hanya jika terjadi kendala serius pada data aplikasi.</li>
            </ul>
        </div>

        <div class="text-right mb-4">
            <button type="button" class="btn btn-warning shadow-sm font-weight-bold" data-toggle="modal" data-target="#restoreModal">
                <i class="fas fa-question-circle mr-1"></i> Panduan Restore Data
            </button>
        </div>
    </div>
</section>

<!-- Restore Modal -->
<div class="modal fade" id="restoreModal" tabindex="-1" role="dialog" aria-labelledby="restoreModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning">
                <h5 class="modal-title font-weight-bold" id="restoreModalLabel">
                    <i class="fas fa-history mr-2"></i> Panduan Restore Database
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-dark">
                <p>Untuk mengembalikan data (Restore) dari file backup:</p>
                <ol>
                    <li class="mb-2"><strong>Cara Instan:</strong> Klik tombol <span class="badge badge-danger">Restore</span> pada daftar backup di atas. Aplikasi akan otomatis mengekstrak dan memulihkan database.</li>
                    <li class="mb-2"><strong>Cara Manual (Jika Dashboard Tidak Akses):</strong>
                        <ul>
                            <li>Download file <code>.zip</code> backup.</li>
                            <li>Ekstrak di komputer Anda untuk mendapatkan file <code>.sql</code>.</li>
                            <li>Buka <strong>phpMyAdmin</strong> atau <strong>Laragon Database</strong>.</li>
                            <li>Pilih database aplikasi Anda.</li>
                            <li>Gunakan menu <strong>Import</strong> dan pilih file <code>.sql</code> tersebut.</li>
                        </ul>
                    </li>
                </ol>
                <div class="alert alert-warning mb-0">
                    <small>
                        <i class="fas fa-exclamation-triangle mr-1"></i> 
                        <strong>PERHATIAN:</strong> Proses restore akan <strong>menimpa/menghapus</strong> data yang ada saat ini dengan data dari backup. Pastikan Anda sudah mem-backup data terbaru sebelum melakukan restore manual.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
