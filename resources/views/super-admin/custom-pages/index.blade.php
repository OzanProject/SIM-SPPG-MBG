@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">
                    <i class="fas fa-file-alt text-primary mr-2"></i>Halaman Kustom
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('custom-pages.create') }}" class="btn btn-primary font-weight-bold shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Halaman
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-top-0 border-bottom-0" width="5%">No</th>
                                <th class="border-top-0 border-bottom-0">Judul Halaman</th>
                                <th class="border-top-0 border-bottom-0">URL Slug</th>
                                <th class="border-top-0 border-bottom-0 text-center">Status Aktif</th>
                                <th class="border-top-0 border-bottom-0 text-center">Tampil di Footer</th>
                                <th class="border-top-0 border-bottom-0 text-center" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pages as $index => $page)
                            <tr>
                                <td class="align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle font-weight-bold">{{ $page->title }}</td>
                                <td class="align-middle">
                                    <code>/page/{{ $page->slug }}</code>
                                    <a href="/page/{{ $page->slug }}" target="_blank" class="text-xs ml-1" title="Lihat Halaman"><i class="fas fa-external-link-alt"></i></a>
                                </td>
                                <td class="align-middle text-center">
                                    @if($page->is_active)
                                        <span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle mr-1"></i>Aktif</span>
                                    @else
                                        <span class="badge badge-danger px-3 py-2"><i class="fas fa-times-circle mr-1"></i>Nonaktif</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    @if($page->show_in_footer)
                                        <span class="badge badge-info px-3 py-2"><i class="fas fa-shoe-prints mr-1"></i>Ya</span>
                                    @else
                                        <span class="badge badge-secondary px-3 py-2">Tidak</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('custom-pages.edit', $page->id) }}" class="btn btn-sm btn-info shadow-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('custom-pages.destroy', $page->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus halaman ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <img src="/adminlte3/dist/img/AdminLTELogo.png" style="opacity: 0.1; height: 50px;" class="mb-3 d-block mx-auto">
                                    Belum ada halaman kustom. Klik tombol <strong>Tambah Halaman</strong> untuk memulai.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($pages->count() > 0)
            <div class="card-footer bg-white border-top">
                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Total: {{ $pages->count() }} halaman terdaftar.</small>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
