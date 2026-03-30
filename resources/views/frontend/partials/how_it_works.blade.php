<section id="how-it-works" class="py-32 relative overflow-hidden bg-slate-950/20">
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center mb-24 reveal">
            <span class="text-indigo-400 text-[10px] font-black uppercase tracking-[0.4em] mb-4 block">{{ $landingSettings['how_it_works']['hiw_badge'] ?? 'Alur Kerja Cerdas' }}</span>
            <h2 class="text-4xl md:text-6xl font-black tracking-tight text-white mb-6">
                {{ $landingSettings['how_it_works']['hiw_title'] ?? 'Cara Kami Mengubah Dapur Anda.' }}
            </h2>
            <div class="w-24 h-1.5 primary-gradient mx-auto rounded-full shadow-lg shadow-indigo-600/30"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            <!-- Connecting Line -->
            <div
                class="hidden lg:block absolute top-16 left-0 w-full h-px bg-gradient-to-r from-transparent via-white/10 to-transparent z-0">
            </div>

            @php
                $steps = [
                    ['icon' => '🚀', 'title' => 'Registrasi Cepat', 'desc' => 'Daftarkan cabang dapur Anda dalam hitungan menit dan mulai kelola data secara digital.'],
                    ['icon' => '📊', 'title' => 'Input Transaksi', 'desc' => 'Catat setiap pemasukan, pengeluaran, dan stok barang dengan antarmuka yang intuitif.'],
                    ['icon' => '🛡️', 'title' => 'Monitoring Real-time', 'desc' => 'Pantau performa keuangan dan operasional dari dashboard pusat kapan saja, di mana saja.'],
                    ['icon' => '📈', 'title' => 'Laporan Otomatis', 'desc' => 'Dapatkan laporan keuangan lengkap secara otomatis setiap periode.'],
                ];
            @endphp

            @for($i = 1; $i <= 4; $i++)
                @php
                    $stepIdx = $i - 1;
                    $stepIcon = $landingSettings['how_it_works']['hiw_step' . $i . '_icon'] ?? $steps[$stepIdx]['icon'];
                    $stepTitle = $landingSettings['how_it_works']['hiw_step' . $i . '_text'] ?? $steps[$stepIdx]['title'];
                    $stepDesc = $landingSettings['how_it_works']['hiw_step' . $i . '_desc'] ?? $steps[$stepIdx]['desc'];
                @endphp

                <div class="reveal group relative z-10" style="transition-delay: {{ $i * 100 }}ms">
                    <div class="relative mb-10 flex justify-center">
                        <div
                            class="w-24 h-24 glass-card rounded-[2rem] flex items-center justify-center text-5xl group-hover:scale-110 group-hover:shadow-3xl group-hover:shadow-indigo-600/20 transition-all duration-700 border border-white/10 relative">
                            <span class="relative z-10">{{ $stepIcon }}</span>
                            <div
                                class="absolute -top-1 -right-1 w-8 h-8 primary-gradient rounded-full flex items-center justify-center text-[10px] font-black text-white shadow-lg border-2 border-slate-950">
                                0{{ $i }}
                            </div>
                        </div>
                    </div>

                    <div class="text-center px-4">
                        <h4
                            class="text-xl font-black text-white mb-4 tracking-tight group-hover:text-indigo-400 transition-colors">
                            {{ $stepTitle }}
                        </h4>
                        <p class="text-sm text-slate-400 font-medium leading-relaxed opacity-60">
                            {{ $stepDesc }}
                        </p>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>