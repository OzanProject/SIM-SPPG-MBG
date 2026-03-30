@extends('layouts.app')

@push('css')
<style>
    /* ===== DASHBOARD PRO STYLES ===== */
    .dash-header-card {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        border-radius: 12px;
        padding: 28px 30px;
        color: white;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .dash-header-card::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 180px; height: 180px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }
    .dash-header-card::after {
        content: '';
        position: absolute;
        bottom: -60px; right: 60px;
        width: 240px; height: 240px;
        background: rgba(255,255,255,0.03);
        border-radius: 50%;
    }
    .plan-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .plan-badge.free { background: rgba(255,255,255,0.15); color: #ccc; }
    .plan-badge.premium { background: linear-gradient(90deg, #f7971e, #ffd200); color: #333; }

    .stat-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: transform .2s ease, box-shadow .2s ease;
        overflow: hidden;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
    .stat-card .card-body { padding: 20px; }
    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }
    .stat-value { font-size: 1.6rem; font-weight: 700; line-height: 1.2; }
    .stat-label { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6c757d; font-weight: 600; }

    .table-card { border-radius: 10px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
    .table-card .card-header { background: white; border-bottom: 1px solid #f0f0f0; padding: 16px 20px; border-radius: 10px 10px 0 0; }
    .table-card .card-header h6 { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; color: #495057; margin: 0; }
    .table-card table th { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px; color: #9a9a9a; font-weight: 600; border-top: none; }
    .table-card table td { font-size: 0.83rem; vertical-align: middle; }

    .subscription-bar {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 18px 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .days-badge { font-size: 0.7rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; }

    .alert-low-stock { border-left: 4px solid #dc3545; }
    .realtime-clock-wrapper {
        background: rgba(255,255,255,0.08);
        border-radius: 10px;
        padding: 10px 18px;
        display: inline-block;
        text-align: center;
    }
</style>
@endpush

@section('content')

{{-- HEADER BANNER --}}
<div class="content-header pt-3 pb-0">
    <div class="container-fluid">
        <div class="dash-header-card">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <small class="text-uppercase" style="letter-spacing:1px; opacity:.6; font-size:0.7rem;">Selamat datang kembali</small>
                    <h2 class="mb-1 font-weight-bold mt-1">{{ Auth::user()->name }}</h2>
                    <p class="mb-2" style="opacity:.7; font-size:0.9rem;">
                        <i class="fas fa-store mr-1"></i> {{ tenant('name') ?? tenant('id') }}
                    </p>
                    <span class="plan-badge {{ $isFreePlan ? 'free' : 'premium' }}">
                        <i class="fas {{ $isFreePlan ? 'fa-leaf' : 'fa-crown' }}"></i>
                        Paket: {{ $planName }}
                        @if(tenant()->is_on_trial)
                            <small class="d-block mt-1 opacity-70" style="font-size:0.65rem;">(Paket Dasar: {{ $tenant->plan->name ?? 'FREE' }})</small>
                        @endif
                    </span>

                    @if(!$isFreePlan && $subscriptionEndsAt)
                        <span class="ml-2 plan-badge free">
                            <i class="fas fa-calendar-check"></i>
                            Aktif hingga {{ $subscriptionEndsAt->format('d M Y') }}
                            @if($daysRemaining !== null)
                                ({{ $daysRemaining >= 0 ? $daysRemaining . ' hari lagi' : 'Kadaluarsa' }})
                            @endif
                        </span>
                    @endif

                    @if($isFreePlan)
                        <div class="mt-3">
                            <a href="{{ route('tenant.billing.index', tenant('id')) }}" class="btn btn-sm btn-warning font-weight-bold">
                                <i class="fas fa-arrow-circle-up mr-1"></i> Upgrade ke Premium
                            </a>
                        </div>
                    @endif
                </div>
                <div class="col-md-5 text-right d-none d-md-block">
                    <div class="realtime-clock-wrapper">
                        <div style="font-size: 2.4rem; font-weight: 700; letter-spacing: 2px;" id="realtime-clock">{{ now()->format('H:i:s') }}</div>
                        <div style="font-size: 0.78rem; opacity: .7;">{{ now()->format('l, d F Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Global Announcements Banner -->
<div id="announcement-container" class="container-fluid mb-3"></div>

<style>
    .announcement-banner {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        border-radius: 12px;
        margin-bottom: 10px;
        color: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        position: relative;
        animation: slideInDown 0.5s ease-out;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .announcement-banner.info { background: linear-gradient(135deg, #0984e3, #6c5ce7); }
    .announcement-banner.warning { background: linear-gradient(135deg, #f0932b, #edaf19); color: #2d3436; }
    .announcement-banner.danger { background: linear-gradient(135deg, #d63031, #e17055); }
    .announcement-banner.success { background: linear-gradient(135deg, #00b894, #55efc4); color: #2d3436; }
    
    .announcement-icon {
        font-size: 1.4rem;
        margin-right: 15px;
        opacity: 0.9;
    }
    .announcement-content {
        flex-grow: 1;
        font-weight: 500;
        line-height: 1.4;
    }
    .announcement-close {
        background: rgba(0,0,0,0.1);
        border: none;
        color: inherit;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        margin-left: 15px;
    }
    .announcement-close:hover {
        background: rgba(0,0,0,0.2);
        transform: scale(1.1);
    }
    
    @keyframes slideInDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

<section class="content">
    <div class="container-fluid">

        {{-- STAT WIDGETS --}}
        <div class="row">
            <!-- Today Sales -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-success" style="border-left: 4px solid #28a745 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label mb-1">Penjualan Hari Ini</div>
                                <div class="stat-value text-success" style="font-size:1.4rem;">Rp{{ number_format($todaySales, 0, ',', '.') }}</div>
                                <small class="text-muted">pendapatan harian</small>
                            </div>
                            <div class="stat-icon" style="background:#e8f5e9;">
                                <i class="fas fa-cash-register text-success"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('tenant.sales.create', tenant('id')) }}" class="btn btn-outline-success btn-sm btn-block py-1 text-xs">
                                <i class="fas fa-plus-circle mr-1"></i> Catat Penjualan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Sales -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-info" style="border-left: 4px solid #17a2b8 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label mb-1">Penjualan Bulan Ini</div>
                                <div class="stat-value text-info" style="font-size:1.4rem;">Rp{{ number_format($monthlySales, 0, ',', '.') }}</div>
                                <small class="text-muted">akumulasi bulan ini</small>
                            </div>
                            <div class="stat-icon" style="background:#e3f2fd;">
                                <i class="fas fa-chart-line text-info"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('tenant.sales.index', tenant('id')) }}" class="btn btn-outline-info btn-sm btn-block py-1 text-xs">
                                <i class="fas fa-history mr-1"></i> Riwayat Transaksi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Stock -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label mb-1">Total Stok</div>
                                <div class="stat-value">{{ number_format($totalStock) }}</div>
                                <small class="text-muted">unit barang tercatat</small>
                            </div>
                            <div class="stat-icon" style="background:#fff3e0;">
                                <i class="fas fa-boxes text-warning"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('inventory.items.index', tenant('id')) }}" class="btn btn-outline-warning btn-sm btn-block py-1 text-xs">
                                <i class="fas fa-warehouse mr-1"></i> Stok Gudang
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sisa Anggaran -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label mb-1">Sisa Anggaran</div>
                                <div class="stat-value" style="font-size:1.25rem;">Rp{{ number_format($remainingBudget, 0, ',', '.') }}</div>
                                <small class="text-muted">bulan {{ now()->format('F Y') }}</small>
                            </div>
                            <div class="stat-icon" style="background:#fff8e1;">
                                <i class="fas fa-wallet text-secondary"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('budgeting.monitoring', tenant('id')) }}" class="btn btn-outline-secondary btn-sm btn-block py-1 text-xs">
                                <i class="fas fa-chart-pie mr-1"></i> Monitor Anggaran
                            </a>
                        </div>
                    </div>
                </div>
            </div> <!-- End Sisa Anggaran -->

            <!-- Monthly Payroll (Merged into Root Row) -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-danger" style="border-left: 4px solid #dc3545 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-label mb-1">Gaji Terbayar</div>
                                <div class="stat-value text-danger" style="font-size:1.4rem;">Rp{{ number_format($monthlyPayroll, 0, ',', '.') }}</div>
                                <small class="text-muted">bulan ini</small>
                            </div>
                            <div class="stat-icon" style="background:#fff5f5;">
                                <i class="fas fa-hand-holding-usd text-danger"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('tenant.hr.payroll.index', tenant('id')) }}" class="btn btn-outline-danger btn-sm btn-block py-1 text-xs">
                                <i class="fas fa-file-invoice-dollar mr-1"></i> Kelola Payroll
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End Consolidated Root Row -->

        {{-- SUBSCRIPTION STATUS + LOW STOCK --}}
        <div class="row">
            {{-- Subscription Info Card --}}
            <div class="col-md-4 mb-4">
                <div class="card table-card h-100">
                    <div class="card-header">
                        <h6><i class="fas fa-id-card mr-2 text-primary"></i>Status Langganan</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div style="font-size:3rem;">{{ $isFreePlan ? '🌱' : '👑' }}</div>
                            <h4 class="font-weight-bold mb-0">{{ $planName }}</h4>
                            <span class="badge {{ $planBadgeClass }} px-3 py-1 mt-1">{{ $isFreePlan ? 'Gratis' : (tenant()->is_on_trial ? 'Percobaan Premium' : 'Premium') }}</span>
                            @if(tenant()->is_on_trial)
                                <div class="mt-2 small text-muted">Paket Dasar: <strong>{{ $tenant->plan->name ?? 'FREE' }}</strong></div>
                            @endif
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between text-sm mb-2">
                            <span class="text-muted"><i class="fas fa-users mr-1"></i> Maks. Pengguna</span>
                            <strong>{{ $maxMembers }} orang</strong>
                        </div>
                        @if($subscriptionEndsAt)
                        <div class="d-flex justify-content-between text-sm mb-2">
                            <span class="text-muted"><i class="fas fa-calendar-alt mr-1"></i> Aktif Hingga</span>
                            <strong>{{ $subscriptionEndsAt->format('d M Y') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between text-sm mb-2">
                            <span class="text-muted"><i class="fas fa-hourglass-half mr-1"></i> Sisa Waktu</span>
                            @if($daysRemaining !== null && $daysRemaining >= 0)
                                <span class="badge badge-{{ $daysRemaining < 7 ? 'danger' : 'success' }} days-badge">{{ $daysRemaining }} hari</span>
                            @else
                                <span class="badge badge-danger days-badge">Kadaluarsa</span>
                            @endif
                        </div>
                        @endif
                        <div class="text-center mt-3">
                            <a href="{{ route('tenant.billing.index', tenant('id')) }}" class="btn btn-primary btn-block btn-sm">
                                <i class="fas fa-file-invoice-dollar mr-1"></i>
                                {{ $isFreePlan ? 'Upgrade Sekarang' : 'Kelola Langganan' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Low Stock Alert --}}
            <div class="col-md-4 mb-4">
                <div class="card table-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6><i class="fas fa-exclamation-triangle mr-2 text-warning"></i>Stok Hampir Habis</h6>
                        @if($lowStockItems->count() > 0)
                            <span class="badge badge-danger">{{ $lowStockItems->count() }}</span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        @forelse($lowStockItems->take(5) as $item)
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <div>
                                <strong class="d-block text-sm">{{ $item->name }}</strong>
                                <small class="text-muted">{{ $item->unit ?? 'pcs' }}</small>
                            </div>
                            <span class="badge badge-{{ $item->stock <= 5 ? 'danger' : 'warning' }} px-2">{{ $item->stock }}</span>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i>
                            <small>Semua stok dalam kondisi baik</small>
                        </div>
                        @endforelse
                    </div>
                    <div class="card-footer text-center bg-white py-2">
                        <a href="{{ route('inventory.items.index', tenant('id')) }}" class="text-xs font-weight-bold text-primary">
                            Lihat Semua Barang <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- PO & Quick Info --}}
            <div class="col-md-4 mb-4">
                <div class="card table-card h-100">
                    <div class="card-header">
                        <h6><i class="fas fa-bell mr-2 text-danger"></i>Notifikasi Sistem</h6>
                    </div>
                    <div class="card-body p-0">
                        @if($pendingPOs > 0)
                        <div class="d-flex align-items-start px-3 py-3 border-bottom">
                            <div class="stat-icon mr-3 flex-shrink-0" style="background:#fce4ec; width:36px; height:36px; font-size:0.9rem;">
                                <i class="fas fa-shopping-bag text-danger"></i>
                            </div>
                            <div>
                                <strong class="d-block text-sm">Purchase Order Menunggu</strong>
                                <small class="text-muted">{{ $pendingPOs }} PO belum diproses</small>
                                <div class="mt-1">
                                    <a href="{{ route('procurement.pos.index', tenant('id')) }}" class="badge badge-danger">Proses Sekarang</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($isFreePlan)
                        <div class="d-flex align-items-start px-3 py-3 border-bottom">
                            <div class="stat-icon mr-3 flex-shrink-0" style="background:#e8f5e9; width:36px; height:36px; font-size:0.9rem;">
                                <i class="fas fa-crown text-success"></i>
                            </div>
                            <div>
                                <strong class="d-block text-sm">Tingkatkan Paket Anda</strong>
                                <small class="text-muted">Buka semua fitur premium tanpa batasan</small>
                                <div class="mt-1">
                                    <a href="{{ route('tenant.billing.index', tenant('id')) }}" class="badge badge-success">Lihat Paket</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($daysRemaining !== null && $daysRemaining < 7 && $daysRemaining >= 0)
                        <div class="d-flex align-items-start px-3 py-3 border-bottom">
                            <div class="stat-icon mr-3 flex-shrink-0" style="background:#fff8e1; width:36px; height:36px; font-size:0.9rem;">
                                <i class="fas fa-hourglass-end text-warning"></i>
                            </div>
                            <div>
                                <strong class="d-block text-sm">Langganan Segera Berakhir</strong>
                                <small class="text-muted">Tersisa {{ $daysRemaining }} hari. Perpanjang sekarang!</small>
                                <div class="mt-1">
                                    <a href="{{ route('tenant.billing.index', tenant('id')) }}" class="badge badge-warning">Perpanjang</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!$pendingPOs && !$isFreePlan && ($daysRemaining === null || $daysRemaining >= 7))
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i>
                            <small>Tidak ada notifikasi mendesak saat ini</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- RECENT JOURNALS --}}
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card table-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6><i class="fas fa-list-alt mr-2 text-primary"></i>Transaksi Jurnal Terkini</h6>
                        <a href="{{ route('accounting.journals.index', tenant('id')) }}" class="btn btn-outline-primary btn-xs" style="font-size:0.7rem; padding:3px 10px;">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="pl-3">Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Sumber</th>
                                    <th class="text-right pr-3">Nilai (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentJournals as $journal)
                                <tr>
                                    <td class="pl-3 text-muted">{{ \Carbon\Carbon::parse($journal->date)->format('d M Y') }}</td>
                                    <td><strong>{{ $journal->description }}</strong></td>
                                    <td>
                                        <span class="badge badge-light text-xs">{{ $journal->source_module ?? 'manual' }}</span>
                                    </td>
                                    <td class="text-right pr-3 font-weight-bold">{{ number_format($journal->total_amount, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Belum ada transaksi jurnal yang tercatat.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const announcements = {!! json_encode($announcements) !!};
        const container = document.getElementById('announcement-container');

        if (!container || !Array.isArray(announcements)) return;

        announcements.forEach(item => {
            // Create Banner
            const banner = document.createElement('div');
            banner.className = `announcement-banner ${item.type || 'info'}`;
            banner.id = `banner-${item.id}`;

            // Icon selection
            let iconClass = 'fa-info-circle';
            if (item.type === 'warning') iconClass = 'fa-exclamation-triangle';
            if (item.type === 'danger') iconClass = 'fa-radiation';
            if (item.type === 'success') iconClass = 'fa-check-circle';

            let bodyHtml = (item.body || '').replace(/\n/g, '<br>');

            let html = `
                <div class="announcement-icon">
                    <i class="fas ${iconClass}"></i>
                </div>
                <div class="announcement-content">
                    <strong>${item.title}</strong>: ${bodyHtml}
                </div>
            `;

            // Close button (always visible for non-persistent items, but no localStorage)
            if (!item.is_persistent) {
                html += `
                    <button class="announcement-close" onclick="dismissAnnouncement(${item.id})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
            }

            banner.innerHTML = html;
            container.appendChild(banner);
        });

        window.dismissAnnouncement = function(id) {
            const banner = document.getElementById(`banner-${id}`);
            if (banner) {
                banner.style.transition = 'all 0.3s ease';
                banner.style.opacity = '0';
                banner.style.transform = 'translateY(-10px)';
                setTimeout(() => banner.remove(), 300);
            }
        };
    });
</script>
@endpush
@endsection

@push('js')
<script>
    // Realtime Clock Update
    var serverTime = new Date();
    serverTime.setHours({{ now()->format('H') }});
    serverTime.setMinutes({{ now()->format('i') }});
    serverTime.setSeconds({{ now()->format('s') }});

    setInterval(() => {
        serverTime.setSeconds(serverTime.getSeconds() + 1);
        const h = String(serverTime.getHours()).padStart(2, '0');
        const m = String(serverTime.getMinutes()).padStart(2, '0');
        const s = String(serverTime.getSeconds()).padStart(2, '0');
        const el = document.getElementById('realtime-clock');
        if (el) el.innerText = h + ':' + m + ':' + s;
    }, 1000);
</script>
@endpush
