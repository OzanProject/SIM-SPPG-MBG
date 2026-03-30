@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
                    <i class="fas fa-cart-plus mr-2 text-success"></i> Catat Penjualan Baru
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
        <form action="{{ route('tenant.sales.store', tenant('id')) }}" method="POST" id="saleForm">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="border-radius:10px;">
                        <div class="card-header bg-white font-weight-bold">Info Transaksi</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="text-sm font-weight-bold">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="text-sm font-weight-bold">Nama Pelanggan (Opsional)</label>
                                <input type="text" name="customer_name" class="form-control" placeholder="Umum">
                            </div>
                            <div class="form-group">
                                <label class="text-sm font-weight-bold">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-control" required>
                                    <option value="cash">💵 Tunai (CASH)</option>
                                    <option value="transfer">🏦 Transfer Bank</option>
                                    <option value="qris">📱 QRIS</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mt-3" style="border-radius:10px; background: #e8f5e9;">
                        <div class="card-body text-center py-4">
                            <h6 class="text-muted mb-1 text-uppercase font-weight-bold" style="font-size: .7rem; letter-spacing: 1px;">Total Bayar</h6>
                            <h2 class="font-weight-bold text-success mb-0" id="grandTotalDisplay">Rp 0</h2>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-block btn-lg shadow-sm mt-3 py-3 font-weight-bold">
                        <i class="fas fa-check-circle mr-2"></i> SIMPAN TRANSAKSI
                    </button>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm border-0" style="border-radius:10px;">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold">Daftar Menu & Pesanan</span>
                        </div>
                        <div class="card-body p-0">
                            <table class="table mb-0" id="itemsTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 50%;">Pilih Menu</th>
                                        <th style="width: 15%;" class="text-center">Harga</th>
                                        <th style="width: 15%;" class="text-center">Qty</th>
                                        <th style="width: 15%;" class="text-center">Subtotal</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="item-row">
                                        <td class="p-2 pl-3">
                                            <select name="items[0][menu_id]" class="form-control menu-select" required onchange="updatePrice(this)">
                                                <option value="">-- Pilih Menu --</option>
                                                @foreach($menus as $menu)
                                                    <option value="{{ $menu->id }}" data-price="{{ $menu->price }}">{{ $menu->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="p-2 text-center align-middle">
                                            <span class="price-display">Rp 0</span>
                                        </td>
                                        <td class="p-2">
                                            <input type="number" name="items[0][qty]" class="form-control text-center qty-input" value="1" min="1" required oninput="calculateRow(this)">
                                        </td>
                                        <td class="p-2 text-center align-middle">
                                            <span class="subtotal-display font-weight-bold">Rp 0</span>
                                        </td>
                                        <td class="p-2 text-center align-middle pr-3">
                                            <button type="button" class="btn btn-link text-danger p-0" onclick="removeRow(this)"><i class="fas fa-times"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="p-3 text-center border-top">
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="addRow()">
                                    <i class="fas fa-plus mr-1"></i> Tambah Item Pesanan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
    let rowIndex = 1;

    function addRow() {
        const tbody = document.querySelector('#itemsTable tbody');
        const newRow = `
            <tr class="item-row">
                <td class="p-2 pl-3">
                    <select name="items[${rowIndex}][menu_id]" class="form-control menu-select" required onchange="updatePrice(this)">
                        <option value="">-- Pilih Menu --</option>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}" data-price="{{ $menu->price }}">{{ $menu->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="p-2 text-center align-middle">
                    <span class="price-display">Rp 0</span>
                </td>
                <td class="p-2">
                    <input type="number" name="items[${rowIndex}][qty]" class="form-control text-center qty-input" value="1" min="1" required oninput="calculateRow(this)">
                </td>
                <td class="p-2 text-center align-middle">
                    <span class="subtotal-display font-weight-bold">Rp 0</span>
                </td>
                <td class="p-2 text-center align-middle pr-3">
                    <button type="button" class="btn btn-link text-danger p-0" onclick="removeRow(this)"><i class="fas fa-times"></i></button>
                </td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', newRow);
        rowIndex++;
    }

    function removeRow(btn) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) {
            btn.closest('tr').remove();
            calculateGrandTotal();
        }
    }

    function updatePrice(select) {
        const price = select.options[select.selectedIndex].dataset.price || 0;
        const row = select.closest('tr');
        row.querySelector('.price-display').innerText = formatRupiah(price);
        calculateRow(row.querySelector('.qty-input'));
    }

    function calculateRow(input) {
        const row = input.closest('tr');
        const select = row.querySelector('.menu-select');
        const price = select.options[select.selectedIndex].dataset.price || 0;
        const qty = input.value || 0;
        const subtotal = price * qty;
        
        row.querySelector('.subtotal-display').innerText = formatRupiah(subtotal);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const select = row.querySelector('.menu-select');
            const price = select.options[select.selectedIndex].dataset.price || 0;
            const qty = row.querySelector('.qty-input').value || 0;
            total += (price * qty);
        });
        document.getElementById('grandTotalDisplay').innerText = formatRupiah(total);
    }

    function formatRupiah(num) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
    }
</script>
@endpush
@endsection
