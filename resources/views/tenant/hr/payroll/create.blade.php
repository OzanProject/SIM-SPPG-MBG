@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
            <i class="fas fa-magic mr-2 text-primary"></i> Generate Payroll Baru
        </h1>
    </div>
</div>

<section class="content pt-2">
    <div class="container-fluid">
        <form action="{{ route('tenant.hr.payroll.store', tenant('id')) }}" method="POST">
            @csrf
            <div class="card shadow-sm border-0 mb-4" style="border-radius:10px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title font-weight-bold mb-0">Parameter Periode</h5>
                </div>
                <div class="card-body py-0">
                    <div class="row pb-3">
                        <div class="col-md-3">
                            <label class="text-xs text-uppercase font-weight-bold text-muted">Bulan</label>
                            <select name="month" class="form-control form-control-sm">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $m == date('m') ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="text-xs text-uppercase font-weight-bold text-muted">Tahun</label>
                            <select name="year" class="form-control form-control-sm">
                                @foreach(range(date('Y'), date('Y')-2) as $y)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0" style="border-radius:10px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title font-weight-bold mb-0">Daftar Karyawan Aktif</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50" class="text-center"><input type="checkbox" id="check-all"></th>
                                    <th>Karyawan</th>
                                    <th class="text-center">Gaji Pokok (Default)</th>
                                    <th class="text-center">Tunjangan</th>
                                    <th class="text-center">Potongan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $employee)
                                    <tr>
                                        <td class="text-center align-middle">
                                            <input type="checkbox" name="payrolls[{{ $employee->id }}][selected]" value="1" class="check-item">
                                        </td>
                                        <td class="align-middle">
                                            <div class="font-weight-bold">{{ $employee->name }}</div>
                                            <small class="text-muted">{{ $employee->position }}</small>
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="number" name="payrolls[{{ $employee->id }}][basic_salary]" class="form-control form-control-sm text-center font-weight-bold text-primary mx-auto" style="max-width: 150px;" value="{{ (int)$employee->basic_salary }}">
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="number" name="payrolls[{{ $employee->id }}][allowance]" class="form-control form-control-sm text-center mx-auto" style="max-width: 120px;" value="{{ (int)$employee->allowance }}">
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="number" name="payrolls[{{ $employee->id }}][deduction]" class="form-control form-control-sm text-center mx-auto" style="max-width: 120px;" value="0">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 text-right py-3">
                    <a href="{{ route('tenant.hr.payroll.index', tenant('id')) }}" class="btn btn-light rounded-pill px-4 mr-2">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 font-weight-bold shadow-sm">Simpan Draft Penggajian</button>
                </div>
            </div>
        </form>
    </div>
</section>

@push('js')
<script>
    $('#check-all').on('change', function() {
        $('.check-item').prop('checked', $(this).prop('checked'));
    });
</script>
@endpush
@endsection
