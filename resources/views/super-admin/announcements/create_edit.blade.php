@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">
                    {{ isset($announcement) ? 'Edit Pengumuman' : 'Buat Pengumuman Baru' }}
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/dashboard') }}"><i class="fas fa-home text-primary"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('announcements.index') }}">Pengumuman</a></li>
                    <li class="breadcrumb-item active">{{ isset($announcement) ? 'Edit' : 'Tambah' }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="{{ isset($announcement) ? route('announcements.update', $announcement->id) : route('announcements.store') }}" method="POST">
                            @csrf
                            @if(isset($announcement)) @method('PUT') @endif

                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Judul Pengumuman <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                       placeholder="Contoh: Pemeliharaan Server Malam Ini" 
                                       value="{{ old('title', $announcement->title ?? '') }}" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Isi Pengumuman <span class="text-danger">*</span></label>
                                <textarea name="body" class="form-control @error('body') is-invalid @enderror" rows="5" 
                                          placeholder="Tuliskan detail pengumuman di sini..." required>{{ old('body', $announcement->body ?? '') }}</textarea>
                                @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold">Tipe Notifikasi</label>
                                        <select name="type" class="form-control custom-select">
                                            <option value="info" {{ old('type', $announcement->type ?? '') == 'info' ? 'selected' : '' }}>Informasi (Biru)</option>
                                            <option value="success" {{ old('type', $announcement->type ?? '') == 'success' ? 'selected' : '' }}>Sukses (Hijau)</option>
                                            <option value="warning" {{ old('type', $announcement->type ?? '') == 'warning' ? 'selected' : '' }}>Peringatan (Kuning)</option>
                                            <option value="danger" {{ old('type', $announcement->type ?? '') == 'danger' ? 'selected' : '' }}>Bahaya (Merah)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold">Target Paket</label>
                                        <select name="target_plan" class="form-control custom-select">
                                            <option value="">Semua Paket (Global)</option>
                                            @foreach($plans as $plan)
                                                <option value="{{ $plan->slug }}" {{ old('target_plan', $announcement->target_plan ?? '') == $plan->slug ? 'selected' : '' }}>
                                                    Hanya Paket: {{ $plan->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold">Status Aktif</label>
                                        <select name="is_active" class="form-control custom-select">
                                            <option value="1" {{ old('is_active', $announcement->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ old('is_active', $announcement->is_active ?? 1) == 0 ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold">Tampilkan Pop-up?</label>
                                        <select name="show_modal" class="form-control custom-select">
                                            <option value="1" {{ old('show_modal', $announcement->show_modal ?? 1) == 1 ? 'selected' : '' }}>Ya (Modal)</option>
                                            <option value="0" {{ old('show_modal', $announcement->show_modal ?? 1) == 0 ? 'selected' : '' }}>Tidak (Biasa)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold">Muncul Terus?</label>
                                        <select name="is_persistent" class="form-control custom-select">
                                            <option value="0" {{ old('is_persistent', $announcement->is_persistent ?? 0) == 0 ? 'selected' : '' }}>Tidak (Sekali Baca)</option>
                                            <option value="1" {{ old('is_persistent', $announcement->is_persistent ?? 0) == 1 ? 'selected' : '' }}>Ya (Setiap Refresh)</option>
                                        </select>
                                        <small class="text-muted">Gunakan 'Ya' untuk info tagihan/penting.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Berlaku Hingga (Opsional)</label>
                                <input type="date" name="expires_at" class="form-control" 
                                       value="{{ old('expires_at', isset($announcement) && $announcement->expires_at ? $announcement->expires_at->format('Y-m-d') : '') }}">
                                <small class="text-muted">Kosongkan jika ingin tampil selamanya hingga dinonaktifkan manual.</small>
                            </div>

                            <hr>

                            <div class="d-flex align-items-center">
                                <a href="{{ route('announcements.index') }}" class="btn btn-light font-weight-bold px-4">Batal</a>
                                <button type="submit" class="btn btn-primary font-weight-bold px-5 ml-auto shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Simpan Pengumuman
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body">
                        <h6 class="font-weight-bold mb-3"><i class="fas fa-lightbulb text-warning mr-1"></i> Tips Pengumuman</h6>
                        <ul class="text-sm pl-3 mb-0">
                            <li class="mb-2">Gunakan tipe <b>Bahaya (Merah)</b> hanya untuk maintenance mendadak atau masalah kritis.</li>
                            <li class="mb-2">Jika Anda memilih <b>Tampilkan Pop-up</b>, pengguna akan langsung melihat pengumuman saat mereka me-refresh dashboard.</li>
                            <li class="mb-2">Jangan terlalu banyak membuat pengumuman pop-up agar tidak mengganggu pengalaman pengguna.</li>
                            <li>Target paket berguna jika Anda ingin memberi promo upgrade ke pengguna paket **Free**.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
