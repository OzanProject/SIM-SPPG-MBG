@extends('layouts.app')

@push('css')
<style>
    :root {
        --sa-primary: #4e73df;
        --sa-success: #1cc88a;
        --sa-info: #36b9cc;
        --sa-warning: #f6c23e;
        --sa-danger: #e74a3b;
        --sa-dark: #1a1c2d;
    }

    /* ===== PREMIUM HEADER ===== */
    .sa-header-card {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        border-radius: 16px;
        padding: 30px;
        color: white;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .sa-header-card::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.03);
        border-radius: 50%;
    }
    .status-indicator {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        background: rgba(40, 167, 69, 0.15);
        color: #2ecc71;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid rgba(46, 204, 113, 0.3);
    }
    .pulse-dot {
        width: 8px; height: 8px;
        background: #2ecc71;
        border-radius: 50%;
        margin-right: 8px;
        box-shadow: 0 0 0 rgba(46, 204, 113, 0.4);
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(46, 204, 113, 0); }
        100% { box-shadow: 0 0 0 0 rgba(46, 204, 113, 0); }
    }

    /* ===== STAT CARDS ===== */
    .sa-stat-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        margin-bottom: 24px;
        background: white;
    }
    .sa-stat-card:hover { transform: translateY(-5px); box-shadow: 0 12px 25px rgba(0,0,0,0.1); }
    .sa-stat-card .card-body { padding: 25px; }
    .sa-icon-box {
        width: 60px; height: 60px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 15px;
    }
    .sa-stat-value { font-size: 1.8rem; font-weight: 800; color: #334155; line-height: 1.2; }
    .sa-stat-label { font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; }
    .sa-trend { font-size: 0.75rem; margin-top: 8px; }

    /* ===== GRADIENTS ===== */
    .grad-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: white; }
    .grad-success { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); color: white; }
    .grad-info { background: linear-gradient(135deg, #36b9cc 0%, #258391 100%); color: white; }
    .grad-warning { background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%); color: white; }
    .grad-danger { background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%); color: white; }

    /* ===== TABLES & CHARTS ===== */
    .sa-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .sa-card .card-header {
        background: white;
        border-bottom: 1px solid #f1f5f9;
        padding: 20px 25px;
        border-radius: 12px 12px 0 0;
    }
    .sa-card .card-header h5 { font-weight: 700; color: #1e293b; margin: 0; font-size: 1rem; }
    .table thead th {
        background: #f8fafc;
        border-top: none;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        padding: 15px;
    }
    .table td { padding: 15px; vertical-align: middle; border-color: #f1f5f9; }

    .quick-action-btn {
        display: flex;
        align-items: center;
        padding: 12px 18px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        margin-bottom: 10px;
        transition: all 0.2s ease;
        color: #475569;
        font-weight: 600;
        text-decoration: none !important;
    }
    .quick-action-btn:hover { background: #eff6ff; border-color: #3b82f6; color: #2563eb; transform: translateX(5px); }
    .quick-action-btn i { width: 30px; font-size: 1rem; }
</style>
@endpush

@section('content')
<div class="content-header pt-3">
    <div class="container-fluid">
        <div class="sa-header-card">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="status-indicator mb-3">
                        <div class="pulse-dot"></div>
                        System Healthy: All Components Operational
                    </div>
                    <h1 class="font-weight-bold mb-1">Super Admin Dashboard</h1>
                    <p class="mb-0 opacity-70">Selamat datang kembali, {{ auth()->user()->name }}. Monitor pertumbuhan ekosistem MBG AkunPro Anda hari ini.</p>
                </div>
                <div class="col-md-5 text-right d-none d-md-block">
                    <div class="px-3" style="border-right: 1px solid rgba(255,255,255,0.1); display:inline-block;">
                        <h2 class="mb-0 font-weight-bold" id="realtime-clock">{{ now()->format('H:i:s') }}</h2>
                        <small class="opacity-50">{{ now()->format('l, d F Y') }}</small>
                    </div>
                    <div class="pl-3" style="display:inline-block; text-align: left;">
                        <small class="d-block opacity-50">Versi Sistem</small>
                        <span class="badge badge-primary">{{ $appVersion }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        <!-- TOP STATS -->
        <div class="row">
            <!-- Total Tenants -->
            <div class="col-lg-3 col-md-6">
                <div class="card sa-stat-card">
                    <div class="card-body">
                        <div class="sa-icon-box" style="background: #eff6ff; color: #3b82f6;">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="sa-stat-label">Total Dapur Aktif</div>
                        <div class="sa-stat-value">{{ number_format($totalTenants) }}</div>
                        <div class="sa-trend text-success">
                            <i class="fas fa-arrow-up"></i> {{ $newTenantsThisMonth }} bergabung bulan ini
                        </div>
                        <hr class="my-2" style="border-top: 1px dashed #e2e8f0;">
                        <div class="sa-trend font-weight-bold text-secondary">
                            <i class="fas fa-users mr-1"></i> {{ number_format($totalUsers) }} Crew dipekerjakan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="col-lg-3 col-md-6">
                <div class="card sa-stat-card">
                    <div class="card-body">
                        <div class="sa-icon-box" style="background: #ecfdf5; color: #10b981;">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="sa-stat-label">Pendapatan Terbayar</div>
                        <div class="sa-stat-value" style="font-size: 1.5rem;">Rp{{ number_format($monthlyRevenue, 0, ',', '.') }}</div>
                        <div class="sa-trend text-muted">
                            Total lifetime: Rp{{ number_format($totalRevenue, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Payments -->
            <div class="col-lg-3 col-md-6">
                <div class="card sa-stat-card">
                    <div class="card-body">
                        <div class="sa-icon-box" style="background: #fffbeb; color: #f59e0b;">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="sa-stat-label">Pending Verifikasi</div>
                        <div class="sa-stat-value">{{ number_format($pendingInvoices) }}</div>
                        <div class="sa-trend text-warning">
                             Menunggu konfirmasi pembayaran
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Tickets -->
            <div class="col-lg-3 col-md-6">
                <div class="card sa-stat-card">
                    <div class="card-body">
                        <div class="sa-icon-box" style="background: #fef2f2; color: #ef4444;">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div class="sa-stat-label">Tiket Terbuka</div>
                        <div class="sa-stat-value">{{ number_format($openTickets) }}</div>
                        <div class="sa-trend text-danger">
                             Membutuhkan respon admin
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHARTS SECTION -->
        <div class="row">
            <!-- Tenant Growth Chart -->
            <div class="col-lg-7">
                <div class="card sa-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Pertumbuhan Tenant</h5>
                        <div class="badge badge-light">6 Bulan Terakhir</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:240px; width:100%">
                            <canvas id="growthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="col-lg-5">
                <div class="card sa-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Pendapatan Bulanan (Juta)</h5>
                        <div class="badge badge-light">Trend Omzet</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:240px; width:100%">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Quick Actions Panel -->
            <div class="col-lg-4">
                <div class="card sa-card h-100">
                    <div class="card-header">
                        <h5>Aksi Cepat Admin</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ url('super-admin/invoices') }}" class="quick-action-btn">
                            <i class="fas fa-check-circle text-success"></i>
                            Verifikasi Pembayaran
                            <span class="badge badge-warning ml-auto">{{ $pendingInvoices }}</span>
                        </a>
                        <a href="{{ url('super-admin/tenants/create') }}" class="quick-action-btn">
                            <i class="fas fa-plus-circle text-primary"></i>
                            Daftarkan Tenant Baru
                        </a>
                        <a href="#" class="quick-action-btn">
                            <i class="fas fa-comments text-info"></i>
                            Respon Tiket Bantuan
                            <span class="badge badge-danger ml-auto">{{ $openTickets }}</span>
                        </a>
                        <a href="{{ url('super-admin/subscription-plans') }}" class="quick-action-btn">
                            <i class="fas fa-gem text-warning"></i>
                            Kelola Paket Langganan
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- System Stats -->
            <div class="col-lg-8">
                <div class="card sa-card h-100">
                    <div class="card-header">
                        <h5>Statistik & Kesehatan Sistem</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4 py-3" style="border-right: 1px solid #f1f5f9;">
                                <h6 class="text-muted text-uppercase text-xs font-weight-bold mb-2">DB Status</h6>
                                <div class="text-success font-weight-bold h5 mb-0"><i class="fas fa-database mr-1"></i> Online</div>
                            </div>
                            <div class="col-md-4 py-3" style="border-right: 1px solid #f1f5f9;">
                                <h6 class="text-muted text-uppercase text-xs font-weight-bold mb-2">Storage Usage</h6>
                                <div class="text-primary font-weight-bold h5 mb-0"><i class="fas fa-hdd mr-1"></i> 92% Free</div>
                            </div>
                            <div class="col-md-4 py-3">
                                <h6 class="text-muted text-uppercase text-xs font-weight-bold mb-2">Super Admins</h6>
                                <div class="text-dark font-weight-bold h5 mb-0"><i class="fas fa-user-shield mr-1"></i> {{ $totalAdmins }} Pengurus</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RECENT TABLES -->
        <div class="row mt-4">
            <!-- Recent Tenants -->
            <div class="col-lg-6">
                <div class="card sa-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Tenant Baru Bergabung</h5>
                        <a href="{{ url('super-admin/tenants') }}" class="btn btn-xs btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Tenant / Dapur</th>
                                    <th>Domain</th>
                                    <th>Terdaftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTenants as $tenant)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $tenant->name ?? $tenant->id }}</div>
                                        <small class="text-muted">ID: {{ $tenant->id }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ url('/' . $tenant->id . '/dashboard') }}" target="_blank" class="badge badge-light text-primary py-1 px-2 border">
                                            <i class="fas fa-external-link-alt mr-1"></i> /{{ $tenant->id }}/dashboard
                                        </a>
                                    </td>
                                    <td>{{ $tenant->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada tenant baru.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Invoices -->
            <div class="col-lg-6">
                <div class="card sa-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Tagihan Terakhir</h5>
                        <a href="{{ url('super-admin/invoices') }}" class="btn btn-xs btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Nomor / Tenant</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInvoices as $invoice)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $invoice->invoice_number }}</div>
                                        <small class="text-muted">{{ $invoice->tenant->name ?? 'N/A' }}</small>
                                    </td>
                                    <td class="font-weight-bold text-dark">Rp{{ number_format($invoice->final_amount, 0, ',', '.') }}</td>
                                    <td>{!! $invoice->status_badge !!}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada tagihan.</td>
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
@endsection

@push('js')
<script src="/adminlte3/plugins/chart.js/Chart.min.js"></script>
<script>
    // Realtime Clock
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

    // Common Chart Options
    var commonOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: { display: false },
        tooltips: {
            backgroundColor: '#1e293b',
            titleFontSize: 13,
            padding: 10,
            cornerRadius: 4,
            intersect: false,
            mode: 'index'
        },
        scales: {
            xAxes: [{ gridLines: { display: false }, ticks: { fontSize: 11 } }],
            yAxes: [{ 
                ticks: { beginAtZero: true, stepSize: 1, fontSize: 11 },
                gridLines: { color: '#f1f5f9', zeroLineColor: '#f1f5f9' }
            }]
        }
    };

    // 1. Growth Chart (Line)
    var ctxGrowth = document.getElementById('growthChart').getContext('2d');
    var gGradient = ctxGrowth.createLinearGradient(0, 0, 0, 300);
    gGradient.addColorStop(0, 'rgba(78, 115, 223, 0.15)');
    gGradient.addColorStop(1, 'rgba(78, 115, 223, 0)');

    new Chart(ctxGrowth, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthsArr) !!},
            datasets: [{
                label: 'Tenant Baru',
                data: {!! json_encode($tenantGrowthArr) !!},
                borderColor: '#4e73df',
                backgroundColor: gGradient,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4e73df',
                pointBorderWidth: 2,
                fill: true,
                lineTension: 0.4
            }]
        },
        options: commonOptions
    });

    // 2. Revenue Chart (Bar)
    var ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthsArr) !!},
            datasets: [{
                label: 'Revenue (jt)',
                data: {!! json_encode($revenueTrendArr) !!},
                backgroundColor: '#1cc88a',
                hoverBackgroundColor: '#13855c',
                barThickness: 20,
                borderRadius: 4
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                xAxes: [{ gridLines: { display: false }, ticks: { fontSize: 11 } }],
                yAxes: [{ 
                    ticks: { beginAtZero: true, fontSize: 11 },
                    gridLines: { color: '#f1f5f9', zeroLineColor: '#f1f5f9' }
                }]
            }
        }
    });
</script>
@endpush
