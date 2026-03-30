@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
                    <i class="fas fa-layer-group mr-2 text-primary"></i> Manajemen Resep (BOM)
                </h1>
                <p class="text-muted text-sm mb-0">Kelola komposisi bahan baku (Bill of Materials) untuk semua menu Anda.</p>
            </div>
        </div>
    </div>
</div>

<section class="content pt-2">
    <div class="container-fluid">
        <div class="card shadow-sm border-0" style="border-radius:10px;">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="pl-4">Nama Menu</th>
                            <th>Kategori</th>
                            <th>Status Resep</th>
                            <th>Ringkasan Bahan</th>
                            <th class="text-right pr-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                        <tr>
                            <td class="pl-4 align-middle">
                                <strong>{{ $menu->name }}</strong>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-light text-muted">{{ $menu->category ?? '-' }}</span>
                            </td>
                            <td class="align-middle">
                                @if($menu->recipes_count > 0)
                                    <span class="badge badge-success px-2"><i class="fas fa-check-circle mr-1"></i> {{ $menu->recipes_count }} Bahan</span>
                                @else
                                    <span class="badge badge-warning px-2"><i class="fas fa-exclamation-circle mr-1"></i> Belum Diatur</span>
                                @endif
                            </td>
                            <td class="align-middle text-sm text-muted">
                                @if($menu->recipes_count > 0)
                                    {{ $menu->recipes->take(2)->map(fn($r) => $r->inventoryItem->name)->implode(', ') }}
                                    @if($menu->recipes_count > 2) ... @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right pr-4 align-middle">
                                <a href="{{ route('tenant.menu.recipe.index', [tenant('id'), $menu->id]) }}" class="btn btn-primary btn-sm rounded-pill px-3 font-weight-bold">
                                    <i class="fas fa-edit mr-1"></i> Edit Resep
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
