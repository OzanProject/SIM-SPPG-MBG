@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
                    <i class="fas fa-file-invoice mr-2 text-primary"></i> Detail Penjualan
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('tenant.sales.index', tenant('id')) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content pt-2">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-0" style="border-radius:10px;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-list mr-2 text-primary"></i>Item Pesanan</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="pl-4">Nama Menu</th>
                                        <th class="text-right">Harga Satuan</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-right pr-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sale->details as $detail)
                                    <tr>
                                        <td class="pl-4 font-weight-bold">{{ $detail->menu_name }}</td>
                                        <td class="text-right">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $detail->quantity }}</td>
                                        <td class="text-right pr-4">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light font-weight-bold">
                                    <tr>
                                        <td colspan="3" class="text-right py-3">GRAND TOTAL (TOTAL BAYAR)</td>
                                        <td class="text-right text-success text-lg pr-4 py-3">{{ $sale->formatted_total }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 mb-3" style="border-radius:10px;">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 font-weight-bold">Informasi Transaksi</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-sm">No. Invoice</span>
                            <strong class="text-sm">{{ $sale->invoice_number }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-sm">Tanggal</span>
                            <strong class="text-sm">{{ $sale->date->format('d M Y') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-sm">Pelanggan</span>
                            <strong class="text-sm">{{ $sale->customer_name ?? 'Umum' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-sm">Metode</span>
                            <span class="badge badge-secondary">{{ strtoupper($sale->payment_method) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-sm">Kasir</span>
                            <strong class="text-sm">{{ $sale->user->name ?? '-' }}</strong>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0" style="border-radius:10px;">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 font-weight-bold">Status Akuntansi</h6>
                    </div>
                    <div class="card-body">
                        @if($sale->journal_id)
                            <div class="alert alert-success py-2 text-sm mb-0">
                                <i class="fas fa-check-circle mr-1"></i> Data sudah ter-jurnal otomatis.
                                <br><small>Ref: {{ $sale->journal->reference_number ?? '-' }}</small>
                            </div>
                        @else
                            <div class="alert alert-warning py-2 text-sm mb-0">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Belum ada rekaman jurnal.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
