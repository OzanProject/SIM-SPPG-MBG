<section id="trust" class="py-24 relative overflow-hidden bg-slate-950/40">

    <!-- Glow Background -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/3 w-64 h-64 bg-indigo-500/20 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 right-1/3 w-64 h-64 bg-purple-500/20 blur-[120px] rounded-full"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">

        <!-- Heading -->
        <div class="text-center mb-14 reveal">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-[0.4em] mb-4">
                {{ $landingSettings['trust']['trust_badge'] ?? 'Dipercaya oleh bisnis dapur modern' }}
            </p>
            <h3 class="text-2xl md:text-3xl font-black text-white">
                {{ $landingSettings['trust']['trust_heading'] ?? 'Bergabung dengan ratusan dapur yang berkembang 🚀' }}
            </h3>
        </div>

        <!-- Logos / Names -->
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 reveal">

            @forelse($tenants as $tenant)
                <div class="group relative px-4 py-2 transition-all duration-500 hover:scale-110">

                    <!-- Glow Hover -->
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/0 via-indigo-500/20 to-purple-500/0 blur-xl opacity-0 group-hover:opacity-100 transition-all"></div>

                    <span class="relative text-lg md:text-xl font-bold tracking-widest text-slate-400 group-hover:text-white transition uppercase">
                        {{ strtoupper(str_replace(['mbg-', '-'], ['', ' '], $tenant->id)) }}
                    </span>

                </div>
            @empty

                {{-- Fallback placeholder brands saat tenant masih kosong --}}
                @foreach(['DAPUR<span class="text-indigo-500">KITA</span>', 'SMART<span class="text-purple-500">CHEF</span>', 'KULINER<span class="text-blue-500">PRO</span>', 'MBG<span class="text-emerald-500">PRIMA</span>', 'DAPUR<span class="text-amber-500">SEHAT</span>'] as $placeholder)
                    <div class="group px-4 py-2 hover:scale-110 transition">
                        <span class="text-xl font-bold text-slate-400 group-hover:text-white">{!! $placeholder !!}</span>
                    </div>
                @endforeach

            @endforelse

        </div>

    </div>
</section>