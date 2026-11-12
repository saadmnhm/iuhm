@extends('layouts.site')

@php
    $title = $project->getLocalized('title');
    $image = $project->featured_image_url;
    $publishedAt = $project->published_at ?? $project->created_at;
    $date = $publishedAt?->translatedFormat('d F Y');
    $shareUrl = route('projects.show', $project->slug);

    $rawDescription = (string) $project->getLocalized('description');
    $description = $rawDescription === strip_tags($rawDescription)
        ? nl2br(e($rawDescription))
        : $rawDescription;

    $gallery = collect((array) $project->gallery)
        ->filter()
        ->map(fn ($path) => $project->resolveMediaUrl($path));
@endphp

@section('title', $title.' | '.config('app.name'))

@section('content')
<main class="bg-[#FAFAFC] pb-20 pt-6">
    <div class="iu-container">

        {{-- Back Link --}}
        <div class="mb-8">
            <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 text-[0.8rem] font-extrabold uppercase tracking-widest text-[#4A5568] hover:text-[#0B1528] transition-colors">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('ui.back_to_projects') }}
            </a>
        </div>

        {{-- Featured Image --}}
        <div class="relative w-full h-[320px] sm:h-[420px] md:h-[500px] lg:h-[560px] rounded-[2rem] overflow-hidden shadow-lg mb-10 md:mb-16">
            <x-cover-image :src="$image" :alt="$title" class="w-full h-full object-cover" icon-class="size-24" />
            <span class="absolute bottom-6 md:bottom-10 left-6 md:left-10 z-10 inline-flex items-center rounded-full bg-[#82E682] px-4 py-2 text-[0.7rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-md backdrop-blur-sm">
                {{ $project->status_label_localized }}
            </span>
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
                        @if($date)
                            <div class="flex items-center gap-2 text-slate-500 font-medium text-[0.85rem]">
                                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <time datetime="{{ $publishedAt?->toAtomString() }}">{{ $date }}</time>
                            </div>
                        @endif

                        @if($project->location)
                            <div class="flex items-center gap-2 text-slate-500 font-medium text-[0.85rem]">
                                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $project->location }}
                            </div>
                        @endif

                        @if($project->partner)
                            <div class="flex items-center gap-2 text-slate-500 font-medium text-[0.85rem]">
                                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4Zm6-4a3 3 0 11-3-3" />
                                </svg>
                                {{ $project->partner }}
                            </div>
                        @endif

                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-bold {{ $project->getStatusColor() }}">
                            {{ $project->status_label_localized }}
                        </span>
                    </div>
                </header>

                <div class="article-content max-w-none">
                    {!! $description !!}
                </div>

                {{-- Gallery --}}
                @if($gallery->isNotEmpty())
                    <div class="mt-16">
                        <h2 class="text-2xl md:text-[2rem] font-bold text-[#0B1528] leading-[1.2] mb-6">{{ __('ui.gallery') }}</h2>
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($gallery as $galleryImage)
                                <a href="{{ $galleryImage }}" target="_blank" rel="noopener" class="block rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow">
                                    <img src="{{ $galleryImage }}" alt="{{ $title }}" loading="lazy" class="w-full h-64 object-cover hover:scale-105 transition-transform duration-300">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- External link --}}
                @if($project->external_link)
                    <div class="mt-12">
                        <a href="{{ $project->external_link }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 rounded-full bg-[#0B1528] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#162D5A]">
                            {{ __('ui.learn_more') }}
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                @endif

                {{-- Tags --}}
                @if($project->tags && count($project->tags) > 0)
                    <div class="mt-16 pt-8 flex flex-wrap gap-3 border-t border-slate-200/80">
                        @foreach($project->tags as $tag)
                            <span class="inline-flex rounded-full bg-[#EAECEF] px-4 py-2 text-[0.8rem] font-bold text-slate-600">#{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                {{-- Mobile share --}}
                <div class="mt-10 lg:hidden">
                    <x-share-buttons :url="$shareUrl" :title="$title" :heading="__('ui.share_project')" />
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="space-y-10 w-full min-w-0">
                <div class="hidden lg:block">
                    <x-share-buttons :url="$shareUrl" :title="$title" :heading="__('ui.share_project')" />
                </div>

                {{-- Related Projects --}}
                <div>
                    <h3 class="text-[1.1rem] font-bold text-[#0B1528] mb-6">{{ __('ui.related_projects') }}</h3>

                    @if($relatedProjects->isNotEmpty())
                        <div class="flex flex-col gap-4">
                            @foreach($relatedProjects as $related)
                                <a href="{{ route('projects.show', $related->slug) }}"
                                   class="bg-[#F8F9FA] rounded-[1.5rem] overflow-hidden transition hover:bg-[#EAECEF] hover:shadow-sm group border border-slate-100 block">
                                    <div class="h-32 w-full overflow-hidden bg-slate-100">
                                        <x-cover-image :src="$related->featured_image_url" :alt="$related->getLocalized('title')" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" icon-class="size-8" />
                                    </div>
                                    <div class="p-5">
                                        <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-2 block">{{ $related->status_label_localized }}</span>
                                        <h4 class="text-[1rem] font-bold text-[#0A1A3A] leading-[1.3] group-hover:text-[#214f95] transition-colors line-clamp-2">{{ $related->getLocalized('title') }}</h4>
                                        @if($related->location)
                                            <p class="text-[0.8rem] text-slate-500 mt-2">{{ $related->location }}</p>
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
            </aside>
        </div>
    </div>
</main>
@endsection
