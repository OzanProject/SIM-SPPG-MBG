@extends('frontend.layouts.app')

@section('title', 'Masuk Akun')

@section('content')
<main class="pt-32 pb-24 bg-slate-950 min-h-screen relative overflow-hidden flex items-center justify-center">
    <!-- Decorative Background Glows -->
    <div class="absolute top-[10%] left-[-10%] w-[40%] h-[40%] bg-indigo-500/10 blur-[150px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-[10%] right-[-10%] w-[40%] h-[40%] bg-purple-500/10 blur-[150px] rounded-full pointer-events-none"></div>

    <div class="max-w-md w-full mx-auto px-6 relative z-10 reveal">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-white px-2">
                @if(tenant())
                    Selamat Datang kembali di <span class="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">{{ tenant('name') ?? 'Dapur Anda' }}</span>
                @else
                    Masuk ke <span class="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">DapurMBG</span>
                @endif
            </h1>
            <p class="text-slate-400 mt-2 font-medium">Buka akses pengelolaan cerdas restoran Anda.</p>
        </div>

        @if(tenant() && tenant()->is_on_trial)
            <div class="mb-6 border border-indigo-500/30 bg-indigo-500/10 px-4 py-3 rounded-2xl flex items-center gap-3 backdrop-blur-md">
                <span class="material-symbols-outlined text-indigo-400">info</span>
                <p class="text-xs text-indigo-200 font-bold tracking-wide">
                    PRO Trial Aktif (Sisa {{ tenant()->trial_days_left }} hari)
                </p>
            </div>
        @endif

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-emerald-400 font-bold text-sm text-center" :status="session('status')" />
        
        @if(session('error'))
            <div class="mb-6 border border-red-500/30 bg-red-500/10 text-red-400 px-4 py-3 rounded-2xl flex items-center gap-3 text-sm font-medium backdrop-blur-md">
                <span class="material-symbols-outlined">warning</span>
                {{ session('error') }}
            </div>
        @endif

        <div class="glass-card rounded-3xl p-8 border border-white/10 shadow-2xl relative overflow-hidden">
            <form id="loginForm" method="POST" action="{{ tenant() ? route('tenant.login', ['tenant' => tenant('id')]) : route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-500 group-focus-within:text-indigo-400 transition-colors">mail</span>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="w-full bg-white/5 border border-white/10 rounded-xl py-3 pl-12 pr-4 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium placeholder:text-slate-600"
                            placeholder="mail@dapur.com">
                    </div>
                    @error('email')
                        <p class="text-red-400 text-xs font-semibold mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-bold text-slate-300">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ tenant() ? route('tenant.password.request', ['tenant' => tenant('id')]) : route('password.request') }}" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition-colors">Lupa Password?</a>
                        @endif
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-500 group-focus-within:text-indigo-400 transition-colors">lock</span>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl py-3 pl-12 pr-12 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium"
                            placeholder="••••••••">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer" onclick="togglePassword()">
                            <span class="material-symbols-outlined text-slate-500 hover:text-white transition-colors text-lg" id="password-hide-icon">visibility_off</span>
                            <span class="material-symbols-outlined text-slate-500 hover:text-white transition-colors text-lg hidden" id="password-show-icon">visibility</span>
                        </div>
                    </div>
                    @error('password')
                        <p class="text-red-400 text-xs font-semibold mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember" class="w-4 h-4 bg-white/10 border-white/20 rounded focus:ring-indigo-500 focus:ring-offset-0 text-indigo-500 cursor-pointer">
                    <label for="remember" class="ml-2 block text-sm text-slate-400 cursor-pointer hover:text-slate-300 transition-colors">
                        Biarkan saya tetap masuk
                    </label>
                </div>

                <button type="submit" id="btnSubmit" class="w-full group relative flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-xl shadow-indigo-500/20 transition-all overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full -translate-x-full transition-transform duration-500 ease-in-out"></div>
                    <span class="relative flex items-center gap-2" id="btnText">
                        Masuk Dashboard <span class="material-symbols-outlined text-sm transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </span>
                    <span class="relative flex items-center gap-2 hidden" id="btnLoading">
                        <span class="material-symbols-outlined animate-spin text-sm">progress_activity</span> Memproses...
                    </span>
                </button>
            </form>

            <div class="mt-8 text-center border-t border-white/5 pt-6">
                <p class="text-slate-500 text-sm mb-2 flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-xs">shield</span> Data dienkripsi & dilindungi.
                </p>
                @if(Route::has('register'))
                <p class="text-slate-400 text-sm font-medium">Buka Cabang Dapur Baru? 
                    <a href="{{ route('register') }}" class="font-bold text-indigo-400 hover:text-indigo-300 transition-colors border-b border-indigo-500/30 hover:border-indigo-400 pb-0.5 ml-1">
                        Daftar 7 Hari GRATIS
                    </a>
                </p>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const hideIcon = document.getElementById('password-hide-icon');
        const showIcon = document.getElementById('password-show-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            hideIcon.classList.add('hidden');
            showIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            hideIcon.classList.remove('hidden');
            showIcon.classList.add('hidden');
        }
    }

    document.getElementById('loginForm').addEventListener('submit', function(e) {
        document.getElementById('btnText').classList.add('hidden');
        document.getElementById('btnLoading').classList.remove('hidden');
        document.getElementById('btnSubmit').classList.add('opacity-80', 'cursor-not-allowed');
    });
</script>
@endpush
