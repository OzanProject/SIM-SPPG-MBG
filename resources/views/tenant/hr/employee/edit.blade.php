@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
            <i class="fas fa-user-edit mr-2 text-primary"></i> Edit Data Karyawan
        </h1>
    </div>
</div>

<section class="content pt-2">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-0" style="border-radius:10px;">
                    <form action="{{ route('tenant.hr.employee.update', [tenant('id'), $employee->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>NIK / ID Karyawan <span class="text-danger">*</span></label>
                                        <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" value="{{ old('employee_id', $employee->employee_id) }}" required>
                                        @error('employee_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $employee->name) }}" required>
                                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jabatan</label>
                                        <input type="text" name="position" class="form-control" value="{{ old('position', $employee->position) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status Karyawan</label>
                                        <select name="is_active" class="form-control">
                                            <option value="1" {{ $employee->is_active ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ !$employee->is_active ? 'selected' : '' }}>Non-Aktif</option>
                                        </select>
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
                                            <input type="number" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" value="{{ old('basic_salary', $employee->basic_salary) }}" required>
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
                                            <input type="number" name="allowance" class="form-control" value="{{ old('allowance', $employee->allowance) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Catatan Tambahan</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $employee->notes) }}</textarea>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 text-right">
                            <a href="{{ route('tenant.hr.employee.index', tenant('id')) }}" class="btn btn-light rounded-pill px-4 mr-2">Batal</a>
                            <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 border-left border-primary" style="border-radius:10px;">
                    <div class="card-body">
                        <h6><i class="fas fa-history mr-2"></i> Info Terakhir</h6>
                        <hr>
                        <p class="text-sm mb-1">Dibuat pada: <strong>{{ $employee->created_at->format('d M Y H:i') }}</strong></p>
                        <p class="text-sm">Terakhir update: <strong>{{ $employee->updated_at->format('d M Y H:i') }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
