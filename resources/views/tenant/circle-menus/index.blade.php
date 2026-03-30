@extends('layouts.app')

@section('content')
<div class="content-header pt-4 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">
                    <i class="fas fa-truck-loading text-primary mr-2"></i> Distribusi Menu Circle
                </h1>
                <p class="text-muted mb-0 small">Manajemen rencana makan harian dan dokumentasi distribusi ke sekolah/lokasi.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('tenant.circle-menus.create', tenant('id')) }}" class="btn btn-primary shadow-sm font-weight-bold">
                    <i class="fas fa-plus mr-1"></i> Rencana Baru
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light text-muted small text-uppercase font-weight-bold">
                            <tr>
                                <th class="pl-4 py-3 border-0" style="width: 15%">Tanggal</th>
                                <th class="py-3 border-0" style="width: 25%">Lokasi / Sekolah</th>
                                <th class="py-3 border-0 text-center" style="width: 10%">Porsi</th>
                                <th class="py-3 border-0" style="width: 25%">Daftar Menu</th>
                                <th class="py-3 border-0 text-center" style="width: 15%">Status</th>
                                <th class="py-3 border-0 text-right pr-4" style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $menu)
                                <tr>
                                    <td class="pl-4 align-middle font-weight-bold text-dark">
                                        {{ $menu->target_date->format('d M Y') }}
                                        <div class="small text-muted font-weight-normal">{{ $menu->target_date->translatedFormat('l') }}</div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="badge badge-light p-2 border">
                                            <i class="fas fa-school text-primary mr-1"></i> {{ $menu->location_name }}
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="font-weight-bold text-primary" style="font-size: 1.1rem;">{{ number_format($menu->total_portions) }}</span>
                                        <div class="small text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Porsi</div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="text-sm">
                                            @foreach(array_slice($menu->menu_items, 0, 3) as $item)
                                                <span class="badge badge-outline-secondary font-weight-normal mb-1">
                                                    <i class="fas fa-check-circle text-xs text-success mr-1"></i> {{ $item }}
                                                </span>
                                            @endforeach
                                            @if(count($menu->menu_items) > 3)
                                                <small class="text-muted ml-1">+{{ count($menu->menu_items) - 3 }} lainnya</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        {!! $menu->status_badge !!}
                                    </td>
                                    <td class="align-middle text-right pr-4">
                                        <div class="btn-group">
                                            <a href="{{ route('tenant.circle-menus.show', [tenant('id'), $menu->id]) }}" class="btn btn-default btn-sm shadow-sm" title="Detail & Dokumentasi">
                                                <i class="fas fa-eye text-primary"></i>
                                            </a>
                                            <a href="{{ route('tenant.circle-menus.edit', [tenant('id'), $menu->id]) }}" class="btn btn-default btn-sm shadow-sm" title="Edit">
                                                <i class="fas fa-edit text-warning"></i>
                                            </a>
                                            <form action="{{ route('tenant.circle-menus.destroy', [tenant('id'), $menu->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus rencana menu ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-default btn-sm shadow-sm" title="Hapus">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="p-5">
                                            <i class="fas fa-truck-loading fa-4x text-muted mb-3 opacity-25"></i>
                                            <h5 class="text-dark font-weight-bold">Belum Ada Rencana Menu</h5>
                                            <p class="text-muted">Buat rencana distribusi menu makanan bergizi pertama Anda sekarang.</p>
                                            <a href="{{ route('tenant.circle-menus.create', tenant('id')) }}" class="btn btn-primary shadow-sm">
                                                <i class="fas fa-plus mr-1"></i> Mulai Membuat Rencana
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($menus->hasPages())
                <div class="card-footer bg-white border-top-0 pt-3 pb-3">
                    <div class="d-flex justify-content-center">
                        {{ $menus->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<style>
    .badge-outline-secondary {
        border: 1px solid #dee2e6;
        background: transparent;
        color: #495057;
        padding: 4px 10px;
        border-radius: 6px;
    }
</style>
@endsection
