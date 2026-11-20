@extends('layouts.site')

@push('head')
    <script>
        document.documentElement.classList.add('has-js');
    </script>
@endpush

@push('styles')
    @include('livewire.site.history.partials.styles')
@endpush

@php
    $locale = app()->getLocale();

    $localizedField = static function ($value) use ($locale): ?string {
        if (is_string($value)) {
            return $value !== '' ? $value : null;
        }

        if (! is_array($value)) {
            return null;
        }

        $value = $value[$locale] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    };

    $pageTitle = $localizedField($page?->title) ?: __('ui.history_page_title');
    $pageLead = $localizedField($page?->excerpt) ?: __('ui.history_hero_lead');
    $pageBody = $localizedField($page?->content) ?: '<p>'.e(__('ui.home_history_description')).'</p>';

    $atelierImage = asset('assets/web_site/images/histoire/atelier.jpg');
    $jardinImage = asset('assets/web_site/images/histoire/jardin.jpg');
    $presidentImage = asset('assets/web_site/images/president.jpg');
    $motifImage = asset('assets/web_site/images/assa_ct.png');

    $chapters = [
        [
            'year' => '2002',
            'label' => __('ui.history_chapter_origin_label'),
            'title' => __('ui.history_chapter_origin_title'),
            'text' => __('ui.home_director_paragraph_1'),
            'image' => $atelierImage,
            'alt' => __('ui.history_chapter_atelier_alt'),
            'caption' => __('ui.history_chapter_atelier_caption'),
            'tags' => [__('ui.home_service_education'), __('ui.category_jeunesse')],
        ],
        [
            'year' => '2009',
            'label' => __('ui.history_chapter_founding_label'),
            'title' => __('ui.history_chapter_founding_title'),
            'text' => __('ui.home_history_description'),
            'image' => $jardinImage,
            'alt' => __('ui.history_chapter_jardin_alt'),
            'caption' => __('ui.history_chapter_jardin_caption'),
            'tags' => [__('ui.category_solidarite'), __('ui.category_urbanisme')],
        ],
        [
            'year' => __('ui.history_chapter_today_label'),
            'label' => __('ui.history_pillars_label'),
            'title' => __('ui.history_chapter_today_title'),
            'text' => __('ui.history_chapter_today_text'),
            'image' => null,
            'alt' => null,
            'caption' => null,
            'tags' => [
                __('ui.home_service_education'),
                __('ui.home_service_health'),
                __('ui.category_culture'),
                __('ui.category_urbanisme'),
            ],
        ],
    ];

    $legacyStats = [
        ['value' => 10000, 'format' => 'compact', 'prefix' => '', 'suffix' => '+', 'label' => __('ui.home_stats_people_helped'), 'counter' => true],
        ['value' => 50, 'format' => 'plain', 'prefix' => '', 'suffix' => '+', 'label' => __('ui.home_stats_active_programs'), 'counter' => true],
        ['value' => 70, 'format' => 'plain', 'prefix' => '', 'suffix' => '+', 'label' => __('ui.home_stats_partenair'), 'counter' => true],
        ['value' => '2002', 'format' => 'plain', 'prefix' => '', 'suffix' => '', 'label' => __('ui.history_hero_origin_label'), 'counter' => false],
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

    {{-- ── Hero ───────────────────────────────────────────────────────────── --}}
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

            <div class="hist-hero__grid">
                <div class="hist-hero__copy">
                    <p class="hist-kicker hist-hero__eyebrow">{{ __('ui.history_hero_eyebrow') }}</p>

                    <h1 id="histoire-title" class="hist-display hist-hero__title">{{ $pageTitle }}</h1>

                    <p class="hist-hero__lead">{{ $pageLead }}</p>

                    <div class="hist-hero__actions">
                        <a href="#chronologie" class="hist-btn hist-btn--mint">
                            {{ __('ui.history_hero_cta_timeline') }}
                            <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M4 10h12M11 5l5 5-5 5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                        <a href="{{ route('projects.index') }}" class="hist-btn hist-btn--ghost">
                            {{ __('ui.history_hero_cta_projects') }}
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

                <figure class="hist-plate">
                    <div class="hist-plate__frame">
                        <img src="{{ $atelierImage }}" alt="{{ __('ui.history_chapter_atelier_alt') }}"
                             width="1024" height="576" fetchpriority="high" decoding="async">
                        <span class="hist-display hist-plate__year" aria-hidden="true">2009</span>
                    </div>
                    <figcaption class="hist-plate__caption">
                        <span>01</span>
                        {{ __('ui.history_hero_plate_caption') }}
                    </figcaption>
                </figure>
            </div>
        </div>

        <a href="#recit" class="hist-scroll" aria-label="{{ __('ui.history_hero_scroll') }}">
            {{ __('ui.history_hero_scroll') }}
            <span aria-hidden="true"></span>
        </a>
    </section>

    {{-- ── Editorial introduction ─────────────────────────────────────────── --}}
    <section id="recit" class="hist-section hist-section--paper">
        <div class="hist-shell">
            <div class="hist-intro">
                <div class="hist-intro__aside" data-reveal>
                    <p class="hist-kicker">{{ __('ui.history_intro_label') }}</p>
                    <h2 class="hist-display hist-intro__title">{{ __('ui.history_intro_title') }}</h2>
                    <p class="hist-intro__note">{{ __('ui.history_intro_note') }}</p>
                </div>

                <div class="hist-prose" data-reveal style="--reveal-delay: 120ms">
                    {!! $pageBody !!}
                </div>
            </div>
        </div>
    </section>

    {{-- ── Chronology ─────────────────────────────────────────────────────── --}}
    <section id="chronologie" class="hist-section hist-section--paper2">
        <div class="hist-shell">
            <header class="hist-head">
                <div>
                    <p class="hist-kicker">{{ __('ui.history_timeline_label') }}</p>
                    <h2 class="hist-display hist-head__title">{{ __('ui.history_timeline_title') }}</h2>
                </div>
                <p class="hist-head__desc">{{ __('ui.history_timeline_description') }}</p>
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
                            <p class="hist-chapter__text">{{ $chapter['text'] }}</p>

                            @if ($chapter['image'])
                                <figure class="hist-chapter__figure">
                                    <img src="{{ $chapter['image'] }}" alt="{{ $chapter['alt'] }}"
                                         width="1024" height="640" loading="lazy" decoding="async">
                                    <figcaption>{{ $chapter['caption'] }}</figcaption>
                                </figure>
                            @endif

                            @if (! empty($chapter['tags']))
                                <ul class="hist-tags">
                                    @foreach ($chapter['tags'] as $tag)
                                        <li>{{ $tag }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── President's story ──────────────────────────────────────────────── --}}
    <!-- <section id="president" class="hist-section hist-section--paper">
        <div class="hist-shell">
            <div class="hist-president">
                <figure class="hist-president__media" data-reveal>
                    <div class="hist-president__frame">
                        <img src="{{ $presidentImage }}" alt="{{ __('ui.home_director_name') }}"
                             width="1080" height="1350" loading="lazy" decoding="async">
                        <span class="hist-president__tag">{{ __('ui.history_story_note') }}</span>
                    </div>
                    <figcaption class="hist-president__caption">
                        <strong>{{ __('ui.home_director_name') }}</strong>
                        <span>{{ __('ui.home_director_role') }}</span>
                    </figcaption>
                </figure>

                <div class="hist-president__body" data-reveal style="--reveal-delay: 120ms">
                    <p class="hist-kicker">{{ __('ui.history_story_label') }}</p>
                    <h2 class="hist-display">{{ __('ui.home_director_title') }}</h2>

                    <blockquote class="hist-quote">« {{ __('ui.home_director_quote') }} »</blockquote>

                    <p>{{ __('ui.home_director_paragraph_1') }}</p>
                    <p>{{ __('ui.home_director_paragraph_2') }}</p>

                    <div class="hist-president__sign">
                        <svg viewBox="0 0 112 48" fill="none" aria-hidden="true">
                            <path d="M4 34c8-14 14-22 18-24 5-3 5 6 2 14s-7 16-3 16c4 1 10-9 15-19s9-15 11-11c2 5-4 18-1 20 4 2 8-6 12-12 3-5 5-6 6-3 2 5-1 12 2 13 4 2 8-3 13-9 4-5 7-7 9-5"
                                  stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p>
                            <strong>{{ __('ui.home_director_name') }}</strong>
                            {{ __('ui.home_director_role') }}
                        </p>
                    </div>

                    <a href="{{ route('contact.index') }}" class="hist-btn hist-btn--outline" style="margin-top: 1.75rem">
                        {{ __('ui.history_story_cta') }}
                        <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M4 10h12M11 5l5 5-5 5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section> -->

    {{-- ── Legacy in figures ──────────────────────────────────────────────── --}}
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

    {{-- ── Gallery ────────────────────────────────────────────────────────── --}}
    <section id="galerie" class="hist-section hist-section--paper2">
        <div class="hist-shell">
            <header class="hist-head">
                <div data-reveal>
                    <p class="hist-kicker">{{ __('ui.history_gallery_label') }}</p>
                    <h2 class="hist-display hist-head__title">{{ __('ui.history_gallery_title') }}</h2>
                </div>
                <p class="hist-head__desc" data-reveal style="--reveal-delay: 120ms">{{ __('ui.history_gallery_description') }}</p>
            </header>

            <div class="hist-gallery">
                @foreach ($gallery as $index => $item)
                    <button type="button"
                            class="hist-gallery__item"
                            data-hist-gallery="{{ $index }}"
                            data-src="{{ $item['src'] }}"
                            data-alt="{{ $item['alt'] }}"
                            data-title="{{ $item['title'] }}"
                            aria-label="{{ __('ui.history_gallery_open') }} : {{ $item['title'] }}"
                            data-reveal
                            style="--reveal-delay: {{ $index * 110 }}ms">
                        <img src="{{ $item['src'] }}" alt="{{ $item['alt'] }}" loading="lazy" decoding="async">
                        <span class="hist-gallery__overlay">
                            <span class="hist-gallery__title">{{ $item['title'] }}</span>
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M9 3H3v6M15 21h6v-6M21 9V3h-6M3 15v6h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Closing invitation ─────────────────────────────────────────────── --}}
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

    {{-- ── Lightbox ───────────────────────────────────────────────────────── --}}
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
@endpush
