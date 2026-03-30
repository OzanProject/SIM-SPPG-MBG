@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Tambah Barang Baru</h1>
                <p class="text-sm text-muted mb-0">Daftarkan bahan baku atau barang baru ke sistem dapur.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0 text-sm">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard', tenant('id')) }}"><i class="fas fa-home"></i> Dasbor</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.items.index', tenant('id')) }}">Data Barang</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card card-outline card-primary shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title font-weight-bold mb-0 text-primary"><i class="fas fa-edit mr-2"></i> Formulir Master Barang</h5>
                    </div>
                    <form action="{{ route('inventory.items.store', tenant('id')) }}" method="POST">
                        @csrf
                        <div class="card-body p-4 bg-light">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted">Kode Barang <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-barcode text-muted text-xs"></i></span>
                                            </div>
                                            <input type="text" name="code" class="form-control border-left-0 shadow-none @error('code') is-invalid @enderror" value="{{ old('code') }}" required placeholder="Contoh: BRG-001" style="border-radius: 0 8px 8px 0;">
                                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted">Satuan <span class="text-danger">*</span></label>
                                        <input type="text" name="unit" class="form-control border-0 shadow-sm @error('unit') is-invalid @enderror" value="{{ old('unit') }}" required placeholder="Kg, Gram, Liter, Pcs, dll" style="border-radius: 8px;">
                                        @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Nama Barang / Bahan <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control border-0 shadow-sm @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Contoh: Daging Sapi Tenderloin" style="border-radius: 8px;">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted">Stok Awal</label>
                                        <div class="input-group">
                                            <input type="number" name="stock" class="form-control border-0 shadow-sm @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" style="border-radius: 8px 0 0 8px;">
                                            <div class="input-group-append">
                                                <span class="input-group-text bg-white border-0 text-muted">Qty</span>
                                            </div>
                                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <small class="text-muted italic">Stok yang ada saat ini di dapur.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted">Stok Minimum <span class="text-danger">*</span></label>
                                        <input type="number" name="minimum_stock" class="form-control border-0 shadow-sm @error('minimum_stock') is-invalid @enderror" value="{{ old('minimum_stock', 5) }}" required min="0" style="border-radius: 8px;">
                                        @error('minimum_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <small class="text-muted">Batas aman stok sebelum muncul peringatan.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Keterangan / Deskripsi</label>
                                <textarea name="description" class="form-control border-0 shadow-sm" rows="3" placeholder="Informasi tambahan mengenai barang ini..." style="border-radius: 8px;">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="card-footer bg-white py-4">
                            <div class="row">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <a href="{{ route('inventory.items.index', tenant('id')) }}" class="btn btn-outline-secondary btn-block rounded-pill font-weight-bold">
                                        <i class="fas fa-arrow-left mr-2"></i> KEMBALI
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-block rounded-pill shadow font-weight-bold">
                                        <i class="fas fa-save mr-2"></i> SIMPAN BARANG
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-3 d-none d-lg-block">
                <div class="card card-outline card-info shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title font-weight-bold text-info mb-0 text-sm"><i class="fas fa-lightbulb mr-2"></i> TIPS</h5>
                    </div>
                    <div class="card-body text-xs text-muted leading-relaxed">
                        <p class="mb-3"><strong>Kode Barang</strong>: Gunakan format yang konsisten (misal: BAHAN-01) untuk memudahkan pencarian.</p>
                        <p class="mb-3"><strong>Satuan</strong>: Gunakan satuan terkecil yang sering digunakan (misal: Gram bukannya Kg jika sering dipakai sedikit-sedikit).</p>
                        <p class="mb-0 text-italic">Data barang ini akan terhubung dengan modul Pengadaan & Pemakaian Stok.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
