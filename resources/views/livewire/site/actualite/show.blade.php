@extends('layouts.site')

@php
    $type = $type ?? 'actualite';
    $isArticle = $type === 'article';

    $indexRoute = $isArticle ? 'articles.index' : 'actualite.index';
    $showRoute = $isArticle ? 'articles.show' : 'actualite.show';

    $title = $post->getLocalized('title');
    $image = $post->image_url;
    $category = $post->category_label ?? ($isArticle ? __('ui.nav_articles') : __('ui.nav_news'));
    $author = $post->author?->name ?? __('ui.brand_name');
    $publishedAt = $post->published_at ?? $post->created_at;
    $date = $publishedAt?->translatedFormat('d F Y');
    $readTime = __('ui.reading_time', ['minutes' => $post->reading_time]);
    $content = $post->getTranslatedContent();
    $tags = collect((array) $post->tags)->filter()->values();
    $shareUrl = route($showRoute, $post->slug);
@endphp

@section('title', $title.' | '.config('app.name'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($post->getLocalized('excerpt') ?: $content), 155))

@section('content')
<main class="bg-[#FAFAFC] pb-20 pt-6">
    <div class="iu-container">

        {{-- Back Link --}}
        <div class="mb-8">
            <a href="{{ route($indexRoute) }}" class="inline-flex items-center gap-2 text-[0.8rem] font-extrabold uppercase tracking-widest text-[#4A5568] hover:text-[#0B1528] transition-colors">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ $isArticle ? __('ui.back_to_articles') : __('ui.back_to_news') }}
            </a>
        </div>

        {{-- Cover Image --}}
        <div class="relative w-full h-[320px] sm:h-[420px] md:h-[500px] lg:h-[560px] rounded-[2rem] overflow-hidden shadow-lg mb-10 md:mb-16">
            <x-cover-image :src="$image" :alt="$title" class="w-full h-full object-cover" icon-class="size-24" />
            @if($category)
                <div class="absolute bottom-6 md:bottom-10 left-6 md:left-10 z-10">
                    <span class="inline-flex items-center rounded-full bg-[#82E682] px-4 py-2 text-[0.7rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-md backdrop-blur-sm">
                        {{ $category }}
                    </span>
                </div>
            @endif
            <div class="absolute inset-0 bottom-0 top-1/2 bg-gradient-to-t from-black/40 to-transparent pointer-events-none"></div>
        </div>

        {{-- Content Layout --}}
        <div class="grid lg:grid-cols-[1fr_320px] gap-12 lg:gap-20">

            {{-- Main Content --}}
            <article class="lg:pl-4 mx-auto lg:mx-0 w-full min-w-0">
                <header class="mb-12">
                    <h1 class="text-[2.25rem] md:text-[3.2rem] font-bold text-[#0B1528] leading-[1.1] mb-8 break-words">
                        {{ $title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-6 md:gap-8 pt-6 border-t border-slate-200/80">
                        <div class="flex items-center gap-3">
                            <div class="size-12 rounded-full overflow-hidden flex-shrink-0 bg-[#0B1528] text-white flex items-center justify-center text-sm font-bold">
                                {{ \Illuminate\Support\Str::of($author)->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                            </div>
                            <div>
                                <div class="text-[0.95rem] font-bold text-[#0B1528]">{{ $author }}</div>
                                <div class="text-[0.8rem] font-medium text-slate-500">{{ __('ui.brand_name') }}</div>
                            </div>
                        </div>

                        @if($date)
                            <div class="flex items-center gap-2 text-slate-500 font-medium text-[0.85rem]">
                                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <time datetime="{{ $publishedAt?->toAtomString() }}">{{ $date }}</time>
                            </div>
                        @endif

                        <div class="flex items-center gap-2 text-slate-500 font-medium text-[0.85rem]">
                            <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $readTime }}
                        </div>
                    </div>
                </header>

                <div class="article-content max-w-none">
                    @if(filled($content))
                        {!! $content !!}
                    @else
                        <p class="text-slate-500">{{ $post->getLocalized('excerpt') }}</p>
                    @endif
                </div>

                {{-- Tags --}}
                @if($tags->isNotEmpty())
                    <div class="mt-16 pt-8 flex flex-wrap gap-3 border-t border-slate-200/80">
                        @foreach($tags as $tag)
                            <span class="inline-flex rounded-full bg-[#EAECEF] px-4 py-2 text-[0.8rem] font-bold text-slate-600">#{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                {{-- Mobile share --}}
                <div class="mt-10 lg:hidden">
                    <x-share-buttons :url="$shareUrl" :title="$title" :heading="$isArticle ? __('ui.share_article') : __('ui.share_news')" />
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="space-y-10 w-full min-w-0">
                <div class="hidden lg:block">
                    <x-share-buttons :url="$shareUrl" :title="$title" :heading="$isArticle ? __('ui.share_article') : __('ui.share_news')" />
                </div>

                {{-- Related content --}}
                <div>
                    <h3 class="text-[1.1rem] font-bold text-[#0B1528] mb-6">
                        {{ $isArticle ? __('ui.related_articles') : __('ui.related_news') }}
                    </h3>

                    @if($relatedPosts->isNotEmpty())
                        <div class="flex flex-col gap-4">
                            @foreach($relatedPosts as $related)
                                <a href="{{ route($showRoute, $related->slug) }}"
                                   class="bg-[#F8F9FA] rounded-[1.5rem] overflow-hidden transition hover:bg-[#EAECEF] hover:shadow-sm group border border-slate-100 block">
                                    <div class="h-32 w-full overflow-hidden bg-slate-100">
                                        <x-cover-image :src="$related->image_url" :alt="$related->getLocalized('title')" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" icon-class="size-8" />
                                    </div>
                                    <div class="p-5">
                                        @if($related->category_label)
                                            <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-2 block">{{ $related->category_label }}</span>
                                        @endif
                                        <h4 class="text-[1rem] font-bold text-[#0A1A3A] leading-[1.3] group-hover:text-[#214f95] transition-colors line-clamp-2">
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
                        <p class="rounded-2xl bg-[#F8F9FA] border border-slate-100 p-6 text-sm font-medium text-slate-500">
                            {{ __('ui.no_related_content') }}
                        </p>
                    @endif
                </div>

                {{-- Newsletter --}}
                <div class="bg-[#0A1633] rounded-[2rem] p-8 relative overflow-hidden shadow-[0_20px_40px_rgba(0,0,0,0.1)]">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-[#162D5A] rounded-full mix-blend-screen filter blur-[40px] opacity-70 pointer-events-none"></div>
                    <div class="relative z-10">
                        <h3 class="text-[1.3rem] font-bold text-white leading-tight mb-3">{{ __('ui.articles_newsletter_title') }}</h3>
                        <p class="text-[0.85rem] text-slate-400 font-medium mb-6 leading-relaxed">
                            {{ __('ui.articles_newsletter_description') }}
                        </p>
                        @livewire('newsletter.subscribe-form', ['stack' => true])
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>
@endsection
