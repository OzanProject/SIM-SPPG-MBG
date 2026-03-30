@extends('frontend.layouts.app')

@section('title', 'Selesaikan Pembayaran - DapurMBG')

@section('content')
<main class="min-h-screen bg-slate-950 pt-28 pb-24 relative overflow-hidden flex items-center justify-center">
    {{-- Background Glows --}}
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-indigo-500/10 rounded-full blur-[150px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-pink-500/8 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Ccircle cx=\'1\' cy=\'1\' r=\'1\' fill=\'rgba(255,255,255,0.03)\'/%3E%3C/svg%3E')] pointer-events-none"></div>

    <div class="max-w-5xl w-full mx-auto px-5 relative z-10">

        {{-- HEADER --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-400/20 text-amber-400 text-xs font-bold uppercase tracking-widest mb-4">
                <span class="material-symbols-outlined text-sm">pending_actions</span>
                Langkah 2 dari 2: Pembayaran
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-white">
                Aktivasi <span class="bg-gradient-to-r from-indigo-400 to-pink-400 bg-clip-text text-transparent">Paket Anda</span>
            </h1>
            <p class="text-slate-400 mt-2 text-sm max-w-md mx-auto">
                Transfer sesuai nominal di bawah, lalu upload bukti bayar. Admin akan mengaktifkan akun Anda dalam <strong class="text-white">1x24 jam</strong>.
            </p>
        </div>

        {{-- SUCCESS / INFO ALERT --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl flex items-start gap-3">
                <span class="material-symbols-outlined text-emerald-400 flex-shrink-0">check_circle</span>
                <p class="text-sm text-emerald-300 font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('info'))
            <div class="mb-6 p-4 bg-indigo-500/10 border border-indigo-500/30 rounded-2xl flex items-start gap-3">
                <span class="material-symbols-outlined text-indigo-400 flex-shrink-0">info</span>
                <p class="text-sm text-indigo-300 font-medium">{{ session('info') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- LEFT: INVOICE DETAIL --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Invoice Card --}}
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Detail Tagihan</h2>
                        <span class="text-xs px-3 py-1 rounded-full font-bold bg-amber-500/15 text-amber-400 border border-amber-500/30">
                            ⏳ Menunggu Pembayaran
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Nomor Invoice</span>
                            <span class="text-white font-mono font-bold">{{ $invoice->invoice_number }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Paket</span>
                            <span class="text-indigo-400 font-bold">{{ $invoice->subscriptionPlan?->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Harga Paket</span>
                            <span class="text-white font-bold">Rp {{ number_format($invoice->base_amount, 0, ',', '.') }}</span>
                        </div>
                        @if($invoice->discount_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Diskon Promo</span>
                            <span class="text-emerald-400 font-bold">- Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="h-px bg-white/5 my-2"></div>
                        <div class="flex justify-between">
                            <span class="text-slate-300 font-bold">Total Bayar</span>
                            <span class="text-2xl font-black text-white">Rp {{ number_format($invoice->final_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-500">Jatuh Tempo</span>
                            <span class="font-bold {{ now()->isAfter($invoice->due_date) ? 'text-red-400' : 'text-amber-400' }}">
                                {{ $invoice->due_date->format('d M Y') }}
                                @if(now()->isAfter($invoice->due_date))
                                    <span class="text-red-500">(Kadaluarsa)</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Status Proof --}}
                @if($invoice->payment_proof)
                <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-2xl p-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-emerald-400 text-2xl">verified</span>
                    <div>
                        <p class="text-sm font-bold text-emerald-300">Bukti Sudah Dikirim</p>
                        <p class="text-xs text-slate-400">Admin sedang memverifikasi pembayaran Anda.</p>
                    </div>
                </div>
                @endif

                {{-- Steps --}}
                <div class="bg-white/3 border border-white/5 rounded-2xl p-5 space-y-3">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Alur Aktivasi</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-indigo-500 flex items-center justify-center text-xs font-black text-white flex-shrink-0">✓</div>
                        <p class="text-sm text-slate-300">Akun & Dapur berhasil dibuat</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full {{ $invoice->payment_proof ? 'bg-indigo-500' : 'bg-slate-700' }} flex items-center justify-center text-xs font-black text-white flex-shrink-0">{{ $invoice->payment_proof ? '✓' : '2' }}</div>
                        <p class="text-sm {{ $invoice->payment_proof ? 'text-slate-300' : 'text-white font-bold' }}">Upload bukti pembayaran</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-slate-700 flex items-center justify-center text-xs font-black text-slate-400 flex-shrink-0">3</div>
                        <p class="text-sm text-slate-500">Admin verifikasi (maks. 1x24 jam)</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-slate-700 flex items-center justify-center text-xs font-black text-slate-400 flex-shrink-0">4</div>
                        <p class="text-sm text-slate-500">Paket aktif & dashboard terbuka 🎉</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: BANK INFO + UPLOAD --}}
            <div class="lg:col-span-3 space-y-5">

                {{-- Bank Transfer Instructions --}}
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-indigo-400 text-lg">account_balance</span>
                        Instruksi Transfer Bank
                    </h2>

                    @forelse($paymentMethods as $pm)
                    <div class="mb-4 p-5 bg-slate-900/60 border border-white/8 rounded-2xl group hover:border-indigo-500/40 transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-indigo-400 text-xs font-bold uppercase tracking-widest mb-1">{{ $pm->bank_name }}</p>
                                <p class="text-2xl font-black text-white tracking-widest font-mono">{{ $pm->account_number }}</p>
                                <p class="text-slate-400 text-sm mt-1">a.n. <strong class="text-white">{{ $pm->account_name }}</strong></p>
                            </div>
                            <button type="button" onclick="copyNumber('{{ $pm->account_number }}', this)"
                                class="p-3 rounded-xl bg-slate-800 hover:bg-indigo-500/20 hover:border-indigo-500/40 border border-white/10 text-slate-400 hover:text-indigo-400 transition-all group-hover:text-indigo-400 flex-shrink-0 copy-btn">
                                <span class="material-symbols-outlined text-lg">content_copy</span>
                            </button>
                        </div>
                        @if($pm->notes)
                            <p class="text-xs text-amber-400/80 mt-3 italic flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">info</span>
                                {{ $pm->notes }}
                            </p>
                        @endif
                    </div>
                    @empty
                    <div class="p-5 bg-red-500/10 border border-red-500/20 rounded-2xl text-center">
                        <span class="material-symbols-outlined text-red-400 text-3xl mb-2 block">error</span>
                        <p class="text-sm text-red-300">Rekening bank belum dikonfigurasi. Hubungi admin via WhatsApp.</p>
                    </div>
                    @endforelse

                    <div class="mt-4 p-4 bg-amber-500/5 border border-amber-500/20 rounded-xl">
                        <p class="text-xs text-amber-300 font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">warning</span>
                            PENTING: Transfer tepat sesuai nominal
                            <strong class="text-white text-sm font-black">Rp {{ number_format($invoice->final_amount, 0, ',', '.') }}</strong>
                            untuk mempercepat verifikasi.
                        </p>
                    </div>
                </div>

                {{-- Upload Proof Form --}}
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-pink-400 text-lg">upload_file</span>
                        Upload Bukti Pembayaran
                    </h2>

                    <form action="{{ url('/'.tenant('id').'/payment/upload-proof') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                        @error('payment_proof')
                            <div class="mb-4 p-3 bg-red-500/10 border border-red-500/30 rounded-xl text-sm text-red-400">
                                {{ $message }}
                            </div>
                        @enderror

                        {{-- Dropzone --}}
                        <div class="relative group cursor-pointer mb-5" id="dropzone" onclick="document.getElementById('proof_file').click()">
                            <input type="file" name="payment_proof" id="proof_file" class="hidden" accept="image/*">
                            <div id="dropzone-content" class="w-full border-2 border-dashed border-white/15 group-hover:border-indigo-500/60 rounded-2xl py-10 flex flex-col items-center justify-center transition-all bg-slate-900/30 group-hover:bg-indigo-500/5">
                                <span class="material-symbols-outlined text-indigo-400/60 group-hover:text-indigo-400 text-5xl transition-colors mb-3">add_photo_alternate</span>
                                <p class="text-sm font-bold text-slate-400 group-hover:text-white transition-colors">Klik atau seret foto bukti transfer</p>
                                <p class="text-xs text-slate-600 mt-1">JPG, PNG, WebP — Maks. 3MB</p>
                            </div>
                            {{-- Preview --}}
                            <div id="preview-container" class="hidden">
                                <img id="preview-img" src="" alt="Preview" class="w-full max-h-64 object-contain rounded-2xl border border-indigo-500/30">
                                <p id="preview-name" class="text-xs text-indigo-400 font-bold text-center mt-2"></p>
                            </div>
                        </div>

                        <button type="submit" id="uploadBtn"
                            class="w-full py-4 px-6 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 rounded-2xl text-white font-black text-base tracking-wide uppercase shadow-[0_0_30px_rgba(99,102,241,0.3)] hover:shadow-[0_0_40px_rgba(99,102,241,0.5)] transition-all flex items-center justify-center gap-2">
                            <span id="uploadBtnText" class="flex items-center gap-2">
                                <span class="material-symbols-outlined">send</span>
                                Kirim Bukti Pembayaran
                            </span>
                            <span id="uploadBtnLoading" class="hidden items-center gap-2">
                                <span class="material-symbols-outlined animate-spin">progress_activity</span>
                                Mengunggah...
                            </span>
                        </button>
                    </form>

                    <p class="text-center text-xs text-slate-600 mt-4">
                        Butuh bantuan? Hubungi kami di WhatsApp atau email admin.
                    </p>
                </div>

                {{-- Link Back to Dashboard --}}
                <div class="text-center">
                    <a href="{{ url('/'.tenant('id').'/dashboard') }}" class="text-sm text-slate-500 hover:text-slate-300 transition-colors flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        Bayar nanti, masuk ke dashboard dulu
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    // File Preview
    document.getElementById('proof_file').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('dropzone-content').classList.add('hidden');
            const previewContainer = document.getElementById('preview-container');
            previewContainer.classList.remove('hidden');
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('preview-name').textContent = file.name;
        };
        reader.readAsDataURL(file);
    });

    // Copy to Clipboard
    function copyNumber(number, btn) {
        navigator.clipboard.writeText(number).then(() => {
            const icon = btn.querySelector('.material-symbols-outlined');
            icon.textContent = 'check';
            btn.classList.add('text-emerald-400', 'border-emerald-500/40');
            setTimeout(() => {
                icon.textContent = 'content_copy';
                btn.classList.remove('text-emerald-400', 'border-emerald-500/40');
            }, 2000);
        });
    }

    // Form Loading State
    document.getElementById('uploadForm').addEventListener('submit', function() {
        document.getElementById('uploadBtnText').classList.add('hidden');
        document.getElementById('uploadBtnLoading').classList.remove('hidden');
        document.getElementById('uploadBtnLoading').classList.add('flex');
        document.getElementById('uploadBtn').disabled = true;
        document.getElementById('uploadBtn').classList.add('opacity-80', 'cursor-not-allowed');
    });
</script>
@endpush
