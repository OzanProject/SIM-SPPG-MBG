@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ route('reporting.index') }}">Reporting</a></li>
  <li class="breadcrumb-item active">Laporan</li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Generate Laporan Keuangan</h3>
            </div>
            <form action="{{ route('reporting.generate') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Jenis Laporan</label>
                        <select name="type" class="form-control" required>
                            <option value="neraca">Neraca</option>
                            <option value="laba_rugi">Laba Rugi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-print"></i> Generate Laporan</button>
                    <!-- Tombol Export PDF/Excel bisa ditambahkan di view detail nantinya -->
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
