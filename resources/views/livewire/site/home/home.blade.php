@extends('layouts.site')

@section('title', __('ui.home_page_title').' | '.config('app.name'))

@php
    $heroImage = 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=1600&q=80';
    $mapEmbed = 'https://www.openstreetmap.org/export/embed.html?bbox=-7.573990%2C33.585155%2C-7.553990%2C33.605155&layer=mapnik&marker=33.595155%2C-7.563990';
    $stats = [
        ['value' => 10000, 'format' => 'compact', 'prefix' => '', 'suffix' => '+', 'label' => __('ui.home_stats_people_helped'), 'icon' => 'users'],
        ['value' => 50, 'format' => 'plain', 'prefix' => '', 'suffix' => '+', 'label' => __('ui.home_stats_active_programs'), 'icon' => 'network'],
        ['value' => 23, 'format' => 'plain', 'prefix' => '+ ', 'suffix' => ' '.__('ui.home_stats_years_suffix'), 'label' => __('ui.home_stats_years_service'), 'icon' => 'calendar'],
        ['value' => 70, 'format' => 'plain', 'prefix' => '+ ', 'suffix' => '', 'label' => __('ui.home_stats_partenair'), 'icon' => 'partenaires'],
    ];

    $formatStat = static function (int $value, string $format): string {
        if ($format === 'compact' && $value >= 1000) {
            return rtrim(rtrim(number_format($value / 1000, 1, '.', ''), '0'), '.').'k';
        }

        return number_format($value, 0, '.', ' ');
    };



@endphp

@section('content')
    <section class="relative isolate overflow-hidden bg-[#081d53] text-white">
        <div class="absolute inset-0">
            <img src="{{ $heroImage }}" alt="Casablanca" class="h-full w-full object-cover opacity-70">
            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(5,17,52,0.82)_0%,rgba(5,17,52,0.72)_42%,rgba(8,29,83,0.58)_100%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(115,196,255,0.28),transparent_34%),radial-gradient(circle_at_bottom_right,rgba(101,255,173,0.12),transparent_26%)]"></div>
        </div>  

        <!-- <video autoplay muted loop playsinline preload="auto" class="absolute inset-0 h-full w-full object-cover opacity-70" poster="{{ $heroImage }}" >
            <source src="{{ asset('assets/web_site/hero_vd.mp4') }}" type="video/mp4"> Your browser does not support the video tag.
        </video> -->

        <div class="relative mx-auto max-w-7xl px-4 pb-28 pt-16 sm:px-6 lg:px-8 lg:pb-32 lg:pt-24">
            <div id="hero" class="max-w-3xl">
                <p class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-white/85 backdrop-blur">
                    {{ __('ui.home_hero_badge') }}
                </p>

                <h1 class="mt-8 max-w-2xl text-5xl font-black tracking-tight text-white sm:text-6xl lg:text-7xl">
                    {{ __('ui.home_hero_title_line_1') }}<br>
                    <span class="text-[#a7ff8f]">{{ __('ui.home_hero_title_line_2') }}</span>
                </h1>

                <p class="mt-6 max-w-xl text-base leading-8 text-white/78 sm:text-lg">
                    {{ __('ui.home_hero_description') }}
                </p>
            </div>

            <div class="mt-12 grid gap-4 md:grid-cols-4 lg:mt-16">
                @foreach ($stats as $stat)
                    <article class="rounded-2xl border border-white/18 bg-white/20 p-5 text-white shadow-xl shadow-black/15 backdrop-blur-md">
                        <div class="flex items-center gap-4">
                            <div class="flex size-14 items-center justify-center rounded-2xl bg-[#a7ff8f]/18 text-[#a7ff8f] ring-1 ring-[#a7ff8f]/25">
                                @if ($stat['icon'] === 'users')
                                    <svg class="size-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm8 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M4 19c0-2.5 2.5-4.5 5.5-4.5S15 16.5 15 19M13 19c0-2.2 2.1-4 4.8-4 2.7 0 4.2 1.4 4.2 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                                    </svg>
                                @elseif ($stat['icon'] === 'calendar')
                                    <svg class="size-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M7 3v3M17 3v3M4.5 9h15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                                        <rect x="4" y="5" width="16" height="16" rx="3" stroke="currentColor" stroke-width="1.7" />
                                    </svg>
                                @elseif ($stat['icon'] === 'partenaires')
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24" height="24" fill="none" stroke="currentColor">
                                    <g stroke="#000" stroke-width="20" stroke-linecap="round" stroke-linejoin="round">

                                        <!-- Left sleeve -->
                                        <path stroke="currentColor" d="M12 202 L138 74 L188 125 L62 252 Z" />

                                        <!-- Right sleeve -->
                                        <path stroke="currentColor" d="M388 61 L501 175 L451 225 L338 112 Z"/>

                                        <!-- Left arm -->
                                        <path stroke="currentColor" d="M188 125 Q216 125 242 148"/>

                                        <!-- Right arm -->
                                        <path stroke="currentColor" d="M338 112 Q319 130 295 125 Q268 125 244 148"/>

                                        <!-- Main handshake -->
                                        <path stroke="currentColor" d="M244 148 L203 190 Q188 207 202 228 Q222 250 246 232 L298 179"/>

                                        <!-- Right palm -->
                                        <path stroke="currentColor" d="M338 112 L451 225 L384 292 L289 196"/>

                                        <!-- Upper connecting fingers -->
                                        <path stroke="currentColor" d="M289 196 L382 290"/>

                                        <!-- Right lower fingers -->
                                        <path stroke="currentColor" d="M312 292 L362 342 Q378 358 394 341 Q409 325 393 309 L383 295"/>
                                        <path stroke="currentColor" d="M277 328 L326 379 Q342 395 359 378 Q375 362 358 346"/>
                                        <path stroke="currentColor" d="M248 365 L290 407 Q305 423 322 407 Q338 390 322 375"/>
                                        <path stroke="currentColor" d="M229 402 L254 427 Q270 445 286 428 Q302 412 286 396"/>

                                        <!-- Left lower fingers -->
                                        <path stroke="currentColor" d="M95 287 L126 257 Q143 241 161 258 Q179 275 161 292 L128 326 Q110 344 92 326 Q76 310 95 287 Z"/>
                                        <path stroke="currentColor" d="M128 326 L162 292 Q180 274 198 292 Q215 309 197 327 L163 362 Q145 379 127 361 Q111 345 128 326 Z"/>
                                        <path stroke="currentColor" d="M163 362 L198 327 Q216 309 234 327 Q251 345 233 363 L198 399 Q180 417 162 399 Q145 381 163 362 Z"/>
                                        <path stroke="currentColor" d="M198 399 L234 363 Q251 345 269 363 Q286 381 269 399 L235 434 Q217 452 199 434 Q181 416 198 399 Z"/>

                                        <!-- Bottom connection -->
                                        <path stroke="currentColor" d="M235 434 Q251 449 269 434 L283 420"/>

                                        <!-- Cuff details -->
                                        <path stroke="currentColor" d="M64 200 L95 169"/>
                                        <path stroke="currentColor" d="M123 143 L123 143"/>
                                        <path stroke="currentColor" d="M430 151 L449 171"/>
                                        <path stroke="currentColor" d="M401 122 L401 122"/>

                                    </g>
                                    </svg>
                                
                                @else
                                    <svg class="size-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                                        <path d="M7 4v16M12 4v16M17 4v16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.5" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <div
                                    class="text-2xl font-bold tracking-tight tabular-nums"
                                    data-counter
                                    data-target="{{ $stat['value'] }}"
                                    data-format="{{ $stat['format'] }}"
                                    data-prefix="{{ $stat['prefix'] }}"
                                    data-suffix="{{ $stat['suffix'] }}"
                                >{{ $stat['prefix'].$formatStat($stat['value'], $stat['format']).$stat['suffix'] }}</div>
                                <p class="text-sm text-white/72">{{ $stat['label'] }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="history" class="bg-[#FBF8FD] py-20 sm:py-24" id="history">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#2d4b86]">{{ __('ui.home_history_label') }}</p>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-[#16254d] sm:text-4xl">{{ __('ui.home_history_title') }}</h2>
                <p class="mt-5 max-w-xl text-base leading-8 text-slate-600">
                    {{ __('ui.home_history_description') }}
                </p>
                <a href="{{ route('history.index') }}" class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-[#16254d] transition hover:text-[#0f2454]">
                    {{ __('ui.home_learn_more') }}
                    <svg class="size-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M4 10h12M11 5l5 5-5 5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>

            <div class="relative">
                <div class="overflow-hidden rounded-[2rem] bg-[radial-gradient(circle_at_top_left,#243a69_0%,#08192f_48%,#07111f_100%)] shadow-2xl shadow-slate-400/30">
                    <div class="relative min-h-[340px] p-8 sm:min-h-[420px]">
                       <img src="{{ asset('assets/web_site/images/assa_ct.png') }}" alt="{{ __('ui.home_history_title') }}" class="absolute inset-0 size-full object-cover">

                        <div class="absolute left-8 bottom-8 rounded-2xl bg-[#118a27] px-5 py-4 text-white shadow-xl shadow-emerald-900/30">
                            <p class="text-base font-bold">{{ __('ui.home_founded') }}</p>
                            <p class="mt-1 text-xs text-white/80">{{ __('ui.home_founded_location') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Mot du Directeur --}}
    <section id="director" class="relative overflow-hidden bg-white py-20 sm:py-24">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(29,107,53,0.08),transparent_55%)]"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
                {{-- Image --}}
                <div class="relative order-1">
                    <div class="absolute -left-5 -top-5 hidden size-28 rounded-[1.75rem] border-4 border-[#a7ff8f]/70 sm:block" aria-hidden="true"></div>

                    <div class="relative overflow-hidden rounded-[2rem] bg-slate-100 shadow-2xl shadow-slate-300/50">
                        <img
                            src="{{ asset('assets/web_site/images/president.jpg') }}"
                            alt="{{ __('ui.home_director_name') }}"
                            class=" w-full object-cover "
                            loading="lazy"
                        >
                    </div>

                    <div class="absolute -bottom-6 -right-5 hidden rounded-2xl bg-[#1d6b35] px-5 py-4 text-white shadow-xl shadow-emerald-900/25 sm:block">
                        <p class="text-2xl font-black leading-none">23+</p>
                        <p class="mt-1 max-w-[7rem] text-[11px] uppercase leading-tight tracking-[0.16em] text-white/80">{{ __('ui.home_stats_years_service') }}</p>
                    </div>
                </div>

                {{-- Text --}}
                <div class="order-2">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#2d4b86]">{{ __('ui.home_director_label') }}</p>
                    <h2 class="mt-4 text-3xl font-bold tracking-tight text-[#16254d] sm:text-4xl">{{ __('ui.home_director_title') }}</h2>

                    <blockquote class="mt-6 border-s-4 border-[#82E682] ps-5 text-lg font-medium italic leading-8 text-[#16254d] sm:text-xl sm:leading-9">
                        « {{ __('ui.home_director_quote') }} »
                    </blockquote>

                    <p class="mt-6 text-base leading-8 text-slate-600">{{ __('ui.home_director_paragraph_1') }}</p>
                    <p class="text-base leading-8 text-slate-600">{{ __('ui.home_director_paragraph_2') }}</p>

                    <p class="mt-4 text-base leading-8 text-slate-600">{{ __('ui.home_director_paragraph_3') }}</p>
                    <p class="mt-1 text-base leading-8 text-slate-600">{{ __('ui.home_director_paragraph_4') }}</p>
                    <p class="mt-1 text-base leading-8 text-slate-600">{{ __('ui.home_director_paragraph_5') }}</p>

                    <div class="mt-8 flex flex-wrap items-center gap-5 border-t border-slate-100 pt-6">
                        <svg class="h-12 w-28 text-[#1d6b35]" viewBox="0 0 112 48" fill="none" aria-hidden="true">
                            <path d="M4 34c8-14 14-22 18-24 5-3 5 6 2 14s-7 16-3 16c4 1 10-9 15-19s9-15 11-11c2 5-4 18-1 20 4 2 8-6 12-12 3-5 5-6 6-3 2 5-1 12 2 13 4 2 8-3 13-9 4-5 7-7 9-5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div>
                            <p class="text-base font-bold text-[#16254d]">{{ __('ui.home_director_name') }}</p>
                            <p class="text-sm text-slate-500">{{ __('ui.home_director_role') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Latest News from ERP --}}
    @if($latestNews->isNotEmpty())
    <section id="latest-news" class="bg-white py-20 sm:py-24">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-[#1d6b35] mb-2">{{ __('ui.nav_news') ?: 'Actualités' }}</p>
                    <h2 class="text-3xl font-bold tracking-tight text-[#16254d] sm:text-4xl">{{ __('ui.home_latest_news_title') ?: 'Dernières actualités' }}</h2>
                </div>
                <a href="{{ route('actualite.index') }}" class="hidden sm:inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    {{ __('ui.home_view_all') ?: 'Voir tout' }}
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($latestNews as $item)
                <article class="rounded-2xl border border-slate-100 bg-white shadow-sm hover:shadow-md transition-shadow overflow-hidden group relative">
                    @if($item->image_url)
                    <div class="aspect-video overflow-hidden bg-slate-100">
                        <img src="{{ $item->image_url }}" alt="{{ $item->getLocalized('title') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    </div>
                    @endif
                    <div class="p-5">
                        <time class="text-xs font-semibold text-slate-400 uppercase tracking-wide">
                            {{ \Carbon\Carbon::parse($item->published_at ?? $item->created_at)->translatedFormat('d M Y') }}
                        </time>
                        <h3 class="mt-2 text-base font-bold text-[#16254d] leading-snug line-clamp-2 group-hover:text-[#1d6b35] transition-colors">
                            <a href="{{ route('actualite.show', $item->slug) }}" class="focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                {{ $item->getLocalized('title') }}
                            </a>
                        </h3>
                        @if($item->getLocalized('excerpt'))
                        <p class="mt-2 text-sm text-slate-500 line-clamp-3">{{ strip_tags($item->getLocalized('excerpt')) }}</p>
                        @endif
                    </div>
                </article>
                @endforeach
            </div>
            <div class="mt-8 text-right sm:hidden">
                <a href="{{ route('actualite.index') }}" class="text-sm font-semibold text-[#1d6b35]">{{ __('ui.home_view_all') ?: 'Voir tout' }}</a>
            </div>
        </div>
    </section>
    @endif

    <section id="trust" class="bg-[#FBF8FD] py-20 sm:py-24">


        @include('livewire.site.home.partenairs')
    </section>

    <section id="contact" class="bg-[#FBF8FD] py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#2d4b86]">{{ __('ui.home_contact_label') ?? 'Contact' }}</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-[#16254d] sm:text-4xl">{{ __('ui.home_contact_title') }}</h2>
                <p class="mt-4 mx-auto max-w-xl text-sm leading-7 text-slate-600">{{ __('ui.home_contact_description') }}</p>
            </div>

            <div class="grid gap-8 lg:grid-cols-5">
                {{-- Contact Info + Map --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="rounded-[1.75rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/40">
                        <div class="space-y-4 text-sm text-slate-600">
                            <div class="flex gap-3">
                                <span class="mt-0.5 inline-flex size-9 items-center justify-center rounded-xl bg-[#EEF2FF] text-[#2d4b86] flex-shrink-0">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M12 21s7-5.4 7-11a7 7 0 1 0-14 0c0 5.6 7 11 7 11Z" stroke="currentColor" stroke-width="1.6" />
                                        <circle cx="12" cy="10" r="2.3" stroke="currentColor" stroke-width="1.6" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-semibold text-[#16254d]">{{ __('ui.home_contact_address_label') }}</p>
                                    <p class="mt-0.5 text-slate-500">{{ __('ui.home_contact_address') }}</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="mt-0.5 inline-flex size-9 items-center justify-center rounded-xl bg-[#EEF2FF] text-[#2d4b86] flex-shrink-0">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M5 4h4l2 6-3 2c1.2 2.4 3.1 4.3 5.5 5.5l2-3 6 2v4c0 1.1-.9 2-2 2C11.6 22 2 12.4 2 6c0-1.1.9-2 2-2h1Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-semibold text-[#16254d]">{{ __('ui.home_contact_phone_label') }}</p>
                                    <p class="mt-0.5 text-slate-500">{{ __('ui.home_contact_phone') }}</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="mt-0.5 inline-flex size-9 items-center justify-center rounded-xl bg-[#EEF2FF] text-[#2d4b86] flex-shrink-0">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M4 6h16v12H4z" stroke="currentColor" stroke-width="1.6" />
                                        <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-semibold text-[#16254d]">{{ __('ui.home_contact_email_label') }}</p>
                                    <p class="mt-0.5 text-slate-500">{{ __('ui.home_contact_email') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-[1.75rem] shadow-xl shadow-slate-200/40" style="height:280px">
                        <iframe
                            src="{{ $mapEmbed }}"
                            class="h-full w-full"
                            style="border:0"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Casablanca map"
                        ></iframe>
                    </div>
                </div>

                {{-- Contact Form --}}
                <div class="lg:col-span-3">
                    <div class="rounded-[1.75rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/40 sm:p-8">
                        <h3 class="text-xl font-bold text-[#16254d] mb-6">Envoyez-nous un message</h3>
                        @livewire('contact.contact-form')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            const counters = document.querySelectorAll('[data-counter]');
            if (!counters.length) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            const format = (value, mode) => {
                if (mode === 'compact' && value >= 1000) {
                    return (value / 1000).toFixed(1).replace(/\.0$/, '') + 'k';
                }

                return String(Math.round(value)).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            };

            const run = (el) => {
                const target = Number(el.dataset.target || 0);
                const mode = el.dataset.format || 'plain';
                const prefix = el.dataset.prefix || '';
                const suffix = el.dataset.suffix || '';
                const duration = 1800;
                const start = performance.now();

                const tick = (now) => {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = prefix + format(target * eased, mode) + suffix;

                    if (progress < 1) {
                        requestAnimationFrame(tick);
                    } else {
                        el.textContent = prefix + format(target, mode) + suffix;
                    }
                };

                el.textContent = prefix + format(0, mode) + suffix;
                requestAnimationFrame(tick);
            };

            if (!('IntersectionObserver' in window)) {
                counters.forEach(run);
                return;
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    observer.unobserve(entry.target);
                    run(entry.target);
                });
            }, { threshold: 0.4 });

            counters.forEach((counter) => observer.observe(counter));
        })();
    </script>
@endpush
