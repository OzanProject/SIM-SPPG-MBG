@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ route('procurement.purchase-orders.index') }}">Procurement</a></li>
  <li class="breadcrumb-item active">Purchase Orders</li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Purchase Order</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>No PO</th>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th>Total (Rp)</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchase_orders as $po)
                        <tr>
                            <td>{{ $po->po_number }}</td>
                            <td>{{ $po->date }}</td>
                            <td>{{ $po->supplier->name ?? '-' }}</td>
                            <td>{{ number_format($po->total_amount, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $po->status == 'approved' ? 'success' : ($po->status == 'draft' ? 'secondary' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $po->status)) }}
                                </span>
                            </td>
                            <td>{{ $po->creator->name ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data purchase order.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
