<section id="home" class="relative pt-44 pb-32 overflow-hidden">

    <!-- Background Glow -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-indigo-600/20 blur-[140px] rounded-full"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-purple-600/20 blur-[140px] rounded-full"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

            <!-- TEXT -->
            <div class="reveal">

                <!-- Badge -->
                <div class="inline-flex items-center gap-3 px-4 py-2 glass rounded-2xl text-[10px] font-black tracking-[0.3em] uppercase text-indigo-400 mb-8 border border-white/5">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    {{ $landingSettings['hero']['hero_badge'] ?? 'Manajemen Dapur Modern' }}
                </div>

                <!-- Heading -->
                <h1 class="text-4xl md:text-5xl lg:text-5xl font-black tracking-tight text-white mb-6 leading-[1.1]">
                    {{ $landingSettings['hero']['hero_title'] ?? 'Kelola Dapur Tanpa Ribet.' }}
                    <span class="block text-primary-gradient italic !not-italic">
                        {{ $landingSettings['hero']['hero_title_gradient'] ?? 'Semua Serba Otomatis.' }}
                    </span>
                </h1>

                <!-- Description -->
                <p class="text-lg md:text-xl text-slate-400 max-w-xl mb-10 leading-relaxed">
                    {{ $landingSettings['hero']['hero_subtitle'] ?? 'Kontrol stok, penjualan, dan laporan keuangan dalam satu sistem. Hemat waktu, minim kesalahan, dan siap scale bisnis Anda.' }}
                </p>

                <!-- CTA -->
                @php
                    $ctaRaw  = $landingSettings['hero']['hero_cta_url']  ?? null;
                    $demoRaw = $landingSettings['hero']['hero_demo_url']  ?? null;
                    $ctaUrl  = $ctaRaw  ? (str_starts_with($ctaRaw,  'http') ? $ctaRaw  : url($ctaRaw))  : route('register');
                    $demoUrl = $demoRaw ? (str_starts_with($demoRaw, 'http') ? $demoRaw : url($demoRaw)) : url('/features');
                @endphp
                <div class="flex flex-col sm:flex-row gap-4">

                    <a href="{{ $ctaUrl }}"
                        class="px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-xl font-bold text-lg shadow-xl hover:scale-105 transition flex items-center justify-center gap-2">
                        {{ $landingSettings['hero']['hero_cta_text'] ?? 'Mulai Gratis 🚀' }}
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>

                    <a href="{{ $demoUrl }}"
                        class="px-8 py-4 border border-white/10 rounded-xl font-semibold text-white hover:bg-white/10 transition text-center">
                        {{ $landingSettings['hero']['hero_demo_text'] ?? 'Lihat Demo' }}
                    </a>

                </div>

                <!-- TRUST METRICS -->
                <div class="mt-14 pt-10 border-t border-white/5 grid grid-cols-3 gap-6 text-center sm:text-left">

                    <div>
                        <p class="text-2xl font-black text-white">
                            {{ $landingSettings['hero']['stats_biz_val'] ?? '500+' }}</p>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">
                            {{ $landingSettings['hero']['stats_biz_lbl'] ?? 'Bisnis Aktif' }}</p>
                    </div>

                    <div>
                        <p class="text-2xl font-black text-white">
                            {{ $landingSettings['hero']['stats_trx_val'] ?? 'Rp 150M+' }}</p>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">
                            {{ $landingSettings['hero']['stats_trx_lbl'] ?? 'Transaksi' }}</p>
                    </div>

                    <div>
                        <p class="text-2xl font-black text-white">
                            {{ $landingSettings['hero']['stats_upt_val'] ?? '99.9%' }}</p>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">
                            {{ $landingSettings['hero']['stats_upt_lbl'] ?? 'Uptime' }}</p>
                    </div>

                </div>
            </div>

            <!-- VISUAL -->
            <div class="relative reveal delay-300">

                <div class="glass-card p-4 rounded-[2rem] border border-white/10 shadow-2xl overflow-hidden group">

                    <div class="aspect-video bg-slate-900 rounded-2xl overflow-hidden relative">

                        <img src="{{ !empty($landingSettings['hero']['hero_image_url']) ? asset($landingSettings['hero']['hero_image_url']) : 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&q=80&w=1200' }}"
                            class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-700"
                            alt="Dashboard Preview">

                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500/10 to-purple-500/10"></div>

                        <!-- Floating Card — Revenue Metric -->
                        <div class="absolute top-5 left-5 glass p-3 rounded-xl border border-white/20 animate-float">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                                    <span class="material-symbols-outlined text-sm">trending_up</span>
                                </div>
                                <div>
                                    <p class="text-[9px] text-slate-400 uppercase">{{ $landingSettings['hero']['stats_revenue_label'] ?? 'Revenue' }}</p>
                                    <p class="text-sm font-bold text-white">{{ $landingSettings['hero']['stats_revenue_value'] ?? '+24.5%' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Glow -->
                <div class="absolute -top-10 -right-10 w-56 h-56 bg-indigo-500/30 blur-[90px] rounded-full -z-10"></div>

            </div>

        </div>
    </div>
</section>