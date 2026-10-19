@extends('layouts.site')

@section('title', $project->getLocalized('title') .' | '.config('app.name'))

@section('content')
    <article class="bg-white">
        {{-- Hero --}}
        @if($project->featured_image)
            <section class="relative overflow-hidden h-[400px] sm:h-[500px]">
                <img src="{{ $project->featured_image }}" alt="{{ $project->getLocalized('title') }}" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="relative h-full flex items-end">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-12">
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            <span class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-sm font-medium text-slate-900 backdrop-blur">
                                {{ $project->getStatusLabel() }}
                            </span>
                            @if($project->is_featured)
                                <span class="inline-flex items-center rounded-full bg-yellow-400 px-3 py-1 text-sm font-medium text-slate-900">{{ __('ui.featured') }}</span>
                            @endif
                        </div>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white">{{ $project->getLocalized('title') }}</h1>
                    </div>
                </div>
            </section>
        @else
            <section class="bg-[#FBF8FD] py-20 sm:py-24">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-sm font-medium text-slate-900 backdrop-blur">
                            {{ $project->getStatusLabel() }}
                        </span>
                        @if($project->is_featured)
                            <span class="inline-flex items-center rounded-full bg-yellow-400 px-3 py-1 text-sm font-medium text-slate-900">{{ __('ui.featured') }}</span>
                        @endif
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#16254d]">{{ $project->getLocalized('title') }}</h1>
                </div>
            </section>
        @endif

        {{-- Content --}}
        <section class="bg-white py-20 sm:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-3 gap-12">
                    <div class="lg:col-span-2 space-y-8">
                        {{-- Description --}}
                        <div class="prose prose-slate max-w-none">
                            @foreach(['en', 'fr', 'ar'] as $lang)
                                @if($project->getLocalized('description', $lang))
                                    <div class="mb-8">
                                        <h2 class="text-xl font-bold text-[#16254d] mb-4 capitalize">{{ $lang }} ({{ ['en' => 'English', 'fr' => 'French', 'ar' => 'Arabic'][$lang] }})</h2>
                                        <div class="whitespace-pre-wrap text-slate-700">{{ $project->getLocalized('description', $lang) }}</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{-- Gallery --}}
                        @if($project->gallery && count($project->gallery) > 0)
                            <div>
                                <h2 class="text-2xl font-bold text-[#16254d] mb-6">{{ __('ui.gallery') }}</h2>
                                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach($project->gallery as $image)
                                        <a href="{{ $image }}" target="_blank" class="block rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow">
                                            <img src="{{ $image }}" alt="{{ $project->getLocalized('title') }}" class="w-full h-64 object-cover hover:scale-105 transition-transform duration-300">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Info Card --}}
                        <div class="bg-[#FBF8FD] rounded-2xl p-6">
                            <h3 class="text-lg font-semibold text-[#16254d] mb-4">{{ __('ui.project_details') }}</h3>
                            <dl class="space-y-4">
                                @if($project->location)
                                    <div>
                                        <dt class="text-sm text-slate-500">{{ __('ui.location') }}</dt>
                                        <dd class="text-sm font-medium text-slate-900 mt-1 flex items-center gap-2">
                                            <svg class="size-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $project->location }}
                                        </dd>
                                    </div>
                                @endif
                                
                                @if($project->partner)
                                    <div>
                                        <dt class="text-sm text-slate-500">{{ __('ui.partner') }}</dt>
                                        <dd class="text-sm font-medium text-slate-900 mt-1">{{ $project->partner }}</dd>
                                    </div>
                                @endif
                                
                                <div>
                                    <dt class="text-sm text-slate-500">{{ __('ui.duration') }}</dt>
                                    <dd class="text-sm font-medium text-slate-900 mt-1">
                                        @if($project->start_date && $project->end_date)
                                            {{ $project->start_date->format('M Y') }} – {{ $project->end_date->format('M Y') }}
                                        @elseif($project->start_date)
                                            {{ __('ui.started') }} {{ $project->start_date->format('M Y') }}
                                        @else
                                            -
                                        @endif
                                    </dd>
                                </div>
                                
                                @if($project->tags && count($project->tags) > 0)
                                    <div>
                                        <dt class="text-sm text-slate-500">{{ __('ui.tags') }}</dt>
                                        <dd class="mt-1 flex flex-wrap gap-1">
                                            @foreach($project->tags as $tag)
                                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">{{ $tag }}</span>
                                            @endforeach
                                        </dd>
                                    </div>
                                @endif
                                
                                @if($project->external_link)
                                    <div>
                                        <dt class="text-sm text-slate-500">{{ __('ui.external_link') }}</dt>
                                        <dd class="mt-1">
                                            <a href="{{ $project->external_link }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-medium text-[#214f95] hover:text-[#1a3d7a]">
                                                {{ __('ui.visit_website') ?? 'Visit Website' }}
                                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        {{-- Share --}}
                        <div class="bg-[#FBF8FD] rounded-2xl p-6">
                            <h3 class="text-lg font-semibold text-[#16254d] mb-4">{{ __('ui.share_project') }}</h3>
                            <div class="flex gap-3">
                                <button onclick="shareProject('twitter')" class="flex-1 rounded-xl bg-[#1DA1F2] px-4 py-2 text-sm font-medium text-white hover:bg-[#0d8bd9] transition">
                                    <svg class="size-5" viewBox="0 0 24 24" fill="currentColor"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>
                                </button>
                                <button onclick="shareProject('facebook')" class="flex-1 rounded-xl bg-[#1877F2] px-4 py-2 text-sm font-medium text-white hover:bg-[#166fe5] transition">
                                    <svg class="size-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12S0 5.446 0 12.073c0 5.992 3.858 11.028 9.126 11.896v-8.305H7.078v-3.47h2.048V9.43c0-2.03 1.236-3.145 3.03-3.145.864 0 1.707.063 1.934.092v2.16h-1.324c-1.039 0-1.237.491-1.237 1.213v1.517h2.321l-.312 2.37H14.9v8.188c-.142.026-.287.04-.434.04-7.046 0-12.762-5.716-12.762-12.762 0-2.534.67-4.91 1.846-6.99z"/></svg>
                                </button>
                                <button onclick="shareProject('linkedin')" class="flex-1 rounded-xl bg-[#0A66C2] px-4 py-2 text-sm font-medium text-white hover:bg-[#095599] transition">
                                    <svg class="size-5" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>

@push('scripts')
<script>
function shareProject(platform) {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ $project->getLocalized('title') }}');
    
    let shareUrl = '';
    switch(platform) {
        case 'twitter':
            shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
            break;
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
            break;
        case 'linkedin':
            shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
            break;
    }
    
    if (shareUrl) {
        window.open(shareUrl, '_blank', 'width=600,height=400');
    }
}
</script>
@endpush