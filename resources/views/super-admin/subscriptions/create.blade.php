@extends('layouts.app')

@section('content')
<div class="content-header pt-4 pb-3">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-8">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">📦 Terbitkan Paket Baru</h1>
                <p class="text-muted mb-0">Rancang paket langganan SaaS baru untuk ditawarkan kepada pengelola dapur/cabang.</p>
            </div>
            <div class="col-sm-4 text-right">
                <a href="{{ url('super-admin/subscriptions') }}" class="btn btn-default shadow-sm font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="{{ url('/super-admin/subscriptions') }}" method="POST">
            @csrf

            <div class="row">
                {{-- KOLOM KIRI (A. Informasi & B. Limit) --}}
                <div class="col-lg-7">
                    
                    {{-- CARD A. IDENTITAS --}}
                    <div class="card card-primary card-outline shadow-sm mb-4" style="border-top-width: 3px;">
                        <div class="card-header bg-white border-bottom-0 pb-0 pt-3">
                            <h4 class="card-title font-weight-bold text-dark">
                                <span class="bg-primary text-white rounded-circle d-inline-block text-center mr-2" style="width:28px;height:28px;line-height:28px;font-size:14px;">1</span>
                                Informasi Dasar & Harga
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Nama / Level Paket <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Cth: BASIC, PRO, ENTERPRISE" required autofocus>
                                @error('name')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                <small class="form-text text-muted">Nama ini akan tampil besar kepada pelanggan saat pendaftaran.</small>
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Deskripsi Persuasif</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2" placeholder="Cth: Cocok untuk bisnis kecil yang baru berkembang.">{{ old('description') }}</textarea>
                                @error('description')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold text-dark">Harga Tagihan (IDR) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text bg-light font-weight-bold">Rp</span></div>
                                            <input type="number" min="0" step="1000" name="price" class="form-control form-control-lg @error('price') is-invalid @enderror" value="{{ old('price', 0) }}" required>
                                            @error('price')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                        <small class="form-text text-muted">Isi <strong>0</strong> untuk paket GRATIS selamanya/trial.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold text-dark">Durasi Masa Aktif <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" min="1" name="duration_in_days" class="form-control form-control-lg @error('duration_in_days') is-invalid @enderror" value="{{ old('duration_in_days', 30) }}" required>
                                            <div class="input-group-append"><span class="input-group-text bg-light">Hari</span></div>
                                            @error('duration_in_days')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                        <small class="form-text text-muted">Bulan = 30, Tahun = 365.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD B. LIMIT DATA --}}
                    <div class="card card-warning card-outline shadow-sm mb-4" style="border-top-width: 3px;">
                        <div class="card-header bg-white border-bottom-0 pb-0 pt-3">
                            <h4 class="card-title font-weight-bold text-dark">
                                <span class="bg-warning text-dark rounded-circle d-inline-block text-center mr-2" style="width:28px;height:28px;line-height:28px;font-size:14px;">2</span>
                                Batasan Kapasitas Sistem (Limits)
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-light border border-warning text-dark small mb-4">
                                <i class="fas fa-info-circle text-warning mr-1"></i> <strong>Penting:</strong> Isi nilai batas dengan angka <strong>0</strong> jika Anda ingin memberikan akses <strong>Tidak Terbatas (Unlimited)</strong>.
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark">Maks Akun Staff <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-users"></i></span></div>
                                            <input type="number" min="0" name="max_users" class="form-control @error('max_users') is-invalid @enderror" value="{{ old('max_users', 1) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark">Maks Item Barang <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-boxes"></i></span></div>
                                            <input type="number" min="0" name="max_items" class="form-control @error('max_items') is-invalid @enderror" value="{{ old('max_items', 100) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark">Transaksi / Bln <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-receipt"></i></span></div>
                                            <input type="number" min="0" name="max_transactions_per_month" class="form-control @error('max_transactions_per_month') is-invalid @enderror" value="{{ old('max_transactions_per_month', 500) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN (C. MODULES & SAVE) --}}
                <div class="col-lg-5">
                    
                    {{-- CARD C. MODUL / FITUR FLAGS --}}
                    <div class="card card-success card-outline shadow-sm mb-4" style="border-top-width: 3px;">
                        <div class="card-header bg-white border-bottom-0 pb-0 pt-3">
                            <h4 class="card-title font-weight-bold text-dark">
                                <span class="bg-success text-white rounded-circle d-inline-block text-center mr-2" style="width:28px;height:28px;line-height:28px;font-size:14px;">3</span>
                                Hak Akses Modul Inti
                            </h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-4">Centang fitur yang akan dibuka atau bisa diakses oleh pelanggan pada paket ini. Modul yang tidak dicentang akan disembunyikan/dikunci dari dashboard pelanggan.</p>

                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mb-3 p-3 bg-light rounded border">
                                <input type="checkbox" class="custom-control-input" id="has_sales" name="has_sales" checked>
                                <label class="custom-control-label font-weight-bold" for="has_sales" style="cursor: pointer;">Penjualan & Kasir (POS)</label>
                                <div class="text-muted small mt-1 ml-3" style="display:block;">Pencatatan kasir, riwayat penjualan, dan struk.</div>
                            </div>
                            
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mb-3 p-3 bg-light rounded border">
                                <input type="checkbox" class="custom-control-input" id="has_inventory" name="has_inventory" checked>
                                <label class="custom-control-label font-weight-bold" for="has_inventory" style="cursor: pointer;">Manajemen Stok (Inventory)</label>
                                <div class="text-muted small mt-1 ml-3" style="display:block;">Data barang, pergerakan stok in/out, bahan baku resep.</div>
                            </div>

                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mb-3 p-3 bg-light rounded border">
                                <input type="checkbox" class="custom-control-input" id="has_procurement" name="has_procurement">
                                <label class="custom-control-label font-weight-bold" for="has_procurement" style="cursor: pointer;">Pengadaan (Procurement/PO)</label>
                                <div class="text-muted small mt-1 ml-3" style="display:block;">Purchase order ke supplier dan penerimaan barang masuk.</div>
                            </div>

                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mb-3 p-3 bg-light rounded border">
                                <input type="checkbox" class="custom-control-input" id="has_accounting_full" name="has_accounting_full">
                                <label class="custom-control-label font-weight-bold" for="has_accounting_full" style="cursor: pointer;">Akuntansi Full & Laporan</label>
                                <div class="text-muted small mt-1 ml-3" style="display:block;">Jurnal umum, buku besar, neraca, dan rugi laba.</div>
                            </div>

                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mb-3 p-3 bg-light rounded border">
                                <input type="checkbox" class="custom-control-input" id="has_budgeting" name="has_budgeting">
                                <label class="custom-control-label font-weight-bold" for="has_budgeting" style="cursor: pointer;">Sistem Anggaran (Budgeting)</label>
                                <div class="text-muted small mt-1 ml-3" style="display:block;">Perencanaan dan monitoring pemakaian anggaran dapur.</div>
                            </div>

                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mb-3 p-3 bg-light rounded border">
                                <input type="checkbox" class="custom-control-input" id="has_hr" name="has_hr">
                                <label class="custom-control-label font-weight-bold" for="has_hr" style="cursor: pointer;">HRM & Penggajian (Payroll)</label>
                                <div class="text-muted small mt-1 ml-3" style="display:block;">Data staff, hitung gaji bulanan dan slip gaji elektronik.</div>
                            </div>

                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mb-3 p-3 bg-light rounded border">
                                <input type="checkbox" class="custom-control-input" id="has_circle_menu" name="has_circle_menu">
                                <label class="custom-control-label font-weight-bold" for="has_circle_menu" style="cursor: pointer;">Distribusi Menu Circle (MBG)</label>
                                <div class="text-muted small mt-1 ml-3" style="display:block;">Jadwal makan harian, porsi, lokasi, dan bukti dokumentasi gratis.</div>
                            </div>

                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success p-3 bg-light rounded border">
                                <input type="checkbox" class="custom-control-input" id="can_export" name="can_export">
                                <label class="custom-control-label font-weight-bold" for="can_export" style="cursor: pointer;">Export Laporan (Excel/PDF)</label>
                                <div class="text-muted small mt-1 ml-3" style="display:block;">Izin mengunduh raw data ke komputer lokal.</div>
                            </div>

                        </div>
                    </div>

                    {{-- CARD D. PBLISHING ACTIONS --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-body bg-light rounded px-4 py-4 text-center">
                            <div class="custom-control custom-switch mb-4 text-left d-inline-block">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                                <label class="custom-control-label font-weight-bold text-dark pt-1" style="font-size: 1.1rem; cursor: pointer;" for="is_active">
                                    Aktif & Tampilkan di Form Reg!
                                </label>
                            </div>
                            
                            <hr class="mt-0 mb-4">
                            
                            <button type="submit" class="btn btn-primary btn-lg btn-block shadow font-weight-bold mb-2">
                                <i class="fas fa-save mr-2"></i> Terbitkan Paket Baru
                            </button>
                            <a href="{{ url('super-admin/subscriptions') }}" class="btn btn-outline-secondary btn-block">Batal</a>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</section>
@endsection
