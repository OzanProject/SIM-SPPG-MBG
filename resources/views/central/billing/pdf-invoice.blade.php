<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        @page { margin: 30px 40px 40px 40px; }
        * { box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.55;
            margin: 0;
            background: #fff;
        }

        /* ── WATERMARK ── */
        .watermark {
            position: fixed;
            top: 38%;
            left: 10%;
            width: 80%;
            text-align: center;
            font-size: 80px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 10px;
            opacity: 0.04;
            color: #4f46e5;
            transform: rotate(-30deg);
            z-index: 0;
        }

        /* ── HEADER ── */
        .header-wrap {
            width: 100%;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 18px;
            margin-bottom: 22px;
        }
        .header-left { float: left; width: 55%; }
        .header-right { float: right; width: 45%; text-align: right; }
        .clearfix::after { content: ''; display: table; clear: both; }

        .app-name {
            font-size: 24px;
            font-weight: 900;
            color: #4f46e5;
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .app-tagline {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: bold;
            margin-top: 4px;
            display: block;
        }
        .app-contact {
            margin-top: 8px;
            font-size: 9.5px;
            color: #475569;
            line-height: 1.6;
        }

        .inv-label {
            font-size: 30px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: 2px;
            line-height: 1;
        }
        .inv-number {
            font-size: 13px;
            font-weight: bold;
            color: #64748b;
            margin-top: 4px;
        }
        .inv-date {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* ── STATUS BADGE ── */
        .badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 10px;
        }
        .badge-paid    { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-pending { background: #fef9c3; color: #a16207; border: 1px solid #fde047; }
        .badge-expired { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
        .badge-cancelled { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }

        /* ── INFO SECTION ── */
        .info-section {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-col-left  { float: left;  width: 48%; }
        .info-col-right { float: right; width: 48%; }

        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #4f46e5;
            border-radius: 6px;
            padding: 14px 16px;
        }
        .info-box-warn {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-left: 4px solid #f59e0b;
            border-radius: 6px;
            padding: 14px 16px;
        }
        .info-group-label {
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
            margin-bottom: 6px;
        }
        .info-name {
            font-size: 13px;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .info-row {
            font-size: 9.5px;
            color: #475569;
            line-height: 1.7;
        }
        .info-row strong { color: #0f172a; }

        /* ── DATES TABLE ── */
        .dates-table { width: 100%; }
        .dates-table td { padding: 4px 0; font-size: 10px; vertical-align: top; }
        .dates-table td.dt-label { color: #64748b; font-weight: bold; width: 45%; text-align: left; }
        .dates-table td.dt-val { color: #0f172a; font-weight: bold; text-align: right; }
        .dates-table td.dt-val.overdue { color: #dc2626; }
        .dates-table td.dt-val.paid-color { color: #16a34a; }

        /* ── ITEMS TABLE ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 22px;
            margin-bottom: 0;
        }
        .items-table thead tr th {
            background: #1e293b;
            color: #e2e8f0;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 12px;
            border: none;
        }
        .items-table thead tr th:first-child { border-radius: 6px 0 0 0; }
        .items-table thead tr th:last-child  { border-radius: 0 6px 0 0; }
        .items-table tbody tr td {
            padding: 14px 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .items-table tbody tr:last-child td { border-bottom: none; }
        .items-table tfoot tr td {
            padding: 10px 12px;
            font-size: 11px;
        }

        .item-name { font-size: 12px; font-weight: 900; color: #0f172a; }
        .item-plan-desc { font-size: 10px; color: #64748b; margin-top: 3px; }
        .feature-list {
            margin: 8px 0 0 0;
            padding: 0;
            list-style: none;
        }
        .feature-list li {
            font-size: 9.5px;
            color: #64748b;
            padding: 2px 0;
            padding-left: 14px;
            position: relative;
        }
        .feature-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #22c55e;
            font-weight: bold;
        }
        .capacity-badges {
            margin-top: 8px;
        }
        .cap-chip {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 2px 8px;
            font-size: 8.5px;
            font-weight: bold;
            margin-right: 4px;
            margin-bottom: 3px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* ── TOTALS / SUMMARY ── */
        .summary-wrap { width: 100%; margin-top: 20px; }
        .summary-left  { float: left;  width: 52%; vertical-align: top; }
        .summary-right { float: right; width: 44%; vertical-align: top; }

        .totals-table { width: 100%; }
        .totals-table td { padding: 6px 10px; font-size: 11px; }
        .totals-table td.t-label { color: #64748b; font-weight: bold; }
        .totals-table td.t-val   { text-align: right; font-weight: bold; color: #0f172a; }
        .totals-table td.t-green { color: #16a34a; }
        .totals-table .grand-row td {
            font-size: 15px;
            font-weight: 900;
            color: #4f46e5;
            border-top: 2px solid #e2e8f0;
            padding-top: 12px;
            padding-bottom: 10px;
        }

        /* ── TERBILANG ── */
        .terbilang-box {
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 10px;
            font-style: italic;
            color: #3730a3;
            font-weight: bold;
            margin-top: 16px;
            margin-bottom: 22px;
        }

        /* ── NOTES / PAYMENT BOX ── */
        .notes-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 12px 14px;
            font-size: 10px;
            color: #475569;
            line-height: 1.6;
        }
        .notes-title { font-weight: 900; color: #0f172a; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }

        /* ── FOOTER ── */
        .footer-line { border-top: 1px solid #e2e8f0; margin-top: 30px; padding-top: 14px; }
        .footer-text { font-size: 9px; color: #94a3b8; text-align: center; line-height: 1.6; }
        .footer-text strong { color: #64748b; }
        .sig-box { text-align: center; margin-top: 30px; }
        .sig-line { border-top: 1px solid #1e293b; width: 180px; margin: 50px auto 0; }
        .sig-label { font-size: 9px; color: #64748b; margin-top: 6px; }
    </style>
</head>
<body>

@php
    $cfg = $appConfig ?? collect();
    $appName    = $cfg['app_name']    ?? config('app.name', 'MBG AKUNPRO');
    $appAddress = $cfg['app_address'] ?? 'Jl. Contoh No.1, Indonesia';
    $appPhone   = $cfg['app_phone']   ?? '-';
    $appEmail   = $cfg['app_email']   ?? '-';
    $appWebsite = $cfg['app_url']     ?? config('app.url');

    $tenant        = $invoice->tenant;
    $plan          = $invoice->subscriptionPlan;
    $domain        = $tenant?->domains?->first()?->domain;
    $tenantUrl     = rtrim(config('app.url'), '/') . '/' . $invoice->tenant_id . '/dashboard';
    $tenantName    = $tenant?->name ?? $invoice->tenant_id;
    $tenantEmail   = $tenant?->email ?? '-';

    $isPaid    = $invoice->status === 'paid';
    $isPending = $invoice->status === 'pending';
    $isDue     = $invoice->due_date && $invoice->due_date->isPast() && !$isPaid;
@endphp

<div class="watermark">{{ $isPaid ? 'LUNAS' : ($isPending ? 'UNPAID' : strtoupper($invoice->status)) }}</div>

{{-- ═══════════ HEADER ═══════════ --}}
<div class="header-wrap clearfix">
    <div class="header-left">
        <div class="app-name">{{ strtoupper($appName) }}</div>
        <span class="app-tagline">Sistem Manajemen Terpadu Dapur</span>
        <div class="app-contact">
            {{ $appAddress }}<br>
            📞 {{ $appPhone }} &nbsp;&nbsp; ✉ {{ $appEmail }}<br>
            🌐 {{ $appWebsite }}
        </div>
    </div>
    <div class="header-right">
        <div class="inv-label">INVOICE</div>
        <div class="inv-number">#{{ $invoice->invoice_number }}</div>
        <div class="inv-date">Diterbitkan: {{ $invoice->created_at->format('d F Y, H:i') }} WIB</div>
        <div>
            @if($isPaid)
                <span class="badge badge-paid">✔ LUNAS</span>
            @elseif($invoice->status === 'pending')
                <span class="badge badge-pending">⏳ MENUNGGU PEMBAYARAN</span>
            @elseif($invoice->status === 'expired')
                <span class="badge badge-expired">✖ KADALUARSA</span>
            @else
                <span class="badge badge-cancelled">{{ strtoupper($invoice->status) }}</span>
            @endif
        </div>
    </div>
</div>

{{-- ═══════════ BILLING INFO ═══════════ --}}
<div class="info-section clearfix">

    {{-- TAGIHAN KEPADA --}}
    <div class="info-col-left">
        <div class="info-box">
            <div class="info-group-label">🏠 TAGIHAN KEPADA</div>
            <div class="info-name">{{ strtoupper($tenantName) }}</div>
            <div class="info-row">
                <strong>Tenant ID:</strong> {{ $invoice->tenant_id }}<br>
                <strong>Email:</strong> {{ $tenantEmail }}<br>
                @if($domain)
                    <strong>Domain:</strong> {{ $domain }}<br>
                @endif
                <strong>URL Dapur:</strong> {{ $tenantUrl }}
                @if($tenant?->subscription_ends_at)
                    <br><strong>Langganan s.d.:</strong> {{ \Carbon\Carbon::parse($tenant->subscription_ends_at)->format('d M Y') }}
                @endif
            </div>
        </div>
    </div>

    {{-- DETAIL TANGGAL --}}
    <div class="info-col-right">
        <div class="info-box">
            <div class="info-group-label">📅 DETAIL TAGIHAN</div>
            <table class="dates-table">
                <tr>
                    <td class="dt-label">Nomor Invoice</td>
                    <td class="dt-val">#{{ $invoice->invoice_number }}</td>
                </tr>
                <tr>
                    <td class="dt-label">Tanggal Terbit</td>
                    <td class="dt-val">{{ $invoice->created_at->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td class="dt-label">Batas Pembayaran</td>
                    <td class="dt-val {{ $isDue ? 'overdue' : '' }}">
                        {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}
                        @if($isDue) (TERLAMBAT) @endif
                    </td>
                </tr>
                @if($isPaid)
                <tr>
                    <td class="dt-label">Tanggal Lunas</td>
                    <td class="dt-val paid-color">{{ $invoice->paid_at->format('d M Y, H:i') }}</td>
                </tr>
                @endif
                @if($invoice->payment_method)
                <tr>
                    <td class="dt-label">Metode Bayar</td>
                    <td class="dt-val">{{ strtoupper($invoice->payment_method) }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
</div>

{{-- ═══════════ ITEMS TABLE ═══════════ --}}
<table class="items-table">
    <thead>
        <tr>
            <th style="width:58%;">DESKRIPSI LAYANAN & FITUR AKTIF</th>
            <th class="text-center" style="width:14%;">SIKLUS</th>
            <th class="text-right" style="width:13%;">HARGA</th>
            <th class="text-right" style="width:15%;">TOTAL</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <div class="item-name">
                    PAKET LANGGANAN {{ strtoupper($plan?->name ?? 'KUSTOM') }}
                </div>
                @if($plan?->description)
                    <div class="item-plan-desc">{{ $plan->description }}</div>
                @endif

                {{-- Capacity Chips --}}
                @if($plan)
                <div class="capacity-badges" style="margin-top: 8px;">
                    <span class="cap-chip">👥 {{ $plan->max_users ?: '∞' }} Pengguna</span>
                    <span class="cap-chip">📦 {{ $plan->max_items ?: '∞' }} Barang</span>
                    <span class="cap-chip">🔁 {{ $plan->max_transactions ?: '∞' }} Transaksi/bln</span>
                </div>
                @endif

                {{-- Feature List --}}
                @if($plan)
                <ul class="feature-list" style="margin-top:8px; columns: 2; column-gap: 10px;">
                    @if($plan->has_pos)         <li>Modul Point of Sales (POS)</li>@endif
                    @if($plan->has_inventory)   <li>Modul Inventori & Stok</li>@endif
                    @if($plan->has_accounting)  <li>Modul Akuntansi & Laporan</li>@endif
                    @if($plan->has_hrd)         <li>Modul HRD & Penggajian</li>@endif
                    @if($plan->has_purchasing)  <li>Modul Pembelian</li>@endif
                    @if($plan->has_reports)     <li>Laporan Lanjutan</li>@endif
                    @if($plan->has_api_access)  <li>Akses API Developer</li>@endif
                    @if($plan->has_multi_branch)<li>Multi Cabang</li>@endif
                    <li>Backup Data Otomatis</li>
                    <li>Support via Sistem Tiket</li>
                </ul>
                @endif
            </td>
            <td class="text-center" style="vertical-align: middle;">
                {{ $plan?->duration_in_days ?? 30 }} Hari
            </td>
            <td class="text-right" style="vertical-align: middle;">
                Rp {{ number_format($invoice->base_amount, 0, ',', '.') }}
            </td>
            <td class="text-right" style="vertical-align: middle; font-weight: bold;">
                Rp {{ number_format($invoice->base_amount, 0, ',', '.') }}
            </td>
        </tr>
    </tbody>
    <tfoot>
        @if($invoice->discount_amount > 0)
        <tr style="background: #f0fdf4;">
            <td colspan="3" class="text-right" style="color:#16a34a; font-weight:bold; font-size:10px;">
                🎟 DISKON KODE PROMO
                @if($invoice->promoCode)
                    ({{ $invoice->promoCode->code }}
                    — {{ $invoice->promoCode->type === 'percent' ? $invoice->promoCode->value.'%' : 'Tetap' }})
                @endif
            </td>
            <td class="text-right" style="color:#16a34a; font-weight:bold;">
                − Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}
            </td>
        </tr>
        @endif
        <tr style="background: #1e293b;">
            <td colspan="3" class="text-right" style="color:#94a3b8; font-size:10px; font-weight:bold; letter-spacing:1px;">
                TOTAL TAGIHAN AKHIR
            </td>
            <td class="text-right" style="color:#a5b4fc; font-size:15px; font-weight:900;">
                Rp {{ number_format($invoice->final_amount, 0, ',', '.') }}
            </td>
        </tr>
    </tfoot>
</table>

{{-- ═══════════ TERBILANG ═══════════ --}}
<div class="terbilang-box">
    <strong>Terbilang:</strong> {{ trim($terbilang) }} Rupiah
</div>

{{-- ═══════════ NOTES + SIGNATURE ═══════════ --}}
<div class="clearfix">
    <div style="float:left; width:54%;">
        @if(!$isPaid)
        <div class="notes-box">
            <div class="notes-title">📌 Instruksi Pembayaran</div>
            Mohon selesaikan pembayaran sebelum <strong>{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}</strong>
            agar layanan tidak dinonaktifkan.<br><br>
            Transfer ke salah satu rekening resmi kami, lalu kirim bukti pembayaran melalui halaman Billing di Dashboard Anda.
        </div>
        @endif
        @if($invoice->notes)
        <div class="notes-box" style="margin-top:10px;">
            <div class="notes-title">💬 Catatan Tambahan</div>
            {{ $invoice->notes }}
        </div>
        @endif
    </div>

    <div style="float:right; width:38%;">
        <div class="sig-box">
            <div style="font-size:10px; color:#64748b; font-weight:bold; text-transform:uppercase; letter-spacing:0.5px;">
                Hormat Kami,
            </div>
            <div class="sig-line"></div>
            <div class="sig-label">
                <strong>{{ $appName }}</strong><br>
                Pengelola Sistem
            </div>
        </div>
    </div>
</div>

{{-- ═══════════ FOOTER ═══════════ --}}
<div class="footer-line">
    <div class="footer-text">
        <strong>{{ $appName }}</strong> — {{ $appAddress }}<br>
        Dokumen ini digenerate secara otomatis oleh sistem &amp; sah tanpa tanda tangan basah.<br>
        Hubungi kami di <strong>{{ $appEmail }}</strong> apabila ada pertanyaan mengenai tagihan ini.<br>
        <span style="font-size:8px;">&copy; {{ date('Y') }} {{ $appName }}. Semua Hak Dilindungi.</span>
    </div>
</div>

</body>
</html>
