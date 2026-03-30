@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Laporan Neraca</h1>
                <p class="text-sm text-muted mb-0">Posisi aset, liabilitas, dan ekuitas dapur.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm mr-2 no-print">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                </a>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-outline-success btn-sm rounded-pill px-3 shadow-sm mr-2 no-print">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </a>
                <button onclick="window.print()" class="btn btn-outline-dark btn-sm rounded-pill px-3 shadow-sm no-print">
                    <i class="fas fa-print mr-1"></i> Cetak Neraca
                </button>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filter Card -->
        <div class="card card-outline card-info shadow-sm border-0 mb-4 no-print">
            <div class="card-body p-3">
                <form action="{{ route('accounting.reports.balance-sheet', tenant('id')) }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Posisi Per Tanggal</label>
                                <input type="date" name="date" class="form-control border-0 shadow-sm" style="border-radius: 8px;" value="{{ $date }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-info btn-block rounded-pill shadow-sm font-weight-bold">
                                <i class="fas fa-sync mr-1"></i> LIHAT NERACA
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-outline card-info shadow-sm border-0">
            <div class="card-body p-5">
                <div class="text-center mb-5">
                    <h3 class="font-weight-bold mb-0 text-uppercase">{{ tenant('name') ?? 'DAPUR MBG' }}</h3>
                    <h5 class="text-dark">LAPORAN NERACA (BALANCE SHEET)</h5>
                    <p class="text-muted small">Posisi per: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
                </div>

                <div class="row">
                    <!-- LEFT SIDE: ASSETS -->
                    <div class="col-md-6 border-right">
                        <h6 class="font-weight-bold text-uppercase text-primary mb-3">AKTIVA (ASET)</h6>
                        <table class="table table-borderless mb-4">
                            <tbody>
                                @php $totalAssets = 0; @endphp
                                @foreach($assetAccounts as $acc)
                                    @php 
                                        $balance = $acc->journalDetails->sum('debit') - $acc->journalDetails->sum('credit');
                                        $totalAssets += $balance;
                                    @endphp
                                    <tr class="text-sm">
                                        <td class="pl-3">{{ $acc->name }}</td>
                                        <td class="text-right">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top" style="border-top: 2px solid #333 !important;">
                                <tr class="font-weight-bold h6">
                                    <td class="pl-0">TOTAL ASET</td>
                                    <td class="text-right">Rp {{ number_format($totalAssets, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- RIGHT SIDE: LIABILITIES & EQUITY -->
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-uppercase text-danger mb-3">KEWAJIBAN (PASIVA)</h6>
                        <table class="table table-borderless mb-4">
                            <tbody>
                                @php $totalLiabilities = 0; @endphp
                                @foreach($liabilityAccounts as $acc)
                                    @php 
                                        $balance = $acc->journalDetails->sum('credit') - $acc->journalDetails->sum('debit');
                                        $totalLiabilities += $balance;
                                    @endphp
                                    <tr class="text-sm">
                                        <td class="pl-3">{{ $acc->name }}</td>
                                        <td class="text-right">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top" style="border-top: 1px solid #ccc !important;">
                                <tr class="font-weight-bold">
                                    <td class="pl-0">TOTAL KEWAJIBAN</td>
                                    <td class="text-right">Rp {{ number_format($totalLiabilities, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>

                        <h6 class="font-weight-bold text-uppercase text-dark mb-3 mt-4">EKUITAS (MODAL)</h6>
                        <table class="table table-borderless mb-2">
                            <tbody>
                                @php $totalEquity = 0; @endphp
                                @foreach($equityAccounts as $acc)
                                    @php 
                                        $balance = $acc->journalDetails->sum('credit') - $acc->journalDetails->sum('debit');
                                        $totalEquity += $balance;
                                    @endphp
                                    <tr class="text-sm">
                                        <td class="pl-3">{{ $acc->name }}</td>
                                        <td class="text-right">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="text-sm italic text-muted">
                                    <td class="pl-3">Laba Ditahan / Periode Berjalan</td>
                                    <td class="text-right">Rp {{ number_format($currentEarnings, 0, ',', '.') }}</td>
                                </tr>
                                @php $totalEquity += $currentEarnings; @endphp
                            </tbody>
                            <tfoot class="border-top" style="border-top: 1px solid #ccc !important;">
                                <tr class="font-weight-bold">
                                    <td class="pl-0">TOTAL EKUITAS</td>
                                    <td class="text-right">Rp {{ number_format($totalEquity, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="mt-4 p-3 bg-dark text-white rounded shadow-sm">
                            <div class="row align-items-center">
                                <div class="col"><h6 class="mb-0 font-weight-bold uppercase">TOTAL PASIVA</h6></div>
                                <div class="col text-right">
                                    <h5 class="mb-0 font-weight-bold">Rp {{ number_format($totalLiabilities + $totalEquity, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>
                        @if(round($totalAssets) != round($totalLiabilities + $totalEquity))
                            <div class="alert alert-danger mt-2 py-2 text-xs no-print">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Neraca belum seimbang! Selisih: Rp {{ number_format(abs($totalAssets - ($totalLiabilities + $totalEquity)), 0, ',', '.') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    @media print {
        .no-print { display: none !important; }
        .main-footer { display: none !important; }
        .content-wrapper { margin-left: 0 !important; }
        .card { box-shadow: none !important; border: 0 !important; }
    }
</style>
@endsection
