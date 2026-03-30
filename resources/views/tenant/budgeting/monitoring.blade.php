@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Monitoring Anggaran</h1>
                <p class="text-sm text-muted mb-0">Pantau realisasi penggunaan anggaran bulan berjalan.</p>
            </div>
            <div class="col-sm-6 text-right">
                <form action="{{ route('budgeting.monitoring', tenant('id')) }}" method="GET" class="form-inline justify-content-end">
                    <select name="month" class="form-control form-control-sm mr-2 rounded-pill px-3 shadow-sm border-0">
                        @for($m=1; $m<=12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>
                    <select name="year" class="form-control form-control-sm mr-2 rounded-pill px-3 shadow-sm border-0">
                        @for($y=date('Y')-1; $y<=date('Y')+2; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="btn btn-default btn-sm rounded-pill px-3 shadow-sm">Filter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<section class="content pb-5">
    <div class="container-fluid">
        <div class="row">
            @forelse($budgets as $budget)
                @php
                    $percent = $budget->amount > 0 ? ($budget->realized_amount / $budget->amount) * 100 : 0;
                    $color = 'success';
                    if ($percent >= 80) $color = 'warning';
                    if ($percent >= 100) $color = 'danger';
                    
                    $isExpense = str_starts_with($budget->account->code, '5');
                @endphp
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-1">{{ $budget->account->code }}</h6>
                                    <h5 class="font-weight-bold text-dark mb-0 text-sm">{{ $budget->account->name }}</h5>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-pill badge-{{ $color }} px-3 py-1 font-weight-bold shadow-sm" style="font-size: 0.75rem;">
                                        {{ number_format($percent, 1) }}%
                                    </span>
                                </div>
                            </div>
                            
                            <div class="progress mb-3" style="height: 10px; border-radius: 5px; background-color: #f0f2f5;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $color }} shadow-sm" 
                                     role="progressbar" 
                                     style="width: {{ min(100, $percent) }}%; transition: width 1s ease-in-out;" 
                                     aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <p class="text-xs text-muted text-uppercase font-weight-bold mb-1">Anggaran</p>
                                    <h6 class="font-weight-bold text-dark mb-0">Rp {{ number_format($budget->amount, 0, ',', '.') }}</h6>
                                </div>
                                <div class="col-6 text-right border-left">
                                    <p class="text-xs text-muted text-uppercase font-weight-bold mb-1">Realisasi</p>
                                    <h6 class="font-weight-bold text-{{ $color }} mb-0">Rp {{ number_format($budget->realized_amount, 0, ',', '.') }}</h6>
                                </div>
                            </div>

                            @if($percent > 100 && $isExpense)
                            <div class="mt-3 p-2 bg-light border-left border-danger rounded shadow-xs" style="font-size: 0.75rem;">
                                <i class="fas fa-exclamation-triangle text-danger mr-1"></i> 
                                <span class="text-danger font-weight-bold">Melebihi Anggaran!</span> Selisih: Rp {{ number_format($budget->realized_amount - $budget->amount, 0, ',', '.') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card shadow-sm border-0 py-5 text-center" style="border-radius: 12px;">
                        <i class="fas fa-chart-bar fa-3x text-light mb-3"></i>
                        <h5 class="text-muted">Belum ada anggaran yang diset untuk periode ini.</h5>
                        <div class="mt-3">
                            <a href="{{ route('budgeting.index', tenant('id')) }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                <i class="fas fa-plus mr-1"></i> Buat Anggaran Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
