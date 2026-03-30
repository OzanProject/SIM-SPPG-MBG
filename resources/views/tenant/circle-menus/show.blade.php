@extends('layouts.app')

@section('content')
<div class="content-header pt-4 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 text-dark font-weight-bold" style="font-size: 1.5rem;">
                    <i class="fas fa-eye text-primary mr-2"></i> Detail Rencana Menu
                </h4>
                <p class="text-muted mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Informasi distribusi Makanan Bergizi Gratis (MBG)</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('tenant.circle-menus.index', tenant('id')) }}" class="btn btn-default shadow-sm font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                </a>
                <a href="{{ route('tenant.circle-menus.edit', [tenant('id'), $menu->id]) }}" class="btn btn-warning shadow-sm font-weight-bold ml-1">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content mb-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                {{-- DETAIL CARD --}}
                <div class="card shadow-sm border-0 rounded-lg overflow-hidden mb-4">
                    <div class="card-header bg-primary py-3">
                        <h5 class="card-title text-white font-weight-bold mb-0">
                            <i class="fas fa-file-alt mr-2"></i> Laporan Distribusi: {{ $menu->target_date->format('d M Y') }}
                        </h5>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted text-uppercase font-weight-bold d-block mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">📅 Hari / Tanggal</small>
                                <h6 class="font-weight-bold text-dark mb-0">{{ $menu->target_date->translatedFormat('l') }}, {{ $menu->target_date->format('d F Y') }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted text-uppercase font-weight-bold d-block mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">🏫 Lokasi Tujuan</small>
                                <h6 class="font-weight-bold text-dark mb-0 font-weight-bold"><i class="fas fa-school text-primary mr-1"></i> {{ $menu->location_name }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted text-uppercase font-weight-bold d-block mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">🔢 Volume Produksi</small>
                                <h4 class="font-weight-black text-primary mb-0 font-weight-bold">{{ number_format($menu->total_portions) }} <small class="text-muted">PORSI</small></h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted text-uppercase font-weight-bold d-block mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">🛡️ Status Eksekusi</small>
                                {!! $menu->status_badge !!}
                            </div>
                        </div>

                        <hr class="my-4" style="border-top: 1px dashed rgba(0,0,0,.15);">

                        <div class="mb-0">
                            <small class="text-muted text-uppercase font-weight-bold d-block mb-3" style="font-size: 0.7rem; letter-spacing: 1px;">🍱 Daftar Menu Harian (Komposisi)</small>
                            <div class="row">
                                @foreach($menu->menu_items as $item)
                                    <div class="col-md-6 mb-2">
                                        <div class="p-3 bg-light rounded border d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success mr-2"></i>
                                            <span class="font-weight-bold text-dark">{{ $item }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                {{-- DOKUMENTASI CARD --}}
                <div class="card shadow-sm border-0 rounded-lg overflow-hidden h-100">
                    <div class="card-header bg-success py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title text-white font-weight-bold mb-0">
                            <i class="fas fa-camera mr-2"></i> Bukti Dokumentasi
                        </h5>
                    </div>
                    <div class="card-body p-4 bg-white text-center d-flex flex-column align-items-center justify-content-center">
                        @if($menu->documentation_photo)
                            <div class="documentation-preview mb-0 w-100">
                                <img src="{{ global_asset('storage/' . $menu->documentation_photo) }}" alt="Dokumentasi MBG" class="img-fluid rounded-lg shadow-sm border w-100" style="max-height: 400px; object-fit: cover;">
                                <p class="text-muted small mt-3 italic text-center">
                                    <i class="fas fa-info-circle mr-1"></i> Foto di atas adalah bukti sah porsi makanan telah sampai di lokasi sekolah.
                                </p>
                                <a href="{{ route('tenant.circle-menus.edit', [tenant('id'), $menu->id]) }}" class="btn btn-outline-success btn-sm btn-block mt-3">
                                    <i class="fas fa-exchange-alt mr-1"></i> Ganti Foto Dokumentasi
                                </a>
                            </div>
                        @else
                            <div class="p-5 text-muted">
                                <i class="fas fa-images fa-4x mb-3 opacity-25"></i>
                                <h6 class="font-weight-bold text-dark">Belum Ada Dokumentasi</h6>
                                <p class="small">Silakan unggah foto sebagai bukti penyelesaian distribusi menu ini.</p>
                                <a href="{{ route('tenant.circle-menus.edit', [tenant('id'), $menu->id]) }}" class="btn btn-success btn-sm px-4 shadow-sm mt-2">
                                    <i class="fas fa-upload mr-1 text-sm"></i> Unggah Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
