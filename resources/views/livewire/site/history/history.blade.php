@extends('layouts.site')

@push('head')
    <script>
        document.documentElement.classList.add('has-js');
    </script>
@endpush

@push('styles')
    @include('livewire.site.history.partials.styles')
    <style>
        .hist-chapter__figure {
            position: relative !important;
            overflow: hidden !important;
            border-radius: 0.75rem;
        }
        .hist-chapter__figure [data-carousel-track] {
            display: flex !important;
            width: 100%;
            transition: transform 0.5s ease-out;
        }
        .hist-chapter__figure [data-carousel-slide] {
            flex: 0 0 100%;
            width: 100%;
        }
        .hist-chapter__figure [data-carousel-slide] img {
            display: block;
            width: 100%;
            aspect-ratio: 16 / 10;
            object-fit: cover;
        }
    </style>
@endpush

@php
    $pageTitle = __('ui.history_page_title');
    $pageLead = __('ui.history_hero_lead');
    $pageBody = '<p>'.e(__('ui.home_history_description')).'</p>';

    $atelierImage = asset('assets/web_site/images/histoire/old_asso.jpeg');
    $jardinImage = asset('assets/web_site/asso_banner.avif');
    $presidentImage = asset('assets/web_site/images/president.jpg');
    $motifImage = asset('assets/web_site/images/assa_ct.png');

    $old1 = asset('assets/web_site/images/histoire/old1.jpeg');
    $old2 = asset('assets/web_site/images/histoire/old2.jpeg');
    $old3 = asset('assets/web_site/images/histoire/odl3.jpeg');
    $old4 = asset('assets/web_site/images/histoire/old4.jpeg');

    $today1 = asset('assets/web_site/images/histoire/today1.jpeg');
    $today2 = asset('assets/web_site/images/histoire/today2.jpeg');
    $today3 = asset('assets/web_site/images/histoire/today3.jpeg');
    $today4 = asset('assets/web_site/images/histoire/today4.jpeg');
    $today5 = asset('assets/web_site/images/histoire/today5.jpeg');
    $today6 = asset('assets/web_site/images/histoire/today6.jpeg');
    $today7 = asset('assets/web_site/images/histoire/today7.jpeg');
    $today8 = asset('assets/web_site/images/histoire/today8.jpeg');
    $today9 = asset('assets/web_site/images/histoire/today9.jpeg');
    $today10 = asset('assets/web_site/images/histoire/today10.jpeg');

    $chapters = [
        [
            'year' => '2002',
            'label' => __('ui.history_chapter_origin_label'),
            'title' => __('ui.history_chapter_origin_title'),
            'text' => __('ui.history_chapter_origin_text'),
            'image' => $atelierImage,
            'alt' => __('ui.history_chapter_atelier_alt'),
            'caption' => ' ',
            'tags' => null,
        ],
        [
            'year' => '2006',
            'label' => __('ui.history_chapter_founding_label'),
            'title' => __('ui.history_chapter_founding_title'),
            'text' => __('ui.home_history_description'),
            'image' => [$old1, $old2, $old3, $old4],
            'alt' => __('ui.history_chapter_jardin_alt'),
            'caption' => __('ui.history_chapter_jardin_caption'),
            'tags' => [__('ui.category_solidarite'), __('ui.category_urbanisme')],
            'side_text' => ' ',
        ],
        [
            'year' => '2007',
            'label' => __('ui.history_chapter_sportif_label'),
            'title' => __('ui.history_chapter_sportif_title'),
            'text' => __('ui.home_history_sportif'),
            'image' => null,
            'alt' => __('ui.history_chapter_jardin_alt'),
            'caption' => __('ui.history_chapter_jardin_caption'),
            'tags' => [__('ui.category_solidarite'), __('ui.category_urbanisme')],
            'side_text' => ' ',
        ],
        [
            'year' => __('ui.history_chapter_today_label'),
            'label' => __('ui.history_pillars_label'),
            'title' => __('ui.history_chapter_today_title'),
            'text' => __('ui.history_chapter_today_text'),
            'image' => [ $today1, $today2, $today3, $today4, $today5, $today6, $today7, $today8, $today9, $today10, ],
            'alt' => null,
            'caption' => null,
            'tags' => [
                __('ui.home_service_education'),
                __('ui.home_service_health'),
                __('ui.category_culture'),
                __('ui.category_urbanisme'),
            ],
            'side_text' => ' ',
        ],
    ];

    $legacyStats = [
        ['value' => 10000, 'format' => 'compact', 'prefix' => '', 'suffix' => '+', 'label' => __('ui.home_stats_people_helped'), 'counter' => true],
        ['value' => 50, 'format' => 'plain', 'prefix' => '', 'suffix' => '+', 'label' => __('ui.home_stats_active_programs'), 'counter' => true],
        ['value' => 70, 'format' => 'plain', 'prefix' => '', 'suffix' => '+', 'label' => __('ui.home_stats_partenair'), 'counter' => true],
    ];

    $formatStat = static function (int $value, string $format): string {
        if ($format === 'compact' && $value >= 1000) {
            return rtrim(rtrim(number_format($value / 1000, 1, '.', ''), '0'), '.').'k';
        }

        return number_format($value, 0, '.', ' ');
    };

    $gallery = [
        [
            'src' => $atelierImage,
            'alt' => __('ui.history_chapter_atelier_alt'),
            'title' => __('ui.history_gallery_1_title'),
        ],
        [
            'src' => $presidentImage,
            'alt' => __('ui.home_director_name'),
            'title' => __('ui.history_gallery_2_title'),
        ],
        [
            'src' => $jardinImage,
            'alt' => __('ui.history_chapter_jardin_alt'),
            'title' => __('ui.history_gallery_3_title'),
        ],
    ];
@endphp

@section('title', $pageTitle.' | '.config('app.name'))

@section('seo')
    <x-seo :data="\App\Support\Seo\SeoData::forPage(
        title: $pageTitle,
        description: $pageLead,
        breadcrumbs: [
            ['name' => __('ui.home'), 'url' => route('home')],
            ['name' => __('ui.history'), 'url' => route('history.index')],
        ],
        schemaType: 'AboutPage',
    )" />
@endsection

@section('content')
<div class="hist-page" data-history-page>

    <section class="hist-hero" aria-labelledby="histoire-title">
        <div class="hist-hero__backdrop" aria-hidden="true">
            <img src="{{ $jardinImage }}" alt="" width="500" height="500" loading="eager" decoding="async">
            <div class="hist-hero__shade"></div>
            <div class="hist-grain"></div>
        </div>

        <div class="hist-shell hist-hero__inner">
            <nav class="hist-crumbs" aria-label="{{ __('ui.history_breadcrumb') }}">
                <a href="{{ route('home') }}">{{ __('ui.home') }}</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">{{ __('ui.history') }}</span>
            </nav>

            <div class="">
                <div class="hist-hero__copy">
                    <p class="hist-kicker hist-hero__eyebrow">{{ __('ui.history_hero_eyebrow') }}</p>

                    <h1 id="histoire-title" class="hist-display hist-hero__title">{{ $pageTitle }}</h1>

                    <p class="hist-hero__lead">{{ $pageLead }}</p>

                    <div class="hist-hero__actions">
                        <a href="#chronologie" class="hist-btn hist-btn--mint">
                            {{ __('ui.history_hero_cta_timeline') }}
                            <i class="fa-solid fa-arrow-down animate-bounce" aria-hidden="true"></i>
                        </a>
                    </div>

                    <dl class="hist-hero__meta">
                        <div>
                            <dt>{{ __('ui.history_chapter_founding_label') }}</dt>
                            <dd>{{ __('ui.home_founded') }}</dd>
                        </div>
                        <div>
                            <dt>{{ __('ui.history_hero_origin_label') }}</dt>
                            <dd>{{ __('ui.history_hero_origin_value') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <a href="#chronologie" class="hist-scroll inline-flex items-center justify-center text-white transition hover:translate-y-1" aria-label="{{ __('ui.history_hero_scroll') }}">
            <i class="fa-solid fa-arrow-down text-[30px] animate-bounce" aria-hidden="true"></i>
        </a>
    </section>

    <section id="chronologie" class="hist-section hist-section--paper2">
        <div class="hist-shell">
            <header class="hist-head">
                <div>
                    <p class="hist-kicker">{{ __('ui.history_timeline_label') }}</p>
                    <h2 class="hist-display hist-head__title">{{ __('ui.history_timeline_title') }}</h2>
                </div>
            </header>

            <ol class="hist-jump" aria-label="{{ __('ui.history_timeline_label') }}">
                @foreach ($chapters as $index => $chapter)
                    <li>
                        <a href="#chapitre-{{ $index }}" data-hist-jump="{{ $index }}">
                            {{ $chapter['year'] }}
                        </a>
                    </li>
                @endforeach
            </ol>

            <div class="hist-timeline" data-hist-timeline>
                <div class="hist-timeline__rail" aria-hidden="true">
                    <span data-hist-progress></span>
                </div>

                @foreach ($chapters as $index => $chapter)
                    <article id="chapitre-{{ $index }}"
                             class="hist-chapter {{ $index % 2 === 0 ? 'is-left' : 'is-right' }}"
                             data-hist-chapter="{{ $index }}"
                             data-reveal>
                        <span class="hist-chapter__marker" aria-hidden="true">
                            <span class="hist-chapter__dot"></span>
                        </span>

                        <div class="hist-chapter__panel">
                            <p class="hist-display hist-chapter__year {{ ctype_digit((string) $chapter['year']) ? '' : 'is-word' }}">{{ $chapter['year'] }}</p>
                            <p class="hist-kicker hist-chapter__label">{{ $chapter['label'] }}</p>
                            <h3 class="hist-display hist-chapter__title">{{ $chapter['title'] }}</h3>

                            @php
                                $chapterImages = is_array($chapter['image'] ?? null)
                                    ? array_values(array_filter($chapter['image']))
                                    : (!empty($chapter['image']) ? [$chapter['image']] : []);
                            @endphp

                            @if (count($chapterImages) > 0)
                                <figure class="hist-chapter__figure relative overflow-hidden group" data-carousel>
                                    <div data-carousel-track>
                                        @foreach ($chapterImages as $img)
                                            <div data-carousel-slide>
                                                <img src="{{ $img }}" alt="{{ $chapter['alt'] ?? '' }}"
                                                     width="1024" height="640" loading="lazy" decoding="async">
                                            </div>
                                        @endforeach
                                    </div>

                                    @if (count($chapterImages) > 1)
                                        <button type="button" data-carousel-prev aria-label="Previous"
                                                class="absolute left-3 top-1/2 z-10 -translate-y-1/2 flex h-9 w-9 items-center justify-center rounded-full bg-black/60 text-white transition hover:bg-black/85">
                                            <i class="fa-solid fa-chevron-left text-xs"></i>
                                        </button>

                                        <button type="button" data-carousel-next aria-label="Next"
                                                class="absolute right-3 top-1/2 z-10 -translate-y-1/2 flex h-9 w-9 items-center justify-center rounded-full bg-black/60 text-white transition hover:bg-black/85">
                                            <i class="fa-solid fa-chevron-right text-xs"></i>
                                        </button>

                                        <div class="absolute bottom-3 left-1/2 z-10 -translate-x-1/2 flex items-center gap-1.5" data-carousel-dots>
                                            @foreach ($chapterImages as $dotIndex => $img)
                                                <button type="button" data-carousel-dot="{{ $dotIndex }}" aria-label="Slide {{ $dotIndex + 1 }}"
                                                        class="h-2 rounded-full transition-all {{ $dotIndex === 0 ? 'w-5 bg-white' : 'w-2 bg-white/50' }}"></button>
                                            @endforeach
                                        </div>
                                    @endif
                                </figure>
                            @endif
                        </div>

                        <div class="hist-chapter__aside flex items-center {{ $index % 2 === 0 ? 'lg:col-start-2 lg:pl-6' : 'lg:col-start-1 lg:row-start-1 lg:pr-6 lg:text-right' }}">
                            <p class="hist-chapter__text_side text-base leading-8 text-slate-600" style="text-align: justify;">
                                {!! nl2br(e(str_replace(['\\n', "\'n"], "\n", $chapter['text'] ?? ''))) !!}
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="heritage" class="hist-legacy" aria-labelledby="heritage-title">
        <div class="hist-shell">
            <header data-reveal>
                <p class="hist-kicker">{{ __('ui.history_legacy_label') }}</p>
                <h2 id="heritage-title" class="hist-display hist-legacy__title">{{ __('ui.history_legacy_title') }}</h2>
                <p class="hist-legacy__desc">{{ __('ui.history_legacy_description') }}</p>
            </header>

            <dl class="hist-legacy__grid">
                @foreach ($legacyStats as $stat)
                    <div class="hist-legacy__item" data-reveal style="--reveal-delay: {{ $loop->index * 90 }}ms">
                        <dt>{{ $stat['label'] }}</dt>
                        <dd class="hist-display">
                            @if ($stat['counter'])
                                <span data-hist-counter
                                      data-target="{{ $stat['value'] }}"
                                      data-format="{{ $stat['format'] }}"
                                      data-prefix="{{ $stat['prefix'] }}"
                                      data-suffix="{{ $stat['suffix'] }}">{{ $stat['prefix'].$formatStat($stat['value'], $stat['format']).$stat['suffix'] }}</span>
                            @else
                                {{ $stat['value'] }}
                            @endif
                        </dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>


    <section class="hist-cta" aria-labelledby="histoire-suite">
        <div class="hist-cta__bg" aria-hidden="true">
            <img src="{{ $motifImage }}" alt="" width="500" height="500" loading="lazy" decoding="async">
            <div class="hist-cta__shade"></div>
        </div>

        <div class="hist-shell hist-cta__inner" data-reveal>
            <p class="hist-kicker">{{ __('ui.history_cta_label') }}</p>
            <h2 id="histoire-suite" class="hist-display hist-cta__title">{{ __('ui.history_cta_title') }}</h2>
            <p class="hist-cta__desc">{{ __('ui.history_cta_description') }}</p>

            <div class="hist-cta__actions">
                <a href="{{ route('contact.index') }}" class="hist-btn hist-btn--mint">
                    {{ __('ui.join_us') }}
                    <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M4 10h12M11 5l5 5-5 5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <a href="{{ route('projects.index') }}" class="hist-btn hist-btn--ghost">
                    {{ __('ui.history_cta_tertiary') }}
                </a>
            </div>
        </div>
    </section>

    <div class="hist-lightbox" data-hist-lightbox role="dialog" aria-modal="true"
         aria-label="{{ __('ui.gallery') }}" aria-hidden="true">
        <button type="button" class="hist-lightbox__btn hist-lightbox__close" data-hist-close
                aria-label="{{ __('ui.history_gallery_close') }}">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </button>

        <button type="button" class="hist-lightbox__btn hist-lightbox__prev" data-hist-step="-1"
                aria-label="{{ __('ui.previous_image') }}">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M15 5 8 12l7 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <button type="button" class="hist-lightbox__btn hist-lightbox__next" data-hist-step="1"
                aria-label="{{ __('ui.next_image') }}">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="m9 5 7 7-7 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <figure class="hist-lightbox__figure">
            <img data-hist-lightbox-image src="" alt="">
            <figcaption>
                <span data-hist-lightbox-title></span>
                <span data-hist-lightbox-counter
                      data-template="{{ __('ui.history_gallery_counter') }}"></span>
            </figcaption>
        </figure>
    </div>
</div>
@endsection

@push('scripts')
    @include('livewire.site.history.partials.scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-carousel]').forEach((carousel) => {
                const track = carousel.querySelector('[data-carousel-track]');
                const slides = carousel.querySelectorAll('[data-carousel-slide]');
                const prevBtn = carousel.querySelector('[data-carousel-prev]');
                const nextBtn = carousel.querySelector('[data-carousel-next]');
                const dots = carousel.querySelectorAll('[data-carousel-dot]');

                if (!track || slides.length <= 1) return;

                let current = 0;

                const goTo = (index) => {
                    current = (index + slides.length) % slides.length;
                    track.style.transform = `translateX(-${current * 100}%)`;
                    dots.forEach((dot, i) => {
                        dot.classList.toggle('w-5', i === current);
                        dot.classList.toggle('bg-white', i === current);
                        dot.classList.toggle('w-2', i !== current);
                        dot.classList.toggle('bg-white/50', i !== current);
                    });
                };

                prevBtn?.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    goTo(current - 1);
                });

                nextBtn?.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    goTo(current + 1);
                });

                dots.forEach((dot, i) => {
                    dot.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        goTo(i);
                    });
                });
            });
        });
    </script>
@endpush