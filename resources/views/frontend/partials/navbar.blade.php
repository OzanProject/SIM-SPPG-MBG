@php
    $nav = $landingSettings['navbar'] ?? [];
    $wa  = $landingSettings['contact']['whatsapp_number'] ?? null;
    $waMsg = urlencode($landingSettings['contact']['whatsapp_message'] ?? 'Halo, saya ingin bertanya tentang ' . ($appConfigs['app_name'] ?? 'MBG AkunPro') . '.');

    // Parse auth URLs
    $regRaw = $nav['nav_register_url'] ?? null;
    $logRaw = $nav['nav_login_url'] ?? null;
    $navRegUrl = $regRaw ? (str_starts_with($regRaw, 'http') ? $regRaw : url($regRaw)) : route('register');
    $navLogUrl = $logRaw ? (str_starts_with($logRaw, 'http') ? $logRaw : url($logRaw)) : route('login');

    // Build nav menu array — only show items with non-empty label
    $navMenus = [
        ['href' => '/features',     'label' => $nav['nav_menu_features'] ?? 'Fitur',  'section' => 'features'],
        ['href' => '/pricing',      'label' => $nav['nav_menu_pricing']  ?? 'Harga',  'section' => 'pricing'],
        ['href' => '/faq',          'label' => $nav['nav_menu_faq']      ?? 'FAQ',    'section' => 'faq'],
    ];
    if (!empty($nav['nav_menu_about']))   $navMenus[] = ['href' => '/about',   'label' => $nav['nav_menu_about'],   'section' => 'about'];
    if (!empty($nav['nav_menu_contact'])) $navMenus[] = ['href' => '/contact', 'label' => $nav['nav_menu_contact'], 'section' => 'contact'];
@endphp

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- SCROLL PROGRESS BAR --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div id="scroll-progress"
     class="fixed top-0 left-0 h-[2px] z-[200] w-0 transition-none"
     style="background: linear-gradient(90deg, #818cf8, #c084fc, #38bdf8);"></div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- MAIN NAV --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<nav id="main-nav"
     class="fixed top-4 left-1/2 -translate-x-1/2 w-[94%] max-w-7xl z-[120]
            transition-all duration-500 ease-out">

    {{-- Pill container --}}
    <div id="nav-pill"
         class="flex items-center justify-between px-5 py-3 rounded-2xl
                border border-white/10 bg-slate-950/60 backdrop-blur-2xl
                shadow-2xl shadow-black/50 transition-all duration-500">

        {{-- ── BRAND ─────────────────────────────────────────── --}}
        <a href="/" class="group flex items-center gap-3 shrink-0"
           aria-label="{{ $appConfigs['app_name'] ?? 'MBG AKUNPRO' }}">
            @php $logo = $appConfigs['logo_url'] ?? null; @endphp
            @if($logo)
                <img src="{{ url($logo) }}"
                     alt="Logo {{ $appConfigs['app_name'] ?? 'App' }}"
                     class="h-9 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
            @else
                <div class="w-9 h-9 primary-gradient rounded-xl flex items-center justify-center
                            text-white shadow-lg shadow-indigo-500/20
                            group-hover:scale-105 transition-transform duration-300">
                    <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1">restaurant_menu</span>
                </div>
            @endif
            <span class="text-base font-extrabold text-white uppercase tracking-widest
                         group-hover:text-indigo-300 transition-colors duration-300 hidden sm:block">
                {{ $appConfigs['app_name'] ?? 'SIM-SPGG' }}
            </span>
        </a>

        {{-- ── DESKTOP MENU ─────────────────────────────────── --}}
        <div class="hidden md:flex items-center gap-1" id="desktop-nav-links">
            @foreach($navMenus as $menu)
                <a href="{{ $menu['href'] }}"
                   data-section="{{ $menu['section'] }}"
                   class="nav-link relative px-4 py-2 text-[11px] font-black uppercase tracking-[0.2em]
                          text-slate-400 hover:text-white transition-colors duration-300 rounded-xl
                          hover:bg-white/5 group">
                    {{ $menu['label'] }}
                    {{-- Active indicator dot --}}
                    <span class="nav-dot absolute bottom-1.5 left-1/2 -translate-x-1/2 w-1 h-1
                                 rounded-full bg-indigo-400 opacity-0 scale-0 transition-all duration-300
                                 group-[.active]:opacity-100 group-[.active]:scale-100"></span>
                </a>
            @endforeach
        </div>

        {{-- ── RIGHT ACTIONS ────────────────────────────────── --}}
        <div class="flex items-center gap-2">

            {{-- WhatsApp Quick Contact (desktop only, jika ada nomor) --}}
            @if($wa)
                <a href="https://wa.me/{{ $wa }}?text={{ $waMsg }}" target="_blank" rel="noopener"
                   title="WhatsApp Kami"
                   class="hidden lg:flex w-9 h-9 items-center justify-center rounded-xl
                          text-emerald-400 hover:text-white hover:bg-emerald-500/20
                          transition-all duration-300 border border-white/5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </a>
            @endif

            {{-- Auth Buttons (desktop) --}}
            <div class="hidden md:flex items-center gap-2">
                @auth
                    @php
                        $user = auth()->user();
                        $dashUrl = $user->tenant_id 
                            ? url("/{$user->tenant_id}/dashboard") 
                            : url('/super-admin/dashboard');
                    @endphp
                    <a href="{{ $dashUrl }}"
                       class="px-5 py-2.5 text-sm font-bold text-white border border-white/10
                              rounded-xl hover:bg-white/10 hover:border-white/20
                              transition-all duration-300 flex items-center gap-2">
                        <span class="material-symbols-outlined text-base" style="font-variation-settings:'FILL' 1">space_dashboard</span>
                        {{ $nav['nav_dashboard_text'] ?? 'Dashboard' }}
                    </a>
                @else
                    <a href="{{ $navLogUrl }}"
                       class="px-5 py-2.5 text-sm font-bold text-slate-400
                              hover:text-white transition-colors duration-300 rounded-xl
                              hover:bg-white/5">
                        {{ $nav['nav_login_text'] ?? 'Masuk' }}
                    </a>
                    <a href="{{ $navRegUrl }}"
                       class="px-5 py-2.5 primary-gradient text-white rounded-xl
                              text-[11px] font-black uppercase tracking-widest
                              hover:scale-[1.04] active:scale-95 transition-all duration-300
                              shadow-lg shadow-indigo-600/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">rocket_launch</span>
                        {{ $nav['nav_register_text'] ?? 'Mulai Sekarang' }}
                    </a>
                @endauth
            </div>

            {{-- Mobile Hamburger --}}
            <button id="mobile-menu-button" aria-label="Toggle Menu"
                    class="md:hidden w-10 h-10 flex items-center justify-center
                           rounded-xl text-white border border-white/10
                           hover:bg-white/10 transition-all duration-300 relative z-[130]">
                <span id="hamburger-icon" class="material-symbols-outlined text-xl font-light">menu</span>
            </button>

        </div>
    </div>
</nav>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- MOBILE FULL-SCREEN MENU --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div id="mobile-menu"
     class="fixed inset-0 z-[125] bg-slate-950/98 backdrop-blur-3xl
            flex-col hidden overflow-y-auto"
     aria-hidden="true">

    {{-- Top bar in mobile menu --}}
    <div class="flex items-center justify-between px-6 pt-7 pb-4 border-b border-white/5">
        <a href="/" class="flex items-center gap-3">
            @if($logo)
                <img src="{{ url($logo) }}" class="h-8 w-auto object-contain" alt="logo">
            @else
                <div class="w-8 h-8 primary-gradient rounded-lg flex items-center justify-center text-white">
                    <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">restaurant_menu</span>
                </div>
            @endif
            <span class="text-sm font-extrabold text-white uppercase tracking-widest">
                {{ $appConfigs['app_name'] ?? 'SIM-SPGG' }}
            </span>
        </a>
        <button id="mobile-menu-close" aria-label="Tutup Menu"
                class="w-10 h-10 flex items-center justify-center rounded-xl
                       text-slate-400 hover:text-white hover:bg-white/10 transition-all border border-white/5">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>
    </div>

    {{-- Navigation Links (large) --}}
    <div class="flex flex-col gap-1 px-6 pt-8">
        @foreach($navMenus as $index => $menu)
            <a href="{{ $menu['href'] }}"
               data-section="{{ $menu['section'] }}"
               class="mobile-nav-link group flex items-center justify-between
                      px-5 py-5 rounded-2xl border border-white/5
                      hover:bg-white/5 hover:border-white/10 transition-all duration-300"
               style="animation-delay: {{ $index * 60 }}ms">
                <span class="text-3xl font-black tracking-tight text-white group-hover:text-indigo-400 transition-colors duration-300">
                    {{ $menu['label'] }}
                </span>
                <span class="material-symbols-outlined text-slate-600 group-hover:text-indigo-400 transition-colors duration-300 text-2xl">
                    arrow_forward
                </span>
            </a>
        @endforeach
    </div>

    {{-- Divider --}}
    <div class="h-px bg-white/5 mx-6 my-8"></div>

    {{-- Auth CTAs --}}
    <div class="flex flex-col gap-3 px-6">
        @auth
            @php
                $user = auth()->user();
                $dashUrl = $user->tenant_id 
                    ? url("/{$user->tenant_id}/dashboard") 
                    : url('/super-admin/dashboard');
            @endphp
            <a href="{{ $dashUrl }}"
               class="w-full py-5 text-center font-black text-lg primary-gradient
                      text-white rounded-2xl shadow-2xl shadow-indigo-600/30
                      flex items-center justify-center gap-3 hover:scale-[1.02] transition-all">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">space_dashboard</span>
                {{ $nav['nav_dashboard_text'] ?? 'Ke Dashboard' }}
            </a>
        @else
            <a href="{{ $navRegUrl }}"
               class="w-full py-5 text-center font-black text-lg primary-gradient
                      text-white rounded-2xl shadow-2xl shadow-indigo-600/30
                      flex items-center justify-center gap-3 hover:scale-[1.02] transition-all">
                <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">rocket_launch</span>
                {{ $nav['nav_register_text'] ?? 'Daftar Sekarang' }}
            </a>
            <a href="{{ $navLogUrl }}"
               class="w-full py-4 text-center font-bold text-base text-white
                      border border-white/10 rounded-2xl bg-white/5
                      hover:bg-white/10 transition-all">
                {{ $nav['nav_login_text'] ?? 'Masuk Akun' }}
            </a>
        @endauth

        {{-- WhatsApp CTA (mobile) --}}
        @if($wa)
            <a href="https://wa.me/{{ $wa }}?text={{ $waMsg }}" target="_blank" rel="noopener"
               class="w-full py-4 text-center font-bold text-base text-emerald-400
                      border border-emerald-500/20 rounded-2xl bg-emerald-500/5
                      hover:bg-emerald-500/10 transition-all flex items-center justify-center gap-3">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Hubungi via WhatsApp
            </a>
        @endif
    </div>

    {{-- Footer branding --}}
    <div class="mt-auto px-6 pb-10 pt-8 border-t border-white/5">
        <p class="text-center text-[10px] uppercase font-black tracking-[0.35em] text-slate-600">
            &copy; {{ date('Y') }} {{ $appConfigs['app_name'] ?? 'MBG AKUNPRO' }}
        </p>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- NAVBAR SCRIPTS --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    const nav        = document.getElementById('main-nav');
    const navPill    = document.getElementById('nav-pill');
    const mobileMenu = document.getElementById('mobile-menu');
    const mbBtn      = document.getElementById('mobile-menu-button');
    const mbClose    = document.getElementById('mobile-menu-close');
    const progress   = document.getElementById('scroll-progress');
    const navLinks   = document.querySelectorAll('.nav-link, .mobile-nav-link');

    // ── Scroll Progress Bar ──────────────────────────────────────
    function updateProgress() {
        const scrolled = window.scrollY;
        const total    = document.documentElement.scrollHeight - window.innerHeight;
        const pct      = total > 0 ? (scrolled / total) * 100 : 0;
        progress.style.width = pct + '%';
    }

    // ── Navbar Shrink on Scroll ──────────────────────────────────
    function updateNav() {
        const scrolled = window.scrollY > 40;
        navPill.classList.toggle('py-2',    scrolled);
        navPill.classList.toggle('py-3',   !scrolled);
        navPill.classList.toggle('shadow-2xl', scrolled);
        navPill.classList.toggle('bg-slate-950/80', scrolled);
        navPill.classList.toggle('bg-slate-950/60', !scrolled);
        navPill.classList.toggle('border-white/10',  true);
    }

    // ── Active Nav Link by Section ───────────────────────────────
    const sectionIds = ['home', 'trust', 'features', 'how-it-works', 'pricing', 'testimonials', 'faq', 'cta'];

    function updateActiveLink() {
        let currentSection = '';
        sectionIds.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            const rect = el.getBoundingClientRect();
            if (rect.top <= 140 && rect.bottom > 140) currentSection = id;
        });

        navLinks.forEach(link => {
            const section = link.dataset.section;
            const isActive = section === currentSection;
            link.classList.toggle('text-indigo-400',  isActive);
            link.classList.toggle('text-white',        isActive);
            link.classList.toggle('bg-indigo-500/10', isActive);
            link.classList.toggle('text-slate-400',   !isActive);
            link.classList.toggle('bg-transparent',   !isActive);

            // Desktop dot indicator
            const dot = link.querySelector('.nav-dot');
            if (dot) {
                dot.classList.toggle('opacity-100', isActive);
                dot.classList.toggle('opacity-0',   !isActive);
                dot.classList.toggle('scale-100',   isActive);
                dot.classList.toggle('scale-0',     !isActive);
            }
        });
    }

    // ── Smooth Scroll by Section ─────────────────────────────────
    function scrollToSection(sectionId) {
        const el = document.getElementById(sectionId);
        if (el) {
            window.scrollTo({ top: el.offsetTop - 90, behavior: 'smooth' });
            history.pushState(null, null, sectionId === 'home' ? '/' : '/' + sectionId);
        } else if (sectionId === 'home') {
            window.scrollTo({ top: 0, behavior: 'smooth' });
            history.pushState(null, null, '/');
        }
    }

    // ── Mobile Menu Toggle ───────────────────────────────────────
    function openMobileMenu() {
        mobileMenu.classList.remove('hidden');
        mobileMenu.classList.add('flex', 'flex-col');
        mobileMenu.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');
    }
    function closeMobileMenu() {
        mobileMenu.classList.add('hidden');
        mobileMenu.classList.remove('flex', 'flex-col');
        mobileMenu.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
    }

    mbBtn?.addEventListener('click',   openMobileMenu);
    mbClose?.addEventListener('click', closeMobileMenu);

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeMobileMenu();
    });

    // ── Intercept Nav Link Clicks ────────────────────────────────
    const allSections = ['features', 'pricing', 'faq', 'how-it-works', 'testimonials', 'trust', 'cta'
        @if(!empty($nav['nav_menu_about']))   , 'about'   @endif
        @if(!empty($nav['nav_menu_contact'])) , 'contact' @endif
    ];

    document.addEventListener('click', (e) => {
        const link = e.target.closest('a[data-section]');
        if (!link) return;
        
        const section = link.dataset.section;
        if (allSections.includes(section)) {
            e.preventDefault();
            closeMobileMenu();
            setTimeout(() => scrollToSection(section), mobileMenu.classList.contains('hidden') ? 0 : 200);
        }
    });

    // ── Restore scroll position on load ─────────────────────────
    const initialPath = window.location.pathname.substring(1);
    if (allSections.includes(initialPath)) {
        window.addEventListener('load', () => {
            setTimeout(() => scrollToSection(initialPath), 400);
        });
    }

    // ── Bind scroll events ───────────────────────────────────────
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(() => {
                updateProgress();
                updateNav();
                updateActiveLink();
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });

    // Initial state
    updateProgress();
    updateNav();
    updateActiveLink();
})();
</script>
