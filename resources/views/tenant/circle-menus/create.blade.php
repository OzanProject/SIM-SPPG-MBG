@extends('layouts.app')

@section('content')
<div class="content-header pt-4 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 text-dark font-weight-bold" style="font-size: 1.5rem;">
                    <i class="fas fa-plus-circle text-primary mr-2"></i> Buat Rencana Menu Baru
                </h4>
                <p class="text-muted mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Informasi Distribusi Makanan Bergizi Gratis (MBG)</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('tenant.circle-menus.index', tenant('id')) }}" class="btn btn-default shadow-sm font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content mb-5">
    <div class="container-fluid">
        <form action="{{ route('tenant.circle-menus.store', tenant('id')) }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-outline card-primary shadow-sm rounded-lg" style="border-top-width: 3px;">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                            <h5 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-info-circle text-primary mr-2"></i> Detail Produksi & Lokasi
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark mb-1">📅 Tanggal Distribusi <span class="text-danger">*</span></label>
                                    <input type="date" name="target_date" class="form-control form-control-lg @error('target_date') is-invalid @enderror" value="{{ old('target_date', date('Y-m-d')) }}" required>
                                    @error('target_date')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark mb-1">🏫 Nama Lokasi / Sekolah <span class="text-danger">*</span></label>
                                    <input type="text" name="location_name" class="form-control form-control-lg @error('location_name') is-invalid @enderror" value="{{ old('location_name') }}" placeholder="Cth: SD Negeri 01 Ciracas" required>
                                    @error('location_name')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark mb-1">🔢 Jumlah Target Porsi <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="total_portions" class="form-control form-control-lg @error('total_portions') is-invalid @enderror" value="{{ old('total_portions', 0) }}" min="1" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text font-weight-bold bg-light">PORSI</span>
                                        </div>
                                        @error('total_portions')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" style="border-top: 1px dashed rgba(0,0,0,.1);">

                            <div class="form-group mb-0">
                                <label class="font-weight-bold text-dark mb-2">🥗 Daftar Menu Makanan <span class="text-danger">*</span></label>
                                <textarea name="menu_items" class="form-control @error('menu_items') is-invalid @enderror" rows="6" placeholder="Masukkan daftar menu (pisahkan per baris)...&#10;Contoh:&#10;• Nasi Putih&#10;• Steak Ayam Fillet&#10;• Tumis Kol dan Wortel&#10;• Buah Strawberry" required>{{ old('menu_items') }}</textarea>
                                @error('menu_items')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                <p class="text-muted small mt-2 ml-1">
                                    <i class="fas fa-lightbulb text-warning mr-1"></i> Masukkan setiap item menu dalam baris baru agar tampil teratur di daftar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-outline card-primary shadow-sm rounded-lg" style="border-top-width: 3px;">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                            <h5 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-check-double text-primary mr-2"></i> Terbitkan Rencana
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-info border-0 shadow-xs mb-4">
                                <i class="fas fa-info-circle mr-2 opacity-75"></i> 
                                <small>Rencana yang diterbitkan akan berstatus <strong>Draft</strong>. Anda dapat mengunggah bukti dokumentasi penyaluran nanti setelah makanan sampai di lokasi.</small>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg btn-block shadow-sm font-weight-bold mb-2 py-3">
                                <i class="fas fa-save mr-2"></i> Simpan & Terbitkan
                            </button>
                            <a href="{{ route('tenant.circle-menus.index', tenant('id')) }}" class="btn btn-default btn-block py-2 font-weight-bold text-muted">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
