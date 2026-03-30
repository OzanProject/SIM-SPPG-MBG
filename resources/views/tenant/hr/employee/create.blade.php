@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
            <i class="fas fa-user-plus mr-2 text-primary"></i> Tambah Karyawan Baru
        </h1>
    </div>
</div>

<section class="content pt-2">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-0" style="border-radius:10px;">
                    <form action="{{ route('tenant.hr.employee.store', tenant('id')) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>NIK / ID Karyawan <span class="text-danger">*</span></label>
                                        <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" value="{{ old('employee_id') }}" placeholder="Contoh: EMP-001" required>
                                        @error('employee_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nama Karyawan" required>
                                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jabatan</label>
                                        <input type="text" name="position" class="form-control" value="{{ old('position') }}" placeholder="Contoh: Koki, Pelayan">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal Bergabung</label>
                                        <input type="date" name="join_date" class="form-control" value="{{ old('join_date', date('Y-m-d')) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gaji Pokok (Rp) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" value="{{ old('basic_salary', 0) }}" required>
                                        </div>
                                        @error('basic_salary') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tunjangan (Rp)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" name="allowance" class="form-control" value="{{ old('allowance', 0) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Catatan Tambahan</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Info tambahan..."></textarea>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 text-right">
                            <a href="{{ route('tenant.hr.employee.index', tenant('id')) }}" class="btn btn-light rounded-pill px-4 mr-2">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-gradient-primary shadow-sm border-0" style="border-radius:10px;">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <h5>Pusat Bantuan</h5>
                        <p class="text-sm">Gaji pokok dan tunjangan akan menjadi nilai default saat pembuatan Payroll bulanan. Anda masih bisa menyesuaikannya saat proses pembayaran gaji.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
