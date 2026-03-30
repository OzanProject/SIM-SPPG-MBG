@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
                    <i class="fas fa-plus-circle mr-2 text-primary"></i> Buat Tiket Dukungan
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('tenant.support.index', tenant('id')) }}" class="btn btn-outline-secondary btn-sm">
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
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-headset mr-2 text-primary"></i>Detail Permasalahan</h6>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 pl-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('tenant.support.store', tenant('id')) }}">
                            @csrf
                            <div class="form-group">
                                <label class="font-weight-bold text-sm">Judul / Subjek <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                                    value="{{ old('subject') }}" placeholder="Contoh: Tidak bisa login, Error saat jurnal, dll.">
                                @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold text-sm">Prioritas <span class="text-danger">*</span></label>
                                <select name="priority" class="form-control @error('priority') is-invalid @enderror">
                                    <option value="">-- Pilih Prioritas --</option>
                                    <option value="low" {{ old('priority')=='low' ? 'selected' : '' }}>🟢 Rendah</option>
                                    <option value="medium" {{ old('priority')=='medium' ? 'selected' : '' }}>🔵 Sedang</option>
                                    <option value="high" {{ old('priority')=='high' ? 'selected' : '' }}>🟡 Tinggi</option>
                                    <option value="urgent" {{ old('priority')=='urgent' ? 'selected' : '' }}>🔴 Mendesak</option>
                                </select>
                                @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold text-sm">Pesan / Deskripsi Masalah <span class="text-danger">*</span></label>
                                <textarea name="message" rows="6" class="form-control @error('message') is-invalid @enderror"
                                    placeholder="Jelaskan masalah yang Anda alami secara detail. Sertakan langkah-langkah yang dilakukan sebelum error terjadi.">{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('tenant.support.index', tenant('id')) }}" class="btn btn-outline-secondary mr-2">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane mr-1"></i> Kirim Tiket
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
