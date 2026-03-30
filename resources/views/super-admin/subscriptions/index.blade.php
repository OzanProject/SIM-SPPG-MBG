@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold">📦 Manajemen Paket Langganan</h1>
                <p class="text-muted mb-0 small">Atur batasan fitur dan harga untuk pengguna SaaS Mbg AkunPro</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ url('/super-admin/tenants') }}" class="btn btn-outline-secondary btn-sm mr-2">
                    <i class="fas fa-store"></i> Lihat Semua Dapur
                </a>
                <a href="{{ url('/super-admin/subscriptions/create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Paket Baru
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- STATS ROW --}}
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-white shadow-sm border border-light">
                    <span class="info-box-icon bg-primary bg-gradient-primary"><i class="fas fa-box-open"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted font-weight-bold">Total Paket Utama</span>
                        <span class="info-box-number text-dark" style="font-size:1.5rem;">{{ $stats['total_plans'] }} <small>Aktif: {{ $stats['active_plans'] }}</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-white shadow-sm border border-light">
                    <span class="info-box-icon bg-success bg-gradient-success"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted font-weight-bold">Total Dapur Aktif</span>
                        <span class="info-box-number text-dark" style="font-size:1.5rem;">{{ $stats['total_subscribers'] }} <small>Cabang</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-white shadow-sm border border-light">
                    <span class="info-box-icon bg-info bg-gradient-info"><i class="fas fa-chart-line"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted font-weight-bold">Potensi Omzet/Bulan</span>
                        <span class="info-box-number text-dark" style="font-size:1.5rem;">Rp {{ number_format($stats['total_revenue_potential'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- PRICING CARDS ROW --}}
        <div class="row justify-content-center">
            @forelse ($plans as $plan)
                <div class="col-lg-3 col-md-4 col-sm-6 d-flex align-items-stretch">
                    <div class="card shadow-sm w-100 flex-fill {{ !$plan->is_active ? 'opacity-75' : '' }}" style="border-top: 5px solid {{ $plan->price > 0 ? '#667eea' : '#a0aec0' }}; transition: transform 0.2s;">
                        
                        {{-- STATUS BADGE --}}
                        @if(!$plan->is_active)
                            <div class="ribbon-wrapper ribbon-lg">
                                <div class="ribbon bg-danger text-lg">NONAKTIF</div>
                            </div>
                        @elseif($plan->price == 0)
                            <div class="ribbon-wrapper ribbon-lg">
                                <div class="ribbon bg-success text-lg">GRATIS</div>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column text-center pt-4">
                            
                            {{-- HEADER IDENTITAS --}}
                            <h4 class="font-weight-bold text-dark mb-1 text-uppercase tracking-wide">{{ $plan->name }}</h4>
                            <p class="text-muted small mb-3 flex-grow-1">{{ $plan->description ?? 'Tidak ada deskripsi singkat.' }}</p>
                            
                            <h2 class="font-weight-black text-primary mb-0" style="font-size: 2.2rem; font-weight: 900;">
                                @if($plan->price > 0)
                                    Rp {{ number_format($plan->price, 0, ',', '.') }}
                                    <span class="text-muted" style="font-size: 0.9rem; font-weight: normal;">/{{ $plan->duration_in_days }} Hari</span>
                                @else
                                    GRATIS
                                    <span class="text-muted" style="font-size: 0.9rem; font-weight: normal;">Selamanya</span>
                                @endif
                            </h2>
                            
                            <hr class="w-100 my-4" style="border-top: 1px dashed rgba(0,0,0,.1);">

                            {{-- BATASAN LIMIT --}}
                            <ul class="list-unstyled text-left small mb-4 text-secondary" style="line-height: 1.8;">
                                <li class="mb-2">
                                    <i class="fas fa-user-friends text-primary mr-2 w-15px text-center"></i> 
                                    <strong>{{ $plan->max_users == 0 ? 'Unlimited' : $plan->max_users }}</strong> Akun Staff
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-boxes text-warning mr-2 w-15px text-center"></i> 
                                    <strong>{{ $plan->max_items == 0 ? 'Unlimited' : $plan->max_items }}</strong> Item Inventory
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-exchange-alt text-info mr-2 w-15px text-center"></i> 
                                    <strong>{{ $plan->max_transactions_per_month == 0 ? 'Unlimited' : $plan->max_transactions_per_month }}</strong> Transaksi/Bln
                                </li>
                            </ul>

                            {{-- FITUR CENTANG --}}
                            <div class="bg-light rounded p-3 text-left small mb-4 flex-grow-1">
                                <strong class="d-block mb-2 text-dark font-weight-bold text-uppercase" style="font-size: 0.75rem;">Akses Modul Inti:</strong>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="fas {{ $plan->has_sales ? 'fa-check text-success' : 'fa-times text-danger' }} mr-2 w-15px text-center"></i>
                                    <span class="{{ !$plan->has_sales ? 'text-muted strike-through' : 'font-weight-medium' }}">Penjualan & Kasir</span>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="fas {{ $plan->has_inventory ? 'fa-check text-success' : 'fa-times text-danger' }} mr-2 w-15px text-center"></i>
                                    <span class="{{ !$plan->has_inventory ? 'text-muted strike-through' : 'font-weight-medium' }}">Manajemen Stok (Inventory)</span>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="fas {{ $plan->has_procurement ? 'fa-check text-success' : 'fa-times text-danger' }} mr-2 w-15px text-center"></i>
                                    <span class="{{ !$plan->has_procurement ? 'text-muted strike-through' : 'font-weight-medium' }}">Pengadaan (PO) / Supplier</span>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="fas {{ $plan->has_accounting_full ? 'fa-check text-success' : 'fa-times text-danger' }} mr-2 w-15px text-center"></i>
                                    <span class="{{ !$plan->has_accounting_full ? 'text-muted strike-through' : 'font-weight-medium' }}">Akuntansi Lengkap (Ledger)</span>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="fas {{ $plan->has_budgeting ? 'fa-check text-success' : 'fa-times text-danger' }} mr-2 w-15px text-center"></i>
                                    <span class="{{ !$plan->has_budgeting ? 'text-muted strike-through' : 'font-weight-medium' }}">Sistem Anggaran (Budgeting)</span>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="fas {{ $plan->has_hr ? 'fa-check text-success' : 'fa-times text-danger' }} mr-2 w-15px text-center"></i>
                                    <span class="{{ !$plan->has_hr ? 'text-muted strike-through' : 'font-weight-medium' }}">HR / Penggajian (Payroll)</span>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="fas {{ $plan->has_circle_menu ? 'fa-check text-success' : 'fa-times text-danger' }} mr-2 w-15px text-center"></i>
                                    <span class="{{ !$plan->has_circle_menu ? 'text-muted strike-through' : 'font-weight-medium' }}">Distribusi Menu Circle</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas {{ $plan->can_export ? 'fa-check text-success' : 'fa-times text-danger' }} mr-2 w-15px text-center"></i>
                                    <span class="{{ !$plan->can_export ? 'text-muted strike-through' : 'font-weight-medium' }}">Export Data Excel/PDF</span>
                                </div>
                            </div>
                        </div>

                        {{-- FOOTER / ACTIONS --}}
                        <div class="card-footer bg-white border-top-0 pb-4 px-4 text-center">
                            <div class="mb-3">
                                <span class="badge badge-light px-3 py-2 text-dark border w-100">
                                    <i class="fas fa-users mr-1"></i> Digunakan oleh <strong>{{ $plan->tenants_count ?? 0 }}</strong> Dapur
                                </span>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ url('/super-admin/subscriptions/' . $plan->id . '/edit') }}" class="btn btn-warning btn-sm flex-fill mr-1 font-weight-bold">
                                    <i class="fas fa-edit"></i> Edit Paket
                                </a>
                                <form action="{{ url('/super-admin/subscriptions/' . $plan->id) }}" method="POST" class="d-inline flex-fill ml-1" onsubmit="return confirm('⚠️ Peringatan: Hapus paket \'{{ $plan->name }}\' secara permanen? Pastikan tidak ada tenant yang sedang menggunakannya, atau mereka akan kehilangan akses!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 font-weight-bold" {{ $plan->tenants_count > 0 ? 'disabled title="Harap kosongkan tenant pengguna terlebih dahulu!"' : '' }}>
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white shadow-sm rounded border border-light d-inline-block">
                        <i class="fas fa-box-open mb-3 text-muted" style="font-size: 4rem; display: block; opacity: 0.5;"></i>
                        <h4 class="text-dark font-weight-bold">Belum Ada Paket Langganan</h4>
                        <p class="text-muted mb-4 max-w-md mx-auto">Buat paket layanan pertama Anda (seperti paket 'Gratis' atau 'Premium') agar klien dapat mulai mendaftar ke sistem cabang/dapur.</p>
                        <a href="{{ url('/super-admin/subscriptions/create') }}" class="btn btn-primary px-4 shadow-sm"><i class="fas fa-plus mr-1"></i> Buat Paket Pertama</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<style>
    .font-weight-medium { font-weight: 500; }
    .strike-through { text-decoration: line-through; opacity: 0.6; }
    .w-15px { width: 15px; display: inline-block; text-align: center; }
</style>
@endsection
