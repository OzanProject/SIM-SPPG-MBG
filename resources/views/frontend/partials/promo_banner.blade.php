@if($promos->count() > 0)
    <div class="fixed top-28 left-1/2 -translate-x-1/2 w-[90%] max-w-2xl z-[115] reveal">
        <div class="glass border-primary/20 p-2 pl-6 rounded-2xl shadow-2xl shadow-primary/10 flex items-center justify-between gap-4 overflow-hidden">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary group animate-pulse">
                    <span class="material-symbols-outlined text-xl font-bold">celebration</span>
                </div>
                <div class="flex flex-col">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Promo Aktif</p>
                    <div class="flex items-center gap-2">
                        @foreach($promos as $promo)
                            <span class="text-sm font-black text-slate-900">{{ $promo->code }}</span>
                            <span class="text-xs font-bold text-primary">(-@if($promo->type === 'percentage') {{ $promo->value }}% @else Rp{{ number_format($promo->value, 0) }} @endif)</span>
                        @endforeach
                    </div>
                </div>
            </div>
            <a href="#pricing" class="px-5 py-3 bg-primary text-white rounded-xl text-xs font-black shadow-lg shadow-primary/20 hover:brightness-110 transition-all">
                Klaim
            </a>
            <!-- Decor blur -->
            <div class="absolute -right-12 top-0 w-24 h-24 bg-primary/5 blur-2xl rounded-full"></div>
        </div>
    </div>
@endif
