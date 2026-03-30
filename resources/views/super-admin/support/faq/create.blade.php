@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size: 1.5rem;">{{ isset($faq) ? 'Edit FAQ' : 'Tambah FAQ Baru' }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('support.faq.index') }}" class="btn btn-sm btn-secondary shadow-sm"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm border-0 col-md-8 mx-auto">
            <div class="card-body py-4">
                <form action="{{ isset($faq) ? route('support.faq.update', $faq) : route('support.faq.store') }}" method="POST">
                    @csrf
                    @if(isset($faq)) @method('PUT') @endif

                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark">Pertanyaan <span class="text-danger">*</span></label>
                        <input type="text" name="question" class="form-control" value="{{ old('question', $faq->question ?? '') }}" placeholder="Contoh: Bagaimana cara reset password?" required>
                        @error('question') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark">Kategori <span class="text-danger">*</span></label>
                        <select name="category" class="form-control" required>
                            <option value="Umum" {{ old('category', $faq->category ?? '') == 'Umum' ? 'selected' : '' }}>Umum</option>
                            <option value="Billing" {{ old('category', $faq->category ?? '') == 'Billing' ? 'selected' : '' }}>Billing / Pembayaran</option>
                            <option value="Akun" {{ old('category', $faq->category ?? '') == 'Akun' ? 'selected' : '' }}>Pengaturan Akun</option>
                            <option value="Teknis" {{ old('category', $faq->category ?? '') == 'Teknis' ? 'selected' : '' }}>Kendala Teknis</option>
                        </select>
                        @error('category') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark">Jawaban <span class="text-danger">*</span></label>
                        <textarea name="answer" class="form-control" rows="6" placeholder="Tulis jawaban lengkap di sini..." required>{{ old('answer', $faq->answer ?? '') }}</textarea>
                        @error('answer') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Prioritas Urutan</label>
                                <input type="number" name="order_priority" class="form-control" value="{{ old('order_priority', $faq->order_priority ?? 0) }}">
                                <small class="text-muted">Angka lebih kecil tampil lebih atas.</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label class="font-weight-bold text-dark d-block">Status</label>
                                <div class="custom-control custom-switch mt-2">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="isActiveSwitch" {{ old('is_active', $faq->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="isActiveSwitch">Tampilkan FAQ ini</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary px-5 shadow-sm font-weight-bold">
                            <i class="fas fa-save mr-2"></i> {{ isset($faq) ? 'Simpan Perubahan' : 'Simpan FAQ' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
