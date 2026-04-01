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
            <div class="col-md-5 mb-4">
                <div class="card card-outline card-primary shadow-sm h-100" style="border-radius: 12px;">
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
                                Akses penuh dikunci karena masa aktif telah habis.
                            </div>
                        @else
                            <div class="alert alert-info bg-info text-white border-0 mt-2 mb-0" style="border-radius: 8px;">
                                Pembayaran perpanjangan disarankan dilakukan minimal 3 hari sebelum kadaluarsa.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
                
            <!-- RIWAYAT TAGIHAN -->
            <div class="col-md-7 mb-4">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
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
        </div>

        <!-- PILIH PAKET FULL WIDTH -->
        <div class="card shadow-sm border-0 mt-3" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom-0 pb-0 pt-4 text-center">
                <h3 class="font-weight-bold text-dark mb-1">Upgrade / Perpanjang Paket Langganan</h3>
                <p class="text-muted">Pilih paket yang sesuai dengan kebutuhan dan ukuran bisnis Dapur Anda.</p>
                @if(tenant()->is_on_trial)
                    <span class="badge badge-warning p-2 shadow-sm animate__animated animate__pulse animate__infinite mt-2" style="border-radius:10px;">
                        <i class="fas fa-clock mr-1"></i> {{ tenant()->trial_days_left }} Hari Trial PRO Sisa
                    </span>
                @endif
            </div>
            
            <div class="card-body pt-5 pb-5 bg-light rounded-bottom" style="border-radius: 12px;">
                <form action="{{ route('tenant.billing.checkout') }}" method="POST">
                    @csrf
                    <div class="row custom-plan-row justify-content-center">
                        @forelse($plans as $plan)
                        <div class="col-lg-3 col-md-6 mb-4">
                            <label class="w-100 h-100 m-0" style="cursor: pointer;">
                                <input type="radio" name="plan_id" value="{{ $plan->id }}" class="d-none plan-radio" required>
                                <div class="card h-100 plan-card pricing-card text-center relative bg-white border-0 shadow-sm">
                                    
                                    @if($plan->price == 0 || $plan->price == '0.00')
                                        <div class="corner-ribbon top-right sticky green shadow">GRATIS</div>
                                    @endif

                                    <div class="card-body p-4 d-flex flex-column">
                                        <!-- Header -->
                                        <h4 class="font-weight-bold text-dark mt-2 mb-2 text-uppercase" style="letter-spacing: 1px;">{{ $plan->name }}</h4>
                                        <p class="text-muted small mb-4 px-2" style="min-height: 40px;">{{ $plan->description }}</p>
                                        
                                        <!-- Price -->
                                        <div class="mb-4">
                                            @if($plan->price == 0 || $plan->price == '0.00')
                                                <h1 class="font-weight-bold text-primary mb-0" style="font-size: 2.2rem;">GRATIS</h1>
                                                <p class="text-muted small mt-2">Selamanya</p>
                                            @else
                                                <h1 class="font-weight-bold text-primary mb-0 d-inline-block" style="font-size: 2.2rem;">Rp {{ number_format($plan->price, 0, ',', '.') }}</h1><span class="text-muted small">/{{ $plan->duration_in_days }} Hari</span>
                                                <p class="text-muted small mt-2">Hari</p>
                                            @endif
                                        </div>

                                        <div class="dashed-divider mb-4"></div>

                                        <!-- Limits -->
                                        <ul class="text-left list-unstyled text-muted small font-weight-bold mb-4 ml-3">
                                            <li class="mb-3"><i class="fas fa-user-friends text-primary mr-3" style="width: 20px;"></i> {{ $plan->max_users == 0 ? 'Unlimited' : $plan->max_users }} Akun Staff</li>
                                            <li class="mb-3"><i class="fas fa-box text-warning mr-3" style="width: 20px;"></i> {{ $plan->max_items == 0 ? 'Unlimited' : number_format($plan->max_items, 0, ',', '.') }} Item Inventory</li>
                                            <li class="mb-3"><i class="fas fa-exchange-alt text-success mr-3" style="width: 20px;"></i> {{ $plan->max_transactions_per_month == 0 ? 'Unlimited' : number_format($plan->max_transactions_per_month, 0, ',', '.') }} Transaksi/Bln</li>
                                        </ul>

                                        <!-- Modules -->
                                        <div class="mt-auto bg-light rounded text-left p-3 pt-4 pb-4 w-100 mx-auto" style="border: 1px solid #f0f0f0;">
                                            <p class="font-weight-bold text-dark mb-3" style="font-size: 13px;">AKSES MODUL INTI:</p>
                                            <ul class="list-unstyled mb-0" style="font-size: 13px; line-height: 2.2;">
                                                <!-- Penjualan & Kasir -->
                                                <li>
                                                    @if($plan->has_sales)
                                                        <i class="fas fa-check text-success mr-2 font-weight-bold"></i> <span class="text-dark">Penjualan & Kasir</span>
                                                    @else
                                                        <i class="fas fa-times text-danger mr-2"></i> <del class="text-muted">Penjualan & Kasir</del>
                                                    @endif
                                                </li>
                                                <!-- Inventory -->
                                                <li>
                                                    @if($plan->has_inventory)
                                                        <i class="fas fa-check text-success mr-2 font-weight-bold"></i> <span class="text-dark">Manajemen Stok (Inventory)</span>
                                                    @else
                                                        <i class="fas fa-times text-danger mr-2"></i> <del class="text-muted">Manajemen Stok (Inventory)</del>
                                                    @endif
                                                </li>
                                                <!-- Procurement -->
                                                <li>
                                                    @if($plan->has_procurement)
                                                        <i class="fas fa-check text-success mr-2 font-weight-bold"></i> <span class="text-dark">Pengadaan (PO) / Supplier</span>
                                                    @else
                                                        <i class="fas fa-times text-danger mr-2"></i> <del class="text-muted">Pengadaan (PO) / Supplier</del>
                                                    @endif
                                                </li>
                                                <!-- Accounting -->
                                                <li>
                                                    @if($plan->has_accounting_full)
                                                        <i class="fas fa-check text-success mr-2 font-weight-bold"></i> <span class="text-dark">Akuntansi Lengkap (Ledger)</span>
                                                    @else
                                                        <i class="fas fa-times text-danger mr-2"></i> <del class="text-muted">Akuntansi Lengkap (Ledger)</del>
                                                    @endif
                                                </li>
                                                <!-- Budgeting -->
                                                <li>
                                                    @if($plan->has_budgeting)
                                                        <i class="fas fa-check text-success mr-2 font-weight-bold"></i> <span class="text-dark">Sistem Anggaran (Budgeting)</span>
                                                    @else
                                                        <i class="fas fa-times text-danger mr-2"></i> <del class="text-muted">Sistem Anggaran (Budgeting)</del>
                                                    @endif
                                                </li>
                                                <!-- HR -->
                                                <li>
                                                    @if($plan->has_hr)
                                                        <i class="fas fa-check text-success mr-2 font-weight-bold"></i> <span class="text-dark">HR / Penggajian (Payroll)</span>
                                                    @else
                                                        <i class="fas fa-times text-danger mr-2"></i> <del class="text-muted">HR / Penggajian (Payroll)</del>
                                                    @endif
                                                </li>
                                                <!-- Circle Menu -->
                                                <li>
                                                    @if($plan->has_circle_menu)
                                                        <i class="fas fa-check text-success mr-2 font-weight-bold"></i> <span class="text-dark">Distribusi Menu Circle</span>
                                                    @else
                                                        <i class="fas fa-times text-danger mr-2"></i> <del class="text-muted">Distribusi Menu Circle</del>
                                                    @endif
                                                </li>
                                                <!-- Export -->
                                                <li>
                                                    @if($plan->can_export)
                                                        <i class="fas fa-check text-success mr-2 font-weight-bold"></i> <span class="text-dark">Export Data Excel/PDF</span>
                                                    @else
                                                        <i class="fas fa-times text-danger mr-2"></i> <del class="text-muted">Export Data Excel/PDF</del>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </label>
                        </div>
                        @empty
                        <div class="col-12 text-center text-muted py-4">Belum ada paket tersedia dari pusat.</div>
                        @endforelse
                    </div>

                    @if(count($plans) > 0)
                    <div class="row align-items-center justify-content-center mt-4">
                        <div class="col-md-5">
                            <div class="form-group mb-0">
                                <label for="promo_code" class="text-muted font-weight-bold"><i class="fas fa-tag mr-1"></i> Punya Kode Promo?</label>
                                <input type="text" name="promo_code" id="promo_code" class="form-control" placeholder="Masukkan kode promo (Opsional)" style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="col-md-3 mt-3 mt-md-0 pt-md-4">
                            <button type="submit" class="btn btn-primary btn-block btn-lg shadow" style="font-weight: 700; border-radius: 8px;">
                                Lanjutkan <i class="fas fa-arrow-right ml-1"></i>
                            </button>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</section>

@push('css')
<style>
    /* Pricing Card Styling */
    .pricing-card {
        border: 1px solid #eaeaea !important;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    .pricing-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        border-color: #d1d5db !important;
    }
    
    .plan-radio:checked + .pricing-card {
        border: 2px solid #007bff !important;
        box-shadow: 0 10px 30px rgba(0, 123, 255, 0.15) !important;
        transform: translateY(-5px);
    }

    /* Dashed Horizontal Divider */
    .dashed-divider {
        border-top: 2px dotted #e5e7eb;
        margin-left: 10px;
        margin-right: 10px;
    }

    /* Modul Inti Box styling */
    .pricing-card .bg-light {
        background-color: #f8f9fa !important;
        border-top: 1px dotted #e5e7eb;
    }

    /* Corner Ribbon for FREE / Bestseller */
    .corner-ribbon {
        width: 160px;
        background: #28a745;
        position: absolute;
        top: 25px;
        left: -40px;
        text-align: center;
        line-height: 40px;
        letter-spacing: 1px;
        color: #f0f0f0;
        font-weight: 900;
        transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
        z-index: 10;
        text-transform: uppercase;
        font-size: 14px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .corner-ribbon.top-right {
        top: 20px;
        right: -40px;
        left: auto;
        transform: rotate(45deg);
        -webkit-transform: rotate(45deg);
    }
    .corner-ribbon.green { background: #28a745; color: white; }
    
</style>
@endpush
@endsection
