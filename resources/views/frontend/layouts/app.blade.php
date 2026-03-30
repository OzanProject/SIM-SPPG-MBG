<!DOCTYPE html>
<html class="light scroll-smooth" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', ($landingSettings['seo']['site_title'] ?? $appConfigs['app_name'] ?? 'Dapur MBG'))</title>
    <meta name="description" content="@yield('meta_description', $landingSettings['seo']['meta_description'] ?? '')">
    
    @php $favicon = $appConfigs['favicon_url'] ?? null; @endphp
    @if($favicon)
        <link rel="icon" type="image/x-icon" href="{{ url($favicon) }}">
    @endif
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '{{ $appConfigs['primary_color'] ?? '#00628C' }}',
                        'primary-soft': '{{ $appConfigs['primary_color'] ?? '#00628C' }}15',
                        surface: '#F8FAFC',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    animation: {
                        'slow-spin': 'spin 12s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'reveal': 'reveal 0.8s cubic-bezier(0.2, 0, 0.2, 1) forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        reveal: {
                            '0%': { opacity: 0, transform: 'translateY(20px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --primary: {{ $appConfigs['primary_color'] ?? '#818cf8' }};
            --primary-glow: {{ ($appConfigs['primary_color'] ?? '#818cf8') . '40' }};
            --surface: #030712;
            --glass: rgba(17, 24, 39, 0.6);
            --border: rgba(255, 255, 255, 0.08);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--surface);
            color: #94a3b8;
        }

        .mesh-gradient {
            background-color: var(--surface);
            background-image: 
                radial-gradient(at 0% 0%, hsla(244, 63%, 15%, 1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(263, 70%, 15%, 1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(217, 91%, 15%, 1) 0, transparent 50%),
                radial-gradient(at 100% 100%, hsla(244, 63%, 10%, 1) 0, transparent 50%);
            background-attachment: fixed;
        }

        .glass {
            background: var(--glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border);
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            background: rgba(51, 65, 85, 0.5);
            border-color: rgba(255, 255, 255, 0.12);
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.5);
        }

        .text-gradient {
            background: linear-gradient(135deg, #fff 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .primary-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, #c084fc 100%);
        }

        .text-primary-gradient {
            background: linear-gradient(135deg, #a5b4fc 0%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Reveal Animation */
        .reveal { 
            opacity: 0; 
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.2, 0, 0.2, 1);
        }
        .reveal.active { 
            opacity: 1; 
            transform: translateY(0); 
        }

        /* Hover Glow Effect */
        .hover-glow {
            position: relative;
        }
        .hover-glow::after {
            content: '';
            position: absolute;
            inset: -1px;
            background: linear-gradient(135deg, var(--primary), #c084fc);
            z-index: -1;
            border-radius: inherit;
            opacity: 0;
            transition: opacity 0.4s;
        }
        .hover-glow:hover::after {
            opacity: 0.3;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--surface); }
        ::-webkit-scrollbar-thumb { background: #1f2937; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #334155; }
        
        section { scroll-margin-top: 100px; }

        /* Prevent Material Symbols from showing as raw text during load (FOUT) */
        .material-symbols-outlined {
            font-display: block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            /* Hide text until font is loaded or fallback to empty */
            overflow: hidden;
            width: 1em;
            height: 1em;
        }
    </style>
</head>
<body class="mesh-gradient min-h-screen text-slate-300 selection:bg-indigo-500/30 selection:text-indigo-200 antialiased overflow-x-hidden">

    
    @include('frontend.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('frontend.partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ── Reveal Animations (Intersection Observer) ──
            const reveals = document.querySelectorAll('.reveal');
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

            reveals.forEach(el => revealObserver.observe(el));
        });
    </script>
    @stack('scripts')
</body>
</html>
