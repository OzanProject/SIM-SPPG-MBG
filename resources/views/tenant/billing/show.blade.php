@extends('layouts.app')

@section('title', 'Detail Tagihan - ' . $invoice->invoice_number)

@section('content')
<div class="content-header pt-4 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard', ['tenant' => tenant('id')]) }}" class="text-primary">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tenant.billing.index') }}" class="text-primary">Billing</a></li>
                        <li class="breadcrumb-item active">{{ $invoice->invoice_number }}</li>
                    </ol>
                </nav>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 2rem;">Detail Tagihan</h1>
            </div>
            <div class="col-sm-6 text-md-right mt-3 mt-md-0">
                <a href="{{ route('tenant.billing.invoice.download', $invoice->id) }}" class="btn btn-primary rounded-pill px-4 shadow-sm mr-2 transition-all hover-scale">
                    <i class="fas fa-file-pdf mr-2"></i> Download PDF
                </a>
                <a href="{{ route('tenant.billing.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-chevron-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content pb-5">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" style="border-radius: 15px;">
                <div class="d-flex align-items-center">
                    <div class="mr-3"><i class="fas fa-check-circle fa-2x"></i></div>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <!-- MAIN INVOICE CARD -->
                <div class="card border-0 shadow-lg overflow-hidden mb-4" style="border-radius: 20px;">
                    <div class="card-header bg-white py-4 border-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge badge-light px-3 py-2 mb-2 text-uppercase letter-spacing-1" style="font-size: 0.75rem;">Official Invoice</span>
                                <h3 class="font-weight-bold text-dark mb-0">{{ $invoice->invoice_number }}</h3>
                                <p class="text-muted mb-0"><i class="far fa-calendar-alt mr-1"></i> Diterbitkan: {{ $invoice->created_at->format('d F Y, H:i') }}</p>
                            </div>
                            <div class="text-right">
                                @php
                                    $statusClass = [
                                        'paid' => 'bg-success-soft text-success',
                                        'pending' => 'bg-warning-soft text-warning',
                                        'expired' => 'bg-danger-soft text-danger',
                                        'cancelled' => 'bg-secondary-soft text-secondary'
                                    ][$invoice->status] ?? 'bg-light text-dark';
                                @endphp
                                <span class="badge {{ $statusClass }} px-4 py-2 rounded-pill font-weight-bold" style="font-size: 0.9rem;">
                                    {{ strtoupper($invoice->status === 'paid' ? 'Lunas' : ($invoice->status === 'pending' ? 'Menunggu Bayar' : $invoice->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        <!-- Summary Table -->
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="bg-light text-muted small text-uppercase font-weight-bold">
                                    <tr>
                                        <th class="pl-4 border-0">Deskripsi Layanan</th>
                                        <th class="text-center border-0">Durasi</th>
                                        <th class="text-right pr-4 border-0">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="align-middle">
                                        <td class="pl-4 py-4 border-bottom-0">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary-soft rounded p-3 mr-3">
                                                    <i class="fas fa-box-open text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="font-weight-bold mb-0">{{ $invoice->subscriptionPlan->name ?? 'Paket Kustom' }}</h6>
                                                    <small class="text-muted">Akses fitur premium MBG Akunpro</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center border-bottom-0">{{ $invoice->subscriptionPlan->duration_in_days ?? 0 }} Hari</td>
                                        <td class="text-right pr-4 border-bottom-0 font-weight-bold">Rp{{ number_format($invoice->base_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($invoice->discount_amount > 0)
                                    <tr>
                                        <td colspan="2" class="text-right text-muted pl-4">Diskon Kode Promo ({{ $invoice->promoCode->code ?? 'Voucher' }})</td>
                                        <td class="text-right pr-4 text-success font-weight-bold">- Rp{{ number_format($invoice->discount_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Grand Total Section -->
                        <div class="bg-dark text-white p-4 d-flex justify-content-between align-items-center shadow-inner">
                            <div>
                                <h5 class="mb-0 font-weight-light">Total Pembayaran</h5>
                                <small class="text-light-50">Silakan bayar sebelum {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</small>
                            </div>
                            <div class="text-right">
                                <h2 class="font-weight-bold mb-0 active-glow">Rp{{ number_format($invoice->final_amount, 0, ',', '.') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NOTES / INFO -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; background: rgba(255,255,255,0.7); backdrop-filter: blur(10px);">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle text-info mt-1 mr-3"></i>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Catatan Tambahan</h6>
                                <p class="text-muted small mb-0">{{ $invoice->notes ?: 'Terima kasih telah berlangganan sistem kami. Pastikan nominal transfer sesuai agar verifikasi berjalan otomatis.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- SIDEBAR: PAYMENT INSTRUCTIONS -->
                @if($invoice->status == 'pending')
                <div class="card border-0 shadow-lg text-white mb-4 position-relative overflow-hidden" style="border-radius: 20px; background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);">
                    <div class="card-body p-4" style="z-index: 1;">
                        <h5 class="font-weight-bold mb-4"><i class="fas fa-university mr-2"></i> Instruksi Pembayaran</h5>
                        <p class="small mb-4 text-light-50">Silakan transfer via ATM / Mobile Banking ke salah satu rekening resmi kami di bawah ini:</p>

                        @forelse($paymentMethods as $pm)
                        <div class="bg-white rounded-lg p-3 mb-3 text-dark shadow-sm position-relative overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge badge-primary px-2 py-1 mb-2">{{ $pm->bank_name }}</span>
                                <button type="button" class="btn btn-sm btn-link text-primary p-0" onclick="copyToClipboard('{{ $pm->account_number }}', this)">
                                    <i class="far fa-copy mr-1"></i> Copy
                                </button>
                            </div>
                            <h4 class="font-weight-bold tracking-tight mb-0" id="acc_{{ $pm->id }}">{{ $pm->account_number }}</h4>
                            <small class="text-muted d-block mt-1">A.n. {{ $pm->account_name }}</small>
                            
                            <!-- Success Check Overlay (HIDDEN BY DEFAULT) -->
                            <div class="copy-success-overlay" style="display:none; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.9); z-index:2; align-items:center; justify-content:center;">
                                <span class="text-success font-weight-bold animated fadeIn"><i class="fas fa-check-circle mr-1"></i> Salin Berhasil</span>
                            </div>
                        </div>
                        @empty
                        <div class="alert alert-light-primary">
                            Belum ada rekening bank yang tersedia. Silakan hubungi admin.
                        </div>
                        @endforelse

                        <div class="alert bg-white-20 text-white border-0 mt-4 mb-0 small" style="border-radius: 10px;">
                            <i class="fas fa-exclamation-triangle mr-1"></i> PENTING: Mohon gunakan kode unik atau sesuai nominal Tagihan agar aktivasi lebih cepat.
                        </div>
                    </div>
                    <!-- Decor Circles -->
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; border-radius:100px; background:rgba(255,255,255,0.1); z-index:0;"></div>
                    <div style="position:absolute; bottom:-30px; left:10px; width:60px; height:60px; border-radius:100px; background:rgba(255,255,255,0.1); z-index:0;"></div>
                </div>

                <!-- UPLOAD BUKTI CARD -->
                <div class="card border-0 shadow-lg text-center" style="border-radius: 20px;">
                    <div class="card-header bg-white pt-4 border-0">
                        <h5 class="font-weight-bold text-dark mb-0">Konfirmasi Bayar</h5>
                    </div>
                    <div class="card-body p-4">
                        @if(!$invoice->payment_proof)
                        <form action="{{ route('tenant.billing.invoice.upload', $invoice->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="drag-upload-area mb-3 p-4 border-dashed rounded cursor-pointer" onclick="document.getElementById('payment_proof').click()">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-2"></i>
                                <h6 class="text-muted">Klik untuk Pilih Foto</h6>
                                <small class="text-muted-50">Format: JPG, PNG, WEBP (Max 2MB)</small>
                                <input type="file" id="payment_proof" name="payment_proof" class="d-none" accept="image/*" onchange="previewUpload(this)">
                            </div>
                            <div id="file_preview_container" style="display:none;" class="mb-3">
                                <span class="badge badge-info py-2 px-3 rounded-pill"><i class="fas fa-file-image mr-1"></i> <span id="file_name"></span></span>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block btn-lg rounded-pill shadow-lg transition-all hover-scale">
                                Kirim Konfirmasi <i class="fas fa-rocket ml-2"></i>
                            </button>
                        </form>
                        @else
                        <div class="alert alert-info py-3 mb-0" style="border-radius: 15px;">
                            <i class="fas fa-hourglass-half mb-2 fa-lg d-block"></i>
                            <h6 class="font-weight-bold mb-1">Bukti Sudah Terkirim</h6>
                            <small>Menunggu verifikasi admin pusat (Estimasi < 24 Jam)</small>
                        </div>
                        <div class="mt-3">
                            <a href="{{ global_asset('storage/' . ltrim(str_replace(['/storage/', 'storage/'], '', $invoice->payment_proof), '/')) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill">
                                <i class="fas fa-search-plus mr-1"></i> Lihat Bukti Saya
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- IF ALREADY PAID -->
                @if($invoice->status == 'paid')
                <div class="card border-0 shadow-lg text-center overflow-hidden" style="border-radius: 20px;">
                    <div class="bg-success py-4">
                        <i class="fas fa-check-circle fa-4x text-white mb-2 animated bounceIn"></i>
                        <h4 class="text-white font-weight-bold mb-0">LUNAS</h4>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted">Terima kasih atas pembayarannya. Langganan Anda telah diperpanjang dan status website AKTIF.</p>
                        <hr>
                        <div class="text-left small mb-3">
                            <span class="text-muted">Metode:</span> <strong class="text-dark">{{ $invoice->payment_method ?: 'Transfer Bank' }}</strong><br>
                            <span class="text-muted">Waktu:</span> <strong class="text-dark">{{ $invoice->paid_at->format('d M Y, H:i') }}</strong>
                        </div>
                        <a href="{{ route('tenant.billing.index') }}" class="btn btn-success btn-block rounded-pill shadow-sm">
                            Atur Paket Lain <i class="fas fa-box-open ml-2"></i>
                        </a>
                    </div>
                </div>

                @if($invoice->payment_proof)
                <div class="card border-0 shadow-sm mt-3" style="border-radius:15px;">
                    <div class="card-body p-3 text-center">
                        <p class="small text-muted mb-2">Histori Bukti Pembayaran:</p>
                        <a href="{{ global_asset('storage/' . ltrim(str_replace(['/storage/', 'storage/'], '', $invoice->payment_proof), '/')) }}" target="_blank">
                             <img src="{{ global_asset('storage/' . ltrim(str_replace(['/storage/', 'storage/'], '', $invoice->payment_proof), '/')) }}" class="img-fluid rounded border shadow-inner" style="max-height: 100px;">
                        </a>
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
</section>

<style>
/* Custom Styles for Premium Feel */
.bg-success-soft { background-color: rgba(40, 167, 69, 0.1); }
.bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); }
.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
.bg-secondary-soft { background-color: rgba(108, 117, 125, 0.1); }
.bg-primary-soft { background-color: rgba(0, 123, 255, 0.1); }
.bg-white-20 { background-color: rgba(255, 255, 255, 0.2); }
.letter-spacing-1 { letter-spacing: 1px; }
.active-glow { text-shadow: 0 0 10px rgba(0, 123, 255, 0.3); }
.border-dashed { border: 2px dashed #ddd; }
.transition-all { transition: all 0.3s ease; }
.hover-scale:hover { transform: scale(1.02); }
.cursor-pointer { cursor: pointer; }
.drag-upload-area:hover { background-color: #f8f9fa; border-color: #007bff; }
.shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06); }
.bg-dark { background-color: #1a1e21 !important; }

/* Timeline colors or similar custom utility classes can be added here */
</style>

@endsection

@push('js')
<script>
    function copyToClipboard(text, btn) {
        var tempInput = document.createElement("input");
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        
        var overlay = $(btn).closest('.bg-white').find('.copy-success-overlay');
        overlay.css('display', 'flex');
        setTimeout(function() {
            overlay.fadeOut();
        }, 1500);
    }

    function previewUpload(input) {
        if (input.files && input.files[0]) {
            $('#file_name').text(input.files[0].name);
            $('#file_preview_container').fadeIn();
        }
    }
</script>
@endpush

