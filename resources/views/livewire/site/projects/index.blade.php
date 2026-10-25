@extends('layouts.site')

@section('title', $page?->getLocalized('title') ?? __('ui.projects_page_title') .' | '.config('app.name'))

@section('content')
<main class="bg-[#FAFAFC] pb-24">
    <!-- Hero Section -->
    <section class="relative pt-12 pb-16 lg:pt-20 lg:pb-24 overflow-hidden bg-white">
        <div class="absolute inset-0 pointer-events-none bg-[radial-gradient(circle_at_0%_0%,_#fdfcff_0%,_transparent_60%)]"></div>
        <div class="iu-container relative z-10">
            <div class="grid lg:grid-cols-[1.1fr_1fr] gap-12 lg:gap-16 items-center">
                <!-- Text Content -->
                <div class="max-w-2xl">
                    <div class="inline-flex rounded-full bg-[#82E682] px-4 py-1.5 text-[0.8rem] font-bold tracking-wide text-[#0a471b] mb-8">
                        {{ __('ui.projects_page_label') }}
                    </div>
                    <h1 class="text-[3.5rem] font-bold tracking-tight text-[#0B1528] leading-[1.05] sm:text-[4rem] mb-6">
                        {{ $page?->getLocalized('title') ?? __('ui.projects_page_title') }}
                    </h1>
                    <p class="text-[1.1rem] leading-relaxed text-slate-600 font-medium">
                        {{ $page?->getLocalized('excerpt') ?? __('ui.projects_page_label') }}
                    </p>
                </div>

                <!-- Hero Image -->
                <div class="relative flex justify-center lg:justify-end">
                    <div class="relative w-[320px] sm:w-[380px] lg:w-[420px] aspect-square rounded-[2.5rem] bg-[#111827] shadow-lg shadow-slate-300/30 flex items-center justify-center p-12 overflow-hidden group">
                        <div class="absolute inset-0 bg-[#0f172a] rounded-[2.5rem]"></div>
                        <div class="relative z-10 size-56 rounded-[2rem] bg-[#141d2f]/90 shadow-[0_5px_40px_rgb(0,0,0,0.4)] border border-slate-700/50 backdrop-blur flex items-center justify-center text-[7rem] text-[#64748b] font-serif italic group-hover:scale-105 transition-transform duration-700">
                            ១
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Grid -->
    <section class="py-8 lg:py-12">
        <div class="iu-container">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                
                @if($projects->isNotEmpty())
                    @foreach($projects as $project)
                    <article class="group flex flex-col bg-[#F8F9FA] rounded-[1.5rem] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <!-- Image -->
                        <div class="relative h-56 w-full overflow-hidden bg-slate-100 shrink-0">
                            @if($project->featured_image)
                            <div class="absolute top-4 left-4 z-20 rounded-full bg-[#82E682] px-3 py-1 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-sm backdrop-blur-sm">
                                {{ $project->getStatusLabel() }}
                            </div>
                            @else
                            <div class="absolute top-4 left-4 z-20 rounded-full bg-[#82E682] px-3 py-1 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-sm backdrop-blur-sm">
                                PROJET
                            </div>
                            @endif
                            
                            <img src="{{ \Illuminate\Support\Str::startsWith($project->featured_image, ['http','https']) ? $project->featured_image : asset($project->featured_image ?? 'https://images.unsplash.com/photo-1449844908441-8829872d2607?auto=format&fit=crop&w=800&q=80') }}" 
                                 alt="{{ $project->getLocalized('title') }}" 
                                 class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>
                        
                        <!-- Content -->
                        <div class="flex flex-col flex-1 p-8">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-bold {{ $project->getStatusColor() }}">
                                    {{ $project->getStatusLabel() }}
                                </span>
                                @if($project->is_featured)
                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-bold text-yellow-800">{{ __('ui.featured') }}</span>
                                @endif
                            </div>
                            
                            <h3 class="text-[1.35rem] font-bold text-[#0B1528] leading-tight mb-4 group-hover:text-[#214f95] transition-colors">
                                <a href="{{ route('projects.show', $project->slug) }}" class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    {{ $project->getLocalized('title') }}
                                </a>
                            </h3>
                            
                            <p class="text-[0.95rem] font-medium text-slate-500 leading-relaxed line-clamp-4 mb-8 flex-1">
                                {{ $project->getLocalized('short_description') ?? strip_tags($project->getLocalized('description')) }}
                            </p>
                            
                            <!-- Meta Footer -->
                            <div class="flex items-center justify-between pt-6 border-t border-[#d5d7de] mt-auto">
                                @if($project->location)
                                <span class="text-[0.8rem] font-bold text-[#0B1528]">
                                    {{ $project->location }}
                                </span>
                                @endif
                                <span class="text-[0.75rem] font-bold text-slate-400">
                                    {{ $project->created_at?->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </article>
                    @endforeach
                @else
                    <div class="col-span-full py-16 text-center">
                        <div class="inline-flex size-16 items-center justify-center rounded-full bg-[#EAECEF] mb-4">
                            <svg class="size-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#0B1528] mb-2">{{ __('ui.no_projects') ?? 'Aucun projet trouvé.' }}</h3>
                        <p class="text-slate-500 font-medium">{{ __('ui.no_projects_desc') ?? 'Il n\'y a pas encore de projets.' }}</p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            <div id="pagination-container">
            @if($projects->hasPages())
            <div class="mt-16 flex justify-center">
                {{ $projects->links() }}
            </div>
            @endif
            </div>
        </div>
    </section>
</main>
@endsection