<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Neraca</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header h3 { margin: 5px 0; color: #555; }
        .header p { margin: 0; font-size: 10pt; color: #777; }
        
        .container { width: 100%; }
        .col { width: 48%; display: inline-block; vertical-align: top; }
        .col-left { margin-right: 2%; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { padding: 6px 0; border-bottom: 1px solid #eee; }
        .section-title { font-weight: bold; text-transform: uppercase; color: #2196f3; padding-top: 15px; border-bottom: 2px solid #2196f3; text-align: left; }
        .total-row { font-weight: bold; border-top: 2px solid #333; border-bottom: none; }
        .subtotal-row { font-weight: bold; border-top: 1px solid #ccc; }
        .text-right { text-align: right; }
        
        .grand-total-box { background: #333; color: #fff; padding: 12px; margin-top: 10px; border-radius: 4px; }
        .grand-total-box h4 { margin: 0; }
        .grand-total-box .amount { float: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ tenant('name') ?? 'DAPUR MBG' }}</h2>
        <h3>LAPORAN NERACA (BALANCE SHEET)</h3>
        <p>Posisi per: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
    </div>

    <div class="container">
        <!-- ASSETS -->
        <div class="col col-left">
            <table>
                <thead>
                    <tr>
                        <th colspan="2" class="section-title">AKTIVA (ASET)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalAssets = 0; @endphp
                    @foreach($assetAccounts as $acc)
                        @php 
                            $balance = $acc->journalDetails->sum('debit') - $acc->journalDetails->sum('credit');
                            $totalAssets += $balance;
                        @endphp
                        <tr>
                            <td>{{ $acc->name }}</td>
                            <td class="text-right">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td>TOTAL ASET</td>
                        <td class="text-right">Rp {{ number_format($totalAssets, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- LIABILITIES & EQUITY -->
        <div class="col">
            <table>
                <thead>
                    <tr>
                        <th colspan="2" class="section-title" style="color: #dc3545; border-color: #dc3545;">KEWAJIBAN (PASIVA)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalLiabilities = 0; @endphp
                    @foreach($liabilityAccounts as $acc)
                        @php 
                            $balance = $acc->journalDetails->sum('credit') - $acc->journalDetails->sum('debit');
                            $totalLiabilities += $balance;
                        @endphp
                        <tr>
                            <td>{{ $acc->name }}</td>
                            <td class="text-right">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="subtotal-row">
                        <td>TOTAL KEWAJIBAN</td>
                        <td class="text-right">Rp {{ number_format($totalLiabilities, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <table>
                <thead>
                    <tr>
                        <th colspan="2" class="section-title" style="color: #333; border-color: #333;">EKUITAS (MODAL)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalEquity = 0; @endphp
                    @foreach($equityAccounts as $acc)
                        @php 
                            $balance = $acc->journalDetails->sum('credit') - $acc->journalDetails->sum('debit');
                            $totalEquity += $balance;
                        @endphp
                        <tr>
                            <td>{{ $acc->name }}</td>
                            <td class="text-right">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr style="color: #666; font-style: italic;">
                        <td>Laba Ditahan / Periode Berjalan</td>
                        <td class="text-right">Rp {{ number_format($currentEarnings, 0, ',', '.') }}</td>
                    </tr>
                    @php $totalEquity += $currentEarnings; @endphp
                </tbody>
                <tfoot>
                    <tr class="subtotal-row">
                        <td>TOTAL EKUITAS</td>
                        <td class="text-right">Rp {{ number_format($totalEquity, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="grand-total-box">
                <span style="font-weight: bold; text-transform: uppercase;">TOTAL PASIVA</span>
                <span class="amount">Rp {{ number_format($totalLiabilities + $totalEquity, 0, ',', '.') }}</span>
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>

    <div style="margin-top: 50px; text-align: left; font-size: 8pt; color: #999;">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }} oleh Sistem Akuntansi Dapur MBG
    </div>
</body>
</html>
