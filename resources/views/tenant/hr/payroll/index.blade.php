@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
                    <i class="fas fa-money-check-alt mr-2 text-success"></i> Penggajian (Payroll)
                </h1>
                <p class="text-muted text-sm mb-0">Kelola pembayaran gaji bulanan dan slip gaji karyawan.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('tenant.hr.payroll.create', tenant('id')) }}" class="btn btn-success btn-sm rounded-pill font-weight-bold shadow-sm px-3">
                    <i class="fas fa-magic mr-1"></i> Generate Payroll Baru
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
                                <th class="border-0 px-4">Ref #</th>
                                <th class="border-0 text-center">Karyawan</th>
                                <th class="border-0 text-center">Periode</th>
                                <th class="border-0 text-center">Gaji Bersih</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-right px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payrolls as $payroll)
                                <tr>
                                    <td class="px-4 align-middle">
                                        <span class="text-xs font-weight-bold">{{ $payroll->reference_number }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="font-weight-bold text-dark">{{ $payroll->employee->name }}</div>
                                        <small class="text-muted">{{ $payroll->employee->position }}</small>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-outline-secondary px-2">
                                            {{ date('F', mktime(0, 0, 0, $payroll->month, 1)) }} {{ $payroll->year }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center font-weight-bold text-success">
                                        {{ $payroll->formatted_net_salary }}
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($payroll->status === 'paid')
                                            <span class="badge badge-success px-3 py-1 rounded-pill">Lunas</span>
                                        @else
                                            <span class="badge badge-warning px-3 py-1 rounded-pill text-white">Draft</span>
                                        @endif
                                    </td>
                                    <td class="text-right px-4 align-middle">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('tenant.hr.payroll.show', [tenant('id'), $payroll->id]) }}" class="btn btn-outline-info btn-sm rounded-pill px-3 mr-1">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                            
                                            @if($payroll->status === 'draft')
                                                <form action="{{ route('tenant.hr.payroll.pay', [tenant('id'), $payroll->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Proses pembayaran gaji ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm font-weight-bold">
                                                        <i class="fas fa-check-circle mr-1"></i> Bayar
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('tenant.hr.payroll.pdf', [tenant('id'), $payroll->id]) }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm">
                                                    <i class="fas fa-file-pdf mr-1"></i> Slip Gaji
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted"> Belum ada data penggajian. </td>
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
