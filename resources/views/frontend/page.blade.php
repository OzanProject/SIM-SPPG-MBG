@extends('frontend.layouts.app')

@section('content')
<main class="pt-32 pb-24 bg-slate-950 min-h-screen relative overflow-hidden">
    <!-- Decorative Background Glows -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-500/10 blur-[150px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-500/10 blur-[150px] rounded-full pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-6 relative z-10">
        <!-- Header -->
        <div class="text-center mb-16 reveal">
            <span class="text-indigo-400 text-[10px] font-black uppercase tracking-[0.4em] mb-4 block">
                Legal
            </span>
            <h1 class="text-4xl md:text-5xl font-black tracking-tight text-white mb-6">
                {{ $title }}
            </h1>
            <div class="h-px w-24 bg-gradient-to-r from-transparent via-indigo-500 to-transparent mx-auto"></div>
        </div>

        <!-- Content Card -->
        <div class="glass-card rounded-3xl p-8 md:p-12 border border-white/10 shadow-2xl reveal">
            <div class="prose prose-invert prose-indigo max-w-none">
                <!-- Gunakan tag unescaped karena isinya mungkin berupa HTML dari database -->
                {!! $content !!}
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-12 text-center reveal">
            <a href="/" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors font-medium text-sm border border-white/10 glass px-6 py-3 rounded-xl hover:bg-white/5">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</main>
@endsection
