@extends('frontend.layouts.app')

@section('title', 'Mulai Daftar Dapur - Pilih Paket & Promo')

@section('content')
<main class="pt-32 pb-24 bg-slate-950 min-h-screen relative overflow-hidden flex items-center justify-center">
    <!-- Decorative Background Glows -->
    <div class="absolute top-[-10%] right-[-10%] w-[50%] h-[50%] bg-indigo-500/10 blur-[150px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-[0%] left-[-10%] w-[40%] h-[40%] bg-pink-500/10 blur-[150px] rounded-full pointer-events-none"></div>

    <div class="max-w-4xl w-full mx-auto px-6 relative z-10 reveal">
        
        <div class="text-center mb-8">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-400/20 text-indigo-400 text-xs font-semibold uppercase tracking-wider mb-4 shadow-[0_0_15px_rgba(99,102,241,0.2)]">
                🚀 Langkah Terakhir Menuju Efisiensi
            </span>
            <h1 class="text-3xl md:text-4xl font-black text-white px-2">
                Daftar <span class="bg-gradient-to-r from-indigo-400 to-pink-400 bg-clip-text text-transparent">Akun Pro DapurMBG</span>
            </h1>
            <p class="text-slate-400 mt-3 font-medium text-sm md:text-base max-w-lg mx-auto">
                Lengkapi data di bawah ini untuk memulai digitalisasi bisnis kuliner Anda.
            </p>
        </div>

        <div class="glass-card rounded-3xl p-8 md:p-10 border border-white/10 shadow-2xl relative overflow-hidden backdrop-blur-xl bg-white/5">
            <form action="{{ route('register') }}" method="POST" id="registerForm" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <!-- LEFT COLUMN: Account Data -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-400 text-sm italic">1</span>
                            Informasi Akun
                        </h3>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-300">Nama Dapur / Resto <span class="text-pink-400">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-slate-500 group-focus-within:text-indigo-400 transition-colors">storefront</span>
                                </div>
                                <input type="text" name="dapur_name" id="dapur_name" value="{{ old('dapur_name') }}" required autofocus
                                    class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 pl-12 pr-4 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium placeholder:text-slate-600 shadow-inner"
                                    placeholder="Cth: Dapur Bunda">
                            </div>
                            <p class="text-[10px] text-slate-500 mt-2 font-mono">
                                URL: <strong class="text-indigo-400">{{ url('/') }}/<span id="slug-preview">...</span>/dashboard</strong>
                            </p>
                            @error('dapur_name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-300">Nama Lengkap Pemilik <span class="text-pink-400">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-indigo-500 transition-all font-medium placeholder:text-slate-600"
                                placeholder="Cth: Budi Santoso">
                            @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-300">Email <span class="text-pink-400">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-indigo-500 transition-all font-medium placeholder:text-slate-600"
                                    placeholder="mail@dapur.com">
                                @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-300">WhatsApp <span class="text-pink-400">*</span></label>
                                <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" required
                                    class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-indigo-500 transition-all font-medium placeholder:text-slate-600"
                                    placeholder="08123456789">
                                @error('whatsapp') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-300">Buat Kata Sandi <span class="text-pink-400">*</span></label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="password" name="password" required
                                    class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-indigo-500 transition-all font-medium"
                                    placeholder="Sandi">
                                <input type="password" name="password_confirmation" required
                                    class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-indigo-500 transition-all font-medium"
                                    placeholder="Ulangi Sandi">
                            </div>
                            @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Subscription & Payment -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-pink-500/20 flex items-center justify-center text-pink-400 text-sm italic">2</span>
                            Paket & Pembayaran
                        </h3>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-300">Pilih Paket Langganan <span class="text-pink-400">*</span></label>
                            <select name="plan_id" id="plan_id" class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-indigo-500 transition-all font-bold appearance-none cursor-pointer">
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" data-price="{{ $plan->price }}" 
                                        {{ (old('plan_id', $selectedPlan) == $plan->slug || old('plan_id') == $plan->id) ? 'selected' : '' }}>
                                        {{ $plan->name }} - Rp {{ number_format($plan->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-300">Kode Promo (Opsional)</label>
                            <div class="flex gap-2">
                                <input type="text" name="promo_code" id="promo_code" value="{{ old('promo_code') }}"
                                    class="flex-1 bg-slate-900/50 border border-white/10 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-indigo-500 transition-all font-bold uppercase placeholder:text-slate-700"
                                    placeholder="KODE PROMO">
                                <button type="button" onclick="applyPromo()" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-xs font-bold text-white transition-all uppercase tracking-widest">Cek</button>
                            </div>
                            <p id="promo-message" class="text-[10px] italic hidden"></p>
                        </div>

                        <!-- TOTAL & SUMMARY -->
                        <div class="p-6 bg-indigo-500/10 border border-indigo-500/20 rounded-3xl space-y-3">
                            <div class="flex justify-between text-sm text-slate-400">
                                <span>Biaya Paket:</span>
                                <span id="summary-base-price" class="font-bold">Rp 0</span>
                            </div>
                            <div id="summary-discount-row" class="flex justify-between text-sm text-emerald-400 hidden">
                                <span>Diskon Promo:</span>
                                <span id="summary-discount-amount" class="font-bold">- Rp 0</span>
                            </div>
                            <div class="h-px bg-white/5 my-2"></div>
                            <div class="flex justify-between text-lg font-black text-white uppercase tracking-wider">
                                <span>Total Bayar:</span>
                                <span id="summary-final-price">Rp 0</span>
                            </div>
                        </div>

                        {{-- PAYMENT INFO NOTE --}}
                        <div class="p-5 border border-dashed border-indigo-500/30 rounded-2xl bg-indigo-500/5 space-y-3">
                            <h4 class="text-sm font-bold text-indigo-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">info</span>
                                Langkah Selanjutnya
                            </h4>
                            <p class="text-xs text-slate-400 leading-relaxed">
                                Setelah mendaftar, Anda akan diarahkan ke <strong class="text-white">halaman pembayaran khusus</strong> untuk melihat detail invoice dan mengunggah bukti transfer.
                            </p>
                            <div class="flex items-center gap-2 text-xs font-bold text-emerald-400">
                                <span class="material-symbols-outlined text-sm">check</span>
                                Paket Gratis tidak perlu bayar — langsung aktif!
                            </div>
                        </div>

                    </div>
                </div>

                <div class="pt-8 border-t border-white/5">
                    <button type="submit" id="btnSubmit" class="w-full group relative flex justify-center items-center gap-3 py-5 px-4 border border-transparent text-lg font-black rounded-2xl text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-indigo-500 shadow-[0_0_30px_rgba(99,102,241,0.4)] transition-all overflow-hidden uppercase tracking-widest">
                        <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full -translate-x-full transition-transform duration-500 ease-in-out"></div>
                        <span id="btnText" class="flex items-center gap-2">
                            Daftar &amp; Lanjut ke Pembayaran <span class="material-symbols-outlined text-2xl transition-transform group-hover:translate-x-1">arrow_forward</span>
                        </span>
                        <span id="btnLoading" class="hidden flex items-center gap-2">
                            <span class="material-symbols-outlined animate-spin">progress_activity</span> Memproses...
                        </span>
                    </button>
                    <p class="text-center text-xs text-slate-500 font-medium mt-6">
                        Setelah daftar, Anda akan diarahkan ke halaman pembayaran. Aktivasi maksimal <strong class="text-slate-400">1x24 jam</strong>.
                    </p>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    const planSelect = document.getElementById('plan_id');
    const bankSection = document.getElementById('bank-section');
    const summaryBase = document.getElementById('summary-base-price');
    const summaryFinal = document.getElementById('summary-final-price');
    const summaryDiscountRow = document.getElementById('summary-discount-row');
    const summaryDiscountVal = document.getElementById('summary-discount-amount');
    
    function formatIDR(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }

    function updateSummary() {
        const selectedOption = planSelect.options[planSelect.selectedIndex];
        const price = parseInt(selectedOption.getAttribute('data-price'));
        
        summaryBase.textContent = formatIDR(price);
        summaryFinal.textContent = formatIDR(price); // Simplified, actual promo logic handled by backend but UI shows basic
        
        if (price > 0) {
            bankSection.classList.remove('hidden');
            document.getElementById('payment_proof').required = true;
        } else {
            bankSection.classList.add('hidden');
            document.getElementById('payment_proof').required = false;
        }
    }

    planSelect.addEventListener('change', updateSummary);
    document.addEventListener('DOMContentLoaded', updateSummary);

    // Live URL preview
    const nameInput = document.getElementById('dapur_name');
    const slugPreview = document.getElementById('slug-preview');
    nameInput.addEventListener('input', function() {
        let name = this.value;
        let slug = name.toLowerCase().replace(/[^a-z0-9]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
        slugPreview.textContent = slug || '...';
    });

    // File Preview
    document.getElementById('payment_proof').addEventListener('change', function() {
        const fileName = this.files[0] ? this.files[0].name : "Klik untuk pilih Foto / Screenshot";
        document.getElementById('file-name').textContent = fileName;
        document.getElementById('file-name').classList.add('text-indigo-400');
    });

    // Copy Tool
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        alert('Nomor Rekening Berhasil di Salin!');
    }

    function applyPromo() {
        const promo = document.getElementById('promo_code').value;
        const msg = document.getElementById('promo-message');
        if (!promo) return;
        
        msg.classList.remove('hidden');
        msg.textContent = 'Mengecek kode promo...';
        msg.className = 'text-[10px] italic text-slate-500 mt-1 block';

        // Fake frontend check for UX (Real check on backend)
        setTimeout(() => {
            msg.textContent = 'Kode promo telah terdeteksi. Diskon akan terhitung di Invoice akhir.';
            msg.className = 'text-[10px] italic text-emerald-400 mt-1 block';
            // Optional: you can add real AJAX here if needed
        }, 800);
    }

    document.getElementById('registerForm').addEventListener('submit', function(e) {
        document.getElementById('btnText').classList.add('hidden');
        document.getElementById('btnLoading').classList.remove('hidden');
        document.getElementById('btnLoading').classList.add('flex');
        document.getElementById('btnSubmit').classList.add('opacity-80', 'cursor-not-allowed');
    });
</script>
@endpush
