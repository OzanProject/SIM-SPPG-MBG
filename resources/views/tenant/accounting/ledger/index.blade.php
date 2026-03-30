@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ global_asset('adminlte3/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ global_asset('adminlte3/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    .select2-container--bootstrap4 .select2-selection { border-radius: 8px !important; border: 0 !important; box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important; height: calc(2.25rem + 2px) !important; }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered { line-height: 2.25rem !important; }
</style>
@endpush

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Buku Besar</h1>
                <p class="text-sm text-muted mb-0">Rincian mutasi per akun akuntansi.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0 text-sm">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard', tenant('id')) }}"><i class="fas fa-home"></i> Dasbor</a></li>
                    <li class="breadcrumb-item active">Buku Besar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filter Card -->
        <div class="card card-outline card-info shadow-sm border-0 mb-4">
            <div class="card-body p-3">
                <form action="{{ route('accounting.ledger.index', tenant('id')) }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Pilih Akun</label>
                                <select name="account_id" class="form-control select2 border-0 shadow-sm" style="border-radius: 8px;" required>
                                    <option value="">-- Pilih Akun --</option>
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}" {{ $selectedAccountId == $acc->id ? 'selected' : '' }}>
                                            {{ $acc->code }} - {{ $acc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control border-0 shadow-sm" style="border-radius: 8px;" value="{{ $startDate }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-uppercase text-muted">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control border-0 shadow-sm" style="border-radius: 8px;" value="{{ $endDate }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-info btn-block rounded-pill shadow-sm font-weight-bold">
                                <i class="fas fa-filter mr-1"></i> FILTER
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($selectedAccountId)
            @php 
                $currentAccount = $accounts->where('id', $selectedAccountId)->first();
            @endphp
            <div class="card card-outline card-info shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title font-weight-bold text-dark mb-0">
                                <i class="fas fa-book mr-2 text-info"></i> Rincian: {{ $currentAccount->code }} - {{ $currentAccount->name }}
                            </h5>
                        </div>
                        <div class="col text-right">
                            <span class="badge badge-light border px-3 py-2 rounded-pill">
                                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s.d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-sm">
                            <thead class="bg-light">
                                <tr class="text-muted text-xs text-uppercase">
                                    <th class="border-0 pl-4 py-3" style="width: 150px;">Tanggal</th>
                                    <th class="border-0 py-3" style="width: 180px;">No. Referensi</th>
                                    <th class="border-0 py-3">Keterangan</th>
                                    <th class="border-0 py-3 text-right" style="width: 150px;">Debit</th>
                                    <th class="border-0 py-3 text-right" style="width: 150px;">Kredit</th>
                                    <th class="border-0 py-3 text-right pr-4" style="width: 180px;">Saldo Berjalan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Opening Balance Row -->
                                <tr class="bg-gray-50 italic">
                                    <td class="pl-4 py-2" colspan="3 text-muted">Saldo Awal (Per {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }})</td>
                                    <td class="text-right py-2">-</td>
                                    <td class="text-right py-2">-</td>
                                    <td class="text-right py-2 pr-4 font-weight-bold text-dark">
                                        Rp {{ number_format($openingBalance, 0, ',', '.') }}
                                    </td>
                                </tr>

                                @php $runningBalance = $openingBalance; @endphp
                                @forelse($ledgerData as $detail)
                                    @php 
                                        $runningBalance += ($detail->debit - $detail->credit);
                                    @endphp
                                    <tr>
                                        <td class="pl-4 align-middle">{{ \Carbon\Carbon::parse($detail->journal->date)->format('d/m/Y') }}</td>
                                        <td class="align-middle">
                                            <span class="badge badge-light border px-2 py-1">{{ $detail->journal->reference_number }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="d-block">{{ $detail->description ?? $detail->journal->description }}</span>
                                            <small class="text-muted">Jurnal #{{ $detail->journal->id }}</small>
                                        </td>
                                        <td class="text-right align-middle text-success font-weight-bold">
                                            {{ $detail->debit > 0 ? 'Rp ' . number_format($detail->debit, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="text-right align-middle text-primary font-weight-bold">
                                            {{ $detail->credit > 0 ? 'Rp ' . number_format($detail->credit, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="text-right align-middle pr-4 font-weight-bold {{ $runningBalance < 0 ? 'text-danger' : 'text-info' }}">
                                            Rp {{ number_format($runningBalance, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-search fa-3x mb-3 d-block opacity-25"></i>
                                            Tidak ada mutasi transaksi pada periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-light font-weight-bold">
                                <tr>
                                    <td colspan="3" class="text-right py-3">TOTAL MUTASI :</td>
                                    <td class="text-right py-3 text-success">Rp {{ number_format($ledgerData->sum('debit'), 0, ',', '.') }}</td>
                                    <td class="text-right py-3 text-primary">Rp {{ number_format($ledgerData->sum('credit'), 0, ',', '.') }}</td>
                                    <td class="text-right py-3 pr-4 text-dark h6 mb-0">Rp {{ number_format($runningBalance, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-md-8 text-center py-5">
                    <div class="card shadow-none border-0 bg-transparent py-5">
                        <i class="fas fa-book-open fa-5x text-light mb-4"></i>
                        <h4 class="text-muted font-weight-bold">Buku Besar</h4>
                        <p class="text-muted px-5">Silakan pilih akun dan rentang tanggal di atas untuk melihat rincian mutasi transaksi secara mendalam.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('js')
<script src="{{ global_asset('adminlte3/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    });
</script>
@endpush
