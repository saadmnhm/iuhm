@extends('layouts.site')

@section('title', $page?->getLocalized('title') ?? __('ui.projects_page_title') .' | '.config('app.name'))

@section('content')
    <section class="bg-white py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#2d4b86]">{{ $page?->getLocalized('excerpt') ?? __('ui.projects_page_label') }}</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-[#16254d] sm:text-4xl">{{ $page?->getLocalized('title') ?? __('ui.projects_page_title') }}</h2>
            </div>

            @if($projects->isNotEmpty())
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($projects as $project)
                        <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl transition-shadow">
                            @if($project->featured_image)
                                <div class="aspect-video overflow-hidden bg-slate-100">
                                    <img src="{{ $project->featured_image }}" alt="{{ $project->getLocalized('title') }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                            @endif
                            
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $project->getStatusColor() }}">
                                        {{ $project->getStatusLabel() }}
                                    </span>
                                    @if($project->is_featured)
                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">{{ __('ui.featured') }}</span>
                                    @endif
                                </div>
                                
                                <h3 class="text-lg font-bold text-[#16254d] mb-2 line-clamp-2">
                                    <a href="{{ route('projects.show', $project->slug) }}" class="hover:text-[#10752d] transition-colors">
                                        {{ $project->getLocalized('title') }}
                                    </a>
                                </h3>
                                
                                @if($project->getLocalized('short_description'))
                                    <p class="text-sm text-slate-600 mb-4 line-clamp-3">{{ $project->getLocalized('short_description') }}</p>
                                @elseif($project->getLocalized('description'))
                                    <p class="text-sm text-slate-600 mb-4 line-clamp-3">{{ strip_tags($project->getLocalized('description')) }}</p>
                                @endif
                                
                                <div class="flex items-center gap-4 text-sm text-slate-500">
                                    @if($project->location)
                                        <span class="flex items-center gap-1">
                                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $project->location }}
                                        </span>
                                    @endif
                                    @if($project->start_date || $project->end_date)
                                        <span class="flex items-center gap-1">
                                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $project->start_date?->format('M Y') }} @if($project->end_date)– {{ $project->end_date->format('M Y') }} @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                <div class="mt-10">
                    {{ $projects->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto size-12 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <p class="mt-4 text-lg font-medium text-slate-900">{{ __('ui.no_projects') ?? 'No projects yet.' }}</p>
                </div>
            @endif
        </div>
    </section>
@endsection