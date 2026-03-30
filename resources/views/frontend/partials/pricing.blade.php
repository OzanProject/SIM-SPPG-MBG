<section id="pricing" class="py-32 relative overflow-hidden bg-slate-950/40">

    <!-- Glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-20 left-1/3 w-72 h-72 bg-indigo-500/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-20 right-1/3 w-72 h-72 bg-purple-500/10 blur-[120px] rounded-full"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">

        <!-- HEADER -->
        <div class="text-center mb-20 reveal">

            <span class="text-indigo-400 text-xs font-bold uppercase tracking-[0.4em] mb-4 block">
                {{ $landingSettings['pricing']['pricing_badge'] ?? 'Harga Fleksibel' }}
            </span>

            <h2 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight">
                {{ $landingSettings['pricing']['pricing_title'] ?? 'Pilih Paket Sesuai Kebutuhan Anda' }}
            </h2>

            <p class="text-lg text-slate-400 max-w-2xl mx-auto">
                {{ $landingSettings['pricing']['pricing_description'] ?? 'Mulai gratis, upgrade kapan saja. Tanpa biaya tersembunyi.' }}
            </p>

            <!-- PROMO -->
            @if($promos->count() > 0)
                <div class="mt-10 flex flex-wrap justify-center gap-3">
                    @foreach($promos as $promo)
                        <div
                            class="px-5 py-2 bg-indigo-500/10 border border-indigo-500/20 rounded-xl text-xs text-indigo-300 font-semibold">
                            🎉 Gunakan kode: <span class="text-white font-bold">{{ $promo->code }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        <!-- PRICING GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            @foreach($plans as $plan)
                    @php $isHighlighted = $plan->is_highlighted ?? false; @endphp

                    <div class="relative flex flex-col justify-between rounded-3xl p-8 border border-white/10 bg-white/5 backdrop-blur-md transition-all duration-500 hover:scale-[1.02]
                            {{ $isHighlighted ? 'border-indigo-500 shadow-2xl shadow-indigo-500/20 bg-indigo-500/10' : '' }}">

                        <!-- BADGE -->
                        @if($isHighlighted)
                            <div
                                class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-xs font-bold rounded-full shadow-lg">
                                ⭐ Paling Populer
                            </div>
                        @endif

                        <!-- HEADER -->
                        <div class="mb-6">

                            <h4 class="text-xl font-bold text-white mb-1">
                                {{ $plan->name }}
                            </h4>

                            <p class="text-xs text-slate-400">
                                {{ $plan->description ?? 'Solusi terbaik untuk bisnis Anda.' }}
                            </p>

                        </div>

                        <!-- PRICE -->
                        <div class="mb-6">
                            <span class="text-3xl font-black text-white">
                                Rp {{ number_format($plan->price, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-slate-500">
                                / {{ $plan->duration_in_days == 30 ? 'bulan' : $plan->duration_in_days . ' hari' }}
                            </span>
                        </div>

                        <!-- LIMIT -->
                        <div class="flex flex-wrap gap-2 mb-6">

                            @if($plan->max_users > 0)
                                <span class="px-2 py-1 text-xs bg-white/10 rounded-lg text-slate-300">
                                    {{ $plan->max_users }} User
                                </span>
                            @endif

                            @if($plan->max_items > 0)
                                <span class="px-2 py-1 text-xs bg-white/10 rounded-lg text-slate-300">
                                    {{ $plan->max_items }} Item
                                </span>
                            @endif

                            @if($plan->has_hr)
                                <span class="px-2 py-1 text-xs bg-indigo-500/20 rounded-lg text-indigo-300">
                                    Payroll
                                </span>
                            @endif

                        </div>

                        <div class="h-px bg-white/10 mb-6"></div>

                        <!-- FEATURES -->
                        <ul class="space-y-3 mb-8 text-sm text-slate-300">
                            @foreach(explode(',', $plan->features) as $feature)
                                <li class="flex items-center gap-2">
                                    <span class="text-indigo-400">✔</span>
                                    {{ trim($feature) }}
                                </li>
                            @endforeach
                        </ul>

                        <!-- CTA -->
                        <a href="{{ route('register', ['plan' => $plan->slug]) }}" class="w-full py-3 rounded-xl text-sm font-bold text-center transition-all
                               {{ $isHighlighted
                ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg hover:scale-105'
                : 'bg-white/10 text-white hover:bg-white/20' }}">

                            {{ $plan->price <= 0 ? 'Mulai Gratis' : 'Pilih Paket' }}

                        </a>

                    </div>

            @endforeach

        </div>

        <!-- TRUST -->
        <div class="mt-16 text-center text-slate-500 text-sm">
            ✔ Tanpa kontrak • ✔ Bisa upgrade kapan saja • ✔ Data aman & terenkripsi
        </div>

    </div>
</section>