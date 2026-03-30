<table>
    <thead>
        <tr>
            <th colspan="2" style="text-align: center; font-weight: bold;">{{ tenant('name') ?? 'DAPUR MBG' }}</th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: center; font-weight: bold;">LAPORAN LABA RUGI</th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: center;">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} s.d {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</th>
        </tr>
        <tr><th></th><th></th></tr>
    </thead>
    <tbody>
        <tr>
            <th style="font-weight: bold; font-size: 12pt;">PENDAPATAN</th>
            <th></th>
        </tr>
        @php $totalRevenue = 0; @endphp
        @foreach($revenueAccounts as $acc)
            @php 
                $balance = $acc->journalDetails->sum('credit') - $acc->journalDetails->sum('debit');
                $totalRevenue += $balance;
            @endphp
            <tr>
                <td>{{ $acc->name }} ({{ $acc->code }})</td>
                <td style="text-align: right;">{{ $balance }}</td>
            </tr>
        @endforeach
        <tr>
            <th style="font-weight: bold;">TOTAL PENDAPATAN</th>
            <th style="font-weight: bold; text-align: right;">{{ $totalRevenue }}</th>
        </tr>

        <tr><th></th><th></th></tr>

        <tr>
            <th style="font-weight: bold; font-size: 12pt;">BEBAN OPERASIONAL</th>
            <th></th>
        </tr>
        @php $totalExpense = 0; @endphp
        @foreach($expenseAccounts as $acc)
            @php 
                $balance = $acc->journalDetails->sum('debit') - $acc->journalDetails->sum('credit');
                $totalExpense += $balance;
            @endphp
            <tr>
                <td>{{ $acc->name }} ({{ $acc->code }})</td>
                <td style="text-align: right;">{{ $balance }}</td>
            </tr>
        @endforeach
        <tr>
            <th style="font-weight: bold;">TOTAL BEBAN</th>
            <th style="font-weight: bold; text-align: right;">{{ $totalExpense }}</th>
        </tr>

        <tr><th></th><th></th></tr>

        <tr>
            <th style="font-weight: bold; font-size: 14pt;">LABA (RUGI) BERSIH</th>
            <th style="font-weight: bold; font-size: 14pt; text-align: right;">{{ $totalRevenue - $totalExpense }}</th>
        </tr>
    </tbody>
</table>
