@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
                    <i class="fas fa-plus-circle mr-2 text-primary"></i> Tambah Menu Baru
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('tenant.menu.index', tenant('id')) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content pt-2">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0" style="border-radius:10px;">
                    <div class="card-body">
                        <form action="{{ route('tenant.menu.store', tenant('id')) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="text-sm font-weight-bold">Kode Menu <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Contoh: MN-001">
                                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="text-sm font-weight-bold">Kategori <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control @error('category') is-invalid @enderror">
                                        <option value="makanan">Makanan</option>
                                        <option value="minuman">Minuman</option>
                                        <option value="snack">Snack</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-sm font-weight-bold">Nama Menu <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Nasi Goreng Spesial">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="text-sm font-weight-bold">Harga Jual (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="0">
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="text-sm font-weight-bold">Deskripsi</label>
                                <textarea name="description" rows="3" class="form-control" placeholder="Penjelasan singkat hidangan">{{ old('description') }}</textarea>
                            </div>

                            <hr>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Simpan Menu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
