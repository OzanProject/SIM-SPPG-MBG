@extends('layouts.app')

@push('css')
<style>
.config-group-title {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #6c757d;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 8px;
    margin-bottom: 20px;
}
.config-badge { font-size: 0.7rem; vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">Konfigurasi Aplikasi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/dashboard') }}"><i class="fas fa-home text-primary"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active">Config Aplikasi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="alert alert-info bg-light border-info shadow-sm d-flex align-items-start mb-4">
            <i class="fas fa-info-circle text-info mr-3 mt-1" style="font-size: 1.3rem;"></i>
            <div>
                <strong>Integrasi Realtime:</strong> Setiap perubahan di sini akan langsung diterapkan ke <strong>nama aplikasi di tab browser</strong>, <strong>brand sidebar</strong>, <strong>versi di footer</strong>, <strong>logo</strong>, dan semua komponen global lainnya tanpa perlu deploy ulang.
            </div>
        </div>

        <form action="{{ url('/super-admin/config') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                {{-- PANEL KIRI: General + Appearance --}}
                <div class="col-lg-8">

                    {{-- GENERAL --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-cog text-primary mr-2"></i> Informasi Umum Aplikasi</h3>
                        </div>
                        <div class="card-body">
                            @if(isset($configs['general']))
                                <div class="row">
                                    @foreach($configs['general'] as $cfg)
                                        <div class="col-md-{{ in_array($cfg->type, ['textarea', 'select']) ? '12' : '6' }} mb-3">
                                            <label class="font-weight-bold text-dark text-sm">{{ $cfg->label }}</label>
                                            @if($cfg->type === 'textarea')
                                                <textarea name="{{ $cfg->key }}" class="form-control" rows="2">{{ $cfg->value }}</textarea>

                                            @elseif($cfg->type === 'select' && $cfg->key === 'timezone')
                                                {{-- Dropdown Zona Waktu --}}
                                                @php
                                                    // Indonesia + Asia paling atas, lalu semua zone lainnya
                                                    $priorityZones = [
                                                        'Asia/Jakarta'      => 'WIB — Asia/Jakarta (UTC+7)',
                                                        'Asia/Makassar'     => 'WITA — Asia/Makassar (UTC+8)',
                                                        'Asia/Jayapura'     => 'WIT — Asia/Jayapura (UTC+9)',
                                                        'Asia/Singapore'    => 'Asia/Singapore (UTC+8)',
                                                        'Asia/Kuala_Lumpur' => 'Asia/Kuala_Lumpur (UTC+8)',
                                                        'Asia/Bangkok'      => 'Asia/Bangkok (UTC+7)',
                                                        'Asia/Tokyo'        => 'Asia/Tokyo (UTC+9)',
                                                        'Asia/Hong_Kong'    => 'Asia/Hong_Kong (UTC+8)',
                                                        'Asia/Karachi'      => 'Asia/Karachi (UTC+5)',
                                                        'Asia/Kolkata'      => 'Asia/Kolkata (UTC+5:30)',
                                                        'Asia/Dubai'        => 'Asia/Dubai (UTC+4)',
                                                        'UTC'               => 'UTC (UTC+0)',
                                                        'Europe/London'     => 'Europe/London',
                                                        'Europe/Paris'      => 'Europe/Paris (UTC+1)',
                                                        'America/New_York'  => 'America/New_York (UTC-5)',
                                                        'America/Los_Angeles' => 'America/Los_Angeles (UTC-8)',
                                                        'Australia/Sydney'  => 'Australia/Sydney (UTC+11)',
                                                    ];
                                                    $allZones = \DateTimeZone::listIdentifiers();
                                                @endphp
                                                <select name="{{ $cfg->key }}" class="form-control select2" style="width:100%;">
                                                    <optgroup label="🇮🇩 Indonesia &amp; Asia Populer">
                                                        @foreach($priorityZones as $tz => $label)
                                                            <option value="{{ $tz }}" {{ $cfg->value === $tz ? 'selected' : '' }}>{{ $label }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="── Semua Zona Waktu ──">
                                                        @foreach($allZones as $tz)
                                                            @if(!array_key_exists($tz, $priorityZones))
                                                                <option value="{{ $tz }}" {{ $cfg->value === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                                <small class="text-muted mt-1 d-block">
                                                    <i class="fas fa-clock mr-1 text-primary"></i>
                                                    Zona waktu aktif: <strong>{{ $cfg->value }}</strong> —
                                                    Jam sekarang: <strong>{{ now()->format('H:i:s, d M Y') }}</strong>
                                                </small>

                                            @else
                                                <input type="{{ $cfg->type }}" name="{{ $cfg->key }}" class="form-control" value="{{ $cfg->value }}">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- CONTACT --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-address-card text-success mr-2"></i> Kontak & Alamat</h3>
                        </div>
                        <div class="card-body">
                            @if(isset($configs['contact']))
                                <div class="row">
                                    @foreach($configs['contact'] as $cfg)
                                        <div class="col-md-{{ in_array($cfg->type, ['textarea']) ? '12' : '6' }} mb-3">
                                            <label class="font-weight-bold text-dark text-sm">{{ $cfg->label }}</label>
                                            @if($cfg->type === 'textarea')
                                                <textarea name="{{ $cfg->key }}" class="form-control" rows="2">{{ $cfg->value }}</textarea>
                                            @else
                                                <input type="{{ $cfg->type }}" name="{{ $cfg->key }}" class="form-control" value="{{ $cfg->value }}">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- SOCIAL MEDIA --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-share-alt text-warning mr-2"></i> Media Sosial</h3>
                        </div>
                        <div class="card-body">
                            @if(isset($configs['social']))
                                <div class="row">
                                    @foreach($configs['social'] as $cfg)
                                        <div class="col-md-12 mb-3">
                                            <label class="font-weight-bold text-dark text-sm">{{ $cfg->label }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i class="fas fa-link text-muted"></i></span>
                                                </div>
                                                <input type="url" name="{{ $cfg->key }}" class="form-control" value="{{ $cfg->value }}" placeholder="https://...">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- PANEL KANAN: Appearance + Save --}}
                <div class="col-lg-4">

                    {{-- APPEARANCE --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-paint-brush text-danger mr-2"></i> Tampilan & Branding</h3>
                        </div>
                        <div class="card-body">

                            {{-- UPLOAD LOGO --}}
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark text-sm">Logo Aplikasi</label>
                                <div class="text-center mb-3">
                                    <img id="logoPreview"
                                        src="{{ $appConfig->get('logo_url', '/adminlte3/dist/img/AdminLTELogo.png') }}"
                                        alt="Logo Preview"
                                        style="height: 70px; border-radius: 8px; border: 2px dashed #dee2e6; padding: 8px; background: #f8f9fa;">
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="logo_upload" name="logo_upload" accept="image/*" onchange="previewImage(this, 'logoPreview', 'logoFileName')">
                                    <label class="custom-file-label" id="logoFileName" for="logo_upload">Pilih file logo baru (PNG, JPG, SVG)...</label>
                                </div>
                                <small class="text-muted">Ukuran rekomendasi: 100x100px atau persegi. Biarkan kosong jika tidak ingin mengubah logo.</small>
                                {{-- disimpan nilai URL lama sebagai hidden jika tidak diupload ulang --}}
                                <input type="hidden" name="logo_url" value="{{ $appConfig->get('logo_url', '') }}">
                            </div>

                            {{-- UPLOAD FAVICON --}}
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark text-sm">Favicon Browser</label>
                                <div class="text-center mb-3">
                                    <img id="faviconPreview"
                                        src="{{ $appConfig->get('favicon_url', '/adminlte3/dist/img/AdminLTELogo.png') }}"
                                        alt="Favicon Preview"
                                        style="height: 40px; border-radius: 4px; border: 2px dashed #dee2e6; padding: 4px; background: #f8f9fa;">
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="favicon_upload" name="favicon_upload" accept="image/*" onchange="previewImage(this, 'faviconPreview', 'faviconFileName')">
                                    <label class="custom-file-label" id="faviconFileName" for="favicon_upload">Pilih file favicon baru (ICO, PNG)...</label>
                                </div>
                                <small class="text-muted">Ukuran rekomendasi: 32x32px atau 64x64px. Biarkan kosong jika tidak ingin mengubah favicon.</small>
                                <input type="hidden" name="favicon_url" value="{{ $appConfig->get('favicon_url', '') }}">
                            </div>

                            {{-- APPEARANCE OTHER configs (color, theme, etc.) --}}
                            @if(isset($configs['appearance']))
                                @foreach($configs['appearance'] as $cfg)
                                    @if(!in_array($cfg->key, ['logo_url', 'favicon_url']))
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold text-dark text-sm">{{ $cfg->label }}</label>
                                            @if($cfg->type === 'color')
                                                <div class="d-flex align-items-center">
                                                    <input type="color" name="{{ $cfg->key }}" class="form-control mr-2" value="{{ $cfg->value }}" style="width: 60px; height: 38px; padding: 2px;">
                                                    <input type="text" class="form-control" value="{{ $cfg->value }}" readonly>
                                                </div>
                                            @else
                                                <input type="{{ $cfg->type }}" name="{{ $cfg->key }}" class="form-control" value="{{ $cfg->value }}">
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- PRATINJAU REAL-TIME --}}
                    <div class="card shadow-sm border-primary mb-4">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-eye mr-2"></i> Pratinjau Integrasi</h3>
                        </div>
                        <div class="card-body bg-light text-sm">
                            <p class="mb-2"><i class="fas fa-tab text-muted mr-2"></i><strong>Tab Browser:</strong> <code>{{ $appConfig->get('app_name', 'MBG AkunPro') }}</code></p>
                            <p class="mb-2"><i class="fas fa-tag text-muted mr-2"></i><strong>Versi Footer:</strong> <code>{{ $appConfig->get('app_version', 'v1.0.0') }}</code></p>
                            <p class="mb-2"><i class="fas fa-copyright text-muted mr-2"></i><strong>Copyright:</strong> <code>© {{ $appConfig->get('copyright_year', '2026') }} {{ $appConfig->get('company_name', '-') }}</code></p>
                            <p class="mb-0"><i class="fas fa-image text-muted mr-2"></i><strong>Logo:</strong>
                                <img src="{{ $appConfig->get('logo_url', '/adminlte3/dist/img/AdminLTELogo.png') }}" style="height: 24px;" class="ml-1 rounded">
                            </p>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow font-weight-bold">
                            <i class="fas fa-save mr-2"></i> Simpan & Terapkan Semua Konfigurasi
                        </button>
                        <p class="text-muted text-center mt-2" style="font-size: 0.8rem;"><i class="fas fa-bolt mr-1"></i> Perubahan langsung aktif di seluruh halaman.</p>
                    </div>

                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('js')
<script>
function previewImage(input, previewId, labelId) {
    const label = document.getElementById(labelId);
    if (input.files && input.files[0]) {
        const file = input.files[0];
        // Update label text
        label.textContent = file.name;
        // Live preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
