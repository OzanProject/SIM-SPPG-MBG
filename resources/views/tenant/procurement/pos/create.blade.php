@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Buat Purchase Order</h1>
                <p class="text-sm text-muted mb-0">Lengkapi formulir pesanan pembelian ke supplier.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('procurement.pos.index', tenant('id')) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fas fa-arrow-left mr-1 text-xs"></i> Batal / Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content pb-5">
    <div class="container-fluid">
        <form action="{{ route('procurement.pos.store', tenant('id')) }}" method="POST" id="poForm">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-outline card-primary shadow-sm border-0">
                        <div class="card-header bg-white"><h5 class="card-title font-weight-bold text-sm">Informasi Pesanan</h5></div>
                        <div class="card-body p-4 bg-light">
                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Supplier <span class="text-danger">*</span></label>
                                <select name="supplier_id" class="form-control select2 border-0 shadow-sm" style="border-radius: 8px;" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Tanggal PO <span class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control border-0 shadow-sm" value="{{ date('Y-m-d') }}" required style="border-radius: 8px;">
                            </div>
                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Estimasi Tiba <span class="text-danger">*</span></label>
                                <input type="date" name="expected_delivery_date" class="form-control border-0 shadow-sm" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required style="border-radius: 8px;">
                            </div>
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Catatan (Opsional)</label>
                                <textarea name="notes" class="form-control border-0 shadow-sm" rows="2" placeholder="Keterangan tambahan..." style="border-radius: 8px;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card card-outline card-primary shadow-sm border-0">
                        <div class="card-header bg-white d-flex align-items-center">
                            <h5 class="card-title font-weight-bold text-sm mb-0">Daftar Item Pesanan</h5>
                            <button type="button" class="btn btn-success btn-xs ml-auto rounded-pill px-3 shadow-sm" id="addItemRow">
                                <i class="fas fa-plus mr-1"></i> Tambah Baris
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="itemsTable">
                                    <thead class="bg-light">
                                        <tr class="text-xs font-weight-bold text-uppercase text-muted">
                                            <th class="border-0 px-4" style="width: 40%;">Pilih Barang</th>
                                            <th class="border-0 text-center" style="width: 15%;">Qty</th>
                                            <th class="border-0 text-right" style="width: 20%;">Harga Satuan</th>
                                            <th class="border-0 text-right" style="width: 20%;">Subtotal</th>
                                            <th class="border-0 text-center" style="width: 5%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsBody">
                                        <tr class="item-row">
                                            <td class="px-4 py-3">
                                                <select name="items[0][inventory_item_id]" class="form-control select2 item-select" required>
                                                    <option value="">-- Pilih --</option>
                                                    @foreach($items as $ii)
                                                    <option value="{{ $ii->id }}" data-price="{{ $ii->average_price }}">{{ $ii->name }} ({{ $ii->unit }})</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="py-3">
                                                <input type="number" name="items[0][quantity]" class="form-control text-center item-qty" min="0.01" step="0.01" required value="1">
                                            </td>
                                            <td class="py-3">
                                                <input type="number" name="items[0][unit_price]" class="form-control text-right item-price" min="0" required placeholder="0">
                                            </td>
                                            <td class="py-3 text-right pr-4 align-middle">
                                                <span class="font-weight-bold text-dark item-subtotal">Rp 0</span>
                                            </td>
                                            <td class="py-3 text-center align-middle">
                                                <button type="button" class="btn btn-link text-danger remove-row p-0"><i class="fas fa-times"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="bg-light border-top">
                                        <tr>
                                            <th colspan="3" class="text-right py-3">TOTAL KESELURUHAN</th>
                                            <th class="text-right pr-4 py-3"><h4 class="font-weight-bold text-primary mb-0" id="grandTotal">Rp 0</h4></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white py-3">
                            <button type="submit" class="btn btn-primary btn-block rounded-pill shadow-lg font-weight-bold py-2">
                                <i class="fas fa-paper-plane mr-2"></i> SIMPAN & PROSES PO
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@push('js')
<script>
    $(document).ready(function() {
        let rowCount = 1;

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        }

        function calculateSubtotal(row) {
            let qty = parseFloat(row.find('.item-qty').val()) || 0;
            let price = parseFloat(row.find('.item-price').val()) || 0;
            let subtotal = qty * price;
            row.find('.item-subtotal').text(formatRupiah(subtotal));
            return subtotal;
        }

        function calculateGrandTotal() {
            let grandTotal = 0;
            $('.item-row').each(function() {
                grandTotal += calculateSubtotal($(this));
            });
            $('#grandTotal').text(formatRupiah(grandTotal));
        }

        // Add Row
        $('#addItemRow').on('click', function() {
            let newRow = $('.item-row').first().clone();
            newRow.find('select').attr('name', `items[${rowCount}][inventory_item_id]`).val('').removeClass('select2-hidden-accessible');
            newRow.find('.select2-container').remove(); // Remove cloned select2 UI
            newRow.find('.item-qty').attr('name', `items[${rowCount}][quantity]`).val(1);
            newRow.find('.item-price').attr('name', `items[${rowCount}][unit_price]`).val('');
            newRow.find('.item-subtotal').text('Rp 0');
            
            $('#itemsBody').append(newRow);
            
            // Re-initialize Select2 for the new row
            newRow.find('.select2').select2({ theme: 'bootstrap4' });
            
            rowCount++;
        });

        // Remove Row
        $(document).on('click', '.remove-row', function() {
            if ($('.item-row').length > 1) {
                $(this).closest('tr').remove();
                calculateGrandTotal();
            }
        });

        // Event for Change
        $(document).on('change keyup', '.item-qty, .item-price, .item-select', function() {
            let row = $(this).closest('tr');
            
            // Auto suggest price if select changes and price is empty
            if ($(this).hasClass('item-select')) {
                let price = $(this).find('option:selected').data('price');
                if (price && row.find('.item-price').val() === '') {
                    row.find('.item-price').val(Math.round(price));
                }
            }
            
            calculateGrandTotal();
        });

        // Initial Total
        calculateGrandTotal();
    });
</script>
@endpush

@endsection
