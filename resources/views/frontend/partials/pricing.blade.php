<section id="pricing" class="py-32 relative overflow-hidden bg-slate-950">
    {{-- Glow Background --}}
    <div class="absolute inset-0 pointer-events-none opacity-40">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-600/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-600/10 blur-[120px] rounded-full"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        {{-- HEADER --}}
        <div class="text-center mb-24 reveal">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-300">
                    {{ $landingSettings['pricing']['pricing_badge'] ?? 'Harga Fleksibel & Transparan' }}
                </span>
            </div>
            
            <h2 class="text-4xl md:text-7xl font-black text-white mb-8 tracking-tighter leading-none">
                {{ $landingSettings['pricing']['pricing_title'] ?? 'Investasi Cerdas Untuk Restoran Anda' }}
            </h2>
            
            <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto leading-relaxed font-medium">
                {{ $landingSettings['pricing']['pricing_description'] ?? 'Pilih paket yang paling sesuai dengan skala bisnis Anda. Selalu ada ruang untuk berkembang.' }}
            </p>

            {{-- PROMO CODES --}}
            @if($promos->count() > 0)
                <div class="mt-12 flex flex-wrap justify-center gap-4">
                    @foreach($promos as $promo)
                        <div class="group relative px-6 py-3 bg-white/5 border border-white/10 rounded-2xl hover:border-indigo-500/50 transition-all duration-300">
                            <span class="text-slate-400 text-sm font-medium">Kode Promo: </span>
                            <span class="text-white font-black ml-1 tracking-wider">{{ $promo->code }}</span>
                            <div class="absolute -top-1 -right-1 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-indigo-500"></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- PRICING GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($plans as $plan)
                @php 
                    $isHighlighted = $plan->is_highlighted ?? false;
                @endphp
                
                <div class="group relative flex flex-col justify-between rounded-[2.5rem] p-10 
                            transition-all duration-500 hover:-translate-y-4
                            {{ $isHighlighted 
                                ? 'bg-indigo-600/5 border-2 border-indigo-500 shadow-2xl shadow-indigo-600/20' 
                                : 'bg-slate-900/50 border border-white/10 hover:border-white/20' }}">
                    
                    @if($isHighlighted)
                        <div class="absolute -top-5 left-1/2 -translate-x-1/2 px-6 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 
                                    text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full shadow-xl">
                            Pilihan Terbaik
                        </div>
                    @endif

                    {{-- Top Section --}}
                    <div>
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <h4 class="text-2xl font-black text-white mb-2 group-hover:text-indigo-400 transition-colors">
                                    {{ $plan->name }}
                                </h4>
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 bg-white/10 rounded-full text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        {{ $plan->duration_in_days == 30 ? 'Bulanan' : $plan->duration_in_days . ' Hari' }}
                                    </span>
                                    @if($plan->badge_label)
                                        <span class="px-3 py-1 bg-emerald-500/10 rounded-full text-[9px] font-black text-emerald-400 uppercase tracking-widest italic">
                                            {{ $plan->badge_label }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-10">
                            <div class="flex items-baseline gap-1">
                                <span class="text-sm font-bold text-slate-500 uppercase">Rp</span>
                                <span class="text-5xl font-black text-white tracking-tight uppercase">
                                    {{ number_format($plan->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <p class="text-slate-500 text-xs mt-2 font-medium">Bebas biaya admin per transaksi</p>
                        </div>

                        <ul class="space-y-4 mb-10">
                            {{-- Core Limits --}}
                            <li class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                                    <span class="material-symbols-outlined text-lg">person</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white leading-none">{{ $plan->max_users > 0 ? $plan->max_users . ' Akun Pengguna' : 'Tanpa Batas Akun' }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium">Akses multi-perangkat</p>
                                </div>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                                    <span class="material-symbols-outlined text-lg">category</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white leading-none">{{ $plan->max_items > 0 ? $plan->max_items . ' Produk/Menu' : 'Produk Tak Terbatas' }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium">Manajemen katalog cerdas</p>
                                </div>
                            </li>

                            <div class="h-px bg-white/5 my-6"></div>

                            {{-- Detailed Feature Flags (Synced with Super Admin) --}}
                            @php
                                $mainFeatures = [
                                    ['flag' => $plan->has_sales,           'label' => 'Penjualan & Kasir',         'icon' => 'shopping_cart'],
                                    ['flag' => $plan->has_inventory,       'label' => 'Manajemen Stok (Inventory)','icon' => 'inventory_2'],
                                    ['flag' => $plan->has_procurement,     'label' => 'Pengadaan (PO) / Supplier', 'icon' => 'local_shipping'],
                                    ['flag' => $plan->has_accounting_full, 'label' => 'Akuntansi Lengkap (Ledger)','icon' => 'account_balance_wallet'],
                                    ['flag' => $plan->has_budgeting,       'label' => 'Sistem Anggaran (Budgeting)','icon' => 'monetization_on'],
                                    ['flag' => $plan->has_hr,              'label' => 'HR / Penggajian (Payroll)', 'icon' => 'groups'],
                                    ['flag' => $plan->has_circle_menu,     'label' => 'Distribusi Menu Circle',    'icon' => 'restaurant_menu'],
                                    ['flag' => $plan->can_export,          'label' => 'Export Data Excel/PDF',     'icon' => 'download'],
                                ];
                            @endphp

                            @foreach($mainFeatures as $feat)
                                <li class="flex items-center gap-3 opacity-{{ $feat['flag'] ? '100' : '30' }}">
                                    <span class="material-symbols-outlined text-{{ $feat['flag'] ? 'emerald-400' : 'slate-500' }} text-lg">
                                        {{ $feat['flag'] ? 'check_circle' : 'cancel' }}
                                    </span>
                                    <span class="text-xs font-bold {{ $feat['flag'] ? 'text-slate-200' : 'text-slate-600 line-through' }}">
                                        {{ $feat['flag'] ? $feat['label'] : $feat['label'] }}
                                    </span>
                                </li>
                            @endforeach

                            {{-- Custom Features list --}}
                            @if($plan->features)
                                @foreach(explode(',', $plan->features) as $extra)
                                    <li class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-indigo-400 text-lg">done_all</span>
                                        <span class="text-xs font-medium text-slate-300 italic">{{ trim($extra) }}</span>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    {{-- Bottom Section --}}
                    <a href="{{ route('register', ['plan' => $plan->slug]) }}" 
                       class="w-full py-5 rounded-2xl text-xs font-black uppercase tracking-widest text-center transition-all duration-300
                            {{ $isHighlighted 
                                ? 'bg-indigo-500 text-white shadow-xl shadow-indigo-600/30 hover:scale-[1.03] active:scale-95' 
                                : 'bg-white/5 text-white border border-white/10 hover:bg-white/10 hover:border-white/20' }}">
                        {{ $plan->price <= 0 ? 'Mulai Gratis 7 Hari' : 'Pilih Paket Sekarang' }}
                    </a>
                </div>
            @endforeach
        </div>

        {{-- TRUST BADGES --}}
        <div class="mt-24 pt-12 border-t border-white/5">
            <div class="flex flex-wrap justify-center gap-12 text-[10px] font-black uppercase tracking-[0.4em] text-slate-500">
                <div class="flex items-center gap-2 hover:text-indigo-400 transition-colors cursor-default">
                    <span class="material-symbols-outlined text-sm">shield_check</span>
                    Data Terenkripsi 256-bit
                </div>
                <div class="flex items-center gap-2 hover:text-indigo-400 transition-colors cursor-default">
                    <span class="material-symbols-outlined text-sm">lock_reset</span>
                    Batal Kapan Saja
                </div>
                <div class="flex items-center gap-2 hover:text-indigo-400 transition-colors cursor-default">
                    <span class="material-symbols-outlined text-sm">auto_graph</span>
                    Analisis Real-time
                </div>
            </div>
        </div>
    </div>
</section>