<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; font-size: 13px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 10px; }
        .header-table { width: 100%; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #1a73e8; }
        .status-badge { padding: 5px 10px; border-radius: 4px; font-size: 11px; text-transform: uppercase; font-weight: bold; }
        .status-paid { background: #e6f4ea; color: #1e7e34; }
        .status-pending { background: #fff4e5; color: #b7791f; }
        
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-table td { vertical-align: top; width: 50%; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background: #f8f9fa; border-bottom: 2px solid #dee2e6; padding: 10px; text-align: left; }
        .items-table td { border-bottom: 1px solid #eee; padding: 10px; }
        
        .totals-table { width: 100%; margin-top: 20px; }
        .totals-table td { padding: 5px 0; }
        .total-row { font-size: 16px; font-weight: bold; color: #1a73e8; border-top: 2px solid #eee; }
        
        .footer { margin-top: 50px; text-align: center; color: #888; font-size: 11px; border-top: 1px solid #eee; padding-top: 10px; }
        .terbilang { font-style: italic; color: #555; margin-top: 10px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td>
                    <div class="logo">MBG AKUNPRO</div>
                    <div style="font-size: 11px; color: #666;">SaaS Digital Multi-Tenant Platform</div>
                </td>
                <td style="text-align: right;">
                    <h1 style="margin: 0; color: #333;">INVOICE</h1>
                    <div style="font-weight: bold;">#{{ $invoice->invoice_number }}</div>
                    <div class="status-badge {{ $invoice->status === 'paid' ? 'status-paid' : 'status-pending' }}">
                        {{ $invoice->status === 'paid' ? 'LUNAS' : 'MENUNGGU PEMBAYARAN' }}
                    </div>
                </td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td>
                    <strong>Diterbitkan Untuk:</strong><br>
                    {{ $invoice->tenant->name }}<br>
                    ID Dapur: {{ $invoice->tenant->id }}<br>
                    Administrator: {{ $invoice->tenant->email ?? '-' }}<br>
                </td>
                <td style="text-align: right;">
                    <strong>Informasi Detail:</strong><br>
                    Tanggal Invoice: {{ $invoice->created_at->format('d M Y') }}<br>
                    Batas Bayar: {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}<br>
                    @if($invoice->paid_at)
                        Tanggal Lunas: {{ $invoice->paid_at->format('d M Y H:i') }}<br>
                    @endif
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Deskripsi Layanan / Paket</th>
                    <th style="text-align: center;">Durasi</th>
                    <th style="text-align: right;">Harga</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>PAKET {{ strtoupper($invoice->subscriptionPlan->name) }}</strong><br>
                        <small style="color: #666;">Akses penuh ke semua fitur sesuai tier {{ $invoice->subscriptionPlan->name }}</small>
                    </td>
                    <td style="text-align: center;">{{ $invoice->subscriptionPlan->duration_in_days }} Hari</td>
                    <td style="text-align: right;">Rp{{ number_format($invoice->base_amount, 0, ',', '.') }}</td>
                </tr>
                @if($invoice->discount_amount > 0)
                <tr>
                    <td colspan="2" style="text-align: right; color: #dc3545;">Diskon / Promo:</td>
                    <td style="text-align: right; color: #dc3545;">- Rp{{ number_format($invoice->discount_amount, 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <table style="width: 100%;">
            <tr>
                <td style="width: 60%;">
                    @if($invoice->status !== 'paid')
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; font-size: 11px;">
                        <strong>Catatan Pembayaran:</strong><br>
                        Silakan lakukan pembayaran ke rekening berikut:<br>
                        Bank Central Asia (BCA)<br>
                        No Rek: 1234-567-890<br>
                        A/N: MBG AkunPro (Pusat)
                    </div>
                    @endif
                </td>
                <td style="width: 40%;">
                    <table class="totals-table">
                        <tr>
                            <td>Subtotal:</td>
                            <td style="text-align: right;">Rp{{ number_format($invoice->base_amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="total-row">
                            <td>TOTAL AKHIR:</td>
                            <td style="text-align: right;">Rp{{ number_format($invoice->final_amount, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="terbilang">
            <strong>Terbilang:</strong> {{ $terbilang }} Rupiah
        </div>

        <div class="footer">
            <p>Invoice ini sah dan diterbitkan secara elektronik oleh Sistem MBG AkunPro.</p>
            <p>&copy; {{ date('Y') }} MBG AkunPro - SaaS Multi-Tenant Platform for Kitchen Management</p>
        </div>
    </div>
</body>
</html>
