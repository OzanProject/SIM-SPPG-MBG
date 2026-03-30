@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">Billing & Invoice</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/dashboard') }}"><i class="fas fa-home text-primary"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active">Billing / Invoice</li>
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

        {{-- STATISTIK RINGKASAN --}}
        <div class="row mb-4">
            <div class="col-lg col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total'] }}</h3>
                        <p>Total Invoice</p>
                    </div>
                    <div class="icon"><i class="fas fa-file-invoice"></i></div>
                </div>
            </div>
            <div class="col-lg col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['pending'] }}</h3>
                        <p>Menunggu Bayar</p>
                    </div>
                    <div class="icon"><i class="fas fa-clock"></i></div>
                </div>
            </div>
            <div class="col-lg col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['paid'] }}</h3>
                        <p>Sudah Lunas</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
            <div class="col-lg col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['expired'] }}</h3>
                        <p>Kadaluarsa</p>
                    </div>
                    <div class="icon"><i class="fas fa-times-circle"></i></div>
                </div>
            </div>
            <div class="col-lg col-6">
                <div class="small-box" style="background: #6f42c1; color:#fff;">
                    <div class="inner">
                        <h3 style="font-size: 1.5rem;">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</h3>
                        <p>Total Pendapatan</p>
                    </div>
                    <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                </div>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body py-2">
                <form method="GET" action="{{ url('/super-admin/billing') }}" class="form-inline flex-wrap gap-2">
                    <select name="status" class="form-control form-control-sm mr-2 mb-1">
                        <option value="">Semua Status</option>
                        <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Menunggu</option>
                        <option value="paid"      {{ request('status') === 'paid'      ? 'selected' : '' }}>Lunas</option>
                        <option value="expired"   {{ request('status') === 'expired'   ? 'selected' : '' }}>Kadaluarsa</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <select name="tenant" class="form-control form-control-sm mr-2 mb-1">
                        <option value="">Semua Cabang</option>
                        @foreach($tenants as $t)
                            <option value="{{ $t->id }}" {{ request('tenant') === $t->id ? 'selected' : '' }}>{{ $t->id }}</option>
                        @endforeach
                    </select>
                    <input type="month" name="month" class="form-control form-control-sm mr-2 mb-1" value="{{ request('month') }}">
                    <button type="submit" class="btn btn-sm btn-primary mb-1 mr-1"><i class="fas fa-filter mr-1"></i>Filter</button>
                    <a href="{{ url('/super-admin/billing') }}" class="btn btn-sm btn-default mb-1">Reset</a>
                    <div class="ml-auto mb-1">
                        <a href="{{ url('/super-admin/billing/create') }}" class="btn btn-sm btn-success font-weight-bold">
                            <i class="fas fa-plus mr-1"></i> Buat Invoice
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABEL INVOICE --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="pl-4">No. Invoice</th>
                                <th>Cabang Dapur</th>
                                <th>Paket</th>
                                <th>Promo</th>
                                <th class="text-right">Tagihan</th>
                                <th>Jatuh Tempo</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $inv)
                                <tr>
                                    <td class="pl-4">
                                        <a href="{{ url('/super-admin/billing/' . $inv->id) }}" class="font-weight-bold text-primary">
                                            {{ $inv->invoice_number }}
                                        </a>
                                        <br><small class="text-muted">{{ $inv->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td class="font-weight-bold">{{ $inv->tenant_id }}</td>
                                    <td>{{ $inv->subscriptionPlan->name ?? '-' }}</td>
                                    <td>
                                        @if($inv->promoCode)
                                            <span class="badge badge-info">{{ $inv->promoCode->code }}</span>
                                            <small class="text-muted d-block">-Rp {{ number_format($inv->discount_amount, 0, ',', '.') }}</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        @if($inv->discount_amount > 0)
                                            <small class="text-muted text-decoration-line-through d-block">Rp {{ number_format($inv->base_amount, 0, ',', '.') }}</small>
                                        @endif
                                        Rp {{ number_format($inv->final_amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="{{ $inv->due_date->isPast() && $inv->status !== 'paid' ? 'text-danger font-weight-bold' : 'text-dark' }}">
                                            {{ $inv->due_date->format('d M Y') }}
                                        </span>
                                        @if($inv->paid_at)
                                            <br><small class="text-success"><i class="fas fa-check mr-1"></i>Dibayar {{ $inv->paid_at->format('d M Y') }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{!! $inv->status_badge !!}</td>
                                    <td class="text-center" style="white-space: nowrap;">
                                        <a href="{{ url('/super-admin/billing/' . $inv->id) }}" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('billing.download', $inv->id) }}" class="btn btn-primary btn-sm" title="Cetak Invoice"><i class="fas fa-print"></i></a>
                                        <a href="{{ url('/super-admin/billing/' . $inv->id . '/edit') }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                        @if($inv->status === 'pending')
                                            <form action="{{ route('billing.markPaid', $inv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tandai invoice ini sebagai Lunas?');">
                                                @csrf
                                                <button class="btn btn-success btn-sm" title="Tandai Lunas"><i class="fas fa-check"></i></button>
                                            </form>
                                        @endif
                                        <form action="{{ url('/super-admin/billing/' . $inv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus invoice ini secara permanen?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-file-invoice mb-3 text-muted" style="font-size: 3rem; display: block;"></i>
                                        <p class="text-muted mb-3">Belum ada invoice. Buat invoice pertama untuk mulai menagih.</p>
                                        <a href="{{ url('/super-admin/billing/create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus mr-1"></i> Buat Invoice</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($invoices->hasPages())
                    <div class="p-3">{{ $invoices->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
