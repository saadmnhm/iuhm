@extends('layouts.site')

@php
    $indexRoute = $section.'.index';
    $showRoute = $section.'.show';
@endphp

@section('seo')
    <x-seo :data="\App\Support\Seo\SeoData::forPage(
        title: $meta['label_text'],
        description: $meta['description_text'] ?: __('ui.footer_tagline'),
        breadcrumbs: [
            ['name' => __('ui.home'), 'url' => route('home')],
            ['name' => $meta['label_text'], 'url' => route($indexRoute)],
        ],
        schemaType: 'CollectionPage',
    )" />
@endsection

@section('content')
<main class="bg-[#FAFAFC] pb-24">
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-white pt-14 pb-16 lg:pt-20 lg:pb-24">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_0%_0%,_#fdfcff_0%,_transparent_60%)]"></div>
        <div class="iu-container relative z-10">
            <div class="grid items-center gap-12 lg:grid-cols-[1.15fr_1fr] lg:gap-16">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 rounded-full bg-[#82E682] px-4 py-1.5 text-[0.8rem] font-bold tracking-wide text-[#0a471b]">
                        <i class="{{ $meta['icon'] }} text-base"></i>
                        {{ $meta['badge_text'] }}
                    </span>

                    <h1 class="mt-7 text-[3.25rem] font-bold leading-[1.05] tracking-tight text-[#0B1528] sm:text-[4rem]">
                        {{ $meta['label_text'] }}
                    </h1>

                    <p class="mt-6 text-[1.05rem] font-medium leading-relaxed text-slate-600">
                        {{ $meta['description_text'] ?: __('ui.footer_tagline') }}
                    </p>

                    <div class="mt-9 flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center gap-2 rounded-full bg-[#0B1528] px-5 py-2.5 text-[0.8rem] font-bold text-white">
                            <i class="ri-file-list-3-line text-base"></i>
                            {{ $items->total() }} {{ $meta['label_text'] }}
                        </span>
                        <a href="{{ route('contact.index') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-[0.8rem] font-bold text-slate-600 transition hover:border-[#82E682] hover:text-[#10752d]">
                            {{ __('ui.contact_page_label') ?? __('ui.join_us') }}
                            <i class="ri-arrow-right-line text-base"></i>
                        </a>
                    </div>
                </div>

                <div class="relative flex justify-center lg:justify-end">
                    <div class="relative flex aspect-square w-[300px] items-center justify-center overflow-hidden rounded-[2.5rem] bg-[#0f172a] p-10 shadow-lg shadow-slate-300/30 sm:w-[360px] lg:w-[400px]">
                        <div class="absolute inset-0 bg-[#0f172a]"></div>
                        <div class="relative z-10 flex size-48 flex-col items-center justify-center gap-3 rounded-[2rem] border border-slate-700/50 bg-[#141d2f]/90 text-center shadow-[0_5px_40px_rgb(0,0,0,0.4)] backdrop-blur">
                            <i class="{{ $meta['icon'] }} text-6xl text-[#82E682]"></i>
                            <span class="text-[0.7rem] font-extrabold uppercase tracking-[0.35em] text-slate-300">{{ $meta['label'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Filters --}}
    <section class="sticky top-20 z-40 border-y border-slate-200/70 bg-[#FAFAFC]/95 py-5 backdrop-blur-md">
        <div class="iu-container flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route($indexRoute, ['search' => $search ?: null]) }}"
                   class="rounded-full px-5 py-2.5 text-[0.9rem] font-bold shadow-sm transition {{ blank($pageSlug) ? 'bg-[#0B1528] text-white' : 'bg-[#EAECEF] text-slate-700 hover:bg-[#DEDFE2]' }}">
                    {{ __('ui.all_posts') }}
                </a>
                @foreach($categories as $category)
                    @php
                        $catKey = 'ui.category_'.$category;
                        $catLabel = __($catKey) === $catKey ? \Illuminate\Support\Str::headline($category) : __($catKey);
                    @endphp
                    <a href="{{ route($indexRoute, ['category' => $category, 'search' => $search ?: null]) }}"
                       class="rounded-full px-5 py-2.5 text-[0.9rem] font-bold shadow-sm transition {{ $pageSlug === $category ? 'bg-[#0B1528] text-white' : 'bg-[#EAECEF] text-slate-700 hover:bg-[#DEDFE2]' }}">
                        {{ $catLabel }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route($indexRoute) }}" class="relative w-full md:w-72">
                @if($pageSlug)
                    <input type="hidden" name="category" value="{{ $pageSlug }}">
                @endif
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <i class="ri-search-line text-slate-400"></i>
                </div>
                <input type="search" name="search" value="{{ $search }}"
                       class="block w-full rounded-2xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm font-medium text-slate-900 placeholder:text-slate-400 shadow-sm transition focus:bg-white focus:ring-2 focus:ring-[#82E682]"
                       placeholder="{{ __('ui.search_placeholder') }}">
            </form>
        </div>
    </section>

    {{-- Grid + sidebar --}}
    <section class="py-10 lg:py-14">
        <div class="iu-container">
            <div class="grid gap-12 lg:grid-cols-[1fr_320px]">
                <div>
                    <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-2">
                        @forelse($items as $item)
                            <article class="group relative flex flex-col overflow-hidden rounded-[1.75rem] border border-slate-100 bg-[#F8F9FA] shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                                <div class="relative h-56 w-full shrink-0 overflow-hidden bg-slate-100">
                                    @if($item->category_label)
                                        <span class="absolute left-4 top-4 z-30 rounded-full bg-[#82E682] px-3 py-1 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-sm">
                                            {{ $item->category_label }}
                                        </span>
                                    @endif
                                    <x-image-carousel :images="$item->carousel_images"
                                                      :alt="$item->getLocalized('title')"
                                                      aspect="h-full"
                                                      rounded=""
                                                      class="h-full"
                                                      :show-counter="false" />
                                </div>

                                <div class="flex flex-1 flex-col p-7">
                                    <h2 class="mb-3 text-[1.2rem] font-bold leading-tight text-[#0B1528] transition-colors group-hover:text-[#10752d]">
                                        <a href="{{ route($showRoute, $item->slug) }}" class="focus:outline-none">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            {{ $item->getLocalized('title') }}
                                        </a>
                                    </h2>
                                    <p class="mb-6 flex-1 text-[0.92rem] leading-relaxed text-slate-500 line-clamp-3">
                                        {{ $item->getLocalized('excerpt') ?: \Illuminate\Support\Str::limit(strip_tags($item->getLocalized('content') ?? ''), 140) }}
                                    </p>
                                    <div class="mt-auto flex items-center justify-between border-t border-slate-200 pt-5">
                                        <span class="text-[0.78rem] font-bold text-slate-500">
                                            {{ $item->author?->name ?? __('ui.brand_name') }}
                                        </span>
                                        <span class="text-[0.75rem] font-semibold text-slate-400">
                                            {{ $item->published_at?->translatedFormat('d M Y') ?? '' }}
                                        </span>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full py-20 text-center">
                                <div class="mb-4 inline-flex size-16 items-center justify-center rounded-full bg-[#EAECEF]">
                                    <i class="{{ $meta['icon'] }} text-2xl text-slate-400"></i>
                                </div>
                                <h3 class="mb-2 text-lg font-bold text-[#0B1528]">{{ __('ui.section_empty_title') }}</h3>
                                <p class="font-medium text-slate-500">{{ __('ui.section_empty_description') }}</p>
                            </div>
                        @endforelse
                    </div>

                    @if($items->hasPages())
                        <div class="mt-14 flex justify-center">
                            {{ $items->links() }}
                        </div>
                    @endif
                </div>

                <aside class="space-y-6">
                    <div class="rounded-[2rem] bg-[#0c132b] p-8 text-white shadow-xl shadow-slate-300/40">
                        <h3 class="mb-6 text-[1.15rem] font-bold">{{ $meta['label_text'] }}</h3>
                        <p class="mb-6 text-[0.85rem] leading-relaxed text-slate-400">
                            {{ $meta['description_text'] ?: __('ui.footer_tagline') }}
                        </p>

                        <div class="space-y-4">
                            @forelse($latestItems as $latest)
                                <a href="{{ route($showRoute, $latest->slug) }}" class="group block border-b border-white/10 pb-4 last:border-0 last:pb-0">
                                    <span class="text-[0.6rem] font-extrabold uppercase tracking-widest text-[#86e288]">
                                        #{{ $latest->category_label ?? $meta['label'] }}
                                    </span>
                                    <h4 class="mt-1 text-sm font-bold leading-snug text-white transition group-hover:text-[#86e288] line-clamp-2">
                                        {{ $latest->getLocalized('title') }}
                                    </h4>
                                </a>
                            @empty
                                <p class="text-sm text-white/60">{{ __('ui.section_empty_title') }}</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-[2rem] bg-[#9BFA9B] p-8 shadow-xl shadow-slate-300/30">
                        <h3 class="text-[1.1rem] font-bold text-[#0f172a]">{{ __('ui.navigation') }}</h3>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('actualite.index') }}" class="rounded-full bg-white/70 px-4 py-2 text-[0.75rem] font-bold text-[#0a471b] transition hover:bg-white">{{ __('ui.nav_news') }}</a>
                            <a href="{{ route('articles.index') }}" class="rounded-full bg-white/70 px-4 py-2 text-[0.75rem] font-bold text-[#0a471b] transition hover:bg-white">{{ __('ui.nav_articles') }}</a>
                            <a href="{{ route('projects.index') }}" class="rounded-full bg-white/70 px-4 py-2 text-[0.75rem] font-bold text-[#0a471b] transition hover:bg-white">{{ __('ui.nav_projects') }}</a>
                            @if($section !== \App\Models\SectionItem::SECTION_CLC)
                                <a href="{{ route('clc.index') }}" class="rounded-full bg-white/70 px-4 py-2 text-[0.75rem] font-bold text-[#0a471b] transition hover:bg-white">{{ __('ui.nav_clc') }}</a>
                            @endif
                            @if($section !== \App\Models\SectionItem::SECTION_ZETTAT)
                                <a href="{{ route('zettat.index') }}" class="rounded-full bg-white/70 px-4 py-2 text-[0.75rem] font-bold text-[#0a471b] transition hover:bg-white">{{ __('ui.nav_zettat') }}</a>
                            @endif
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>
@endsection
