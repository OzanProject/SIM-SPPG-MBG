@extends('layouts.app')

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const planSelect = document.getElementById('subscription_plan_id');
    const promoSelect = document.getElementById('promo_code_id');
    const baseAmountEl = document.getElementById('base_amount');
    const discountEl = document.getElementById('discount_amount');
    const finalEl = document.getElementById('final_amount');

    const planPrices = @json($plans->pluck('price', 'id'));
    const promoCodes = @json($promos->map(fn($p) => ['id' => $p->id, 'type' => $p->type, 'value' => $p->value]));

    function recalculate() {
        const planId = planSelect.value;
        const base = planPrices[planId] ? parseFloat(planPrices[planId]) : 0;
        baseAmountEl.value = 'Rp ' + base.toLocaleString('id-ID');

        const promoId = promoSelect.value;
        let discount = 0;
        if (promoId) {
            const promo = promoCodes.find(p => p.id == promoId);
            if (promo) {
                discount = promo.type === 'percent' ? Math.round(base * promo.value / 100) : promo.value;
            }
        }
        discountEl.value = 'Rp ' + discount.toLocaleString('id-ID');
        finalEl.value = 'Rp ' + Math.max(0, base - discount).toLocaleString('id-ID');
    }

    planSelect.addEventListener('change', recalculate);
    promoSelect.addEventListener('change', recalculate);
    recalculate();
});
</script>
@endpush

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">Buat Invoice Baru</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/dashboard') }}"><i class="fas fa-home text-primary"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/billing') }}">Billing</a></li>
                    <li class="breadcrumb-item active">Buat Invoice</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-file-invoice-dollar text-primary mr-2"></i> Detail Penagihan</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/super-admin/billing') }}" method="POST" id="invoiceForm">
                            @csrf

                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Cabang Dapur (Tenant) <span class="text-danger">*</span></label>
                                <select name="tenant_id" class="form-control @error('tenant_id') is-invalid @enderror" required>
                                    <option value="">— Pilih Cabang Dapur —</option>
                                    @foreach($tenants as $tenant)
                                        <option value="{{ $tenant->id }}" {{ old('tenant_id') === $tenant->id ? 'selected' : '' }}>
                                            {{ $tenant->id }}
                                            @if($tenant->domains->first()) ({{ $tenant->domains->first()->domain }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('tenant_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">Paket Langganan <span class="text-danger">*</span></label>
                                        <select name="subscription_plan_id" class="form-control @error('subscription_plan_id') is-invalid @enderror" required id="subscription_plan_id">
                                            <option value="">— Pilih Paket —</option>
                                            @foreach($plans as $plan)
                                                <option value="{{ $plan->id }}" {{ old('subscription_plan_id') == $plan->id ? 'selected' : '' }}>
                                                    {{ $plan->name }} (Rp {{ number_format($plan->price, 0, ',', '.') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('subscription_plan_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">Kode Promo (Opsional)</label>
                                        <select name="promo_code_id" class="form-control" id="promo_code_id">
                                            <option value="">— Tanpa Promo —</option>
                                            @foreach($promos as $promo)
                                                <option value="{{ $promo->id }}" {{ old('promo_code_id') == $promo->id ? 'selected' : '' }}>
                                                    {{ $promo->code }} ({{ $promo->type === 'percent' ? $promo->value.'%' : 'Rp '.number_format($promo->value,0,',','.') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                                <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
                                    value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" required>
                                @error('due_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Catatan untuk Tenant</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                            </div>

                            <div class="text-right">
                                <a href="{{ url('super-admin/billing') }}" class="btn btn-default px-4 mr-2">Batal</a>
                                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save mr-1"></i> Terbitkan Invoice</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- PANEL KALKULASI --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-primary">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title font-weight-bold"><i class="fas fa-calculator mr-2"></i> Kalkulasi Otomatis</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="text-muted text-sm">Harga Paket</label>
                            <input type="text" id="base_amount" class="form-control font-weight-bold" readonly value="Rp 0">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-muted text-sm">Diskon Promo</label>
                            <input type="text" id="discount_amount" class="form-control text-danger" readonly value="Rp 0">
                        </div>
                        <hr>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-dark">Total Tagihan</label>
                            <input type="text" id="final_amount" class="form-control font-weight-bold text-success" style="font-size: 1.2rem;" readonly value="Rp 0">
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle mr-1"></i>
                    Invoice dengan status <strong>Pending</strong> akan dikirim otomatis. Tenant dapat melihat dan melakukan pembayaran dari dashboard mereka.
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
