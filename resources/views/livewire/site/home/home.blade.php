@extends('layouts.site')

@section('title', __('ui.home_page_title').' | '.config('app.name'))

@php
    $heroImage = 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=1600&q=80';
    $mapEmbed = 'https://www.openstreetmap.org/export/embed.html?bbox=-7.573990%2C33.585155%2C-7.553990%2C33.605155&layer=mapnik&marker=33.595155%2C-7.563990';
    $stats = [
        ['value' => '10k+', 'label' => __('ui.home_stats_people_helped'), 'icon' => 'users'],
        ['value' => '50+', 'label' => __('ui.home_stats_active_programs'), 'icon' => 'network'],
        ['value' => '+ 23 ans', 'label' => __('ui.home_stats_years_service'), 'icon' => 'calendar'],
        ['value' => '+ 70', 'label' => __('ui.home_stats_partenair'), 'icon' => 'partenaires'],
    ];
    $services = [
        ['title' => __('ui.home_service_education'), 'description' => __('ui.home_service_education_desc'), 'icon' => 'education'],
        ['title' => __('ui.home_service_health'), 'description' => __('ui.home_service_health_desc'), 'icon' => 'health'],
        ['title' => __('ui.home_service_food'), 'description' => __('ui.home_service_food_desc'), 'icon' => 'food'],
    ];
    $partnerDirectory = public_path('assets/site/images/partenaires');
    $partnerExtensions = ['png', 'jpg', 'jpeg', 'webp', 'svg', 'gif'];
    $partnerFiles = is_dir($partnerDirectory) ? glob($partnerDirectory.'/*') : [];
    $partnerFiles = array_values(array_filter($partnerFiles ?: [], function ($file) use ($partnerExtensions) {
        return is_file($file) && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $partnerExtensions, true);
    }));
    sort($partnerFiles, SORT_NATURAL | SORT_FLAG_CASE);

    $partners = array_map(fn ($file) => [
        'name' => str_replace(['-', '_'], ' ', pathinfo($file, PATHINFO_FILENAME)),
        'image' => asset('assets/site/images/partenaires/'.basename($file)),
    ], $partnerFiles);

    // Fallback placeholders while no logo has been added yet.
    if ($partners === []) {
        $partners = array_map(fn ($name) => ['name' => $name, 'image' => null], ['AA', 'IU', 'CB', 'MC', 'RB', 'AS', 'CC', 'UP']);
    }

    // Slider shows 3 partners per page.
    $partnerPages = array_chunk($partners, 3);
    // Duplicate the first page at the end so the carousel can loop seamlessly.
    $partnerSlides = count($partnerPages) > 1 ? [...$partnerPages, $partnerPages[0]] : $partnerPages;
    $isRtl = in_array(app()->getLocale(), config('app.rtl_locales', []), true);
@endphp

@section('content')
    <section class="relative isolate overflow-hidden bg-[#081d53] text-white">
        <div class="absolute inset-0">
            <img src="{{ $heroImage }}" alt="Casablanca" class="h-full w-full object-cover opacity-70">
            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(5,17,52,0.82)_0%,rgba(5,17,52,0.72)_42%,rgba(8,29,83,0.58)_100%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(115,196,255,0.28),transparent_34%),radial-gradient(circle_at_bottom_right,rgba(101,255,173,0.12),transparent_26%)]"></div>
        </div>

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
                                <div class="text-2xl font-bold tracking-tight">{{ $stat['value'] }}</div>
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

    <section id="services" class="bg-white py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between gap-6">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#2d4b86]">{{ __('ui.home_services_label') }}</p>
                    <h2 class="mt-4 text-3xl font-bold tracking-tight text-[#16254d] sm:text-4xl">{{ __('ui.home_services_title') }}</h2>
                </div>

                <a href="{{ route('services.index') }}" class="hidden text-sm font-semibold text-[#1d6b35] transition hover:text-[#124722] sm:inline-flex">
                    {{ __('ui.home_view_all_services') }}
                </a>
            </div>

            <div class="mt-10 grid gap-5 md:grid-cols-3">
                @foreach ($services as $service)
                    <article class="rounded-3xl border border-white/80 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-300/40">
                        <div class="flex size-11 items-center justify-center rounded-2xl bg-[#f3f7ef] text-[#1d7a2f] ring-1 ring-[#dcead8]">
                            @if ($service['icon'] === 'education')
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M3 8.5 12 4l9 4.5-9 4.5L3 8.5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                                    <path d="M7 10.5V15c0 1.5 2.2 3 5 3s5-1.5 5-3v-4.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @elseif ($service['icon'] === 'health')
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 4v16M4 12h16" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" />
                                    <rect x="4" y="4" width="16" height="16" rx="4" stroke="currentColor" stroke-width="1.5" opacity="0.25" />
                                </svg>
                            @else
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 4c4.5 0 8 3.5 8 8 0 3.5-2.2 6.4-5.3 7.4-1.1.3-2.1-.5-2.1-1.7V17" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                                    <path d="M9 14c0-1.5.9-2.5 2-3 1.6-.8 2.2-1.5 2.2-2.6C13.2 7.1 12.3 6 11 6c-1.1 0-2 .7-2.4 1.8" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                                </svg>
                            @endif
                        </div>
                        <h3 class="mt-5 text-lg font-semibold text-[#16254d]">{{ $service['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $service['description'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="mt-8 text-right sm:hidden">
                <a href="{{ route('services.index') }}" class="text-sm font-semibold text-[#1d6b35] transition hover:text-[#124722]">{{ __('ui.home_view_all_services') }}</a>
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
        <div class="mx-auto max-w-5xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold tracking-tight text-[#16254d] sm:text-4xl">{{ __('ui.home_trust_title') }}</h2>
            <div class="mt-6 flex justify-center">
                <span class="inline-flex items-center gap-2 rounded-full bg-[#303a67] px-5 py-3 text-sm font-semibold text-white shadow-xl shadow-slate-400/25">
                    {{ __('ui.home_trust_badge') }}
                </span>
            </div>
            <p class="mx-auto mt-6 max-w-2xl text-sm leading-7 text-slate-500">
                {{ __('ui.home_trust_description') }}
            </p>

            <div
                class="relative mt-10"
                role="region"
                aria-roledescription="carousel"
                aria-label="{{ __('ui.home_trust_title') }}"
                x-data="{
                    page: 0,
                    pages: {{ count($partnerPages) }},
                    animating: true,
                    timer: null,
                    resetTimer: null,
                    get active() { return this.page % this.pages; },
                    init() { this.startAuto(); },
                    startAuto() {
                        if (this.pages < 2) return;
                        this.stopAuto();
                        this.timer = setInterval(() => this.next(), 5000);
                    },
                    stopAuto() {
                        if (this.timer) { clearInterval(this.timer); this.timer = null; }
                    },
                    next() {
                        if (this.pages < 2 || this.page >= this.pages) return;
                        this.animating = true;
                        this.page += 1;
                        if (this.page === this.pages) {
                            clearTimeout(this.resetTimer);
                            this.resetTimer = setTimeout(() => this.resetIfClone(), 600);
                        }
                    },
                    prev() {
                        if (this.pages < 2) return;
                        if (this.page === 0) {
                            // Jump to the cloned first slide, then animate back to the real last slide.
                            this.animating = false;
                            this.page = this.pages;
                            this.$nextTick(() => {
                                requestAnimationFrame(() => {
                                    this.animating = true;
                                    requestAnimationFrame(() => { this.page = this.pages - 1; });
                                });
                            });
                            return;
                        }
                        this.animating = true;
                        this.page -= 1;
                    },
                    go(index) {
                        if (index === this.active) { this.startAuto(); return; }
                        this.animating = true;
                        this.page = index;
                        this.startAuto();
                    },
                    onTransitionEnd(event) {
                        if (event.target !== this.$refs.track) return;
                        this.resetIfClone();
                    },
                    resetIfClone() {
                        if (this.page !== this.pages) return;
                        clearTimeout(this.resetTimer);
                        this.animating = false;
                        this.page = 0;
                        this.$nextTick(() => {
                            requestAnimationFrame(() => { this.animating = true; });
                        });
                    }
                }"
                @mouseenter="stopAuto()"
                @mouseleave="startAuto()"
            >
                <div class="overflow-hidden">
                    <div
                        x-ref="track"
                        class="flex"
                        :class="animating ? 'transition-transform duration-500 ease-out' : ''"
                        :style="'transform: translateX(' + (page * {{ $isRtl ? '' : '-' }}100) + '%)'"
                        @transitionend="onTransitionEnd($event)"
                    >
                        @foreach ($partnerSlides as $pageIndex => $pagePartners)
                            @php($isClone = count($partnerPages) > 1 && $loop->last)
                            <div
                                class="w-full shrink-0"
                                @if ($isClone)
                                    aria-hidden="true"
                                @else
                                    role="group"
                                    aria-roledescription="slide"
                                    aria-label="{{ $pageIndex + 1 }} / {{ count($partnerPages) }}"
                                @endif
                            >
                                <div class="grid grid-cols-3 gap-3 sm:gap-5">
                                    @foreach ($pagePartners as $partner)
                                        <div class="flex h-28 items-center justify-center rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md sm:h-36 sm:p-6">
                                            @if ($partner['image'])
                                                <img
                                                    src="{{ $partner['image'] }}"
                                                    alt="{{ $partner['name'] }}"
                                                    class="max-h-16 w-auto max-w-full object-contain sm:max-h-20"
                                                    loading="lazy"
                                                >
                                            @else
                                                <span class="text-sm font-bold text-slate-400">{{ $partner['name'] }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if (count($partnerPages) > 1)
                    <button
                        type="button"
                        class="absolute -left-2 top-1/2 z-10 flex size-10 -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white text-[#16254d] shadow-md transition hover:bg-slate-50 sm:-left-5"
                        @click="prev()"
                        aria-label="{{ __('ui.home_trust_prev') }}"
                    >
                        <svg class="size-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M12 5l-5 5 5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <button
                        type="button"
                        class="absolute -right-2 top-1/2 z-10 flex size-10 -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white text-[#16254d] shadow-md transition hover:bg-slate-50 sm:-right-5"
                        @click="next()"
                        aria-label="{{ __('ui.home_trust_next') }}"
                    >
                        <svg class="size-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M8 5l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <div class="mt-6 flex items-center justify-center gap-2">
                        @foreach ($partnerPages as $pageIndex => $pagePartners)
                            <button
                                type="button"
                                class="h-2 rounded-full transition-all duration-300"
                                :class="active === {{ $pageIndex }} ? 'w-6 bg-[#1d6b35]' : 'w-2 bg-slate-300 hover:bg-slate-400'"
                                :aria-current="active === {{ $pageIndex }} ? 'true' : 'false'"
                                @click="go({{ $pageIndex }})"
                                aria-label="{{ __('ui.home_trust_slide') }} {{ $pageIndex + 1 }}"
                            ></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
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
