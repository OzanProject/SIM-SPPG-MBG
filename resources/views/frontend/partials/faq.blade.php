<section id="faq" class="py-32 relative overflow-hidden">

    <!-- Background Glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-indigo-500/5 blur-[120px] rounded-full"></div>
    </div>

    <div class="max-w-4xl mx-auto px-6 relative z-10">

        <!-- Header -->
        <div class="text-center mb-20 reveal">
            <span class="text-indigo-400 text-[10px] font-black uppercase tracking-[0.4em] mb-4 block">
                {{ $landingSettings['faq']['faq_badge'] ?? 'FAQ' }}
            </span>

            <h2 class="text-4xl md:text-6xl font-black tracking-tight text-white mb-6">
                {{ $landingSettings['faq']['faq_title'] ?? 'Masih' }}
                <span class="text-primary-gradient">
                    {{ $landingSettings['faq']['faq_title_gradient'] ?? 'Ragu?' }}
                </span>
            </h2>

            <p class="text-lg text-slate-400 font-medium opacity-70 max-w-xl mx-auto">
                {{ $landingSettings['faq']['faq_description'] ?? 'Kami sudah merangkum pertanyaan yang paling sering ditanyakan sebelum mulai menggunakan sistem ini.' }}
            </p>
        </div>

        <!-- FAQ List -->
        <div x-data="{ open: null }" class="space-y-4 reveal">

            @forelse($faqs as $faq)
                <div class="glass-card rounded-2xl overflow-hidden border border-white/5 transition-all duration-500"
                    :class="open === {{ $faq->id }} ? 'bg-white/5 border-indigo-500/20 shadow-xl shadow-indigo-500/10' : ''">

                    <!-- Question -->
                    <button @click="open = open === {{ $faq->id }} ? null : {{ $faq->id }}"
                        class="w-full px-6 py-6 flex items-center justify-between text-left group">
                        <span class="text-base md:text-lg font-semibold text-white group-hover:text-indigo-400 transition">
                            {{ $faq->question }}
                        </span>

                        <div class="w-8 h-8 shrink-0 flex items-center justify-center rounded-full bg-white/5 group-hover:bg-indigo-500/20 transition"
                            :class="open === {{ $faq->id }} ? 'rotate-180 bg-indigo-500 text-white' : 'text-white/50'">
                            <span class="material-symbols-outlined text-xl">expand_more</span>
                        </div>
                    </button>

                    <!-- Answer -->
                    <div x-show="open === {{ $faq->id }}" x-collapse
                        class="px-6 pb-6 text-slate-400 leading-relaxed text-sm md:text-base">
                        <div class="pt-4 border-t border-white/5">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </div>

                </div>
            @empty

                <!-- Empty State -->
                <div class="glass-card rounded-3xl py-16 px-10 text-center border-dashed border border-white/10">
                    <p class="text-slate-500">
                        Belum ada pertanyaan umum saat ini.
                    </p>
                </div>

            @endforelse

        </div>

        <!-- CTA Bottom -->
        <div class="mt-20 text-center reveal">

            <p class="text-slate-400 mb-6">
                {{ $landingSettings['faq']['faq_cta_text'] ?? 'Masih ada pertanyaan lain?' }}
            </p>

            @php
                $waNum = $landingSettings['contact']['whatsapp_number'] ?? '';
                $waMsg = $landingSettings['contact']['whatsapp_message'] ?? 'Halo, saya ingin bertanya tentang MBG AkunPro.';
                $waUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $waNum) . '?text=' . urlencode($waMsg);

                $faqCta1Raw = $landingSettings['faq']['faq_cta_btn1_url'] ?? null;
                $faqCta2Raw = $landingSettings['faq']['faq_cta_btn2_url'] ?? null;

                $faqCta1Url = $faqCta1Raw ? (str_starts_with($faqCta1Raw, 'http') || str_starts_with($faqCta1Raw, '#') ? $faqCta1Raw : url($faqCta1Raw)) : route('register');
                
                if (in_array($faqCta2Raw, ['#contact', '#wa', '#whatsapp', '#'])) {
                    $faqCta2Url = $waUrl;
                } else {
                    $faqCta2Url = $faqCta2Raw ? (str_starts_with($faqCta2Raw, 'http') || str_starts_with($faqCta2Raw, '#') ? $faqCta2Raw : url($faqCta2Raw)) : $waUrl;
                }
            @endphp
            <div class="flex flex-col sm:flex-row justify-center gap-4">

                <a href="{{ $faqCta1Url }}"
                    class="px-8 py-4 primary-gradient text-white rounded-2xl font-bold text-sm uppercase tracking-widest shadow-lg shadow-indigo-500/20 hover:scale-105 transition">
                    {{ $landingSettings['faq']['faq_cta_btn1_text'] ?? 'Coba Gratis Sekarang' }}
                </a>

                <a href="{{ $faqCta2Url }}"
                    class="px-8 py-4 glass border border-white/10 text-white rounded-2xl font-semibold text-sm hover:bg-white/10 transition">
                    {{ $landingSettings['faq']['faq_cta_btn2_text'] ?? 'Hubungi Tim Kami' }}
                </a>

            </div>

        </div>

    </div>
</section>