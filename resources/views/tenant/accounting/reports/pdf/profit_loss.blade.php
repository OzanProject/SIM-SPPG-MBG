<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11pt; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header h3 { margin: 5px 0; color: #555; }
        .header p { margin: 0; font-size: 10pt; color: #777; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px 0; border-bottom: 1px solid #eee; }
        .section-title { font-weight: bold; text-transform: uppercase; color: #2196f3; padding-top: 20px; border-bottom: 2px solid #2196f3; }
        .total-row { font-weight: bold; border-top: 2px solid #333; border-bottom: none; }
        .text-right { text-align: right; }
        .summary-box { background: #f9f9f9; padding: 20px; margin-top: 40px; border-radius: 8px; }
        .summary-title { font-size: 14pt; font-weight: bold; }
        .amount { float: right; font-size: 14pt; font-weight: bold; }
        .success { color: #28a745; }
        .danger { color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ tenant('name') ?? 'DAPUR MBG' }}</h2>
        <h3>LAPORAN LABA RUGI</h3>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} s.d {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th colspan="2" class="section-title">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php $totalRevenue = 0; @endphp
            @foreach($revenueAccounts as $acc)
                @php 
                    $balance = $acc->journalDetails->sum('credit') - $acc->journalDetails->sum('debit');
                    $totalRevenue += $balance;
                @endphp
                <tr>
                    <td>{{ $acc->name }} ({{ $acc->code }})</td>
                    <td class="text-right">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>TOTAL PENDAPATAN</td>
                <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="2" class="section-title" style="color: #dc3545; border-color: #dc3545;">Beban Operasional</th>
            </tr>
        </thead>
        <tbody>
            @php $totalExpense = 0; @endphp
            @foreach($expenseAccounts as $acc)
                @php 
                    $balance = $acc->journalDetails->sum('debit') - $acc->journalDetails->sum('credit');
                    $totalExpense += $balance;
                @endphp
                <tr>
                    <td>{{ $acc->name }} ({{ $acc->code }})</td>
                    <td class="text-right">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>TOTAL BEBAN</td>
                <td class="text-right">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="summary-box">
        <span class="summary-title {{ ($totalRevenue - $totalExpense) >= 0 ? 'success' : 'danger' }}">LABA (RUGI) BERSIH</span>
        <span class="amount {{ ($totalRevenue - $totalExpense) >= 0 ? 'success' : 'danger' }}">
            Rp {{ number_format($totalRevenue - $totalExpense, 0, ',', '.') }}
        </span>
    </div>

    <div style="margin-top: 50px; text-align: right; font-size: 9pt; color: #999;">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
