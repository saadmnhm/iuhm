@extends('layouts.site')

@section('title', __('ui.resources_page_title').' | '.config('app.name'))

@section('content')
    <div class="bg-white pb-24">
        <!-- Header -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-16 pb-12">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div class="max-w-2xl">
                    <h1 class="text-5xl md:text-6xl font-bold tracking-tight text-[#16254d]">
                        Bibliothèque de <br>
                        <span class="text-[#0e7025]">Ressources</span>
                    </h1>
                    <p class="mt-6 text-lg text-slate-600 max-w-xl">
                        Accédez à l'ensemble de nos publications, guides et rapports de recherche. Des outils concrets pour accompagner le développement urbain et social.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="rounded-full bg-[#adfda2] px-5 py-2.5 text-sm font-semibold text-[#0a1e47] hover:bg-[#9af08e] transition">
                        Toutes les publications
                    </button>
                    <button class="rounded-full bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-200 transition">
                        Recherche
                    </button>
                </div>
            </div>
        </div>

        <!-- Featured Document & Recent -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
            <div class="grid lg:grid-cols-[1fr_360px] gap-8 items-start">
                <!-- Featured Card -->
                @if($featuredDeliverable)
                <div class="relative overflow-hidden rounded-[2rem] bg-[#162a52] text-white p-8 md:p-12 shadow-xl min-h-[420px] flex flex-col justify-center">
                    <div class="absolute inset-0 pointer-events-none opacity-20" style="background-image: radial-gradient(circle at 100% 50%, rgba(255,255,255,0.1) 0%, transparent 50%);"></div>
                    <div class="absolute inset-0 pointer-events-none opacity-30">
                        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                            <rect width="100%" height="100%" fill="none" stroke="currentColor" stroke-width="0.5" stroke-dasharray="4 4" rx="32"/>
                            <path d="M0,80 L800,80 M150,0 L150,600 M0,200 L800,200 M300,0 L300,600" stroke="currentColor" stroke-width="0.5" />
                        </svg>
                    </div>
                    <div class="relative z-10 max-w-xl">
                        @if($featuredDeliverable->category)
                        <span class="inline-flex rounded bg-[#10752d] px-2.5 py-1 text-xs font-bold uppercase tracking-wide text-white">
                            {{ $featuredDeliverable->category }}
                        </span>
                        @endif
                        <h2 class="mt-4 text-4xl font-bold tracking-tight">{{ $featuredDeliverable->title }}</h2>
                        @if($featuredDeliverable->description)
                        <p class="mt-4 text-lg text-white/70">{{ Str::limit($featuredDeliverable->description, 200) }}</p>
                        @endif
                        @if($featuredDeliverable->file_url)
                        <a href="{{ $featuredDeliverable->file_url }}" target="_blank" rel="noopener"
                           class="mt-8 inline-flex items-center gap-2 rounded-full bg-[#127027] px-6 py-3 font-semibold text-white transition hover:bg-[#0f5a1f]">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v7.586l2.293-2.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L9 11.586V4a1 1 0 011-1zM5 15a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                            Télécharger{{ $featuredDeliverable->file_type ? ' (' . strtoupper($featuredDeliverable->file_type) . ')' : '' }}
                        </a>
                        @endif
                    </div>
                </div>
                @else
                <div class="relative overflow-hidden rounded-[2rem] bg-[#162a52] text-white p-8 md:p-12 shadow-xl min-h-[420px] flex items-center justify-center">
                    <p class="text-white/50 font-medium">Aucune publication mise en avant pour le moment.</p>
                </div>
                @endif

                <!-- Recent Docs sidebar -->
                <div class="bg-[#FAFAFB] rounded-[2rem] p-8 lg:p-10">
                    <h3 class="text-xl font-bold text-[#16254d]">Documents Récents</h3>
                    <ul class="mt-8 space-y-6">
                        @forelse($recentDeliverables as $doc)
                        <li class="flex items-center gap-4 group">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm border border-slate-100 group-hover:border-slate-300 transition">
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-[#16254d] text-sm group-hover:text-[#0e7025] transition truncate">{{ $doc->title }}</h4>
                                <p class="text-[13px] font-medium text-slate-500">
                                    {{ $doc->file_type ? strtoupper($doc->file_type) : 'Document' }}
                                    @if($doc->downloads_count) • {{ $doc->downloads_count }} téléch. @endif
                                </p>
                            </div>
                            @if($doc->file_url)
                            <a href="{{ $doc->file_url }}" target="_blank" rel="noopener" class="shrink-0 text-slate-400 group-hover:text-[#0e7025] transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                            @endif
                        </li>
                        @empty
                        <li class="text-sm text-slate-400 font-medium py-4 text-center">Aucun document récent.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Complete Library -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">
                <h2 class="text-2xl font-bold text-[#16254d]">
                    Bibliothèque Complète
                    <span class="ml-2 text-base font-medium text-slate-400">({{ $deliverables->total() }})</span>
                </h2>
                <form method="GET" action="{{ route('resources.index') }}" class="relative w-full sm:w-72">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher un document..."
                           class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm placeholder:text-slate-500 focus:border-[#16254d] focus:outline-none focus:ring-1 focus:ring-[#16254d]">
                </form>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @forelse($deliverables as $doc)
                <div class="group flex flex-col h-full bg-[#FAFAFB] rounded-[1.5rem] overflow-hidden shadow-sm hover:shadow-md transition">
                    <!-- Cover -->
                    <div class="relative w-full aspect-[4/3] bg-[#162a52] overflow-hidden flex items-center justify-center">
                        <svg class="w-16 h-16 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        @if($doc->file_type)
                        <span class="absolute top-4 right-4 rounded bg-white/10 px-2 py-0.5 text-[0.65rem] font-extrabold uppercase tracking-wide text-white">
                            {{ strtoupper($doc->file_type) }}
                        </span>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="flex flex-col flex-1 p-6">
                        <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider mb-3">
                            <span class="text-[#0e7025]">{{ $doc->category ?: 'Document' }}</span>
                            @if($doc->downloads_count)
                            <span class="text-slate-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                {{ $doc->downloads_count }}
                            </span>
                            @endif
                        </div>
                        <h3 class="text-base font-bold text-[#16254d] mb-2 leading-snug group-hover:text-[#0e7025] transition">{{ $doc->title }}</h3>
                        @if($doc->description)
                        <p class="text-sm text-slate-600 leading-relaxed line-clamp-3 flex-1">{{ $doc->description }}</p>
                        @endif
                        @if($doc->file_url)
                        <a href="{{ $doc->file_url }}" target="_blank" rel="noopener"
                           class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-[#0e7025] hover:text-[#0a4d1a] transition">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Télécharger
                        </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full py-16 text-center">
                    <div class="inline-flex size-16 items-center justify-center rounded-full bg-slate-100 mb-4">
                        <svg class="size-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#16254d] mb-2">Aucune publication trouvée.</h3>
                    <p class="text-slate-500 font-medium">Essayez de modifier votre recherche.</p>
                </div>
                @endforelse
            </div>

            @if($deliverables->hasPages())
            <div class="mt-16 flex justify-center">
                {{ $deliverables->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection
