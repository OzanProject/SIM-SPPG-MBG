@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Detail Purchase Order</h1>
                <p class="text-sm text-muted mb-0">No. PO: <span class="text-primary font-weight-bold">{{ $po->po_number }}</span></p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('procurement.pos.index', tenant('id')) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fas fa-arrow-left mr-1 text-xs"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content pb-5 text-sm">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <div class="row mb-5">
                            <div class="col-sm-6">
                                <h5 class="font-weight-bold text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Supplier</h5>
                                <h4 class="font-weight-bold text-dark mb-1">{{ $po->supplier->name }}</h4>
                                <p class="mb-1 text-muted">{{ $po->supplier->contact_person }}</p>
                                <p class="mb-1 text-muted">{{ $po->supplier->phone }}</p>
                                <p class="mb-0 text-muted">{{ $po->supplier->address }}</p>
                            </div>
                            <div class="col-sm-6 text-sm-right mt-4 mt-sm-0">
                                <h5 class="font-weight-bold text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Informasi PO</h5>
                                <div class="mb-1 text-muted">Tanggal: <span class="text-dark font-weight-bold">{{ \Carbon\Carbon::parse($po->date)->format('d F Y') }}</span></div>
                                <div class="mb-1 text-muted">Estimasi Tiba: <span class="text-dark font-weight-bold">{{ \Carbon\Carbon::parse($po->expected_delivery_date)->format('d F Y') }}</span></div>
                                <div>Status: 
                                    @php
                                        $badges = [
                                            'draft' => 'secondary',
                                            'pending_approval' => 'warning',
                                            'approved' => 'info',
                                            'received' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge badge-{{ $badges[$po->status] ?? 'secondary' }} px-3 py-1 ml-2">{{ strtoupper(str_replace('_', ' ', $po->status)) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="bg-light">
                                    <tr class="text-uppercase text-muted font-weight-bold" style="font-size: 0.7rem;">
                                        <th class="border-0 px-3">Nama Barang</th>
                                        <th class="border-0 text-center">Qty</th>
                                        <th class="border-0 text-right">Harga Satuan</th>
                                        <th class="border-0 text-right pr-3">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($po->items as $item)
                                    <tr>
                                        <td class="px-3">
                                            <div class="font-weight-bold text-dark">{{ $item->inventoryItem->name }}</div>
                                            <div class="text-xs text-muted">SKU: {{ $item->inventoryItem->code }}</div>
                                        </td>
                                        <td class="text-center">{{ number_format($item->quantity) }} {{ $item->inventoryItem->unit }}</td>
                                        <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="text-right font-weight-bold text-dark pr-3">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="border-top-0">
                                    <tr>
                                        <th colspan="3" class="text-right py-4 border-0">GRAND TOTAL</th>
                                        <th class="text-right pr-3 py-4 border-0"><h4 class="font-weight-bold text-primary mb-0">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</h4></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if($po->notes)
                        <div class="mt-4 p-3 bg-light border-left border-primary" style="border-radius: 4px;">
                            <h6 class="font-weight-bold text-xs text-uppercase text-muted mb-2">Catatan Pesanan:</h6>
                            <p class="mb-0">{{ $po->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                    <div class="card-header bg-white"><h5 class="card-title font-weight-bold text-sm">Action Center</h5></div>
                    <div class="card-body">
                        @if($po->status === 'pending_approval')
                        <form action="{{ route('procurement.pos.update-status', [tenant('id'), $po->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success btn-block rounded-pill shadow-sm font-weight-bold mb-3">
                                <i class="fas fa-check-circle mr-2"></i> SETUJUI PO
                            </button>
                        </form>
                        @endif

                        @if($po->status === 'approved')
                        <div class="alert alert-info py-2" style="font-size: 0.75rem;">
                            <i class="fas fa-info-circle mr-1"></i> Klik tombol di bawah jika barang sudah tiba di gudang.
                        </div>
                        <form action="{{ route('procurement.pos.update-status', [tenant('id'), $po->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="received">
                            <button type="submit" class="btn btn-success btn-block rounded-pill shadow-sm font-weight-bold mb-3" onclick="return confirm('Apakah barang benar-benar sudah diterima? Stok akan bertambah dan jurnal hutang akan dibuat.')">
                                <i class="fas fa-truck-loading mr-2"></i> BARANG DITERIMA
                            </button>
                        </form>
                        @endif

                        @if(in_array($po->status, ['pending_approval', 'approved']))
                        <form action="{{ route('procurement.pos.update-status', [tenant('id'), $po->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-outline-danger btn-block rounded-pill font-weight-bold" onclick="return confirm('Batalkan pesanan ini?')">
                                <i class="fas fa-times-circle mr-2"></i> BATALKAN PO
                            </button>
                        </form>
                        @endif

                        @if($po->status === 'received')
                        <div class="text-center py-3">
                            <i class="fas fa-check-double fa-3x text-success mb-3"></i>
                            <h6 class="font-weight-bold text-success">PESANAN SELESAI</h6>
                            <p class="text-xs text-muted mb-0">Barang telah masuk gudang & terjurnal.</p>
                        </div>
                        @endif

                        @if($po->status === 'cancelled')
                        <div class="text-center py-3">
                            <i class="fas fa-ban fa-3x text-danger mb-3"></i>
                            <h6 class="font-weight-bold text-danger">DIBATALKAN</h6>
                        </div>
                        @endif

                        <hr>
                        <button type="button" class="btn btn-default btn-block rounded-pill shadow-sm font-weight-bold mt-2" onclick="window.print()">
                            <i class="fas fa-print mr-2"></i> CETAK PO
                        </button>
                    </div>
                </div>

                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-white"><h5 class="card-title font-weight-bold text-sm">Audit Trail</h5></div>
                    <div class="card-body p-3">
                        <div class="text-xs text-muted mb-2">Dibuat oleh: <span class="text-dark font-weight-bold">{{ $po->creator->name ?? 'System' }}</span></div>
                        <div class="text-xs text-muted">Tgl Dibuat: <span class="text-dark">{{ $po->created_at->format('d/m/Y H:i') }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
