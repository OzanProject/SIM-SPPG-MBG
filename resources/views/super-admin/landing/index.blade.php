@extends('layouts.app')

@push('css')
<style>
/* ── Tab Nav ────────────────────────────────── */
.ls-tab-nav { display:flex; flex-wrap:wrap; gap:6px; margin-bottom:1.5rem; }
.ls-tab-btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:7px 16px; border-radius:8px; font-size:.82rem; font-weight:700;
    color:#6c757d; background:transparent; border:1px solid #dee2e6;
    cursor:pointer; transition:all .2s;
}
.ls-tab-btn:hover  { background:#f8f9fa; color:#495057; }
.ls-tab-btn.active { background:#007bff; color:#fff; border-color:#007bff; }

/* ── Pane ───────────────────────────────────── */
.ls-pane { display:none; }
.ls-pane.active { display:block; }

/* ── Group divider ──────────────────────────── */
.group-label {
    font-size:.7rem; font-weight:800; letter-spacing:.12em;
    text-transform:uppercase; color:#adb5bd; margin:1.5rem 0 .75rem;
    padding-bottom:.5rem; border-bottom:1px dashed #e9ecef;
}
.group-label:first-child { margin-top:0; }

/* ── Branding card ──────────────────────────── */
.branding-card { background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%); border-radius:8px; }
.branding-item {
    display:flex; align-items:center; padding:10px 0;
    border-bottom:1px solid rgba(255,255,255,.07);
}
.branding-item:last-child { border-bottom:none; }
</style>
@endpush

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size:1.8rem;">
                    <i class="fas fa-layer-group text-primary mr-2"></i>Landing Page Settings
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/dashboard') }}"><i class="fas fa-home text-primary"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active">Landing Page Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- ── Flash Messages ──────────────────────────────── --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            </div>
        @endif

        <div class="row">

            {{-- ══ KOLOM KIRI: Form ════════════════════════════ --}}
            <div class="col-lg-8">

                {{-- Tab Navigasi --}}
                @php
                    $tabs = [
                        'hero'         => ['icon'=>'fas fa-star',         'label'=>'Hero'],
                        'trust'        => ['icon'=>'fas fa-handshake',    'label'=>'Trust'],
                        'features'     => ['icon'=>'fas fa-puzzle-piece', 'label'=>'Features'],
                        'how_it_works' => ['icon'=>'fas fa-list-ol',      'label'=>'How It Works'],
                        'pricing'      => ['icon'=>'fas fa-tags',         'label'=>'Pricing'],
                        'testimonials' => ['icon'=>'fas fa-quote-left',   'label'=>'Testimoni'],
                        'faq'          => ['icon'=>'fas fa-question-circle','label'=>'FAQ'],
                        'cta'          => ['icon'=>'fas fa-bullhorn',     'label'=>'CTA'],
                        'navbar'       => ['icon'=>'fas fa-bars',         'label'=>'Navbar'],
                        'seo'          => ['icon'=>'fas fa-search',       'label'=>'SEO'],
                        'contact'      => ['icon'=>'fab fa-whatsapp',     'label'=>'WhatsApp'],
                        'smtp'         => ['icon'=>'fas fa-envelope',     'label'=>'SMTP'],
                    ];
                    $firstTab = true;
                @endphp

                <div class="ls-tab-nav">
                    @foreach($tabs as $group => $tab)
                        @if(isset($settings[$group]))
                            <button type="button"
                                    class="ls-tab-btn {{ $firstTab ? 'active' : '' }}"
                                    data-target="{{ $group }}">
                                <i class="{{ $tab['icon'] }}"></i> {{ $tab['label'] }}
                            </button>
                            @php $firstTab = false; @endphp
                        @endif
                    @endforeach
                </div>

                {{-- ── MAIN FORM ─────────────────────────────── --}}
                <form action="{{ route('landing.settings.update') }}" method="POST" enctype="multipart/form-data" id="landing-form">
                    @csrf

                    @foreach($tabs as $group => $tab)
                        @if(isset($settings[$group]))
                            @php $isFirst = array_key_first($tabs) === $group; @endphp
                            <div class="ls-pane {{ $loop->first ? 'active' : '' }}" id="pane-{{ $group }}">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                                        <h5 class="card-title font-weight-bold text-dark mb-0">
                                            <i class="{{ $tab['icon'] }} text-primary mr-2"></i>
                                            {{ $tab['label'] }} Section
                                        </h5>
                                        <span class="badge badge-light border">{{ $settings[$group]->count() }} field</span>
                                    </div>
                                    <div class="card-body">

                                        {{-- Info khusus per tab --}}
                                        @if($group === 'seo')
                                            <div class="alert alert-light border mb-4">
                                                <i class="fas fa-lightbulb text-warning mr-1"></i>
                                                Meta Title di bawah <strong>60 karakter</strong> dan Description di bawah <strong>160 karakter</strong> untuk hasil SEO optimal.
                                            </div>
                                        @elseif($group === 'contact')
                                            <div class="alert alert-light border mb-4">
                                                <i class="fas fa-info-circle text-info mr-1"></i>
                                                Format nomor WA tanpa tanda +, contoh: <strong>6281234567890</strong>
                                            </div>
                                        @elseif($group === 'smtp')
                                            <div class="alert alert-warning border mb-4">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                <strong>Penting:</strong> Pastikan kredensial SMTP benar agar fitur email tidak terganggu.
                                            </div>
                                        @elseif($group === 'hero')
                                            <div class="alert alert-info border mb-4">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                <strong>URL CTA</strong> bisa berupa path relatif (<code>/register</code>) atau URL absolut produksi (<code>https://domain.com/register</code>).
                                            </div>
                                        @elseif($group === 'navbar')
                                            <div class="alert alert-light border mb-4">
                                                <i class="fas fa-info-circle text-primary mr-1"></i>
                                                Kosongkan field <strong>Menu Tentang Kami</strong> atau <strong>Menu Kontak</strong> jika tidak ingin ditampilkan di navbar.
                                            </div>
                                        @endif

                                        {{-- Fields --}}
                                        @if($group === 'smtp')
                                            <div class="row">
                                                @foreach($settings[$group] as $s)
                                                    <div class="col-md-{{ in_array($s->key, ['mail_host','mail_username','mail_password']) ? '6' : '4' }} mb-3">
                                                        <div class="form-group mb-0">
                                                            <label class="font-weight-bold text-dark" style="font-size:.88rem;">{{ $s->label }}</label>
                                                            <input type="{{ $s->type }}" name="{{ $s->key }}"
                                                                   class="form-control form-control-sm"
                                                                   value="{{ $s->value }}"
                                                                   placeholder="{{ $s->label }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            @foreach($settings[$group] as $s)
                                                <div class="form-group mb-4">
                                                    <label class="font-weight-bold text-dark d-flex align-items-center gap-2">
                                                        {{ $s->label }}
                                                        @if(in_array($s->key, ['hero_cta_url','hero_demo_url','cta_btn2_url','faq_cta_btn2_url']))
                                                            <span class="badge badge-info ml-1" style="font-size:.65rem;">URL — bisa relatif atau https://</span>
                                                        @endif
                                                    </label>

                                                    @if($s->type === 'textarea')
                                                        <textarea name="{{ $s->key }}"
                                                                  class="form-control"
                                                                  rows="3"
                                                                  placeholder="{{ $s->label }}">{{ $s->value }}</textarea>

                                                    @elseif($s->type === 'file')
                                                        @if($s->value)
                                                            <div class="mb-2">
                                                                <img src="{{ $s->value }}"
                                                                     style="max-height:120px;border-radius:6px;border:1px solid #dee2e6;"
                                                                     id="preview_{{ $s->key }}"
                                                                     onerror="this.style.display='none'">
                                                            </div>
                                                        @endif
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="{{ $s->key }}"
                                                                   id="input_{{ $s->key }}" accept="image/*">
                                                            <label class="custom-file-label" for="input_{{ $s->key }}">
                                                                {{ $s->value ? basename($s->value) : 'Pilih gambar...' }}
                                                            </label>
                                                        </div>
                                                        <small class="text-muted">Format: JPG, PNG, WebP. Maks 2MB.</small>

                                                    @elseif($s->type === 'password')
                                                        <input type="password" name="{{ $s->key }}"
                                                               class="form-control"
                                                               value="{{ $s->value }}"
                                                               autocomplete="new-password"
                                                               placeholder="••••••••">

                                                    @elseif($s->type === 'url' || in_array($s->key, ['hero_cta_url','hero_demo_url','cta_btn2_url','faq_cta_btn2_url']))
                                                        <input type="text" name="{{ $s->key }}"
                                                               class="form-control"
                                                               value="{{ $s->value }}"
                                                               placeholder="/path-relatif  atau  https://domain.com/path">

                                                    @else
                                                        <input type="{{ $s->type }}" name="{{ $s->key }}"
                                                               class="form-control"
                                                               value="{{ $s->value }}"
                                                               placeholder="{{ $s->label }}">
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif

                                        {{-- WhatsApp Preview (hanya di tab contact) --}}
                                        @if($group === 'contact')
                                            @php
                                                $waNum = $settings['contact']->firstWhere('key','whatsapp_number');
                                                $waMsg = $settings['contact']->firstWhere('key','whatsapp_message');
                                                $waUrl = 'https://wa.me/' . ($waNum?->value ?? '') . '?text=' . urlencode($waMsg?->value ?? '');
                                            @endphp
                                            <div class="mt-2">
                                                <a href="{{ $waUrl }}" target="_blank" class="btn btn-success btn-sm">
                                                    <i class="fab fa-whatsapp mr-1"></i> Preview Link WhatsApp
                                                </a>
                                            </div>
                                        @endif

                                    </div>{{-- card-body --}}
                                </div>{{-- card --}}
                            </div>{{-- ls-pane --}}
                        @endif
                    @endforeach

                    {{-- ── SAVE BUTTON ─────────────────────────── --}}
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-5">
                        <small class="text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Perubahan hanya memengaruhi tab yang sedang aktif.
                        </small>
                        <button type="submit" id="save-btn"
                                class="btn btn-primary btn-lg px-5 shadow-sm font-weight-bold">
                            <i class="fas fa-save mr-2"></i> Simpan Pengaturan
                        </button>
                    </div>

                </form>
            </div>

            {{-- ══ KOLOM KANAN: Informasi & Branding ══════════ --}}
            <div class="col-lg-4">

                {{-- Branding Read-Only --}}
                <div class="card shadow-sm branding-card text-white mb-3">
                    <div class="card-header border-0 pb-0" style="background:transparent;">
                        <h5 class="card-title font-weight-bold mb-0">
                            <i class="fas fa-palette mr-2 text-warning"></i> Logo & Branding
                        </h5>
                        <small style="color:#adb5bd;">Dikelola di
                            <a href="{{ url('/super-admin/config') }}" class="text-warning">Config Aplikasi</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <div class="branding-item">
                            <div class="mr-3">
                                <img src="{{ $appConfig->get('logo_url', '/adminlte3/dist/img/AdminLTELogo.png') }}"
                                     style="height:48px;width:48px;border-radius:8px;object-fit:contain;background:#fff;padding:4px;">
                            </div>
                            <div>
                                <div class="font-weight-bold" style="font-size:1.05rem;">{{ $appConfig->get('app_name', '-') }}</div>
                                <small style="color:#adb5bd;">{{ $appConfig->get('app_tagline', '') }}</small>
                            </div>
                        </div>
                        <div class="branding-item">
                            <i class="fas fa-tag mr-3 text-warning" style="width:20px;"></i>
                            <div>
                                <small style="color:#adb5bd;">Versi</small>
                                <div class="font-weight-bold">{{ $appConfig->get('app_version', '-') }}</div>
                            </div>
                        </div>
                        <div class="branding-item">
                            <i class="fas fa-envelope mr-3 text-info" style="width:20px;"></i>
                            <div>
                                <small style="color:#adb5bd;">Email Support</small>
                                <div class="font-weight-bold" style="font-size:.85rem;">{{ $appConfig->get('support_email', '-') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-0" style="background:transparent;">
                        <a href="{{ url('/super-admin/config') }}" class="btn btn-warning btn-sm btn-block">
                            <i class="fas fa-cog mr-1"></i> Kelola di Config Aplikasi
                        </a>
                    </div>
                </div>

                {{-- Statistik Setting --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white">
                        <h6 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-chart-pie text-primary mr-2"></i> Ringkasan Setting
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($tabs as $group => $tab)
                                @if(isset($settings[$group]))
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3" style="font-size:.85rem;">
                                        <span><i class="{{ $tab['icon'] }} text-muted mr-2"></i>{{ $tab['label'] }}</span>
                                        <span class="badge badge-primary badge-pill">{{ $settings[$group]->count() }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Preview Landing --}}
                <div class="alert alert-info shadow-sm">
                    <i class="fas fa-eye mr-1"></i>
                    <strong>Preview Landing Page:</strong><br>
                    <a href="{{ url('/') }}" target="_blank" class="text-info font-weight-bold">
                        <i class="fas fa-external-link-alt mr-1"></i> Buka Landing Page <small>(tab baru)</small>
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
(function () {
    'use strict';

    // ── Tab Switching ──────────────────────────────────────────────
    const btns  = document.querySelectorAll('.ls-tab-btn');
    const panes = document.querySelectorAll('.ls-pane');

    btns.forEach(btn => {
        btn.addEventListener('click', function () {
            const target = this.dataset.target;

            btns.forEach(b => b.classList.remove('active'));
            panes.forEach(p => p.classList.remove('active'));

            this.classList.add('active');
            const pane = document.getElementById('pane-' + target);
            if (pane) pane.classList.add('active');
        });
    });

    // ── Image Preview ──────────────────────────────────────────────
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const label   = e.target.nextElementSibling;
            const key     = e.target.id.replace('input_', '');
            const preview = document.getElementById('preview_' + key);

            label.innerText = file.name;

            if (preview) {
                const reader = new FileReader();
                reader.onload = re => { preview.src = re.target.result; preview.style.display = ''; };
                reader.readAsDataURL(file);
            }
        });
    });

    // ── Save Button Loading State ──────────────────────────────────
    document.getElementById('landing-form').addEventListener('submit', function () {
        const btn = document.getElementById('save-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
    });

})();
</script>
@endpush
