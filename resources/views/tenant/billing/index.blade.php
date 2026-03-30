@extends('layouts.app')

@section('title', 'Tagihan & Langganan')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">Langganan Dapur</h1>
                <p class="text-muted mb-0">Kelola paket aktif, limit kuota, dan riwayat tagihan Anda.</p>
            </div>
        </div>
    </div>
</div>

<section class="content pt-3">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-ban mr-1"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-1"></i> <strong>Peringatan!</strong> Terjadi kesalahan:
                <ul class="mb-0 mt-1 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <!-- STATUS SAAT INI -->
            <div class="col-md-5">
                <div class="card card-outline card-primary shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h4 class="font-weight-bold text-dark mb-0">Paket Aktif Saat Ini</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $endsAt = tenant('subscription_ends_at') ? \Carbon\Carbon::parse(tenant('subscription_ends_at')) : null;
                            $isActive = $endsAt && $endsAt->isFuture();
                            $daysLeft = $isActive ? (int) now()->diffInDays($endsAt) : 0;
                        @endphp
                        
                        <div class="d-flex align-items-center mb-4">
                            <div class="mr-3">
                                @if($isActive)
                                    <i class="fas fa-check-circle text-success fa-3x shadow-xs rounded-circle bg-white p-1"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger fa-3x shadow-xs rounded-circle bg-white p-1"></i>
                                @endif
                            </div>
                            <div>
                                <h2 class="mb-0 font-weight-bold text-primary">{{ $activePlanName }}</h2>
                                @if($isActive)
                                    <span class="badge badge-success px-2 py-1">Aktif</span>
                                @else
                                    <span class="badge badge-danger px-2 py-1">Kadaluarsa</span>
                                @endif
                            </div>
                        </div>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-top-0 px-0">
                                <span class="text-muted"><i class="far fa-calendar-alt text-primary mr-1"></i> Berakhir Pada</span>
                                <strong class="text-dark">{{ $endsAt ? $endsAt->format('d F Y') : '-' }} 
                                    @if($isActive)
                                        <small class="text-orange">({{ $daysLeft }} hari lagi)</small>
                                    @endif
                                </strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-bottom-0">
                                <span class="text-muted"><i class="fas fa-users text-primary mr-1"></i> Kuota User/Staf</span>
                                @php
                                    $currentUserCount = \App\Models\User::count();
                                    $currentPlan = $tenant->plan;
                                    $maxUsers = $currentPlan ? $currentPlan->max_users : 1;
                                    $percent = $maxUsers > 0 ? ($currentUserCount / $maxUsers) * 100 : 0;
                                @endphp
                                <div class="text-right">
                                    <strong class="text-dark">{{ $currentUserCount }}</strong> / {{ $maxUsers > 0 ? $maxUsers : 'Unlimited' }}
                                    @if($maxUsers > 0)
                                        <div class="progress progress-xs mt-1" style="height: 6px;">
                                            <div class="progress-bar {{ $percent > 80 ? 'bg-danger' : 'bg-success' }}" style="width: {{ $percent }}%"></div>
                                        </div>
                                    @endif
                                </div>
                            </li>
                        </ul>
                        
                        @if(!$isActive)
                            <div class="alert alert-danger bg-danger text-white border-0 mt-2 mb-0" style="border-radius: 8px;">
                                <h5><i class="icon fas fa-ban"></i> Akses Dibatasi!</h5>
                                Akses penuh ke aplikasi dikunci karena masa aktif telah habis. Berlakukan perpanjangan segera.
                            </div>
                        @else
                            <div class="alert alert-info bg-info text-white border-0 mt-2 mb-0" style="border-radius: 8px;">
                                Pembayaran perpanjangan disarankan dilakukan minimal 3 hari sebelum kadaluarsa untuk menghindari pemblokiran.
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- RIWAYAT TAGIHAN -->
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-white">
                        <h3 class="card-title font-weight-bold">Riwayat Tagihan</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0 text-sm">
                                <thead>
                                    <tr>
                                        <th>No. Invoice</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $inv)
                                    <tr>
                                        <td class="font-weight-bold">{{ $inv->invoice_number }}<br><small class="text-muted">{{ $inv->created_at->format('d M Y') }}</small></td>
                                        <td>Rp{{ number_format($inv->final_amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if($inv->status == 'paid') <span class="badge badge-success">Lunas</span>
                                            @elseif($inv->status == 'pending') <span class="badge badge-warning">Menunggu Pembayaran</span>
                                            @else <span class="badge badge-danger">{{ ucfirst($inv->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('tenant.billing.invoice.show', $inv->id) }}" class="btn btn-xs btn-primary"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('tenant.billing.invoice.download', $inv->id) }}" class="btn btn-xs btn-secondary" title="Cetak PDF"><i class="fas fa-print"></i></a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Belum ada riwayat tagihan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PILIH PAKET -->
            <div class="col-md-7">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom-0 pb-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-0"><i class="fas fa-rocket text-primary mr-1"></i> Pilih Paket Langganan</h4>
                            <p class="text-muted mt-1 mb-0">Tingkatkan operasional & keuntungan bisnis Anda.</p>
                        </div>
                        @if(tenant()->is_on_trial)
                            <span class="badge badge-warning p-2 shadow-sm animate__animated animate__pulse animate__infinite" style="border-radius:10px;">
                                <i class="fas fa-clock mr-1"></i> {{ tenant()->trial_days_left }} Hari Trial PRO Sisa
                            </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tenant.billing.checkout') }}" method="POST">
                            @csrf
                            <div class="row">
                                @forelse($plans as $plan)
                                <div class="col-sm-6 mb-3">
                                    <label class="w-100" style="cursor: pointer;">
                                        <input type="radio" name="plan_id" value="{{ $plan->id }}" class="d-none plan-radio" required>
                                        <div class="card card-outline {{ $plan->is_highlighted ? 'card-warning shadow' : 'card-primary' }} h-100 plan-card" style="border-width: 2px; transition: 0.3s; position: relative; overflow: hidden;">
                                            @if($plan->is_highlighted)
                                                <div style="position: absolute; top: 12px; right: -30px; background: #ffc107; color: #000; padding: 2px 40px; transform: rotate(45deg); font-size: 0.65rem; font-weight: 800; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                    {{ $plan->badge_label ?? 'POPULER' }}
                                                </div>
                                            @endif
                                            
                                            <div class="card-header text-center border-bottom-0 pt-4 pb-2">
                                                <h5 class="font-weight-bold text-dark">{{ $plan->name }}</h5>
                                                <h2 class="text-primary font-weight-bold mb-0" style="font-size: 1.8rem;">Rp{{ number_format($plan->price, 0, ',', '.') }}</h2>
                                                <p class="text-muted small">/ Bulan</p>
                                            </div>
                                            <div class="card-body pt-0 text-sm">
                                                <div class="mb-3 text-center text-muted px-2" style="font-size: 0.8rem; line-height: 1.4;">
                                                    {{ $plan->description }}
                                                </div>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> <strong>{{ $plan->max_transactions_per_month == 0 ? 'Unlimited' : $plan->max_transactions_per_month }}</strong> Transaksi</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> <strong>{{ $plan->max_items == 0 ? 'Unlimited' : $plan->max_items }}</strong> Barang</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> <strong>{{ $plan->max_users }}</strong> User Terdaftar</li>
                                                    
                                                    @if($plan->has_sales)
                                                        <li class="mb-2 text-primary font-weight-bold"><i class="fas fa-star text-warning mr-2"></i> Modul Penjualan PIN</li>
                                                    @else
                                                        <li class="mb-2 text-muted"><i class="fas fa-lock mr-2 opacity-50"></i> <del>Modul Penjualan</del></li>
                                                    @endif

                                                    @if($plan->can_export)
                                                        <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> Export Excel/PDF</li>
                                                    @endif
                                                    
                                                    @if($plan->has_hr)
                                                        <li class="mb-2 text-purple font-weight-bold"><i class="fas fa-bolt text-warning mr-2"></i> SDM & Payroll</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @empty
                                <div class="col-12 text-center text-muted py-4">Belum ada paket tersedia dari pusat.</div>
                                @endforelse
                            </div>

                            @if(count($plans) > 0)
                            <hr class="my-4">
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    <div class="form-group mb-0">
                                        <label for="promo_code" class="text-muted"><i class="fas fa-tag mr-1"></i> Punya Kode Promo?</label>
                                        <input type="text" name="promo_code" id="promo_code" class="form-control" placeholder="Masukkan kode promo (Opsional)">
                                    </div>
                                </div>
                                <div class="col-md-5 text-right mt-3 mt-md-0">
                                    <button type="submit" class="btn btn-primary btn-block btn-lg rounded-pill shadow-sm py-3" style="font-weight: 700;">
                                        Upgrade Sekarang <i class="fas fa-rocket ml-1"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('css')
<style>
    .plan-radio:checked + .plan-card {
        background-color: #f0f8ff;
        border-color: #007bff !important;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        transform: translateY(-3px);
    }
    .plan-card:hover {
        border-color: #b3d7ff !important;
    }
</style>
@endpush
@endsection
