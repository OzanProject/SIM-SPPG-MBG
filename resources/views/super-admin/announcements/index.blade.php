@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">Pengumuman Global</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/dashboard') }}"><i class="fas fa-home text-primary"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active">Pengumuman</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header d-flex align-items-center bg-white py-3">
                <h3 class="card-title font-weight-bold text-dark mb-0">Daftar Pengumuman</h3>
                <div class="ml-auto">
                    <a href="{{ route('announcements.create') }}" class="btn btn-primary font-weight-bold shadow-sm">
                        <i class="fas fa-plus mr-1"></i> Buat Pengumuman
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="pl-4">Judul</th>
                                <th>Tipe</th>
                                <th>Target Paket</th>
                                <th class="text-center">Modal/Popup</th>
                                <th class="text-center">Persistence</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Berakhir</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($announcements as $announcement)
                                <tr>
                                    <td class="pl-4 font-weight-bold text-dark">{{ $announcement->title }}</td>
                                    <td>{!! $announcement->type_badge !!}</td>
                                    <td>
                                        @if($announcement->target_plan)
                                            <span class="badge badge-secondary">{{ strtoupper($announcement->target_plan) }}</span>
                                        @else
                                            <span class="text-muted font-italic">Semua Paket</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($announcement->show_modal)
                                            <i class="fas fa-check-circle text-success" title="Ya"></i>
                                        @else
                                            <i class="fas fa-times-circle text-muted" title="Tidak"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($announcement->is_persistent)
                                            <span class="badge badge-primary">Sering</span>
                                        @else
                                            <span class="badge badge-light">Sekali</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($announcement->is_active)
                                            <span class="badge badge-success px-3 py-1" style="border-radius:12px;">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary px-3 py-1" style="border-radius:12px;">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-muted">
                                        {{ $announcement->expires_at ? $announcement->expires_at->format('d/m/Y') : 'Selamanya' }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('announcements.edit', $announcement->id) }}" class="btn btn-warning btn-sm shadow-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengumuman ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm shadow-sm" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-bullhorn mb-3" style="font-size: 3rem; display: block;"></i>
                                        <p>Belum ada pengumuman yang dibuat.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
