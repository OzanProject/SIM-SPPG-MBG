@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">Manajemen Global Users</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/dashboard') }}"><i class="fas fa-home text-primary"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active">Global Users</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-warning alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            </div>
        @endif

        <div class="card card-primary card-outline card-outline-tabs shadow-sm">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold" id="tabs-superadmin-tab" data-toggle="pill" href="#tabs-superadmin" role="tab" aria-controls="tabs-superadmin" aria-selected="true"><i class="fas fa-user-shield mr-1"></i> Super Admin (Pusat)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" id="tabs-tenant-tab" data-toggle="pill" href="#tabs-tenant" role="tab" aria-controls="tabs-tenant" aria-selected="false"><i class="fas fa-users mr-1"></i> Karyawan Cabang (Tenant)</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    
                    <!-- TAB SUPER ADMIN -->
                    <div class="tab-pane fade show active" id="tabs-superadmin" role="tabpanel" aria-labelledby="tabs-superadmin-tab">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Pengguna</th>
                                        <th>Alamat Email</th>
                                        <th>Terdaftar Sejak</th>
                                        <th class="text-center">Aksi Manajemen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($centralUsers as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td class="font-weight-bold">{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at->format('d M Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('/super-admin/users/' . $user->id . '/edit') }}" class="btn btn-sm btn-info" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                                                <form action="{{ url('/super-admin/users/' . $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun Super Admin ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" {{ auth()->id() == $user->id ? 'disabled' : '' }} title="Hapus"><i class="fas fa-trash"></i> Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada data Super Admin.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB TENANT USERS -->
                    <div class="tab-pane fade" id="tabs-tenant" role="tabpanel" aria-labelledby="tabs-tenant-tab">
                        <div class="alert bg-light border-info d-flex align-items-center mb-3">
                            <i class="fas fa-info-circle text-info mr-3" style="font-size: 1.5rem;"></i>
                            <span class="text-dark">Ini adalah semua pengguna yang diregistrasikan di masing-masing Cabang Dapur Anda.<br><b>Perhatian:</b> Merubah data ini akan langsung mempengaruhi database di cabang tersebut secara real-time.</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Cabang</th>
                                        <th>ID User</th>
                                        <th>Nama Karyawan</th>
                                        <th>Email Akses</th>
                                        <th>Terdaftar Pada</th>
                                        <th class="text-center">Aksi Lintas-Cabang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tenantUsers as $user)
                                        <tr>
                                            <td><span class="badge badge-primary">{{ $user->tenant_id }}</span></td>
                                            <td>{{ $user->id }}</td>
                                            <td class="font-weight-bold">{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at_formatted }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('/super-admin/users/' . $user->id . '/edit?tenant_id=' . $user->tenant_id) }}" class="btn btn-sm btn-info" title="Edit Data Cabang"><i class="fas fa-edit"></i> Edit</a>
                                                <form action="{{ url('/super-admin/users/' . $user->id . '?tenant_id=' . $user->tenant_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permanen karyawan ini dari database cabang {{ $user->tenant_id }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus Akun Karyawan"><i class="fas fa-trash"></i> Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada karyawan yang terdaftar di cabang manapun.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
