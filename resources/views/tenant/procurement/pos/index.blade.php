@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Purchase Order (PO)</h1>
                <p class="text-sm text-muted mb-0">Kelola pesanan pembelian barang ke supplier.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('procurement.pos.create', tenant('id')) }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                    <i class="fas fa-plus mr-1 text-xs"></i> Buat PO Baru
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="info-box shadow-sm border-0">
                    <span class="info-box-icon bg-warning text-white"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-xs uppercase font-weight-bold">Menunggu</span>
                        <span class="info-box-number h4 mb-0">{{ $pos->where('status', 'pending_approval')->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box shadow-sm border-0">
                    <span class="info-box-icon bg-success"><i class="fas fa-check-double"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-xs uppercase font-weight-bold">Diterima</span>
                        <span class="info-box-number h4 mb-0">{{ $pos->where('status', 'received')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary shadow-sm border-0 mt-2">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4">No. PO</th>
                                <th class="border-0">Supplier</th>
                                <th class="border-0">Tanggal</th>
                                <th class="border-0">Estimasi Tiba</th>
                                <th class="border-0 text-right">Total Amount</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-right px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pos as $po)
                            <tr>
                                <td class="px-4 font-weight-bold text-primary">{{ $po->po_number }}</td>
                                <td>{{ $po->supplier->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($po->date)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($po->expected_delivery_date)->format('d/m/Y') }}</td>
                                <td class="text-right font-weight-bold text-dark">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @php
                                        $badges = [
                                            'draft' => 'secondary',
                                            'pending_approval' => 'warning',
                                            'approved' => 'info',
                                            'received' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge badge-{{ $badges[$po->status] }} px-2 py-1" style="font-size: 0.7rem;">
                                        {{ strtoupper(str_replace('_', ' ', $po->status)) }}
                                    </span>
                                </td>
                                <td class="text-right px-4">
                                    <a href="{{ route('procurement.pos.show', [tenant('id'), $po->id]) }}" class="btn btn-default btn-xs rounded-pill px-2 shadow-sm border">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-shopping-cart fa-3x text-light mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada Purchase Order.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
