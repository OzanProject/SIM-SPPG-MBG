@extends('layouts.app')

@section('title', 'Manajemen Dapur Tenant')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold">🍽️ Manajemen Cabang Dapur</h1>
                <p class="text-muted mb-0 small">Total {{ $tenants->count() }} dapur terdaftar di sistem</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ url('/super-admin/billing') }}" class="btn btn-outline-warning btn-sm mr-2">
                    <i class="fas fa-receipt"></i> Lihat Semua Invoice
                </a>
                <a href="{{ url('/super-admin/tenants/create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Dapur Baru
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
                <div class="info-box bg-gradient-primary shadow-sm">
                    <span class="info-box-icon"><i class="fas fa-store"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Dapur</span>
                        <span class="info-box-number">{{ $tenants->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-gradient-success shadow-sm">
                    <span class="info-box-icon"><i class="fas fa-crown"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Berlangganan Aktif</span>
                        <span class="info-box-number">{{ $tenants->filter(fn($t) => $t->plan && $t->plan->price > 0 && $t->pending_invoice === null)->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-gradient-warning shadow-sm">
                    <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Menunggu Verifikasi</span>
                        <span class="info-box-number">{{ $tenants->filter(fn($t) => $t->pending_invoice && $t->pending_invoice->payment_proof)->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-gradient-secondary shadow-sm">
                    <span class="info-box-icon"><i class="fas fa-gift"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Paket Gratis</span>
                        <span class="info-box-number">{{ $tenants->filter(fn($t) => !$t->plan || $t->plan->price == 0)->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- TENANT CARDS --}}
        @forelse ($tenants as $tenant)
        <div class="card shadow-sm mb-4" style="border-left: 4px solid {{ $tenant->plan ? ($tenant->plan->price > 0 ? '#007bff' : '#6c757d') : '#6c757d' }};">
            <div class="card-body py-3">
                <div class="row align-items-center">

                    {{-- COL 1: IDENTITY --}}
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 text-white font-weight-bold"
                                style="width:48px;height:48px;font-size:18px;background: linear-gradient(135deg, #667eea, #764ba2);flex-shrink:0;">
                                {{ strtoupper(substr($tenant->id, 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-0 font-weight-bold text-dark">{{ $tenant->name ?? $tenant->id }}</h6>
                                <code class="text-muted small">{{ $tenant->id }}</code>
                                @if($tenant->central_user)
                                    <br><small class="text-muted"><i class="fas fa-user-circle mr-1"></i>{{ $tenant->central_user->name }}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- COL 2: PLAN --}}
                    <div class="col-md-2 text-center">
                        @if($tenant->plan)
                            @php
                                $badgeClass = match(strtolower($tenant->plan->slug ?? '')) {
                                    'pro', 'professional' => 'badge-primary',
                                    'enterprise' => 'badge-dark',
                                    default => 'badge-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} px-3 py-2" style="font-size:.8rem;">
                                {{ strtolower($tenant->plan->slug ?? '') === 'pro' ? '👑' : '🎁' }}
                                {{ $tenant->plan->name }}
                            </span>
                            <br>
                            <small class="text-muted">Rp {{ number_format($tenant->plan->price, 0, ',', '.') }}/bln</small>
                        @else
                            <span class="badge badge-secondary px-2 py-1">🎁 Gratis</span>
                        @endif
                    </div>

                    {{-- COL 3: STATUS SUBSCRIPTION --}}
                    <div class="col-md-2 text-center">
                        @if($tenant->pending_invoice)
                            @if($tenant->pending_invoice->payment_proof)
                                <span class="badge badge-warning text-dark px-2 py-1">
                                    <i class="fas fa-clock mr-1"></i>Menunggu Verif.
                                </span>
                                <br>
                                <small class="text-muted">Bukti sudah dikirim</small>
                            @else
                                <span class="badge badge-danger px-2 py-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>Belum Bayar
                                </span>
                                <br>
                                <small class="text-muted">Jatuh tempo: {{ $tenant->pending_invoice->due_date?->format('d M Y') }}</small>
                            @endif
                        @elseif($tenant->subscription_ends_at)
                            @if(now()->isAfter($tenant->subscription_ends_at))
                                <span class="badge badge-danger px-2 py-1">
                                    <i class="fas fa-times-circle mr-1"></i>Kadaluarsa
                                </span>
                            @else
                                <span class="badge badge-success px-2 py-1">
                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                </span>
                                <br>
                                <small class="text-muted">s/d {{ \Carbon\Carbon::parse($tenant->subscription_ends_at)->format('d M Y') }}</small>
                            @endif
                        @else
                            <span class="badge badge-secondary px-2 py-1">Tanpa Batas</span>
                        @endif
                    </div>

                    {{-- COL 4: CONTACT --}}
                    <div class="col-md-2 text-center">
                        @if($tenant->central_user)
                            <small class="d-block text-muted"><i class="fas fa-envelope mr-1"></i>{{ $tenant->central_user->email }}</small>
                            @if($tenant->central_user->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->central_user->whatsapp) }}" target="_blank"
                                    class="badge badge-success mt-1">
                                    <i class="fab fa-whatsapp mr-1"></i>{{ $tenant->central_user->whatsapp }}
                                </a>
                            @endif
                        @else
                            <small class="text-muted">-</small>
                        @endif
                        <br>
                        <small class="text-muted text-xs"><i class="fas fa-calendar mr-1"></i>Daftar {{ $tenant->created_at->format('d M Y') }}</small>
                    </div>

                    {{-- COL 5: ACTIONS --}}
                    <div class="col-md-3 text-right">
                        <a href="{{ url('/' . $tenant->id . '/dashboard') }}" target="_blank"
                            class="btn btn-outline-primary btn-sm mr-1" title="Buka Dashboard Dapur">
                            <i class="fas fa-external-link-alt"></i> Dashboard
                        </a>

                        @if($tenant->pending_invoice)
                            <a href="{{ url('/super-admin/billing/' . $tenant->pending_invoice->id) }}"
                                class="btn btn-warning btn-sm mr-1 {{ $tenant->pending_invoice->payment_proof ? '' : 'disabled' }}"
                                title="{{ $tenant->pending_invoice->payment_proof ? 'Verifikasi Pembayaran' : 'Menunggu Bukti' }}">
                                <i class="fas fa-check-double"></i> Verif
                            </a>

                            @if($tenant->pending_invoice->payment_proof)
                                <form action="{{ url('/super-admin/billing/'.$tenant->pending_invoice->id.'/mark-paid') }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Tandai pembayaran {{ $tenant->id }} sebagai LUNAS?')">
                                    @csrf
                                    <button class="btn btn-success btn-sm mr-1" title="Tandai Lunas">
                                        <i class="fas fa-check"></i> Lunas
                                    </button>
                                </form>
                            @endif
                        @endif

                        <form action="{{ url('/super-admin/tenants/'.$tenant->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('⚠️ HAPUS PERMANEN dapur \'{{ $tenant->id }}\' beserta database dan semua datanya?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm" title="Hapus Dapur">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- FOOTER CARD: domain & invoice info --}}
            <div class="card-footer py-2 bg-light d-flex align-items-center justify-content-between" style="font-size:.78rem;">
                <span class="text-muted">
                    <i class="fas fa-link mr-1"></i>
                    <a href="{{ url('/' . $tenant->id . '/dashboard') }}" target="_blank" class="text-decoration-none">
                        <code>{{ url('/' . $tenant->id . '/dashboard') }}</code>
                    </a>
                </span>
                @if($tenant->latest_invoice)
                    <span class="text-muted">
                        Invoice terakhir:
                        <strong>#{{ $tenant->latest_invoice->invoice_number }}</strong>
                        — Rp {{ number_format($tenant->latest_invoice->final_amount, 0, ',', '.') }}
                        <span class="badge badge-{{ $tenant->latest_invoice->status === 'paid' ? 'success' : ($tenant->latest_invoice->status === 'pending' ? 'warning text-dark' : 'secondary') }} px-1">
                            {{ ucfirst($tenant->latest_invoice->status) }}
                        </span>
                    </span>
                @endif
            </div>
        </div>
        @empty
            <div class="card shadow-sm">
                <div class="card-body text-center py-5 text-muted">
                    <i class="fas fa-store fa-3x mb-3 text-muted opacity-50"></i>
                    <h5>Belum ada cabang (tenant) yang terdaftar.</h5>
                    <a href="{{ url('/super-admin/tenants/create') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-plus"></i> Tambah Dapur Pertama
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection
