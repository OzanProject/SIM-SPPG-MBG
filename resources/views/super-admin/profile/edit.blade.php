@extends('layouts.app')

@section('content')
<div class="content-header pt-4 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size: 1.8rem;">
                    <i class="fas fa-id-card text-primary mr-2"></i> Profil Super Admin
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <span class="badge badge-primary px-3 py-2 rounded-pill shadow-sm">
                    <i class="fas fa-crown mr-1"></i> SaaS Administrator
                </span>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <!-- Information & Email -->
            <div class="col-md-7">
                <div class="card card-outline card-primary shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title font-weight-bold text-dark mb-0">Informasi Profil</h5>
                        <p class="text-xs text-muted mt-1 mb-0">Perbarui informasi nama, email, dan foto profil administrator Anda.</p>
                    </div>
                    <form action="{{ route('super-admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PATCH')
                        <div class="card-body">
                            <!-- Profile Photo Section -->
                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <img id="profile-preview" 
                                         src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                         alt="Profile Photo" 
                                         class="rounded-circle shadow-sm border border-light" 
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                    <label for="profile_photo" class="btn btn-sm btn-white rounded-circle shadow-sm position-absolute" style="bottom: 0; right: 0; cursor: pointer;">
                                        <i class="fas fa-camera text-primary"></i>
                                    </label>
                                    <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*" onchange="previewImage(this)">
                                </div>
                                <div class="mt-2">
                                    <p class="text-xs text-muted mb-0">Klik ikon kamera untuk mengganti foto (Maks. 2MB)</p>
                                    @error('profile_photo')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="name" class="text-sm font-weight-bold">Nama Lengkap</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-user text-muted"></i></span>
                                    </div>
                                    <input type="text" name="name" id="name" class="form-control border-light shadow-none @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autocomplete="name">
                                </div>
                                @error('name')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label for="email" class="text-sm font-weight-bold">Alamat Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-muted"></i></span>
                                    </div>
                                    <input type="email" name="email" id="email" class="form-control border-light shadow-none @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="mt-2 p-2 bg-light rounded text-xs">
                                        Email Anda belum diverifikasi. <a href="{{ route('verification.send') }}" class="text-primary font-weight-bold">Klik di sini</a> untuk mengirim ulang.
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-white text-right py-3 border-top-0">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password -->
            <div class="col-md-5">
                <div class="card card-outline card-warning shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title font-weight-bold text-dark mb-0">Ganti Kata Sandi</h5>
                        <p class="text-xs text-muted mt-1 mb-0">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk menjaga keamanan.</p>
                    </div>
                    <form action="{{ route('super-admin.profile.password') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="current_password" class="text-sm font-weight-bold">Kata Sandi Saat Ini</label>
                                <input type="password" name="current_password" id="current_password" class="form-control border-light shadow-none @error('current_password', 'updatePassword') is-invalid @enderror" required autocomplete="current-password">
                                @error('current_password', 'updatePassword')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="password" class="text-sm font-weight-bold">Kata Sandi Baru</label>
                                <input type="password" name="password" id="password" class="form-control border-light shadow-none @error('password', 'updatePassword') is-invalid @enderror" required autocomplete="new-password">
                                @error('password', 'updatePassword')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label for="password_confirmation" class="text-sm font-weight-bold">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-light shadow-none" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="card-footer bg-white text-right py-3 border-top-0">
                            <button type="submit" class="btn btn-warning rounded-pill px-4 shadow-sm font-weight-bold">
                                <i class="fas fa-key mr-1"></i> Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="col-12">
                <div class="card card-outline card-danger shadow-sm border-0 mt-2">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title font-weight-bold text-danger mb-0">Hapus Akun</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-9">
                                <p class="text-sm text-muted mb-0">Setelah akun Anda dihapus, semua sumber daya dan data yang terkait akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.</p>
                            </div>
                            <div class="col-md-3 text-right">
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" data-toggle="modal" data-target="#deleteAccountModal">
                                    Hapus Akun Permanen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Delete Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('super-admin.profile.destroy') }}" method="POST" class="modal-content border-0 shadow-lg">
            @csrf @method('DELETE')
            <div class="modal-header bg-danger">
                <h5 class="modal-title font-weight-bold" id="deleteAccountModalLabel">Konfirmasi Hapus Akun</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <p class="font-weight-bold text-dark">Apakah Anda yakin ingin menghapus akun ini?</p>
                <p class="text-sm text-muted">Semua data Anda akan dihapus secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.</p>
                <div class="form-group mt-3 mb-0">
                    <input type="password" name="password" class="form-control border-light" placeholder="Masukkan Kata Sandi Anda" required>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm font-weight-bold">Ya, Hapus Akun Saya</button>
            </div>
        </form>
    </div>
</section>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
