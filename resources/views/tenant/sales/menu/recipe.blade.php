@extends('layouts.app')

@push('css')
<style>
    .ingredient-row:hover { background: #f8f9fa; }
    .select2-container .select2-selection--single { height: 38px !important; line-height: 38px !important; }
</style>
@endpush

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
                    <i class="fas fa-layer-group mr-2 text-primary"></i> Kelola Resep: {{ $menu->name }}
                </h1>
                <p class="text-muted text-sm mb-0">Tentukan bahan baku yang digunakan untuk setiap porsi menu ini.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('tenant.menu.index', tenant('id')) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Menu
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content pt-2">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-9">
                <form action="{{ route('tenant.menu.recipe.store', [tenant('id'), $menu->id]) }}" method="POST">
                    @csrf
                    <div class="card shadow-sm border-0" style="border-radius:10px;">
                        <div class="card-header bg-white font-weight-bold border-bottom">
                            Daftar Bahan Baku (BOM)
                        </div>
                        <div class="card-body p-0">
                            <table class="table mb-0" id="recipeTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 45%;" class="pl-4">Pilih Bahan Baku</th>
                                        <th style="width: 20%;" class="text-center">Jumlah Diserap</th>
                                        <th style="width: 15%;" class="text-center">Satuan</th>
                                        <th style="width: 15%;" class="text-center">Catatan</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($menu->recipes as $index => $recipe)
                                    <tr class="ingredient-row">
                                        <td class="p-2 pl-4">
                                            <select name="ingredients[{{ $index }}][inventory_item_id]" class="form-control" required>
                                                <option value="">-- Pilih Bahan --</option>
                                                @foreach($inventoryItems as $item)
                                                    <option value="{{ $item->id }}" data-unit="{{ $item->unit }}" {{ $recipe->inventory_item_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="p-2">
                                            <input type="number" step="0.01" name="ingredients[{{ $index }}][quantity]" class="form-control text-center" value="{{ $recipe->quantity }}" required oninput="updateUnit(this)">
                                        </td>
                                        <td class="p-2 text-center align-middle">
                                            <span class="unit-display text-muted text-sm font-weight-bold">{{ $recipe->inventoryItem->unit }}</span>
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="ingredients[{{ $index }}][note]" class="form-control text-sm" value="{{ $recipe->note }}" placeholder="...">
                                        </td>
                                        <td class="p-2 text-center align-middle pr-3">
                                            <button type="button" class="btn btn-link text-danger p-0" onclick="removeRow(this)"><i class="fas fa-times"></i></button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="ingredient-row">
                                        <td class="p-2 pl-4">
                                            <select name="ingredients[0][inventory_item_id]" class="form-control" required onchange="updateUnit(this, true)">
                                                <option value="">-- Pilih Bahan --</option>
                                                @foreach($inventoryItems as $item)
                                                    <option value="{{ $item->id }}" data-unit="{{ $item->unit }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="p-2">
                                            <input type="number" step="0.01" name="ingredients[0][quantity]" class="form-control text-center" value="1" required>
                                        </td>
                                        <td class="p-2 text-center align-middle">
                                            <span class="unit-display text-muted text-sm font-weight-bold">-</span>
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="ingredients[0][note]" class="form-control text-sm" placeholder="...">
                                        </td>
                                        <td class="p-2 text-center align-middle pr-3">
                                            <button type="button" class="btn btn-link text-danger p-0" onclick="removeRow(this)"><i class="fas fa-times"></i></button>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="p-3 text-center border-top bg-light">
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill font-weight-bold" onclick="addRow()">
                                    <i class="fas fa-plus mr-1"></i> Tambah Bahan Baku
                                </button>
                            </div>
                        </div>
                        <div class="card-footer bg-white text-right">
                            <button type="submit" class="btn btn-primary px-5 shadow-sm font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Simpan Resep
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0" style="border-radius:10px;">
                    <div class="card-header bg-white font-weight-bold border-bottom">Info Harga Menu</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Harga Jual per Porsi</small>
                            <h4 class="font-weight-bold text-dark">{{ $menu->formatted_price }}</h4>
                        </div>
                        <hr>
                        <div class="alert alert-info py-2 px-3 text-sm mb-0">
                            <i class="fas fa-info-circle mr-1"></i> Setiap kali <strong>{{ $menu->name }}</strong> terjual, stok bahan di samping akan otomatis berkurang dari gudang.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    let rowIndex = {{ max(count($menu->recipes), 1) }};

    function addRow() {
        const tbody = document.querySelector('#recipeTable tbody');
        const newRow = `
            <tr class="ingredient-row">
                <td class="p-2 pl-4">
                    <select name="ingredients[${rowIndex}][inventory_item_id]" class="form-control" required onchange="updateUnit(this, true)">
                        <option value="">-- Pilih Bahan --</option>
                        @foreach($inventoryItems as $item)
                            <option value="{{ $item->id }}" data-unit="{{ $item->unit }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="p-2">
                    <input type="number" step="0.01" name="ingredients[${rowIndex}][quantity]" class="form-control text-center" value="1" required>
                </td>
                <td class="p-2 text-center align-middle">
                    <span class="unit-display text-muted text-sm font-weight-bold">-</span>
                </td>
                <td class="p-2">
                    <input type="text" name="ingredients[${rowIndex}][note]" class="form-control text-sm" placeholder="...">
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
        const rows = document.querySelectorAll('.ingredient-row');
        if (rows.length > 1) {
            btn.closest('tr').remove();
        }
    }

    function updateUnit(select, isSelect = false) {
        if (!isSelect) return;
        const unit = select.options[select.selectedIndex].dataset.unit || '-';
        const row = select.closest('tr');
        row.querySelector('.unit-display').innerText = unit;
    }
</script>
@endpush
@endsection
