@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Master Barang & Stok</h1>
                <p class="text-sm text-muted mb-0">Kelola daftar stok bahan baku untuk operasional dapur.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0 text-sm">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard', tenant('id')) }}"><i class="fas fa-home"></i> Dasbor</a></li>
                    <li class="breadcrumb-item active">Data Barang</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Summary Widgets -->
        <div class="row mb-3">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box shadow-none border">
                    <span class="info-box-icon bg-info elevation-0"><i class="fas fa-boxes"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted text-xs uppercase font-weight-bold">Total Item</span>
                        <span class="info-box-number h5 mb-0">{{ $totalItems }} <small class="font-weight-normal text-muted">Barang</small></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box shadow-none border">
                    <span class="info-box-icon bg-warning elevation-0"><i class="fas fa-exclamation-triangle text-white"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted text-xs uppercase font-weight-bold">Stok Rendah</span>
                        <span class="info-box-number h5 mb-0 text-warning">{{ $lowStockCount }} <small class="font-weight-normal text-muted font-italic">Peringatan</small></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box shadow-none border">
                    <span class="info-box-icon bg-danger elevation-0"><i class="fas fa-times-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted text-xs uppercase font-weight-bold">Stok Habis</span>
                        <span class="info-box-number h5 mb-0 text-danger">{{ $outOfStockCount }} <small class="font-weight-normal text-muted">Item</small></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="py-2">
                    <a href="{{ route('inventory.items.create', tenant('id')) }}" class="btn btn-primary btn-block py-3 rounded shadow-sm font-weight-bold">
                        <i class="fas fa-plus-circle mr-2"></i> TAMBAH BARANG BARU
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        @endif

        <div class="card card-outline card-primary shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="card-title font-weight-bold text-dark mb-0">List Master Barang</h5>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <input type="text" id="tableSearch" class="form-control" placeholder="Cari nama atau kode barang...">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="itemTable">
                        <thead class="bg-light">
                            <tr class="text-muted text-xs text-uppercase">
                                <th class="border-0 px-4 py-3" style="width: 15%">Kode</th>
                                <th class="border-0 py-3">Nama Barang</th>
                                <th class="border-0 py-3" style="width: 10%">Satuan</th>
                                <th class="border-0 py-3 text-right" style="width: 12%">Stok Saat Ini</th>
                                <th class="border-0 py-3 text-right" style="width: 12%">Min. Stok</th>
                                <th class="border-0 py-3 text-center" style="width: 12%">Status</th>
                                <th class="border-0 px-4 py-3 text-right" style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                            <tr>
                                <td class="px-4 align-middle">
                                    <span class="badge badge-light border font-weight-bold text-primary px-2 py-1">{{ $item->code }}</span>
                                </td>
                                <td class="align-middle">
                                    <div class="font-weight-bold text-dark">{{ $item->name }}</div>
                                    <small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                </td>
                                <td class="align-middle"><span class="text-muted">{{ $item->unit }}</span></td>
                                <td class="text-right align-middle">
                                    <span class="font-weight-bold {{ $item->stock <= $item->minimum_stock ? 'text-danger' : 'text-dark' }}">
                                        {{ number_format($item->stock) }}
                                    </span>
                                </td>
                                <td class="text-right align-middle text-muted">{{ number_format($item->minimum_stock) }}</td>
                                <td class="text-center align-middle">
                                    @if($item->stock <= 0)
                                        <span class="badge badge-pill badge-danger py-1 px-3">OUT OF STOCK</span>
                                    @elseif($item->stock <= $item->minimum_stock)
                                        <span class="badge badge-pill badge-warning py-1 px-3">LOW STOCK</span>
                                    @else
                                        <span class="badge badge-pill badge-success py-1 px-3">AVAILABLE</span>
                                    @endif
                                </td>
                                <td class="px-4 text-right align-middle">
                                    <div class="btn-group">
                                        <a href="{{ route('inventory.items.edit', [tenant('id'), $item->id]) }}" class="btn btn-xs btn-outline-info rounded-circle mx-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('inventory.items.destroy', [tenant('id'), $item->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus barang ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger rounded-circle" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-box-open fa-4x text-light mb-3"></i>
                                        <h5 class="text-muted font-weight-normal">Belum ada data barang dalam inventory.</h5>
                                        <a href="{{ route('inventory.items.create', tenant('id')) }}" class="btn btn-primary btn-sm mt-3 px-4 rounded-pill">Tambah Sekarang</a>
                                    </div>
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

@push('js')
<script>
    $(document).ready(function() {
        $("#tableSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#itemTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endpush
@endsection
