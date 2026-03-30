@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Pergerakan Stok</h1>
                <p class="text-sm text-muted mb-0">Histori barang masuk dan keluar di operasional dapur.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0 text-sm">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard', tenant('id')) }}"><i class="fas fa-home"></i> Dasbor</a></li>
                    <li class="breadcrumb-item active">Pergerakan Stok</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Summary Stats -->
            <div class="col-md-3">
                <div class="info-box shadow-sm border-0">
                    <span class="info-box-icon bg-success"><i class="fas fa-arrow-down"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-sm">Masuk (Bulan Ini)</span>
                        <span class="info-box-number h4 font-weight-bold mb-0">
                            {{ number_format($movements->where('type', 'in')->where('date', '>=', now()->startOfMonth())->sum('quantity')) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box shadow-sm border-0">
                    <span class="info-box-icon bg-danger"><i class="fas fa-arrow-up"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-sm">Keluar (Bulan Ini)</span>
                        <span class="info-box-number h4 font-weight-bold mb-0">
                            {{ number_format($movements->where('type', 'out')->where('date', '>=', now()->startOfMonth())->sum('quantity')) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-success shadow-sm border-0 mt-2">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title font-weight-bold text-dark mb-0">Log Transaksi Barang</h5>
                    </div>
                    <div class="col text-right">
                        <button type="button" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm" data-toggle="modal" data-target="#moveModal">
                            <i class="fas fa-plus mr-1 text-xs"></i> Catat Pergerakan
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4">Tanggal</th>
                                <th class="border-0">Barang</th>
                                <th class="border-0 text-center">Tipe</th>
                                <th class="border-0 text-right">Qty</th>
                                <th class="border-0 text-right">Harga Satuan</th>
                                <th class="border-0 text-right">Subtotal</th>
                                <th class="border-0 px-4">Referensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movements as $mov)
                            <tr>
                                <td class="px-4 text-xs font-weight-bold">{{ $mov->date }}</td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $mov->item->name }}</div>
                                    <div class="text-xs text-muted">{{ $mov->item->code }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $mov->type === 'in' ? 'badge-success' : 'badge-danger' }} px-2 py-1" style="font-size: 0.7rem;">
                                        {{ $mov->type === 'in' ? 'MASUK' : 'KELUAR' }}
                                    </span>
                                </td>
                                <td class="text-right font-weight-bold {{ $mov->type === 'in' ? 'text-success' : 'text-danger' }}">
                                    {{ $mov->type === 'out' ? '-' : '+' }} {{ number_format($mov->quantity) }}
                                </td>
                                <td class="text-right text-muted text-sm border-left">
                                    Rp {{ number_format($mov->unit_price, 0, ',', '.') }}
                                </td>
                                <td class="text-right font-weight-bold text-dark border-left">
                                    Rp {{ number_format($mov->quantity * $mov->unit_price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 text-xs font-italic text-muted">
                                    <code>{{ $mov->reference_number }}</code>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-history fa-3x text-light mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada histori pergerakan stok.</p>
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

<!-- Movement Modal (UI Revamped) -->
<div class="modal fade" id="moveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('inventory.movements.store', tenant('id')) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            @csrf
            <div class="modal-header bg-success text-white" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i> Input Mutasi Barang</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-6">
                        <label class="d-block w-100 text-center p-3 border rounded shadow-sm hover-shadow bg-light cursor-pointer">
                            <input type="radio" name="type" value="in" checked class="mr-2">
                            <span class="text-success font-weight-bold">MASUK (+)</span>
                        </label>
                    </div>
                    <div class="col-6">
                        <label class="d-block w-100 text-center p-3 border rounded shadow-sm bg-light cursor-pointer">
                            <input type="radio" name="type" value="out" class="mr-2">
                            <span class="text-danger font-weight-bold">KELUAR (-)</span>
                        </label>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="text-sm font-weight-bold">Pilih Barang/Bahan</label>
                    <select name="inventory_item_id" class="form-control select2 shadow-sm" style="border-radius: 8px;" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach(App\Models\Tenant\InventoryItem::all() as $item)
                        <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->name }} (Stok: {{ $item->stock }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 font-weight-bold">
                        <div class="form-group mb-3 text-sm">
                            <label>Jumlah (Qty)</label>
                            <input type="number" name="quantity" class="form-control shadow-sm" required min="1" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-6 font-weight-bold">
                        <div class="form-group mb-3 text-sm">
                            <label>Harga Satuan</label>
                            <input type="number" name="unit_price" class="form-control shadow-sm" required min="0" placeholder="Rp">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3 text-sm font-weight-bold">
                    <label>Tanggal Transaksi</label>
                    <input type="date" name="date" class="form-control shadow-sm" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group mb-0 text-sm font-weight-bold">
                    <label>Catatan</label>
                    <textarea name="notes" class="form-control shadow-sm" rows="2" placeholder="Keterangan transaksi..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light border-0" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-success rounded-pill px-5 shadow-sm font-weight-bold">SIMPAN DATA</button>
            </div>
        </form>
    </div>
</div>
@push('js')
<script>
    $(document).ready(function() {
        // Initialize Select2 if needed, but layouts/app usually handles it.
        // If not, we can trigger it here.
        
        $('#itemSelector').on('change', function() {
            var selected = $(this).find('option:selected');
            var price = selected.data('price');
            var type = $('input[name="type"]:checked').val();
            
            if(type === 'out' && price) {
                $('input[name="unit_price"]').val(Math.round(price));
            }
        });

        $('input[name="type"]').on('change', function() {
            var type = $(this).val();
            var price = $('#itemSelector').find('option:selected').data('price');
            
            if(type === 'out' && price) {
                $('input[name="unit_price"]').val(Math.round(price));
            } else if(type === 'in') {
                $('input[name="unit_price"]').val('');
            }
        });
    });
</script>
@endpush
@endsection
