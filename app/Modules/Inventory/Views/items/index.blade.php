@extends('layouts.app')

@section('title', 'Stok Barang')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ route('inventory.items.index') }}">Inventory</a></li>
  <li class="breadcrumb-item active">Stok Barang</li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Barang</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Harga Rata-rata</th>
                            <th>Min Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                @if($item->stock <= $item->minimum_stock)
                                    <span class="badge badge-danger">{{ $item->stock }}</span>
                                @else
                                    <span class="badge badge-success">{{ $item->stock }}</span>
                                @endif
                            </td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ number_format($item->average_price, 2) }}</td>
                            <td>{{ $item->minimum_stock }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data barang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
