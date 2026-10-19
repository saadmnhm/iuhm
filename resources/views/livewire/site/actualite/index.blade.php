@extends('layouts.site')

@section('title', __('ui.news').' | '.config('app.name'))

@section('content')
<div class="bg-[#FAFAFC] pb-24">
    <!-- Hero Section -->
    <section class="relative pt-16 pb-20 sm:pt-24 sm:pb-28 overflow-hidden">
        <!-- Abstract gradient backgrounds -->
        <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(circle at 0% 0%, #fdfcff 0%, transparent 60%), radial-gradient(circle at 100% 100%, #f8fafc 0%, transparent 60%);"></div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-[1.1fr_1fr] gap-12 lg:gap-16 items-center">
                <!-- Text Content -->
                <div class="max-w-xl">
                    <h1 class="text-[3.5rem] font-medium tracking-tight text-[#0f172a] leading-[1.1] sm:text-[4rem]">
                        Actualités &<br>
                        <span class="text-[#10752d]">Événements</span>
                    </h1>
                    <p class="mt-8 text-[1.05rem] leading-relaxed text-slate-600 font-medium">
                        Restez informé de la vie de votre quartier. Initiative Urbaine s'engage à partager avec vous les projets, les rencontres et les évolutions qui façonnent notre environnement commun.
                    </p>
                    
                    <div class="mt-10 flex flex-wrap gap-4">
                        <span class="inline-flex rounded-full bg-[#82E682] px-5 py-2.5 text-[0.8rem] font-bold text-[#0a471b] tracking-wide">
                            Quartier Vivant
                        </span>
                        <span class="inline-flex rounded-full bg-[#E2E4E9] px-5 py-2.5 text-[0.8rem] font-bold text-[#475569] tracking-wide">
                            Impact 2024
                        </span>
                    </div>
                </div>

                <!-- Hero Image with Overlapping Card -->
                <div class="relative">
                    <div class="relative overflow-hidden rounded-[2rem] ">
                        <img src="{{ asset('assets/web_site/images/act.png') }}" alt="Garden" class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Floating Stats Card -->
                    <div class="absolute -bottom-8 left-8 rounded-3xl bg-white/90 backdrop-blur-md px-8 py-5 shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-white">
                        <div class="text-[1.75rem] font-extrabold text-[#0f172a] leading-none mb-1">12+</div>
                        <div class="text-[0.65rem] font-bold uppercase tracking-wider text-slate-500">Événements ce mois</div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- All News Grid + Search -->
    <section class="py-8 lg:py-12 bg-white" x-data="newsFilter()">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <!-- Search & heading -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">
                <h2 class="text-2xl font-bold tracking-tight text-[#0f172a]">
                    Toutes les Actualités
                    <span class="ml-2 text-base font-medium text-slate-400">({{ $posts->total() }})</span>
                </h2>
                <div class="relative w-full sm:w-72">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="size-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" x-model.debounce.500ms="search"
                        class="block w-full rounded-2xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-[#82E682] transition-all shadow-sm"
                        placeholder="Rechercher...">
                </div>
            </div>

            <!-- News grid -->
            <div id="news-grid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" :class="{ 'opacity-50 pointer-events-none': loading }">
                @forelse($posts as $post)
                <article class="group relative flex flex-col bg-[#F8F9FA] rounded-[2rem] overflow-hidden transition hover:shadow-lg hover:shadow-slate-200/50 border border-slate-100">
                    <div class="relative h-52 w-full overflow-hidden bg-slate-100 shrink-0">
                        @if($post->page)
                        <div class="absolute top-4 left-4 z-10 rounded-full bg-[#82E682] px-3 py-1 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-sm">
                            {{ $post->page->getLocalized('title') }}
                        </div>
                        @endif
                        <img src="{{ $post->image_url ?? 'https://images.unsplash.com/photo-1599839619722-39751411ea63?auto=format&fit=crop&w=800&q=80' }}"
                             alt="{{ $post->getLocalized('title') }}"
                             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </div>
                    <div class="flex flex-col flex-1 p-8">
                        <h3 class="text-[1.15rem] font-bold text-[#0f172a] leading-tight mb-3 group-hover:text-[#10752d] transition-colors line-clamp-2">
                            <a href="{{ route('actualite.show', $post->slug) }}" class="focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                {{ $post->getLocalized('title') }}
                            </a>
                        </h3>
                        <p class="text-sm text-slate-500 leading-relaxed line-clamp-3 mb-6 flex-1">
                            {{ $post->getLocalized('excerpt') ?: Str::limit(strip_tags($post->getLocalized('content')), 130) }}
                        </p>
                        <div class="flex items-center justify-between pt-5 border-t border-slate-200 mt-auto">
                            <span class="text-[0.78rem] font-bold text-slate-500">{{ $post->author?->name ?? 'Initiative Urbaine' }}</span>
                            <span class="text-[0.75rem] text-slate-400">{{ $post->published_at?->format('d M Y') ?? '' }}</span>
                        </div>
                    </div>
                </article>
                @empty
                <div class="col-span-full py-20 text-center">
                    <div class="inline-flex size-16 items-center justify-center rounded-full bg-slate-100 mb-4">
                        <svg class="size-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700 mb-2">Aucune actualité disponible.</h3>
                    <p class="text-slate-500">Revenez bientôt pour découvrir nos dernières nouvelles.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div id="news-pagination">
            @if($posts->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $posts->links() }}
            </div>
            @endif
            </div>
        </div>
    </section>
    <section class="py-12 sm:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-[1.3fr_1fr] gap-12 lg:gap-16 items-start">
                
                <!-- Left: Recent Publications -->
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-[#0f172a] sm:text-[1.75rem] mb-2">Publications Récentes</h2>
                    <p class="text-sm font-medium text-slate-500 mb-10">Nos derniers livrables et documents publiés.</p>

                    <div class="space-y-4">
                        @forelse($deliverables as $index => $deliverable)
                        <a href="{{ $deliverable->file_url ?? '#' }}"
                           {{ $deliverable->file_url ? 'target="_blank" rel="noopener"' : '' }}
                           class="group flex items-center gap-6 p-5 sm:p-6 rounded-[2rem] bg-[#F6F7F9] transition hover:bg-[#F0F1F4]">
                            <div class="flex flex-col items-center justify-center w-[4.5rem] h-[4.5rem] rounded-full shrink-0
                                        {{ $index === 0 ? 'bg-[#82E682] shadow-sm' : 'bg-[#EAECEF] border border-slate-200' }}">
                                <svg class="w-7 h-7 {{ $index === 0 ? 'text-[#0a471b]' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    @if($deliverable->category)
                                    <span class="text-[0.6rem] font-extrabold uppercase tracking-widest {{ $index === 0 ? 'text-[#0a471b]' : 'text-slate-400' }}">
                                        {{ $deliverable->category }}
                                    </span>
                                    @endif
                                </div>
                                <h4 class="text-base font-bold text-[#0f172a] mb-1 group-hover:text-[#10752d] transition line-clamp-1">
                                    {{ $deliverable->title }}
                                </h4>
                                <div class="flex items-center gap-4 text-[0.78rem] font-bold text-slate-400">
                                    @if($deliverable->file_type)
                                    <span class="uppercase">{{ $deliverable->file_type }}</span>
                                    @endif
                                    @if($deliverable->published_at)
                                    <span>{{ $deliverable->published_at->format('d M Y') }}</span>
                                    @endif
                                    @if($deliverable->downloads_count)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        {{ $deliverable->downloads_count }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-slate-400 group-hover:text-[#10752d] transition shrink-0 pl-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>
                        @empty
                        <p class="text-sm font-medium text-slate-400 py-8 text-center">Aucune publication disponible pour le moment.</p>
                        @endforelse
                    </div>

                    <a href="{{ route('resources.index') }}" class="mt-8 inline-flex items-center gap-2 text-sm font-bold text-[#10752d] hover:text-[#0c5921] transition">
                        Voir toutes les publications
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <!-- Right: Sidebar Cards -->
                <div class="flex flex-col gap-6">
                    <!-- Service Updates Card -->
                    <div class="rounded-2xl p-8 sm:p-10 bg-[#0c132b] text-white shadow-xl shadow-slate-300/40">
                        <h3 class="text-[1.35rem] font-medium mb-8">Mises à jour des Services</h3>
                        
                        <div class="space-y-6">
                            <!-- Update 1 -->
                            <div class="flex gap-4">
                                <div class="shrink-0 mt-0.5">
                                    <svg class="w-5 h-5 text-[#86e288]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold mb-1">Espace Multimédia</h4>
                                    <p class="text-xs text-white/70 font-medium leading-relaxed">Nouveaux horaires : Ouvert jusqu'à 20h les mardis et jeudis.</p>
                                </div>
                            </div>
                            
                            <!-- Update 2 -->
                            <div class="flex gap-4">
                                <div class="shrink-0 mt-0.5">
                                    <svg class="w-5 h-5 text-[#86e288]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold mb-1">Programme Jeunesse</h4>
                                    <p class="text-xs text-white/70 font-medium leading-relaxed">Les inscriptions pour les camps de printemps sont maintenant ouvertes.</p>
                                </div>
                            </div>

                            <!-- Update 3 -->
                            <div class="flex gap-4">
                                <div class="shrink-0 mt-0.5">
                                    <svg class="w-5 h-5 text-[#82E682]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold mb-1">Bureau Administratif</h4>
                                    <p class="text-xs text-white/70 font-medium leading-relaxed">Fermeture exceptionnelle ce vendredi pour formation interne.</p>
                                </div>
                            </div>
                        </div>

                        <button class="w-full mt-10 rounded-full border border-white/20 bg-white/5 py-4 text-xs font-bold text-white transition hover:bg-white/10 text-center">
                            Consulter l'annuaire complet
                        </button>
                    </div>

                    <!-- Impact Card -->
                    <div class="rounded-2xl p-8 sm:p-10 bg-[#9BFA9B] shadow-xl shadow-slate-300/40">
                        <h3 class="text-xl font-medium text-[#0f172a] mb-2">Notre Impact</h3>
                        <p class="text-sm font-medium text-[#10752d] leading-relaxed mb-6">Chaque action renforce les liens de notre tissu social.</p>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-[#B2FBB2] rounded-xl p-4">
                                <div class="text-[1.35rem] leading-none mb-1 font-bold text-[#0f172a]">2.4k</div>
                                <div class="text-[0.6rem] font-extrabold uppercase tracking-widest text-[#10752d]">MEMBRES ACTIFS</div>
                            </div>
                            <div class="bg-[#B2FBB2] rounded-xl p-4">
                                <div class="text-[1.35rem] leading-none mb-1 font-bold text-[#0f172a]">150</div>
                                <div class="text-[0.6rem] font-extrabold uppercase tracking-widest text-[#10752d]">ATELIERS / AN</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section removed and Articles removed as per user request -->
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('newsFilter', () => ({
        search: new URLSearchParams(window.location.search).get('search') || '',
        loading: false,

        init() {
            this.$watch('search', () => this.fetchNews());
        },

        fetchNews() {
            this.loading = true;
            const url = new URL(window.location.href);
            this.search ? url.searchParams.set('search', this.search) : url.searchParams.delete('search');
            url.searchParams.delete('page');

            fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => {
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const grid = doc.querySelector('#news-grid');
                    if (grid) document.querySelector('#news-grid').innerHTML = grid.innerHTML;
                    const pag = doc.querySelector('#news-pagination');
                    if (pag) document.querySelector('#news-pagination').innerHTML = pag.innerHTML;
                    window.history.pushState({}, '', url.toString());
                    this.loading = false;
                })
                .catch(() => { this.loading = false; });
        },
    }));
});
</script>
@endpush
@endsection
