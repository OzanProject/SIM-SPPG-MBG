@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
                    <i class="fas fa-file-invoice-dollar mr-2 text-primary"></i> Detail Payroll
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('tenant.hr.payroll.index', tenant('id')) }}" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm border">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content pt-2">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <!-- Digital Receipt / Slip -->
                <div class="card shadow-lg border-0" style="border-radius:15px; border-top: 5px solid #007bff !important;">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h4 class="font-weight-bold mb-1">{{ tenant('name') ?? 'MBG-AKUNPRO' }}</h4>
                            <p class="text-muted text-sm border-bottom pb-3">SLIP GAJI KARYAWAN</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="text-xs text-uppercase text-muted d-block mb-0">Ref Number</label>
                                <span class="font-weight-bold">{{ $payroll->reference_number }}</span>
                            </div>
                            <div class="col-6 text-right">
                                <label class="text-xs text-uppercase text-muted d-block mb-0">Periode</label>
                                <span class="font-weight-bold text-primary">{{ date('F Y', mktime(0,0,0,$payroll->month, 1, $payroll->year)) }}</span>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded mb-4 shadow-sm border">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h6 class="mb-0 font-weight-bold text-dark">{{ $payroll->employee->name }}</h6>
                                    <small class="text-muted">{{ $payroll->employee->position }}</small>
                                </div>
                                <div class="col-4 text-right">
                                    <span class="badge badge-{{ $payroll->status === 'paid' ? 'success' : 'warning' }} text-uppercase">
                                        {{ $payroll->status }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <h6 class="font-weight-bold text-sm border-bottom pb-2 mb-3">RINCIAN PENGHASILAN</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Gaji Pokok</span>
                            <span class="font-weight-bold">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 border-bottom pb-2">
                            <span class="text-secondary">Tunjangan</span>
                            <span class="font-weight-bold">Rp {{ number_format($payroll->allowance, 0, ',', '.') }}</span>
                        </div>
                        
                        <h6 class="font-weight-bold text-sm text-danger mt-4 border-bottom pb-2 mb-3">POTONGAN</h6>
                        <div class="d-flex justify-content-between mb-2 border-bottom pb-2">
                            <span class="text-danger">Total Potongan</span>
                            <span class="font-weight-bold text-danger">(Rp {{ number_format($payroll->deduction, 0, ',', '.') }})</span>
                        </div>

                        <div class="bg-primary text-white p-3 rounded mt-5 d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold">GAJI BERSIH</span>
                            <h4 class="font-weight-bold mb-0">{{ $payroll->formatted_net_salary }}</h4>
                        </div>

                        @if($payroll->status === 'draft')
                            <div class="mt-5 text-center">
                                <form action="{{ route('tenant.hr.payroll.pay', [tenant('id'), $payroll->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg btn-block rounded-pill font-weight-bold shadow-sm" onclick="return confirm('Proses pembayaran gaji ini sekarang?')">
                                        <i class="fas fa-check-circle mr-2"></i> Konfirmasi Pembayaran & Posting Jurnal
                                    </button>
                                </form>
                                <p class="text-muted text-xs mt-3">Tindakan ini akan otomatis mencatat posting Jurnal Umum ke modul Akuntansi.</p>
                            </div>
                        @else
                            <div class="mt-5 text-center">
                                <p class="text-success font-weight-bold mb-0"><i class="fas fa-check-double mr-1"></i> TELAH DIBAYARKAN PADA {{ $payroll->payment_date ? $payroll->payment_date->format('d M Y') : '-' }}</p>
                                <a href="{{ route('tenant.hr.payroll.pdf', [tenant('id'), $payroll->id]) }}" class="btn btn-outline-danger btn-sm rounded-pill px-4 mt-3">
                                    <i class="fas fa-file-pdf mr-2"></i> Cetak PDF Slip Gaji
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
