@extends('layouts.app')

@section('title', 'Anggaran (Budgeting)')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ route('budgeting.budgets.index') }}">Budgeting</a></li>
  <li class="breadcrumb-item active">Anggaran</li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Anggaran</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Akun</th>
                            <th>Bulan/Tahun</th>
                            <th>Anggaran (Rp)</th>
                            <th>Realisasi (Rp)</th>
                            <th>Sisa (Rp)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budgets as $budget)
                        @php
                            $sisa = $budget->amount - $budget->realized_amount;
                            $isOverBudget = $sisa < 0;
                        @endphp
                        <tr>
                            <td>{{ $budget->account->name ?? '-' }}</td>
                            <td>{{ str_pad($budget->month, 2, '0', STR_PAD_LEFT) }}/{{ $budget->year }}</td>
                            <td>{{ number_format($budget->amount, 2) }}</td>
                            <td>{{ number_format($budget->realized_amount, 2) }}</td>
                            <td>{{ number_format($sisa, 2) }}</td>
                            <td>
                                @if($isOverBudget)
                                    <span class="badge badge-danger">Over Budget</span>
                                @else
                                    <span class="badge badge-success">Aman</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data anggaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
