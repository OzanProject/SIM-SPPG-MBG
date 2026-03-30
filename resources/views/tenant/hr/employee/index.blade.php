@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
                    <i class="fas fa-users-cog mr-2 text-primary"></i> Manajemen Karyawan
                </h1>
                <p class="text-muted text-sm mb-0">Kelola data staf, posisi, dan parameter gaji pokok mereka.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('tenant.hr.employee.create', tenant('id')) }}" class="btn btn-primary btn-sm rounded-pill font-weight-bold shadow-sm px-3">
                    <i class="fas fa-plus mr-1"></i> Tambah Karyawan
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content pt-2">
    <div class="container-fluid">
        <div class="card shadow-sm border-0" style="border-radius:10px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4">NIK</th>
                                <th class="border-0 text-center">Nama Karyawan</th>
                                <th class="border-0 text-center">Jabatan</th>
                                <th class="border-0 text-center">Gaji Pokok</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-right px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr>
                                    <td class="px-4 align-middle">
                                        <span class="badge badge-light text-muted">{{ $employee->employee_id }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="font-weight-bold text-dark">{{ $employee->name }}</div>
                                        <small class="text-muted">Bergabung: {{ $employee->join_date ? $employee->join_date->format('d M Y') : '-' }}</small>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-outline-secondary px-2 py-1" style="border:1px solid #ddd;">{{ $employee->position ?? '-' }}</span>
                                    </td>
                                    <td class="align-middle text-center font-weight-bold text-primary">
                                        {{ $employee->formatted_salary }}
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($employee->is_active)
                                            <span class="badge badge-success px-3 py-1 rounded-pill">Aktif</span>
                                        @else
                                            <span class="badge badge-danger px-3 py-1 rounded-pill">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-right px-4 align-middle">
                                        <div class="btn-group shadow-sm" style="border-radius:20px; overflow:hidden;">
                                            <a href="{{ route('tenant.hr.employee.edit', [tenant('id'), $employee->id]) }}" class="btn btn-white btn-sm px-3" title="Edit">
                                                <i class="fas fa-edit text-primary"></i>
                                            </a>
                                            <form action="{{ route('tenant.hr.employee.destroy', [tenant('id'), $employee->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data karyawan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-white btn-sm px-3" title="Hapus">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" style="width:150px;" class="mb-3 opacity-50">
                                        <p class="text-muted">Belum ada data karyawan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
