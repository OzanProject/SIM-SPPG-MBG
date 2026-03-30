@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">Edit Pengguna Sistem</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/dashboard') }}"><i class="fas fa-home text-primary"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/users') }}">Global Users</a></li>
                    <li class="breadcrumb-item active">Ubah Data</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline shadow-sm" style="max-width: 700px; margin: 0 auto;">
            <div class="card-header border-bottom-0 pb-1">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-user-edit mr-2 text-primary"></i>
                    Edit {{ $isTenant ? 'Karyawan Cabang ('.$tenant->id.')' : 'Super Admin (Pusat)' }}
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('/super-admin/users/' . $user->id . ($isTenant ? '?tenant_id=' . $tenant->id : '')) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-4">
                        <label class="font-weight-normal text-sm text-dark">Nama Lengkap</label>
                        <div class="input-group">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            <div class="input-group-append">
                                <div class="input-group-text bg-white"><i class="fas fa-user text-muted"></i></div>
                            </div>
                            @error('name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-normal text-sm text-dark">Alamat Email Akses</label>
                        <div class="input-group">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            <div class="input-group-append">
                                <div class="input-group-text bg-white"><i class="fas fa-envelope text-muted"></i></div>
                            </div>
                            @error('email')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <hr class="mt-4 mb-4">

                    <div class="alert alert-warning py-2 mb-4 d-flex align-items-center rounded bg-light border-warning">
                        <i class="fas fa-exclamation-triangle text-warning mr-3" style="font-size: 1.5rem;"></i>
                        <small class="mb-0 text-dark" style="font-size: 0.9rem;">Biarkan kolom sandi di bawah ini <b>kosong</b> jika Anda tidak ingin merubah kata sandi pengguna ini.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-normal text-sm text-dark">Setel Kata Sandi Baru (Opsional)</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Ketik kata sandi baru">
                            <div class="input-group-append">
                                <div class="input-group-text bg-white"><i class="fas fa-lock text-muted"></i></div>
                            </div>
                            @error('password')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-normal text-sm text-dark">Ulangi Verifikasi Sandi</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi untuk verifikasi">
                            <div class="input-group-append">
                                <div class="input-group-text bg-white"><i class="fas fa-lock text-muted"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-right">
                        <a href="{{ url('super-admin/users') }}" class="btn btn-default shadow-sm mr-2 px-4">Batal</a>
                        <button type="submit" class="btn btn-primary shadow-sm px-4"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
