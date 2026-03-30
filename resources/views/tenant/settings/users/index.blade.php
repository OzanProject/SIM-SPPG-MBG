@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Manajemen User</h1>
                <p class="text-sm text-muted mb-0">Kelola staf dan hak akses dapur Anda.</p>
            </div>
            <div class="col-sm-6 text-right">
                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm font-weight-bold" data-toggle="modal" data-target="#modalAddUser">
                    <i class="fas fa-plus-circle mr-2"></i> TAMBAH USER
                </button>
            </div>
        </div>
    </div>
</div>

<section class="content pb-5">
    <div class="container-fluid">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4 py-3" style="width: 50px;">#</th>
                                <th class="border-0 py-3">Nama Lengkap</th>
                                <th class="border-0 py-3">Email</th>
                                <th class="border-0 py-3">Role / Akses</th>
                                <th class="border-0 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="px-4 text-muted small">{{ $loop->iteration }}</td>
                                <td class="font-weight-bold text-dark">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="badge badge-pill badge-info ml-1" style="font-size: 0.6rem;">SAYA</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge badge-pill badge-outline-primary px-3 border text-primary font-weight-normal" style="font-size: 0.75rem;">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light rounded-circle shadow-sm mx-1" data-toggle="modal" data-target="#modalEditUser{{ $user->id }}" title="Edit">
                                        <i class="fas fa-edit text-primary"></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('settings.users.destroy', [tenant('id'), $user->id]) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light rounded-circle shadow-sm mx-1" onclick="return confirm('Hapus user ini?')" title="Hapus">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal Edit User -->
                            <div class="modal fade" id="modalEditUser{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                                        <form action="{{ route('settings.users.update', [tenant('id'), $user->id]) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title font-weight-bold">Edit User</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body py-4">
                                                <div class="form-group">
                                                    <label class="small font-weight-bold text-muted">Nama Lengkap</label>
                                                    <input type="text" name="name" class="form-control rounded-pill px-3 shadow-sm" value="{{ $user->name }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="small font-weight-bold text-muted">Email</label>
                                                    <input type="email" name="email" class="form-control rounded-pill px-3 shadow-sm" value="{{ $user->email }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="small font-weight-bold text-muted">Role / Akses</label>
                                                    <select name="role" class="form-control rounded-pill px-3 shadow-sm" required>
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <hr class="my-3">
                                                <div class="form-group">
                                                    <label class="small font-weight-bold text-muted">Password Baru (Kosongkan jika tidak ganti)</label>
                                                    <input type="password" name="password" class="form-control rounded-pill px-3 shadow-sm">
                                                </div>
                                                <div class="form-group">
                                                    <label class="small font-weight-bold text-muted">Konfirmasi Password Baru</label>
                                                    <input type="password" name="password_confirmation" class="form-control rounded-pill px-3 shadow-sm">
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-link text-muted font-weight-bold" data-dismiss="modal">BATAL</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm font-weight-bold">SIMPAN PERUBAHAN</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Add User -->
<div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <form action="{{ route('settings.users.store', tenant('id')) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Tambah User Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-4">
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control rounded-pill px-3 shadow-sm" placeholder="Contoh: Budi Santoso" required>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted">Email</label>
                        <input type="email" name="email" class="form-control rounded-pill px-3 shadow-sm" placeholder="email@contoh.com" required>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted">Role / Akses</label>
                        <select name="role" class="form-control rounded-pill px-3 shadow-sm" required>
                            <option value="" disabled selected>Pilih Role...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted">Password</label>
                        <input type="password" name="password" class="form-control rounded-pill px-3 shadow-sm" required>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control rounded-pill px-3 shadow-sm" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-link text-muted font-weight-bold" data-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm font-weight-bold">SIMPAN USER</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
