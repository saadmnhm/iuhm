@extends('layouts.site')

@php
    $indexRoute = $section.'.index';
    $showRoute = $section.'.show';

    $title = $item->getLocalized('title');
    $category = $item->category_label ?? $meta['label_text'];
    $author = $item->author?->name ?? __('ui.brand_name');
    $publishedAt = $item->published_at ?? $item->created_at;
    $date = $publishedAt?->translatedFormat('d F Y');
    $readTime = __('ui.reading_time', ['minutes' => $item->reading_time]);
    $content = $item->getTranslatedContent();
    $tags = collect((array) $item->tags)->filter()->values();
    $shareUrl = route($showRoute, $item->slug);
@endphp

@section('seo')
    <x-seo :data="$item" />
@endsection

@section('content')
<main class="bg-[#FAFAFC] pb-20 pt-6">
    <div class="iu-container">

        {{-- Back Link --}}
        <div class="mb-8">
            <a href="{{ route($indexRoute) }}" class="inline-flex items-center gap-2 text-[0.8rem] font-extrabold uppercase tracking-widest text-[#4A5568] transition-colors hover:text-[#0B1528]">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('ui.section_back_to') }} {{ $meta['label_text'] }}
            </a>
        </div>

        {{-- Image slider --}}
        <div class="mb-10 md:mb-16">
            <x-image-carousel :images="$item->carousel_images"
                              :alt="$title"
                              aspect="h-[320px] sm:h-[420px] md:h-[500px] lg:h-[560px]"
                              class="shadow-lg"
                              :show-thumbnails="count($item->carousel_images) > 1">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                @if($category)
                    <div class="absolute bottom-6 left-6 z-30 md:bottom-10 md:left-10">
                        <span class="inline-flex items-center rounded-full bg-[#82E682] px-4 py-2 text-[0.7rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-md backdrop-blur-sm">
                            {{ $category }}
                        </span>
                    </div>
                @endif
            </x-image-carousel>
        </div>

        {{-- Content Layout --}}
        <div class="grid gap-12 lg:grid-cols-[1fr_320px] lg:gap-20">

            {{-- Main Content --}}
            <article class="mx-auto w-full min-w-0 lg:mx-0 lg:pl-4">
                <header class="mb-12">
                    <!-- <span class="mb-5 inline-flex items-center gap-2 rounded-full bg-[#EAECEF] px-4 py-1.5 text-[0.7rem] font-extrabold uppercase tracking-widest text-slate-600">
                        <i class="{{ $meta['icon'] }} text-base text-[#10752d]"></i>
                        {{ $meta['label_text'] }}
                    </span> -->

                    <h1 class="mb-8 break-words text-[2.25rem] font-bold leading-[1.1] text-[#0B1528] md:text-[3.2rem]">
                        {{ $title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-6 border-t border-slate-200/80 pt-6 md:gap-8">
                        <div class="flex items-center gap-3">
                            <div class="flex size-12 shrink-0 items-center justify-center overflow-hidden rounded-full bg-[#0B1528] text-sm font-bold text-white">
                                {{ \Illuminate\Support\Str::of($author)->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                            </div>
                            <div>
                                <div class="text-[0.95rem] font-bold text-[#0B1528]">{{ $author }}</div>
                                <div class="text-[0.8rem] font-medium text-slate-500">{{ __('ui.brand_name') }}</div>
                            </div>
                        </div>

                        @if($date)
                            <div class="flex items-center gap-2 text-[0.85rem] font-medium text-slate-500">
                                <i class="ri-calendar-line text-base"></i>
                                <time datetime="{{ $publishedAt?->toAtomString() }}">{{ $date }}</time>
                            </div>
                        @endif

                        <div class="flex items-center gap-2 text-[0.85rem] font-medium text-slate-500">
                            <i class="ri-time-line text-base"></i>
                            {{ $readTime }}
                        </div>
                    </div>
                </header>

                <div class="article-content max-w-none">
                    @if(filled($content))
                        {!! $content !!}
                    @else
                        <p class="text-slate-500">{{ $item->getLocalized('excerpt') }}</p>
                    @endif
                </div>

                {{-- Tags --}}
                @if($tags->isNotEmpty())
                    <div class="mt-16 flex flex-wrap gap-3 border-t border-slate-200/80 pt-8">
                        @foreach($tags as $tag)
                            <span class="inline-flex rounded-full bg-[#EAECEF] px-4 py-2 text-[0.8rem] font-bold text-slate-600">#{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="mt-10 lg:hidden">
                    <x-share-buttons :url="$shareUrl" :title="$title" :heading="__('ui.share_article')" />
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="w-full min-w-0 space-y-10">
                <div class="hidden lg:block">
                    <x-share-buttons :url="$shareUrl" :title="$title" :heading="__('ui.share_project')" />
                </div>

                <!-- <div>
                    <h3 class="mb-6 text-[1.1rem] font-bold text-[#0B1528]">{{ __('ui.related_projects') }}</h3>

                    @if($relatedItems->isNotEmpty())
                        <div class="flex flex-col gap-4">
                            @foreach($relatedItems as $related)
                                <a href="{{ route($showRoute, $related->slug) }}"
                                   class="group block overflow-hidden rounded-[1.5rem] border border-slate-100 bg-[#F8F9FA] transition hover:bg-[#EAECEF] hover:shadow-sm">
                                    <div class="h-32 w-full overflow-hidden bg-slate-100">
                                        <x-cover-image :src="$related->image_url" :alt="$related->getLocalized('title')" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" icon-class="size-8" />
                                    </div>
                                    <div class="p-5">
                                        @if($related->category_label)
                                            <span class="mb-2 block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">{{ $related->category_label }}</span>
                                        @endif
                                        <h4 class="text-[1rem] font-bold leading-[1.3] text-[#0A1A3A] transition-colors group-hover:text-[#214f95] line-clamp-2">
                                            {{ $related->getLocalized('title') }}
                                        </h4>
                                        @if($related->published_at)
                                            <time class="mt-2 block text-[0.72rem] font-semibold text-slate-400" datetime="{{ $related->published_at->toAtomString() }}">
                                                {{ $related->published_at->translatedFormat('d M Y') }}
                                            </time>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="rounded-2xl border border-slate-100 bg-[#F8F9FA] p-6 text-sm font-medium text-slate-500">
                            {{ __('ui.no_related_content') }}
                        </p>
                    @endif
                </div> -->

              
            </aside>
        </div>
    </div>
</main>
@endsection
