@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Laporan Laba Rugi</h1>
                <p class="text-sm text-muted mb-0">Ringkasan pendapatan dan beban operasional.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm mr-2 no-print">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                </a>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-outline-success btn-sm rounded-pill px-3 shadow-sm mr-2 no-print">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </a>
                <button onclick="window.print()" class="btn btn-outline-dark btn-sm rounded-pill px-3 shadow-sm no-print">
                    <i class="fas fa-print mr-1"></i> Cetak Laporan
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
                <form action="{{ route('accounting.reports.profit-loss', tenant('id')) }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control border-0 shadow-sm" style="border-radius: 8px;" value="{{ $startDate }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control border-0 shadow-sm" style="border-radius: 8px;" value="{{ $endDate }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-info btn-block rounded-pill shadow-sm font-weight-bold">
                                <i class="fas fa-sync mr-1"></i> REFRESH LAPORAN
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
                    <h5 class="text-dark">LAPORAN LABA RUGI</h5>
                    <p class="text-muted small">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} s.d {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <!-- Revenue Section -->
                        <h6 class="font-weight-bold text-uppercase text-primary mb-3"><i class="fas fa-arrow-down mr-2"></i> Pendapatan</h6>
                        <table class="table table-borderless mb-4">
                            <tbody>
                                @php $totalRevenue = 0; @endphp
                                @forelse($revenueAccounts as $acc)
                                    @php 
                                        $balance = $acc->journalDetails->sum('credit') - $acc->journalDetails->sum('debit');
                                        $totalRevenue += $balance;
                                    @endphp
                                    <tr>
                                        <td class="pl-4">{{ $acc->name }} ({{ $acc->code }})</td>
                                        <td class="text-right">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted small py-3">Tidak ada data pendapatan.</td></tr>
                                @endforelse
                                <tr class="border-top font-weight-bold" style="border-top: 2px solid #333 !important;">
                                    <td class="pl-0 h6">TOTAL PENDAPATAN</td>
                                    <td class="text-right h6">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Expense Section -->
                        <h6 class="font-weight-bold text-uppercase text-danger mb-3 mt-5"><i class="fas fa-arrow-up mr-2"></i> Beban Operasional</h6>
                        <table class="table table-borderless mb-4">
                            <tbody>
                                @php $totalExpense = 0; @endphp
                                @forelse($expenseAccounts as $acc)
                                    @php 
                                        $balance = $acc->journalDetails->sum('debit') - $acc->journalDetails->sum('credit');
                                        $totalExpense += $balance;
                                    @endphp
                                    <tr>
                                        <td class="pl-4">{{ $acc->name }} ({{ $acc->code }})</td>
                                        <td class="text-right text-muted">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted small py-3">Tidak ada data beban.</td></tr>
                                @endforelse
                                <tr class="border-top font-weight-bold" style="border-top: 2px solid #333 !important;">
                                    <td class="pl-0 h6">TOTAL BEBAN</td>
                                    <td class="text-right h6">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Summary Section -->
                        <div class="mt-5 p-4 rounded bg-light border-0 shadow-sm">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="mb-0 font-weight-bold {{ ($totalRevenue - $totalExpense) >= 0 ? 'text-success' : 'text-danger' }}">
                                        LABA (RUGI) BERSIH
                                    </h5>
                                </div>
                                <div class="col text-right">
                                    <h4 class="mb-0 font-weight-bold {{ ($totalRevenue - $totalExpense) >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($totalRevenue - $totalExpense, 0, ',', '.') }}
                                    </h4>
                                </div>
                            </div>
                        </div>
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
