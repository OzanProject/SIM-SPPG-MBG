<section id="features" class="py-32 relative overflow-hidden">
    <!-- Background Glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-20 left-1/4 w-72 h-72 bg-indigo-500/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-20 right-1/4 w-72 h-72 bg-purple-500/10 blur-[120px] rounded-full"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">

        <!-- HEADER -->
        <div class="text-center mb-20 reveal">
            <span class="text-indigo-400 text-xs font-bold uppercase tracking-[0.4em] mb-4 block">
                {{ $landingSettings['features']['features_badge'] ?? 'Ekosistem Digital Terpadu' }}
            </span>
            <h2 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight">
                {{ $landingSettings['features']['features_title'] ?? 'Seluruh Kebutuhan Dapur' }}
                <br>
                <span class="text-primary-gradient italic !not-italic">
                    {{ $landingSettings['features']['features_subtitle'] ?? 'Dalam Satu Dashboard.' }}
                </span>
            </h2>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto opacity-70">
                {{ $landingSettings['features']['features_description'] ?? 'Sinkronisasi data real-time antara stok, penjualan, biaya, hingga gaji karyawan untuk efisiensi maksimal.' }}
            </p>
        </div>

        <!-- FEATURES GRID — Dynamic dari DB -->
        @if($features->count() > 0)

            @php
                $largeFeature  = $features->firstWhere('size', 'large');
                $mediumFeatures = $features->where('size', 'medium');
                $smallFeatures  = $features->where('size', 'small');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

                <!-- LARGE CARD -->
                @if($largeFeature)
                    @php $lc = $largeFeature->color_config; @endphp
                    <div class="md:col-span-12 lg:col-span-8 glass-card rounded-[3rem] p-10 md:p-14 group relative overflow-hidden reveal">
                        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/10 blur-[100px] rounded-full translate-x-1/2 -translate-y-1/2"></div>
                        <div class="relative z-10 flex flex-col md:flex-row gap-12 items-center">
                            <div class="flex-1">
                                <div class="w-16 h-16 primary-gradient rounded-2xl flex items-center justify-center text-white mb-10 shadow-lg shadow-indigo-600/20">
                                    @if($largeFeature->icon_type === 'emoji')
                                        <span class="text-4xl">{{ $largeFeature->icon }}</span>
                                    @else
                                        <span class="material-symbols-outlined text-4xl font-bold">{{ $largeFeature->icon }}</span>
                                    @endif
                                </div>
                                <h3 class="text-3xl md:text-4xl font-black text-white mb-6 leading-tight">
                                    {!! nl2br(e($largeFeature->title)) !!}
                                </h3>
                                <p class="text-lg text-slate-400 font-medium leading-relaxed mb-10 opacity-80">
                                    {{ $largeFeature->description }}
                                </p>
                            </div>

                            {{-- Visual mock widget --}}
                            <div class="w-full md:w-64 glass p-6 rounded-3xl border-white/5 shadow-2xl shrink-0">
                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Ayam Fillet</span>
                                    <span class="px-2 py-1 bg-red-500/20 text-red-400 text-[8px] font-bold rounded-lg uppercase tracking-widest">Kritis</span>
                                </div>
                                <div class="space-y-4">
                                    <div class="h-2 bg-white/5 rounded-full overflow-hidden">
                                        <div class="w-1/4 h-full primary-gradient transition-all duration-1000"></div>
                                    </div>
                                    <div class="flex justify-between text-[10px] font-bold text-white">
                                        <span>12.5 Kg</span>
                                        <span class="opacity-40">Min 50 Kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- MEDIUM CARDS -->
                @foreach($mediumFeatures as $feature)
                    @php $mc = $feature->color_config; @endphp
                    <div class="md:col-span-12 lg:col-span-4 glass-card rounded-[3rem] p-10 group reveal">
                        <div class="w-16 h-16 {{ $mc['bg'] }} rounded-2xl flex items-center justify-center {{ $mc['text'] }} mb-8 border {{ $mc['border'] }}">
                            @if($feature->icon_type === 'emoji')
                                <span class="text-4xl">{{ $feature->icon }}</span>
                            @else
                                <span class="material-symbols-outlined text-4xl">{{ $feature->icon }}</span>
                            @endif
                        </div>
                        <h3 class="text-2xl font-black text-white mb-4">
                            {!! nl2br(e($feature->title)) !!}
                        </h3>
                        <p class="text-slate-400 font-medium leading-relaxed opacity-70 mb-8">
                            {{ $feature->description }}
                        </p>
                        @if($feature->badge_text)
                            <div class="flex gap-2">
                                <div class="px-4 py-2 glass rounded-xl text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                    {{ $feature->badge_text }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach

                <!-- SMALL CARDS group -->
                @if($smallFeatures->count() > 0)
                    <div class="md:col-span-12 lg:col-span-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-6">
                        @foreach($smallFeatures as $feature)
                            @php $sc = $feature->color_config; @endphp
                            <div class="glass-card rounded-[2rem] p-8 reveal">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-10 h-10 {{ $sc['bg'] }} rounded-xl flex items-center justify-center {{ $sc['text'] }}">
                                        @if($feature->icon_type === 'emoji')
                                            <span class="text-xl">{{ $feature->icon }}</span>
                                        @else
                                            <span class="material-symbols-outlined">{{ $feature->icon }}</span>
                                        @endif
                                    </div>
                                    <h4 class="text-lg font-black text-white">{{ $feature->title }}</h4>
                                </div>
                                <p class="text-xs text-slate-500 font-medium leading-relaxed">{{ $feature->description }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

        @else
            {{-- Empty state saat belum ada features di DB --}}
            <div class="glass-card rounded-[3rem] py-24 px-10 text-center border-dashed border-2 border-white/5">
                <div class="w-20 h-20 primary-gradient rounded-full mx-auto mb-8 flex items-center justify-center shadow-lg">
                    <span class="material-symbols-outlined text-white text-3xl">widgets</span>
                </div>
                <h3 class="text-2xl font-black text-white mb-4">Belum ada fitur?</h3>
                <p class="text-slate-400 max-w-md mx-auto">
                    Tambahkan fitur produk melalui Super Admin Dashboard untuk menampilkannya di sini.
                </p>
            </div>
        @endif

    </div>
</section>