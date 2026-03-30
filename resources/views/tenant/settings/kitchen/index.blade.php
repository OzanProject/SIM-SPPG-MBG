@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Profil Dapur</h1>
                <p class="text-sm text-muted mb-0">Atur identitas dan informasi kontak dapur Anda.</p>
            </div>
        </div>
    </div>
</div>

<section class="content pb-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title font-weight-bold text-dark mb-0">Informasi Dasar</h5>
                    </div>
                    <form action="{{ route('settings.kitchen.update', tenant('id')) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body py-0">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="small font-weight-bold text-muted">Nama Dapur / Cabang</label>
                                    <input type="text" name="name" class="form-control rounded-pill px-3 shadow-sm" value="{{ tenant('name') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="small font-weight-bold text-muted">Telepon / WhatsApp</label>
                                    <input type="text" name="phone" class="form-control rounded-pill px-3 shadow-sm" value="{{ tenant('phone') }}" placeholder="0812...">
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label class="small font-weight-bold text-muted">Alamat Lengkap</label>
                                <textarea name="address" class="form-control shadow-sm" style="border-radius: 12px;" rows="3">{{ tenant('address') }}</textarea>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="form-group pb-4">
                                <label class="small font-weight-bold text-muted d-block">Logo Dapur</label>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3 border rounded p-2 bg-white shadow-xs" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        @if(tenant('logo_url'))
                                            <img src="{{ url('storage/' . tenant('logo_url')) }}" alt="Logo" style="max-width: 100%; max-height: 100%;">
                                        @else
                                            <i class="fas fa-store fa-2x text-muted"></i>
                                        @endif
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="logo" class="custom-file-input" id="customFile">
                                        <label class="custom-file-label rounded-pill shadow-sm" for="customFile" style="overflow: hidden;">Pilih logo baru...</label>
                                    </div>
                                </div>
                                <p class="text-xs text-muted mt-2">Format: JPG, PNG. Maksimal 2MB.</p>
                            </div>
                        </div>
                        <div class="card-footer bg-white py-4 text-right border-0 px-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-lg font-weight-bold">
                                <i class="fas fa-save mr-2"></i> SIMPAN PENGATURAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-primary shadow-lg border-0" style="border-radius: 15px; background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                    <div class="card-body p-4 text-white">
                        <h5 class="font-weight-bold mb-3">Informasi Sistem</h5>
                        <div class="mb-3 border-bottom border-white-50 pb-2">
                            <label class="small text-white-50 d-block mb-0">Nama Terdaftar</label>
                            <span class="font-weight-bold">{{ tenant('name') ?? 'Belum Diatur' }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="small text-white-50 d-block mb-0">ID Dapur (Slug)</label>
                            <span class="font-weight-bold text-monospace">{{ tenant('id') }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="small text-white-50 d-block mb-0">Domain Utama</label>
                            <span class="font-weight-bold">{{ request()->getHost() }}</span>
                        </div>
                        <div class="mb-0">
                            <label class="small text-white-50 d-block mb-1">Status Layanan</label>
                            @php
                                $isExpired = tenant('ready_at') && now()->isAfter(tenant('ready_at')); // Contoh logic status
                            @endphp
                            <span class="badge badge-pill badge-light text-primary px-3 py-1 font-weight-bold shadow-sm">
                                {{ tenant('status') ?? 'AKTIF' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow-sm border-0 mt-4" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-info-circle text-primary mr-2"></i> Bantuan</h6>
                        <p class="text-sm text-muted">
                            Informasi identitas dapur akan digunakan pada **Kop Laporan**, **Form PO**, dan **Profil Dashboard**. Pastikan data sudah akurat.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
    // Update label custom-file-input
    $('#customFile').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
@endpush
