@extends('frontend.layouts.app')

@section('title', 'Buat Sandi Baru')

@section('content')
<main class="pt-32 pb-24 bg-slate-950 min-h-screen relative overflow-hidden flex items-center justify-center">
    <!-- Decorative Background Glows -->
    <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-500/10 blur-[150px] rounded-full pointer-events-none"></div>

    <div class="max-w-md w-full mx-auto px-6 relative z-10 reveal">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-500/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-indigo-400/20 shadow-[0_0_15px_rgba(99,102,241,0.2)]">
                <span class="material-symbols-outlined text-3xl text-indigo-400">key</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-white px-2">
                Buat Sandi <span class="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">Baru Anda</span>
            </h1>
            <p class="text-slate-400 mt-2 font-medium text-sm">
                Harap gunakan sandi yang unik dan kuat untuk keamanan ekstra.
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-emerald-400 font-bold text-sm text-center bg-emerald-500/10 border border-emerald-500/20 rounded-xl px-4 py-3" :status="session('status')" />

        <div class="glass-card rounded-3xl p-8 border border-white/10 shadow-2xl relative overflow-hidden backdrop-blur-xl bg-white/5">
            <form id="resetForm" method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">Email Anda</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-500 group-focus-within:text-indigo-400 transition-colors">mark_email_read</span>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email', $request->email) }}" required autofocus
                            class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 pl-12 pr-4 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium placeholder:text-slate-600 shadow-inner"
                            readonly>
                    </div>
                    @error('email')
                        <p class="text-red-400 text-xs font-semibold mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">Sandi Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-500 group-focus-within:text-indigo-400 transition-colors">lock</span>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 pl-12 pr-12 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium shadow-inner"
                            placeholder="Min. 8 Karakter">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer" onclick="togglePassword('password', 'password-icon')">
                            <span class="material-symbols-outlined text-slate-500 hover:text-white transition-colors text-lg" id="password-icon">visibility_off</span>
                        </div>
                    </div>
                    @error('password')
                        <p class="text-red-400 text-xs font-semibold mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">Ulangi Sandi Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-500 group-focus-within:text-indigo-400 transition-colors">key</span>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 pl-12 pr-12 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium shadow-inner"
                            placeholder="Konfirmasi Sandi Baru">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer" onclick="togglePassword('password_confirmation', 'password-confirm-icon')">
                            <span class="material-symbols-outlined text-slate-500 hover:text-white transition-colors text-lg" id="password-confirm-icon">visibility_off</span>
                        </div>
                    </div>
                    @error('password_confirmation')
                        <p class="text-red-400 text-xs font-semibold mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" id="btnSubmit" class="w-full group relative flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-indigo-500 shadow-[0_0_20px_rgba(99,102,241,0.2)] transition-all overflow-hidden">
                        <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full -translate-x-full transition-transform duration-500 ease-in-out"></div>
                        <span class="relative flex items-center gap-2" id="btnText">
                            Perbarui Sandi Sekarang <span class="material-symbols-outlined text-[16px] transition-transform group-hover:translate-x-1">data_saver_on</span>
                        </span>
                        <span class="relative flex items-center gap-2 hidden" id="btnLoading">
                            <span class="material-symbols-outlined animate-spin text-[16px]">progress_activity</span> Memproses...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.textContent = 'visibility';
        } else {
            passwordInput.type = 'password';
            icon.textContent = 'visibility_off';
        }
    }

    document.getElementById('resetForm').addEventListener('submit', function(e) {
        document.getElementById('btnText').classList.add('hidden');
        document.getElementById('btnLoading').classList.remove('hidden');
        document.getElementById('btnLoading').classList.add('flex');
        document.getElementById('btnSubmit').classList.add('opacity-80', 'cursor-not-allowed');
    });
</script>
@endpush
