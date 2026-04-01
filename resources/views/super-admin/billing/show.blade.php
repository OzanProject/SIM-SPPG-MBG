@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">Detail Invoice</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/dashboard') }}"><i class="fas fa-home text-primary"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/billing') }}">Billing</a></li>
                    <li class="breadcrumb-item active">{{ $billing->invoice_number }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row">
            {{-- INVOICE DETAIL --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    {{-- Header Invoice --}}
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="card-title font-weight-bold text-dark mb-0">
                                <i class="fas fa-file-invoice text-primary mr-2"></i>
                                {{ $billing->invoice_number }}
                            </h3>
                            <small class="text-muted">Diterbitkan: {{ $billing->created_at->format('d F Y, H:i') }} WIB</small>
                        </div>
                        <div>
                            {!! $billing->status_badge !!}
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Info Tenant & Paket --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 1px;">TAGIHAN KEPADA</h6>
                                <p class="mb-0 font-weight-bold text-dark" style="font-size: 1.1rem;">{{ $billing->tenant_id }}</p>
                                @if($billing->tenant && $billing->tenant->domains->first())
                                    <p class="text-muted mb-0 d-flex align-items-center" style="gap: 5px;">
                                        <i class="fas fa-globe"></i> 
                                        <a href="//{{ $billing->tenant->domains->first()->domain }}" target="_blank" class="text-primary text-truncate" style="max-width: 280px; text-decoration: none;">
                                            {{ $billing->tenant->domains->first()->domain }}
                                        </a>
                                    </p>
                                @elseif($billing->tenant)
                                    <p class="text-muted mb-0 d-flex align-items-center" style="gap: 5px;">
                                        <i class="fas fa-globe"></i> 
                                        <a href="{{ url('/' . $billing->tenant_id) }}" target="_blank" class="text-primary text-truncate" style="max-width: 280px; text-decoration: none;">
                                            {{ url('/' . $billing->tenant_id) }}
                                        </a>
                                    </p>
                                @endif
                            </div>
                            <div class="col-md-6 text-right">
                                <h6 class="text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 1px;">JATUH TEMPO</h6>
                                <p class="mb-0 font-weight-bold {{ $billing->due_date->isPast() && $billing->status !== 'paid' ? 'text-danger' : 'text-dark' }}" style="font-size: 1.1rem;">
                                    {{ $billing->due_date->format('d F Y') }}
                                </p>
                                @if($billing->paid_at)
                                    <small class="text-success"><i class="fas fa-check mr-1"></i> Dibayar: {{ $billing->paid_at->format('d F Y, H:i') }}</small>
                                @endif
                            </div>
                        </div>

                        <hr>

                        {{-- Rincian Tagihan --}}
                        <table class="table table-borderless">
                            <thead class="bg-light">
                                <tr>
                                    <th>Deskripsi</th>
                                    <th class="text-center">Durasi</th>
                                    <th class="text-right">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>{{ $billing->subscriptionPlan->name ?? '-' }}</strong><br>
                                        <small class="text-muted">{{ $billing->subscriptionPlan->description ?? '' }}</small>
                                    </td>
                                    <td class="text-center">{{ $billing->subscriptionPlan->duration_in_days ?? '-' }} Hari</td>
                                    <td class="text-right font-weight-bold">Rp {{ number_format($billing->base_amount, 0, ',', '.') }}</td>
                                </tr>
                                @if($billing->promoCode)
                                    <tr>
                                        <td colspan="2">
                                            <span class="badge badge-info mr-1">{{ $billing->promoCode->code }}</span>
                                            <small class="text-muted">Diskon {{ $billing->promoCode->type === 'percent' ? $billing->promoCode->value.'%' : 'Tetap' }}</small>
                                        </td>
                                        <td class="text-right text-danger font-weight-bold">- Rp {{ number_format($billing->discount_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td colspan="2" class="text-right font-weight-bold text-dark">TOTAL TAGIHAN</td>
                                    <td class="text-right font-weight-bold text-primary" style="font-size: 1.3rem;">
                                        Rp {{ number_format($billing->final_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        @if($billing->notes)
                            <div class="alert alert-light border mt-3">
                                <strong><i class="fas fa-sticky-note mr-1 text-warning"></i> Catatan:</strong> {{ $billing->notes }}
                            </div>
                        @endif

                        @if($billing->payment_method)
                            <p class="text-muted"><strong>Metode Bayar:</strong> {{ $billing->payment_method }}</p>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                        <a href="{{ url('/super-admin/billing') }}" class="btn btn-default btn-sm"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                        <div>
                            <a href="{{ route('billing.download', $billing->id) }}" class="btn btn-primary btn-sm mr-1">
                                <i class="fas fa-print mr-1"></i> Cetak Invoice
                            </a>
                            <a href="{{ url('/super-admin/billing/' . $billing->id . '/edit') }}" class="btn btn-warning btn-sm mr-1">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            @if($billing->status === 'pending')
                                <form action="{{ route('billing.markPaid', $billing->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tandai invoice ini sebagai Lunas?');">
                                    @csrf
                                    <button class="btn btn-success btn-sm"><i class="fas fa-check mr-1"></i> Tandai Lunas</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- PANEL KANAN --}}
            <div class="col-lg-4">
                {{-- Status Timeline --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white">
                        <h5 class="card-title font-weight-bold text-dark mb-0"><i class="fas fa-history text-primary mr-2"></i> Riwayat Status</h5>
                    </div>
                    <div class="card-body pb-2">
                        <div class="timeline timeline-inverse">
                            <div class="time-label"><span class="bg-primary">Invoice</span></div>
                            <div>
                                <i class="fas fa-file-invoice bg-info"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> {{ $billing->created_at->format('d M Y') }}</span>
                                    <h3 class="timeline-header">Invoice Diterbitkan</h3>
                                </div>
                            </div>
                            @if($billing->paid_at)
                                <div>
                                    <i class="fas fa-check bg-success"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> {{ $billing->paid_at->format('d M Y, H:i') }}</span>
                                        <h3 class="timeline-header">Pembayaran Diterima</h3>
                                        @if($billing->payment_method)
                                            <div class="timeline-body">via {{ $billing->payment_method }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div><i class="fas fa-clock bg-gray"></i></div>
                        </div>
                    </div>
                </div>

                {{-- Bukti Pembayaran --}}
                @if($billing->payment_proof)
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-white">
                            <h5 class="card-title font-weight-bold text-dark mb-0"><i class="fas fa-receipt text-success mr-2"></i> Bukti Pembayaran</h5>
                        </div>
                        <div class="card-body text-center">
                            <a href="{{ asset('storage/' . ltrim(str_replace(['/storage/', 'storage/'], '', $billing->payment_proof), '/')) }}" target="_blank">
                                <img src="{{ asset('storage/' . ltrim(str_replace(['/storage/', 'storage/'], '', $billing->payment_proof), '/')) }}" class="img-fluid rounded" style="max-height: 200px;">
                            </a>
                            <p class="text-muted mt-2 mb-0" style="font-size: 0.8rem;">Klik untuk lihat ukuran penuh</p>
                        </div>
                    </div>
                @endif

                {{-- Info Paket --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title font-weight-bold text-dark mb-0"><i class="fas fa-box-open text-warning mr-2"></i> Informasi Paket</h5>
                    </div>
                    <div class="card-body">
                        @php $plan = $billing->subscriptionPlan; @endphp
                        @if($plan)
                            <table class="table table-sm table-borderless mb-0">
                                <tr><th class="text-muted" style="width: 40%">Paket</th><td class="font-weight-bold">{{ $plan->name }}</td></tr>
                                <tr><th class="text-muted">Durasi</th><td>{{ $plan->duration_in_days }} Hari</td></tr>
                                <tr><th class="text-muted">Batas Member</th><td>{{ $plan->max_members ?: '∞' }}</td></tr>
                                <tr><th class="text-muted">Batas Admin</th><td>{{ $plan->max_admins ?: '∞' }}</td></tr>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
