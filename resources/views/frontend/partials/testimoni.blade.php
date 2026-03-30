<section id="testimonials" class="py-32 relative overflow-hidden">
    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-20">
        <div class="absolute top-[20%] left-[10%] w-[500px] h-[500px] bg-indigo-500/30 blur-[120px] rounded-full animate-slow-spin"></div>
        <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-purple-500/20 blur-[100px] rounded-full animate-float"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <!-- Header Section -->
        <div class="max-w-3xl mb-16 reveal">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                <span class="text-indigo-300 text-[10px] font-black uppercase tracking-widest">
                    {{ $landingSettings['testimonials']['testi_badge'] ?? 'Kisah Sukses Nyata' }}
                </span>
            </div>
            <h2 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight tracking-tight">
                {{ $landingSettings['testimonials']['testi_title'] ?? 'Dicintai oleh' }}
                <span class="text-primary-gradient">
                    {{ $landingSettings['testimonials']['testi_title_gradient'] ?? 'Para Kreator Dapur.' }}
                </span>
            </h2>
            <p class="text-lg text-slate-400 max-w-2xl leading-relaxed">
                {{ $landingSettings['testimonials']['testi_description'] ?? 'Ribuan pemilik bisnis katering dan dapur katering telah bertransformasi bersama MBG Akunpro. Dengarkan kisah mereka.' }}
            </p>
        </div>

        @if($testimonials->count() > 0)
            <!-- Multi-column Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($testimonials as $testi)
                    <div class="reveal glass-card rounded-[2.5rem] p-10 flex flex-col justify-between group hover:-translate-y-3 transition-all duration-700 relative overflow-hidden"
                         style="transition-delay: {{ $loop->index * 150 }}ms">

                        <!-- Floating Quote Accent -->
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-indigo-500/5 rounded-full flex items-center justify-center group-hover:bg-indigo-500/10 transition-colors">
                            <span class="material-symbols-outlined text-5xl text-indigo-500/20 group-hover:scale-110 transition-transform">format_quote</span>
                        </div>

                        <div class="relative z-10">
                            <!-- Rating -->
                            <div class="flex gap-1 mb-8">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="material-symbols-outlined text-sm {{ $i <= ($testi->rating ?? 5) ? 'text-amber-400' : 'text-slate-700' }}"
                                          style="font-variation-settings: 'FILL' 1">star</span>
                                @endfor
                            </div>

                            <!-- Comment -->
                            <p class="text-lg text-white/90 font-medium leading-relaxed mb-10 italic tracking-tight">
                                "{{ $testi->content }}"
                            </p>
                        </div>

                        <!-- Author Profile -->
                        <div class="flex items-center gap-5 pt-8 border-t border-white/10 mt-auto">
                            <div class="relative">
                                <div class="w-16 h-16 rounded-2xl overflow-hidden shadow-2xl border-2 border-white/10 group-hover:border-indigo-500/50 transition-colors">
                                    @if($testi->image_url)
                                        <img src="{{ asset('storage/' . $testi->image_url) }}" alt="{{ $testi->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full primary-gradient flex items-center justify-center text-white font-black text-xl">
                                            {{ substr($testi->name ?? 'A', 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                @if($testi->source == 'tenant')
                                <div class="absolute -bottom-2 -right-2 bg-indigo-600 border-2 border-slate-900 rounded-lg px-2 py-0.5 shadow-lg">
                                    <span class="text-[8px] font-black text-white uppercase tracking-tighter">Verified</span>
                                </div>
                                @endif
                            </div>

                            <div class="flex flex-col">
                                <h4 class="text-lg font-black text-white tracking-tight group-hover:text-indigo-400 transition-colors">{{ $testi->name }}</h4>
                                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                                    {{ $testi->source == 'tenant' ? 'Owner ' . strtoupper($testi->tenant_id) : 'Client Dapur Terverifikasi' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Placeholder for Zero State -->
            <div class="reveal glass-card rounded-[3rem] py-24 px-10 text-center border-dashed border-2 border-white/5">
                <div class="w-20 h-20 primary-gradient rounded-full mx-auto mb-8 flex items-center justify-center shadow-lg">
                    <span class="material-symbols-outlined text-white text-3xl">chat_bubble</span>
                </div>
                <h3 class="text-2xl font-black text-white mb-4 italic !not-italic">Belum ada testimoni?</h3>
                <p class="text-slate-400 max-w-md mx-auto mb-8">
                    Jadilah yang pertama untuk membagikan kisah sukses dapur Anda melalui Dashboard Tenant!
                </p>
            </div>
        @endif
    </div>
</section>
