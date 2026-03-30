@extends('layouts.app')

@section('content')
<div class="content-header pt-4 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">
                    <i class="fas fa-user-circle text-primary mr-2"></i> Profil Saya
                </h1>
                <p class="text-muted mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Kelola Informasi Pribadi & Keamanan Akun</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('dashboard', tenant('id')) }}" class="btn btn-default shadow-sm font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            {{-- KOLOM KIRI: INFO UTAMA & FOTO --}}
            <div class="col-lg-4">
                <div class="card card-primary card-outline shadow-sm rounded-lg overflow-hidden border-top-width-3">
                    <div class="card-body box-profile py-4">
                        <div class="text-center position-relative mb-4">
                            <img class="profile-user-img img-fluid img-circle elevation-2 border-primary"
                                 src="{{ $user->profile_photo ? global_asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}"
                                 alt="User profile picture"
                                 style="width: 120px; height: 120px; object-fit: cover; border-width: 3px;">
                        </div>

                        <h3 class="profile-username text-center font-weight-bold text-dark mt-2 mb-1">{{ $user->name }}</h3>
                        <p class="text-muted text-center text-sm mb-4">{{ $user->email }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 border-left-0 border-right-0">
                                <span class="text-muted"><i class="fas fa-id-badge mr-2"></i> Role</span>
                                <span class="badge badge-light p-2 font-weight-bold">{{ $user->roles->pluck('name')->first() ?? 'User' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 border-left-0 border-right-0">
                                <span class="text-muted"><i class="fas fa-calendar-alt mr-2"></i> Join Date</span>
                                <span class="font-weight-bold text-dark">{{ $user->created_at->format('d M Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="alert alert-info border-0 shadow-sm rounded-lg">
                    <h5 class="font-weight-bold"><i class="icon fas fa-info-circle"></i> Catatan</h5>
                    <p class="mb-0 small">Email Anda digunakan untuk masuk ke aplikasi. Pastikan email tetap aktif untuk menerima notifikasi penting dari sistem.</p>
                </div>
            </div>

            {{-- KOLOM KANAN: FORM EDIT --}}
            <div class="col-lg-8">
                {{-- CARD: UPDATE PROFIL --}}
                <div class="card shadow-sm rounded-lg mb-4 overflow-hidden border-0">
                    <div class="card-header bg-white py-3 px-4 border-bottom-0">
                        <h5 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-user-edit text-primary mr-2"></i> Informasi Dasar
                        </h5>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <form action="{{ route('profile.update', tenant('id')) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="font-weight-bold text-dark mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                    @error('name')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="font-weight-bold text-dark mb-2">Alamat Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                    @error('email')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-12 mb-4">
                                    <label class="font-weight-bold text-dark mb-2">Foto Profil <small class="text-muted">(Pilih file jika ingin mengganti)</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="profile_photo" class="custom-file-input @error('profile_photo') is-invalid @enderror" id="profilePhotoInput">
                                        <label class="custom-file-label" for="profilePhotoInput">Pilih gambar...</label>
                                        @error('profile_photo')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                    <small class="text-muted mt-2 d-block"><i class="fas fa-lightbulb text-warning mr-1"></i> Gunakan format JPG/PNG dengan ukuran maksimal 2MB.</small>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- CARD: UPDATE PASSWORD --}}
                <div class="card shadow-sm rounded-lg mb-4 overflow-hidden border-0">
                    <div class="card-header bg-white py-3 px-4 border-bottom-0">
                        <h5 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-lock text-danger mr-2"></i> Keamanan Akun
                        </h5>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <form action="{{ route('profile.password.update', tenant('id')) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label class="font-weight-bold text-dark mb-2">Kata Sandi Saat Ini</label>
                                    <input type="password" name="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required>
                                    @error('current_password', 'updatePassword')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="font-weight-bold text-dark mb-2">Kata Sandi Baru</label>
                                    <input type="password" name="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" required>
                                    @error('password', 'updatePassword')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="font-weight-bold text-dark mb-2">Konfirmasi Sandi Baru</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-outline-danger px-4 shadow-sm font-weight-bold">
                                    <i class="fas fa-shield-alt mr-1"></i> Perbarui Kata Sandi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('js')
<script>
    // Show filename in custom-file input
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
@endpush
@endsection
