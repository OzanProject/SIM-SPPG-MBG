<table>
    <thead>
        <tr>
            <th colspan="2" style="text-align: center; font-weight: bold;">{{ tenant('name') ?? 'DAPUR MBG' }}</th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: center; font-weight: bold;">LAPORAN NERACA</th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: center;">Posisi per: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</th>
        </tr>
        <tr><th></th><th></th></tr>
    </thead>
    <tbody>
        <tr>
            <th style="font-weight: bold; font-size: 12pt;">AKTIVA (ASET)</th>
            <th></th>
        </tr>
        @php $totalAssets = 0; @endphp
        @foreach($assetAccounts as $acc)
            @php 
                $balance = $acc->journalDetails->sum('debit') - $acc->journalDetails->sum('credit');
                $totalAssets += $balance;
            @endphp
            <tr>
                <td>{{ $acc->name }}</td>
                <td style="text-align: right;">{{ $balance }}</td>
            </tr>
        @endforeach
        <tr>
            <th style="font-weight: bold;">TOTAL ASET</th>
            <th style="font-weight: bold; text-align: right;">{{ $totalAssets }}</th>
        </tr>

        <tr><th></th><th></th></tr>

        <tr>
            <th style="font-weight: bold; font-size: 12pt;">KEWAJIBAN (PASIVA)</th>
            <th></th>
        </tr>
        @php $totalLiabilities = 0; @endphp
        @foreach($liabilityAccounts as $acc)
            @php 
                $balance = $acc->journalDetails->sum('credit') - $acc->journalDetails->sum('debit');
                $totalLiabilities += $balance;
            @endphp
            <tr>
                <td>{{ $acc->name }}</td>
                <td style="text-align: right;">{{ $balance }}</td>
            </tr>
        @endforeach
        <tr>
            <th style="font-weight: bold;">TOTAL KEWAJIBAN</th>
            <th style="font-weight: bold; text-align: right;">{{ $totalLiabilities }}</th>
        </tr>

        <tr><th></th><th></th></tr>

        <tr>
            <th style="font-weight: bold; font-size: 12pt;">EKUITAS (MODAL)</th>
            <th></th>
        </tr>
        @php $totalEquity = 0; @endphp
        @foreach($equityAccounts as $acc)
            @php 
                $balance = $acc->journalDetails->sum('credit') - $acc->journalDetails->sum('debit');
                $totalEquity += $balance;
            @endphp
            <tr>
                <td>{{ $acc->name }}</td>
                <td style="text-align: right;">{{ $balance }}</td>
            </tr>
        @endforeach
        <tr>
            <td>Laba Ditahan / Periode Berjalan</td>
            <td style="text-align: right;">{{ $currentEarnings }}</td>
        </tr>
        @php $totalEquity += $currentEarnings; @endphp
        <tr>
            <th style="font-weight: bold;">TOTAL EKUITAS</th>
            <th style="font-weight: bold; text-align: right;">{{ $totalEquity }}</th>
        </tr>

        <tr><th></th><th></th></tr>

        <tr>
            <th style="font-weight: bold; font-size: 14pt;">TOTAL PASIVA</th>
            <th style="font-weight: bold; font-size: 14pt; text-align: right;">{{ $totalLiabilities + $totalEquity }}</th>
        </tr>
    </tbody>
</table>
