@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Edit Data Barang</h1>
                <p class="text-sm text-muted mb-0">Perbarui informasi barang: <strong>{{ $item->name }}</strong></p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0 text-sm">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard', tenant('id')) }}"><i class="fas fa-home"></i> Dasbor</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.items.index', tenant('id')) }}">Data Barang</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card card-outline card-info shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title font-weight-bold mb-0 text-info"><i class="fas fa-sync-alt mr-2"></i> Perbarui Data: {{ $item->code }}</h5>
                    </div>
                    <form action="{{ route('inventory.items.update', [tenant('id'), $item->id]) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="card-body p-4 bg-light">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted">Kode Barang <span class="text-danger">*</span></label>
                                        <input type="text" name="code" class="form-control border-0 shadow-sm @error('code') is-invalid @enderror" value="{{ old('code', $item->code) }}" required style="border-radius: 8px;">
                                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted">Satuan <span class="text-danger">*</span></label>
                                        <input type="text" name="unit" class="form-control border-0 shadow-sm @error('unit') is-invalid @enderror" value="{{ old('unit', $item->unit) }}" required style="border-radius: 8px;">
                                        @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control border-0 shadow-sm @error('name') is-invalid @enderror" value="{{ old('name', $item->name) }}" required style="border-radius: 8px;">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted">Stok Minimum <span class="text-danger">*</span></label>
                                        <input type="number" name="minimum_stock" class="form-control border-0 shadow-sm @error('minimum_stock') is-invalid @enderror" value="{{ old('minimum_stock', $item->minimum_stock) }}" required min="0" style="border-radius: 8px;">
                                        @error('minimum_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted">Stok Saat Ini</label>
                                        <div class="form-control border-0 bg-white" style="border-radius: 8px;">{{ number_format($item->stock) }} {{ $item->unit }}</div>
                                        <small class="text-info font-italic">Stok hanya bisa diubah melalui modul Pergerakan Stok.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Keterangan / Deskripsi</label>
                                <textarea name="description" class="form-control border-0 shadow-sm" rows="3" style="border-radius: 8px;">{{ old('description', $item->description) }}</textarea>
                            </div>
                        </div>
                        <div class="card-footer bg-white py-4">
                            <div class="row">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <a href="{{ route('inventory.items.index', tenant('id')) }}" class="btn btn-outline-secondary btn-block rounded-pill font-weight-bold">
                                        <i class="fas fa-times mr-2"></i> BATAL
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-info btn-block rounded-pill shadow font-weight-bold text-white">
                                        <i class="fas fa-sync-alt mr-2"></i> UPDATE DATA BARANG
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Danger Zone -->
            <div class="col-md-3">
                <div class="card card-outline card-danger shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="card-title font-weight-bold text-danger mb-0 text-sm"><i class="fas fa-trash-alt mr-2"></i> Hapus Barang</h5>
                    </div>
                    <div class="card-body text-xs text-muted">
                        <p class="mb-3">Menghapus barang akan memindahkannya ke tempat sampah. Barang tidak akan terlihat lagi di menu stok.</p>
                        <form action="{{ route('inventory.items.destroy', [tenant('id'), $item->id]) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus barang ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger btn-block rounded-pill">Hapus Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
