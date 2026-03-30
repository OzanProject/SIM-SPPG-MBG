@extends('layouts.app')

@push('css')
<style>
    .item-row:hover { background: #f8f9fa; }
</style>
@endpush

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
                    <i class="fas fa-shopping-cart mr-2 text-success"></i> Riwayat Penjualan
                </h1>
                <p class="text-muted text-sm mb-0">Daftar transaksi penjualan harian dapur Anda.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('tenant.sales.create', tenant('id')) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus mr-1"></i> Catat Penjualan Baru
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content pt-2">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm border-0" style="border-radius:10px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 pl-4">No. Invoice</th>
                                <th class="border-0">Tanggal</th>
                                <th class="border-0">Pelanggan</th>
                                <th class="border-0">Metode</th>
                                <th class="border-0 text-right">Total</th>
                                <th class="border-0 text-center">Status Jurnal</th>
                                <th class="border-0 text-right pr-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                            <tr>
                                <td class="pl-4 font-weight-bold">{{ $sale->invoice_number }}</td>
                                <td>{{ $sale->date->format('d/m/Y') }}</td>
                                <td>{{ $sale->customer_name ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-light border">{{ strtoupper($sale->payment_method) }}</span>
                                </td>
                                <td class="text-right font-weight-bold text-success">{{ $sale->formatted_total }}</td>
                                <td class="text-center">
                                    @if($sale->journal_id)
                                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Tersinkron</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="text-right pr-4">
                                    <a href="{{ route('tenant.sales.show', [tenant('id'), $sale->id]) }}" class="btn btn-outline-info btn-xs">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-shopping-basket fa-3x mb-3 text-light"></i>
                                    <p>Belum ada transaksi penjualan.</p>
                                    <a href="{{ route('tenant.sales.create', tenant('id')) }}" class="btn btn-success btn-sm">Catat Sekarang</a>
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
