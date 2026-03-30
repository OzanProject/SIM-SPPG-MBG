<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $payroll->employee->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 10px;
            color: #666;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 2px 0;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }
        .main-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .section-title {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .total-row {
            font-weight: bold;
            font-size: 14px;
            background-color: #eee;
        }
        .footer {
            margin-top: 30px;
        }
        .signature-table {
            width: 100%;
            margin-top: 50px;
        }
        .signature-box {
            text-align: center;
            width: 50%;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SLIP GAJI KARYAWAN</h1>
        <p>Periode: {{ date('F Y', mktime(0, 0, 0, $payroll->month, 1, $payroll->year)) }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">Nama</td>
            <td width="35%">: <strong>{{ $payroll->employee->name }}</strong></td>
            <td width="15%">ID Karyawan</td>
            <td width="35%">: {{ $payroll->employee->employee_id }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>: {{ $payroll->employee->position ?? '-' }}</td>
            <td>Status Transfer</td>
            <td>: {{ strtoupper($payroll->status) }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th class="text-right">Jumlah (IDR)</th>
            </tr>
        </thead>
        <tbody>
            <tr class="section-title">
                <td colspan="2">PENGHASILAN</td>
            </tr>
            <tr>
                <td>Gaji Pokok</td>
                <td class="text-right">{{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan</td>
                <td class="text-right">{{ number_format($payroll->allowance, 0, ',', '.') }}</td>
            </tr>
            <tr class="section-title">
                <td colspan="2">POTONGAN</td>
            </tr>
            <tr>
                <td>Potongan Lain-lain</td>
                <td class="text-right">({{ number_format($payroll->deduction, 0, ',', '.') }})</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL DITERIMA (NET)</td>
                <td class="text-right">IDR {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p><em>Terbilang: # {{ ucwords(terbilang($payroll->net_salary)) }} Rupiah #</em></p>

    <table class="signature-table">
        <tr>
            <td class="signature-box">
                Penerima,<br><br><br><br>
                ( ____________________ )
            </td>
            <td class="signature-box">
                Bagian Keuangan,<br><br><br><br>
                ( {{ auth()->user()->name }} )
            </td>
        </tr>
    </table>

    <div style="margin-top: 40px; font-size: 9px; color: #999; text-align: center;">
        Dicetak otomatis oleh MBG AkunPro pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>

<?php
/**
 * Helper function for Indonesian number to words (simple version)
 */
function terbilang($angka) {
    $angka = abs($angka);
    $baca = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $terbilang = "";
    if ($angka < 12) {
        $terbilang = " " . $baca[$angka];
    } else if ($angka < 20) {
        $terbilang = terbilang($angka - 10) . " belas";
    } else if ($angka < 100) {
        $terbilang = terbilang($angka / 10) . " puluh" . terbilang($angka % 10);
    } else if ($angka < 200) {
        $terbilang = " seratus" . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $terbilang = terbilang($angka / 100) . " ratus" . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $terbilang = " seribu" . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $terbilang = terbilang($angka / 1000) . " ribu" . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $terbilang = terbilang($angka / 1000000) . " juta" . terbilang($angka % 1000000);
    }
    return $terbilang;
}
?>
