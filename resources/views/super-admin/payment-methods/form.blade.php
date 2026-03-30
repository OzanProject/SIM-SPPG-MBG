@extends('layouts.app')

@section('title', ($paymentMethod->exists ? 'Edit' : 'Tambah') . ' Rekening Bank')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold text-primary">{{ $paymentMethod->exists ? 'Edit' : 'Tambah baru' }} Rekening Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form action="{{ $action }}" method="POST">
                        @csrf
                        @if($method === 'PUT')
                            @method('PUT')
                        @endif

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Nama Bank / Platform <span class="text-danger">*</span></label>
                            <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" 
                                   value="{{ old('bank_name', $paymentMethod->bank_name) }}" placeholder="Contoh: BCA, MANDIRI, OVO, GOPAY" required>
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Nomor Rekening / ID <span class="text-danger">*</span></label>
                                    <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" 
                                           value="{{ old('account_number', $paymentMethod->account_number) }}" placeholder="0011223344" required>
                                    @error('account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Atas Nama <span class="text-danger">*</span></label>
                                    <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror" 
                                           value="{{ old('account_name', $paymentMethod->account_name) }}" placeholder="Contoh: PT MBG AKUNPRO" required>
                                    @error('account_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Instruksi Pembayaran (Opsional)</label>
                            <textarea name="instructions" class="form-control" rows="3" placeholder="Contoh: Lampirkan bukti di kolom konfirmasi setelah transfer.">{{ old('instructions', $paymentMethod->instructions) }}</textarea>
                            <small class="form-text text-muted">Akan muncul di halaman checkout pendaftaran sebagai panduan untuk pendaftar.</small>
                        </div>

                        <div class="form-group mb-4">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_active" class="custom-control-input" id="isActiveSwitch" 
                                       value="1" {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold" for="isActiveSwitch">Rekening Aktif (Muncul di Halaman Depan)</label>
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('super-admin.payment-methods.index') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 font-weight-bold shadow-sm">
                                <i class="fas fa-save mr-1"></i> Simpan Rekening
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
