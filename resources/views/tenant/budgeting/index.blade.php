@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Input Anggaran</h1>
                <p class="text-sm text-muted mb-0">Tentukan plafon anggaran pendapatan dan biaya dapur.</p>
            </div>
            <div class="col-sm-6 text-right">
                <form action="{{ route('budgeting.index', tenant('id')) }}" method="GET" class="form-inline justify-content-end">
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
        <div class="card card-outline card-primary shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="card-title font-weight-bold text-primary mb-0">
                    <i class="fas fa-edit mr-2"></i> Periode: {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}
                </h5>
                <div class="card-tools">
                    <span class="badge badge-info px-3 py-1">Hanya Akun Pendapatan & Biaya</span>
                </div>
            </div>
            <form action="{{ route('budgeting.store', tenant('id')) }}" method="POST">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="month" value="{{ $month }}">
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4" style="width: 200px;">Kode Akun</th>
                                    <th class="border-0">Nama Akun</th>
                                    <th class="border-0 text-right pr-4" style="width: 300px;">Nilai Anggaran (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $currentType = ''; @endphp
                                @foreach($accounts as $account)
                                    @php 
                                        $type = str_starts_with($account->code, '4') ? 'PENDAPATAN' : 'BIAYA / BEBAN';
                                    @endphp
                                    @if($type != $currentType)
                                        <tr class="bg-light shadow-none">
                                            <td colspan="3" class="px-4 py-2 font-weight-bold text-muted small" style="background-color: #f8f9fa;">
                                                <i class="fas fa-layer-group mr-1"></i> GROUP: {{ $currentType = $type }}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="px-4 font-weight-bold text-muted">{{ $account->code }}</td>
                                        <td class="align-middle">{{ $account->name }}</td>
                                        <td class="pr-4 py-2">
                                            <div class="input-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text border-0 bg-white text-xs font-weight-bold text-muted">Rp</span>
                                                </div>
                                                <input type="hidden" name="budgets[{{ $loop->index }}][account_id]" value="{{ $account->id }}">
                                                <input type="number" name="budgets[{{ $loop->index }}][amount]" 
                                                       class="form-control border-0 text-right font-weight-bold text-primary" 
                                                       value="{{ isset($budgets[$account->id]) ? round($budgets[$account->id]->amount) : 0 }}" 
                                                       min="0" step="1000">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-4 d-flex justify-content-between align-items-center">
                    <div class="text-sm text-muted">
                        <i class="fas fa-info-circle mr-1"></i> Masukkan angka 0 jika tidak ada anggaran untuk akun tersebut.
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-lg font-weight-bold">
                        <i class="fas fa-save mr-2"></i> SIMPAN ANGGARAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
