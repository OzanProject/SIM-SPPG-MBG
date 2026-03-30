@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">Buat Kode Promo Baru</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/dashboard') }}"><i class="fas fa-home text-primary"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/promos') }}">Kelola Diskon</a></li>
                    <li class="breadcrumb-item active">Buat Baru</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline shadow-sm" style="max-width: 820px; margin: 0 auto;">
            <div class="card-header border-bottom-0">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-percent mr-2 text-primary"></i>
                    Formulir Penerbitan Kode Promo
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('/super-admin/promos') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Kode Promo <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control text-uppercase font-weight-bold @error('code') is-invalid @enderror"
                                    value="{{ old('code') }}" placeholder="Cth: BARU2026" required autofocus
                                    style="letter-spacing: 2px;">
                                @error('code')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                <small class="text-muted">Akan otomatis diubah jadi huruf kapital.</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Tipe Diskon <span class="text-danger">*</span></label>
                                <select name="type" class="form-control @error('type') is-invalid @enderror" required id="typeSelect">
                                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed (Rp)</option>
                                    <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percent (%)</option>
                                </select>
                                @error('type')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Nilai Diskon <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white font-weight-bold" id="valuePrefix">Rp</span>
                                    </div>
                                    <input type="number" min="0" name="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', 0) }}" required>
                                    @error('value')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Mulai Berlaku <span class="text-danger">*</span></label>
                                <input type="date" name="starts_at" class="form-control @error('starts_at') is-invalid @enderror" value="{{ old('starts_at', date('Y-m-d')) }}" required>
                                @error('starts_at')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Berakhir Pada <span class="text-danger">*</span></label>
                                <input type="date" name="ends_at" class="form-control @error('ends_at') is-invalid @enderror" value="{{ old('ends_at', date('Y-m-d', strtotime('+30 days'))) }}" required>
                                @error('ends_at')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Kuota <span class="text-danger">*</span></label>
                                <input type="number" min="1" name="max_uses" class="form-control @error('max_uses') is-invalid @enderror" value="{{ old('max_uses', 5) }}" required>
                                @error('max_uses')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Relasi ke Paket Langganan --}}
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark">Berlaku untuk Paket Langganan</label>
                        <div class="row mt-2">
                            @forelse($plans as $plan)
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="plan_{{ $plan->id }}"
                                            name="subscription_plan_ids[]" value="{{ $plan->id }}"
                                            {{ in_array($plan->id, old('subscription_plan_ids', [])) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="plan_{{ $plan->id }}">
                                            <span class="badge badge-secondary">{{ $plan->name }}</span>
                                        </label>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-muted"><i>Belum ada paket. Buat paket terlebih dahulu.</i></div>
                            @endforelse
                        </div>
                        <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle mr-1"></i> Jika tidak dipilih, promo berlaku untuk <strong>semua paket</strong>.</small>
                    </div>

                    <div class="form-group mb-5">
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                            <label class="custom-control-label text-dark" for="is_active">Aktif & Langsung Berlaku</label>
                        </div>
                    </div>

                    <div class="text-right">
                        <a href="{{ url('super-admin/promos') }}" class="btn btn-default shadow-sm mr-2 px-4">Batal</a>
                        <button type="submit" class="btn btn-primary shadow-sm px-4"><i class="fas fa-save mr-1"></i> Terbitkan Promo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
    document.getElementById('typeSelect').addEventListener('change', function() {
        document.getElementById('valuePrefix').textContent = this.value === 'percent' ? '%' : 'Rp';
    });
    // Trigger on load
    document.getElementById('typeSelect').dispatchEvent(new Event('change'));
</script>
@endpush
