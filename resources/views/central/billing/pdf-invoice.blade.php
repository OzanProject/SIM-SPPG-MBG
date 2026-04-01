<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        @page { margin: 40px 45px; }
        body { font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif; color: #334155; line-height: 1.5; font-size: 12px; background-color: #ffffff; margin: 0; }
        
        .header { width: 100%; border-bottom: 2px solid #e2e8f0; padding-bottom: 25px; margin-bottom: 30px; }
        .header td { vertical-align: top; }
        .logo-text { font-size: 28px; font-weight: 900; color: #4f46e5; margin: 0; letter-spacing: -0.5px; line-height: 1; }
        .logo-sub { font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; font-weight: bold; margin-top: 5px; display: block; }
        
        .inv-title { font-size: 32px; font-weight: 900; color: #0f172a; margin: 0; text-align: right; letter-spacing: 1px; line-height: 1; }
        .inv-no { font-size: 14px; font-weight: 700; color: #64748b; text-align: right; margin-top: 8px; }
        
        .badge { display: inline-block; padding: 5px 12px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; text-align: center; }
        .badge-paid { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-pending { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .badge-expired { background-color: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

        .info-row { width: 100%; margin-bottom: 35px; }
        .info-row td { width: 50%; vertical-align: top; }
        .info-box { background-color: #f8fafc; padding: 15px 20px; border-radius: 8px; border: 1px solid #f1f5f9; }
        .info-label { font-size: 9px; color: #94a3b8; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; margin-bottom: 5px; }
        .info-main { font-size: 14px; font-weight: bold; color: #0f172a; margin-bottom: 3px; }
        .info-sub { font-size: 11px; color: #64748b; line-height: 1.6; }
        
        .dates-wrapper { text-align: right; }
        .date-item { margin-bottom: 8px; }
        .date-label { font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: bold; display: inline-block; width: 100px; }
        .date-val { font-size: 12px; font-weight: bold; color: #0f172a; display: inline-block; }

        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .items-table th { background-color: #f1f5f9; color: #475569; font-size: 10px; font-weight: bold; text-transform: uppercase; padding: 12px 15px; text-align: left; border-top: 1px solid #cbd5e1; border-bottom: 2px solid #cbd5e1; letter-spacing: 0.5px; }
        .items-table th.text-right { text-align: right; }
        .items-table th.text-center { text-align: center; }
        .items-table td { padding: 15px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .items-table td.text-right { text-align: right; }
        .items-table td.text-center { text-align: center; }
        
        .item-name { font-size: 14px; font-weight: bold; color: #0f172a; margin-bottom: 4px; text-transform: uppercase; }
        .item-desc { font-size: 11px; color: #64748b; line-height: 1.4; }
        .feature-ul { margin: 8px 0 0 15px; padding: 0; font-size: 10px; color: #64748b; }
        .feature-ul li { margin-bottom: 3px; }

        .summary-row { width: 100%; }
        .summary-row td { vertical-align: top; }
        
        .payment-box { background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 15px; width: 85%; }
        .payment-title { font-size: 11px; font-weight: bold; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .payment-text { font-size: 11px; color: #64748b; line-height: 1.5; }

        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 8px 15px; text-align: right; font-size: 12px; color: #475569; }
        .totals-table td.lbl { text-align: left; font-weight: bold; color: #64748b; }
        .totals-table .discount { color: #10b981; }
        .totals-table .grand-total td { font-size: 18px; font-weight: 900; color: #4f46e5; border-top: 2px solid #e2e8f0; padding-top: 15px; margin-top: 5px; }
        
        .terbilang-box { background-color: #eef2ff; border: 1px solid #c7d2fe; color: #3730a3; padding: 12px 15px; border-radius: 6px; font-size: 11px; font-style: italic; font-weight: bold; margin-top: 10px; }

        .footer { margin-top: 60px; padding-top: 20px; border-top: 1px solid #e2e8f0; text-align: center; }
        .footer-text { font-size: 10px; color: #94a3b8; line-height: 1.6; }
        .footer-text strong { color: #64748b; }
    </style>
</head>
<body>

    <!-- HEADER -->
    <table class="header">
        <tr>
            <td>
                <div class="logo-text">MBG AKUNPRO</div>
                <span class="logo-sub">Sistem Manajemen Terpadu Dapur</span>
            </td>
            <td style="text-align: right;">
                <h1 class="inv-title">INVOICE</h1>
                <div class="inv-no">#{{ $invoice->invoice_number }}</div>
                <div style="margin-top: 10px;">
                    @if($invoice->status === 'paid')
                        <span class="badge badge-paid">LUNAS</span>
                    @elseif($invoice->status === 'pending')
                        <span class="badge badge-pending">PENDING (MENUNGGU PEMBAYARAN)</span>
                    @else
                        <span class="badge badge-expired">{{ strtoupper($invoice->status) }}</span>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- INFO ROW -->
    <table class="info-row">
        <tr>
            <td style="padding-right: 20px;">
                <div class="info-box">
                    <div class="info-label">DITAGIHKAN KEPADA:</div>
                    <div class="info-main">{{ strtoupper($invoice->tenant->name ?? $invoice->tenant_id) }}</div>
                    <div class="info-sub">
                        <strong>Tenant ID:</strong> {{ $invoice->tenant_id }}<br>
                        <strong>Email Pengelola:</strong> {{ $invoice->tenant->email ?? 'Tidak Tersedia' }}<br>
                        @if($invoice->tenant && $invoice->tenant->domains->first())
                            <strong>Domain:</strong> {{ $invoice->tenant->domains->first()->domain }}
                        @endif
                    </div>
                </div>
            </td>
            <td>
                <div class="dates-wrapper">
                    <div class="date-item">
                        <span class="date-label">Tanggal Terbit:</span>
                        <span class="date-val">{{ $invoice->created_at->format('d F Y') }}</span>
                    </div>
                    <div class="date-item">
                        <span class="date-label">Batas Waktu:</span>
                        <span class="date-val">{{ $invoice->due_date ? $invoice->due_date->format('d F Y') : '-' }}</span>
                    </div>
                    @if($invoice->paid_at)
                    <div class="date-item">
                        <span class="date-label">Tanggal Lunas:</span>
                        <span class="date-val" style="color: #166534;">{{ $invoice->paid_at->format('d F Y, H:i') }}</span>
                    </div>
                    @endif
                    @if($invoice->payment_method)
                    <div class="date-item">
                        <span class="date-label">Metode Bayar:</span>
                        <span class="date-val">{{ strtoupper($invoice->payment_method) }}</span>
                    </div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- ITEMS TABLE -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 55%;">Deskripsi Layanan & Fitur</th>
                <th class="text-center" style="width: 15%;">Siklus</th>
                <th class="text-right" style="width: 30%;">Harga Net</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="item-name">PAKET LANGGANAN: {{ $invoice->subscriptionPlan->name ?? 'Kustom' }}</div>
                    <div class="item-desc">Akses penuh ke sistem MBG AkunPro sesuai dengan kapasitas dan fitur paket:</div>
                    
                    @if($invoice->subscriptionPlan)
                    <ul class="feature-ul">
                        <li>Kapasitas User: {{ $invoice->subscriptionPlan->max_users ?: 'Unlimited' }} Karyawan</li>
                        <li>Kapasitas Barang: {{ $invoice->subscriptionPlan->max_items ?: 'Unlimited' }} Data</li>
                        <li>Kapasitas Transaksi: {{ $invoice->subscriptionPlan->max_transactions ?: 'Unlimited' }} /Bulan</li>
                        @if($invoice->subscriptionPlan->has_pos) <li>Akses Modul Point of Sales (POS)</li> @endif
                        @if($invoice->subscriptionPlan->has_inventory) <li>Akses Modul Inventori & Stok</li> @endif
                        @if($invoice->subscriptionPlan->has_accounting) <li>Akses Modul Akuntansi & Laporan</li> @endif
                        @if($invoice->subscriptionPlan->has_hrd) <li>Akses Modul HRD & Penggajian</li> @endif
                    </ul>
                    @endif
                </td>
                <td class="text-center">
                    {{ $invoice->subscriptionPlan->duration_in_days ?? 0 }} Hari
                </td>
                <td class="text-right font-bold" style="color: #0f172a;">
                    Rp {{ number_format($invoice->base_amount, 0, ',', '.') }}
                </td>
            </tr>
            @if($invoice->discount_amount > 0)
            <tr>
                <td colspan="2" class="text-right" style="color: #10b981; font-weight: bold; font-size: 10px;">
                    POTONGAN KODE PROMO ({{ $invoice->promoCode->code ?? 'VOUCHER' }})
                </td>
                <td class="text-right" style="color: #10b981; font-weight: bold;">
                    - Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- SUMMARY SECTION -->
    <table class="summary-row">
        <tr>
            <td style="width: 50%;">
                @if($invoice->status !== 'paid')
                <div class="payment-box">
                    <div class="payment-title">📌 Instruksi Pembayaran Bank</div>
                    <div class="payment-text">
                        Mohon segera lunasi tagihan ini sebelum batas waktu <strong>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</strong> agar layanan tidak dinonaktifkan.<br><br>
                        Transfer dapat dilakukan ke salah satu rekening resmi kami. Konfirmasi bukti transfer wajib dilampirkan lewat Dashboard.
                    </div>
                </div>
                @endif
                @if($invoice->notes)
                <div style="margin-top: 15px; font-size: 10px; color: #64748b;">
                    <strong>Catatan Admin:</strong><br>
                    {{ $invoice->notes }}
                </div>
                @endif
            </td>
            <td style="width: 50%;">
                <table class="totals-table">
                    <tr>
                        <td class="lbl">Subtotal Tarip</td>
                        <td>Rp {{ number_format($invoice->base_amount, 0, ',', '.') }}</td>
                    </tr>
                    @if($invoice->discount_amount > 0)
                    <tr class="discount">
                        <td class="lbl" style="color: #10b981;">Total Diskon</td>
                        <td>- Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="grand-total">
                        <td class="lbl" style="color: #0f172a;">TOTAL TAGIHAN</td>
                        <td>Rp {{ number_format($invoice->final_amount, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="terbilang-box">
        Terbilang: "{{ $terbilang }} Rupiah"
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-text">
            <strong>Terima kasih atas kepercayaan Anda menggunakan layanan MBG AkunPro.</strong><br>
            Dokumen ini dihasilkan secara otomatis oleh sistem komputer dan sah sebagai alat bukti tagihan.<br>
            Apabila ada ketidaksesuaian, harap hubungi Layanan Pelanggan (Support Center).<br>
            <span style="font-size: 9px; margin-top: 10px; display: block;">&copy; {{ date('Y') }} MBG AkunPro Software. All rights reserved.</span>
        </div>
    </div>

</body>
</html>
