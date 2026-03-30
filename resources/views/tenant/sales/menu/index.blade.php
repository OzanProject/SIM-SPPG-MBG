@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
                    <i class="fas fa-utensils mr-2 text-primary"></i> Daftar Menu
                </h1>
                <p class="text-muted text-sm mb-0">Kelola hidangan dan harga jual dapur Anda.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('tenant.menu.create', tenant('id')) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Menu Baru
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content pt-2">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm border-0" style="border-radius:10px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 pl-4">Kode</th>
                                <th class="border-0">Nama Menu</th>
                                <th class="border-0">Kategori</th>
                                <th class="border-0 text-right">Harga Jual</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-right pr-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $menu)
                            <tr>
                                <td class="pl-4 font-weight-bold">{{ $menu->code }}</td>
                                <td>{{ $menu->name }}</td>
                                <td>
                                    <span class="badge badge-secondary badge-pill px-2">
                                        {{ ucfirst($menu->category) }}
                                    </span>
                                </td>
                                <td class="text-right"><strong>{{ $menu->formatted_price }}</strong></td>
                                <td class="text-center">
                                    @if($menu->is_available)
                                        <span class="badge badge-success">Tersedia</span>
                                    @else
                                        <span class="badge badge-danger">Habis</span>
                                    @endif
                                </td>
                                <td class="text-right pr-4">
                                    <a href="{{ route('tenant.menu.recipe.index', [tenant('id'), $menu->id]) }}" class="btn btn-outline-primary btn-xs mr-1" title="Kelola Resep (BOM)">
                                        <i class="fas fa-layer-group"></i> Resep
                                    </a>
                                    <a href="{{ route('tenant.menu.edit', [tenant('id'), $menu->id]) }}" class="btn btn-info btn-xs">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('tenant.menu.destroy', [tenant('id'), $menu->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus menu ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-utensils fa-3x mb-3 text-light"></i>
                                    <p>Belum ada menu yang ditambahkan.</p>
                                    <a href="{{ route('tenant.menu.create', tenant('id')) }}" class="btn btn-primary btn-sm">Mulai Tambah Menu</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
