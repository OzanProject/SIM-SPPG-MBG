<section id="cta" class="py-24 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <!-- Main CTA Container -->
        <div class="glass-card rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden group border border-white/10 shadow-3xl shadow-indigo-600/10">
            <!-- Sophisticated Inner Glows -->
            <div class="absolute -top-1/2 -left-1/4 w-full h-full bg-indigo-500/10 blur-[130px] rounded-full opacity-50"></div>
            <div class="absolute -bottom-1/2 -right-1/4 w-full h-full bg-purple-500/10 blur-[130px] rounded-full opacity-50"></div>

            <div class="relative z-10 max-w-3xl mx-auto reveal">
                <!-- Subheader with Accent -->
                <div class="flex items-center justify-center gap-2 mb-8">
                    <span class="h-px w-8 bg-gradient-to-r from-transparent to-indigo-400"></span>
                    <span class="text-indigo-400 text-[11px] font-black uppercase tracking-[0.5em]">
                        {{ $landingSettings['cta']['cta_badge'] ?? 'Siap Berevolusi?' }}
                    </span>
                    <span class="h-px w-8 bg-gradient-to-l from-transparent to-indigo-400"></span>
                </div>

                <h2 class="text-4xl md:text-6xl font-black tracking-tight text-white mb-8 leading-[1.1]">
                    {{ $landingSettings['cta']['cta_title'] ?? 'Revolusi Dapur Anda' }} <br>
                    <span class="text-primary-gradient italic !not-italic">
                        {{ $landingSettings['cta']['cta_title_gradient'] ?? 'Mulai Dari Sini.' }}
                    </span>
                </h2>

                <p class="text-base md:text-lg text-slate-400 font-medium mb-12 opacity-80 max-w-lg mx-auto leading-relaxed">
                    {{ $landingSettings['cta']['cta_description'] ?? 'Bergabunglah sekarang dan rasakan efisiensi operasional yang belum pernah Anda bayangkan sebelumnya.' }}
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-5">
                    @php
                        $cta1Raw = $landingSettings['cta']['cta_btn1_url'] ?? null;
                        $cta2Raw = $landingSettings['cta']['cta_btn2_url'] ?? null;
                        $cta1Url = $cta1Raw ? (str_starts_with($cta1Raw, 'http') ? $cta1Raw : url($cta1Raw)) : route('register');
                        $cta2Url = $cta2Raw ? (str_starts_with($cta2Raw, 'http') ? $cta2Raw : url($cta2Raw)) : url('/features');
                    @endphp
                    <a href="{{ $cta1Url }}" class="w-full sm:w-auto px-10 py-5 primary-gradient text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl shadow-indigo-600/30 flex items-center justify-center gap-3">
                        {{ $landingSettings['cta']['cta_btn1_text'] ?? 'Daftar Sekarang' }}
                        <span class="material-symbols-outlined font-black text-lg">arrow_forward</span>
                    </a>
                    <a href="{{ $cta2Url }}" class="w-full sm:w-auto px-10 py-5 glass border border-white/10 text-white rounded-2xl font-bold text-sm hover:bg-white/10 transition-all flex items-center justify-center">
                        {{ $landingSettings['cta']['cta_btn2_text'] ?? 'Eksplorasi Fitur' }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Outer Decorative Glow -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full pointer-events-none opacity-5">
        <div class="absolute inset-0 primary-gradient blur-[180px] rounded-full scale-125"></div>
    </div>
</section>
