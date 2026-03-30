@extends('layouts.app')

@section('title', 'Jurnal Umum')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ route('accounting.journals.index') }}">Accounting</a></li>
  <li class="breadcrumb-item active">Jurnal Umum</li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Jurnal Umum</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No Ref</th>
                            <th>Keterangan</th>
                            <th>Total (Rp)</th>
                            <th>Pembuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($journals as $journal)
                        <tr>
                            <td>{{ $journal->date }}</td>
                            <td>{{ $journal->reference_number }}</td>
                            <td>{{ $journal->description }}</td>
                            <td>{{ number_format($journal->total_amount, 2) }}</td>
                            <td>{{ $journal->creator->name ?? 'System' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data jurnal.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
