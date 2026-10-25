@extends('layouts.site')

@section('title', $project->getLocalized('title') .' | '.config('app.name'))

@php
    $image = $project->featured_image ?: 'https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?auto=format&fit=crop&w=2000&q=80';
    $date = $project->created_at?->translatedFormat('d F Y') ?? '14 Octobre 2024';
    $category = "PROJET";
@endphp

@section('content')
<main class="bg-[#FAFAFC] pb-20 pt-6">
    <div class="iu-container">
        
        <!-- Back Link -->
        <div class="mb-8">
            <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 text-[0.8rem] font-extrabold uppercase tracking-widest text-[#4A5568] hover:text-[#0B1528] transition-colors">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                RETOUR AUX PROJETS
            </a>
        </div>

        <!-- Featured Image -->
        <div class="relative w-full h-[400px] md:h-[500px] lg:h-[600px] rounded-[2rem] overflow-hidden shadow-lg mb-10 md:mb-16">
            <img src="{{ \Illuminate\Support\Str::startsWith($project->featured_image, ['http','https']) ? $project->featured_image : asset($project->featured_image) }}" alt="{{ $project->getLocalized('title') }}" class="w-full h-full object-cover">
            <span class="absolute bottom-6 md:bottom-10 left-6 md:left-10 z-10 inline-flex items-center rounded-full bg-[#82E682] px-4 py-2 text-[0.7rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-md backdrop-blur-sm">
                {{ $category }}
            </span>
            <div class="absolute inset-0 bottom-0 top-1/2 bg-gradient-to-t from-black/40 to-transparent pointer-events-none"></div>
        </div>

        <!-- Content Layout -->
        <div class="grid lg:grid-cols-[1fr_320px] gap-12 lg:gap-20">
            
            <!-- Main Content Area -->
            <article class="lg:pl-4 mx-auto lg:mx-0 w-full">
                <!-- Title & Meta -->
                <header class="mb-12">
                    <h1 class="text-[2.5rem] md:text-[3.2rem] font-bold text-[#0B1528] leading-[1.1] mb-8">
                        {{ $project->getLocalized('title') }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-6 md:gap-8 pt-6 border-t border-slate-200/80">
                        <!-- Date -->
                        <div class="flex items-center gap-2 text-slate-500 font-medium text-[0.85rem]">
                            <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $date }}
                        </div>
                        
                        <!-- Location -->
                        @if($project->location)
                        <div class="flex items-center gap-2 text-slate-500 font-medium text-[0.85rem]">
                            <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $project->location }}
                        </div>
                        @endif
                        
                        <!-- Status -->
                        <div class="flex items-center gap-2 text-slate-500 font-medium text-[0.85rem]">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-bold {{ $project->getStatusColor() }}">
                                {{ $project->getStatusLabel() }}
                            </span>
                        </div>
                    </div>
                </header>

                <div class="prose prose-lg prose-slate max-w-none prose-headings:text-[#0B1528] prose-headings:font-bold prose-p:text-[#4A5568] prose-p:leading-relaxed prose-a:text-[#214f95] marker:bg-[#82E682]">
                    @php
                        $desc = trim($project->getLocalized('description', 'fr'));
                        if ($desc && strpos($desc, '<') !== 0) {
                            $desc = '<p>' . $desc . '</p>';
                        }
                    @endphp
                    <div class="whitespace-pre-wrap text-[#4A5568] leading-relaxed">{!! $desc !!}</div>
                </div>

                {{-- Gallery --}}
                @if($project->gallery && count($project->gallery) > 0)
                <div>
                    <h2 class="text-3xl md:text-[2.5rem] font-bold text-[#0B1528] leading-[1.2] mb-6 capitalize">{{ __('ui.gallery') }}</h2>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($project->gallery as $image)
                        <a href="{{ \Illuminate\Support\Str::startsWith($image, ['http','https']) ? $image : asset($image) }}" target="_blank" class="block rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow">
                            <img src="{{ \Illuminate\Support\Str::startsWith($image, ['http','https']) ? $image : asset($image) }}" alt="{{ $project->getLocalized('title') }}" class="w-full h-64 object-cover hover:scale-105 transition-transform duration-300">
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Tags -->
                <div class="mt-16 pt-8 flex flex-wrap gap-3">
                    @if($project->tags && count($project->tags) > 0)
                        @foreach($project->tags as $tag)
                            <span class="inline-flex rounded-full bg-[#EAECEF] px-4 py-2 text-[0.8rem] font-bold text-slate-600 transition hover:bg-slate-200 cursor-pointer">#{{ $tag }}</span>
                        @endforeach
                    @else
                        <span class="inline-flex rounded-full bg-[#EAECEF] px-4 py-2 text-[0.8rem] font-bold text-slate-600">#Projet</span>
                    @endif
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="space-y-10 w-full">
                <!-- Share -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm shadow-slate-200 border border-slate-100">
                    <h3 class="text-[1.1rem] font-bold text-[#0B1528] mb-6">Partager le projet</h3>
                    <div class="flex gap-4">
                        <button class="size-11 rounded-full bg-[#0B1528] text-white flex items-center justify-center hover:bg-[#162D5A] shadow-md shadow-slate-800/20 transition">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 110 2.684 3 3 0 00-5.367-2.684z"/></svg>
                        </button>
                        <button class="size-11 rounded-full bg-[#EAECEF] text-slate-600 flex items-center justify-center hover:bg-slate-200 transition">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </button>
                        <button class="size-11 rounded-full bg-[#EAECEF] text-slate-600 flex items-center justify-center hover:bg-slate-200 transition">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Related Projects -->
                <div>
                    <h3 class="text-[1.1rem] font-bold text-[#0B1528] mb-6">Projets similaires</h3>
                    <div class="flex flex-col gap-4">
                        @foreach($relatedProjects as $related)
                        <a href="{{ route('projects.show', $related->slug) }}" class="bg-[#F8F9FA] rounded-[1.5rem] p-6 transition hover:bg-[#EAECEF] hover:shadow-sm group border border-slate-100">
                            <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-2 block">{{ $related->getStatusLabel() }}</span>
                            <h4 class="text-[1rem] font-bold text-[#0A1A3A] leading-[1.3] group-hover:text-[#214f95] transition-colors">{{ $related->getLocalized('title') }}</h4>
                            @if($related->location)
                            <p class="text-[0.8rem] text-slate-500 mt-2">{{ $related->location }}</p>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>
@endsection