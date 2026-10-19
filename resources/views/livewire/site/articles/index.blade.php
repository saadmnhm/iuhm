@extends('layouts.site')

@section('title', 'Articles | ' . config('app.name'))

@section('content')
<main class="bg-[#FAFAFC] pb-24" x-data="articlesFilter()">
    <!-- Hero Section -->
    <section class="relative pt-12 pb-16 lg:pt-20 lg:pb-24 overflow-hidden bg-white">
        <div class="absolute inset-0 pointer-events-none bg-[radial-gradient(circle_at_0%_0%,_#fdfcff_0%,_transparent_60%)]"></div>
        <div class="iu-container relative z-10">
            <div class="grid lg:grid-cols-[1.1fr_1fr] gap-12 lg:gap-16 items-center">
                <!-- Text Content -->
                <div class="max-w-2xl">
                    <div class="inline-flex rounded-full bg-[#82E682] px-4 py-1.5 text-[0.8rem] font-bold tracking-wide text-[#0a471b] mb-8">
                        Perspective Citoyenne
                    </div>
                    <h1 class="text-[3.5rem] font-bold tracking-tight text-[#0B1528] leading-[1.05] sm:text-[4rem] mb-6">
                        L'Observateur Urbain
                    </h1>
                    <p class="text-[1.1rem] leading-relaxed text-slate-600 font-medium">
                        Analyses approfondies, reportages de terrain et visions d'avenir pour une ville
                        plus inclusive et durable. Découvrez notre regard sur les transformations urbaines.
                    </p>
                </div>

                <!-- Hero Image -->
                <div class="relative flex justify-center lg:justify-end">
                    <div class="relative w-[320px] sm:w-[380px] lg:w-[420px] aspect-square rounded-[2.5rem] bg-[#111827] shadow-lg shadow-slate-300/30 flex items-center justify-center p-12 overflow-hidden group">
                        <!-- Abstract icon for "L'Observateur Urbain" -->
                        <div class="absolute inset-0 bg-[#0f172a] rounded-[2.5rem]"></div>
                        <div class="relative z-10 size-56 rounded-[2rem] bg-[#141d2f]/90 shadow-[0_5px_40px_rgb(0,0,0,0.4)] border border-slate-700/50 backdrop-blur flex items-center justify-center text-[7rem] text-[#64748b] font-serif italic group-hover:scale-105 transition-transform duration-700">
                            ១
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filtering & Search Row -->
    <section class="sticky top-20 z-40 bg-[#FAFAFC]/95 backdrop-blur-md py-6">
        <div class="iu-container flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Filters -->
            <div class="flex flex-wrap items-center gap-2">
                <button @click="setCategory('')" 
                   :class="category === '' ? 'bg-[#0B1528] text-white' : 'bg-[#EAECEF] text-slate-700 hover:bg-[#DEDFE2]'"
                   class="rounded-full px-5 py-2.5 text-[0.95rem] font-bold transition-all shadow-sm">
                    Tous les articles
                </button>
                @foreach($categories as $category)
                <button @click="setCategory('{{ $category }}')" 
                   :class="category === '{{ $category }}' ? 'bg-[#0B1528] text-white' : 'bg-[#EAECEF] text-slate-700 hover:bg-[#DEDFE2]'"
                   class="rounded-full px-5 py-2.5 text-[0.95rem] font-bold transition-all shadow-sm">
                    {{ $category }}
                </button>
                @endforeach
            </div>

            <!-- Search Bar -->
            <div class="relative w-full md:w-64 lg:w-80">
                <div class="relative flex items-center">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="size-4.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" x-model.debounce.500ms="search" 
                        class="block w-full rounded-2xl border-none bg-[#EAECEF] py-2.5 pl-11 pr-4 text-[0.95rem] font-medium text-slate-900 placeholder:text-slate-500 focus:bg-white focus:ring-2 focus:ring-[#82E682] transition-all shadow-sm" 
                        placeholder="Rechercher...">
                </div>
            </div>
        </div>
    </section>

    <!-- Articles Grid -->
    <section class="py-8 lg:py-12" id="articles-container" :class="{ 'opacity-50': loading, 'transition-opacity duration-200': true }">
        <div class="iu-container">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12" id="articles-grid">
                
                @forelse($posts as $post)
                <article class="group flex flex-col bg-[#F8F9FA] rounded-[1.5rem] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <!-- Image -->
                    <div class="relative h-56 w-full overflow-hidden bg-slate-100 shrink-0">
                        @if($post->page)
                        <div class="absolute top-4 left-4 z-20 rounded-full bg-[#82E682] px-3 py-1 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-sm backdrop-blur-sm">
                            {{ $post->page->getLocalized('title') ?? 'ARTICLE' }}
                        </div>
                        @else
                        <div class="absolute top-4 left-4 z-20 rounded-full bg-[#82E682] px-3 py-1 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-sm backdrop-blur-sm">
                            ARTICLE
                        </div>
                        @endif
                        
                        <img src="{{ $post->image_url ?? 'https://images.unsplash.com/photo-1449844908441-8829872d2607?auto=format&fit=crop&w=800&q=80' }}" 
                             alt="{{ $post->getLocalized('title') }}" 
                             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </div>
                    
                    <!-- Content -->
                    <div class="flex flex-col flex-1 p-8">
                        <h3 class="text-[1.35rem] font-bold text-[#0B1528] leading-tight mb-4 group-hover:text-[#214f95] transition-colors">
                            <a href="{{ route('articles.show', $post->slug) }}" class="focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                {{ $post->getLocalized('title') }}
                            </a>
                        </h3>
                        <p class="text-[0.95rem] font-medium text-slate-500 leading-relaxed line-clamp-4 mb-8 flex-1">
                            {{ $post->getLocalized('excerpt') ?? Str::limit(strip_tags($post->getLocalized('content') ?? ''), 150) }}
                        </p>
                        
                        <!-- Meta Footer -->
                        <div class="flex items-center justify-between pt-6 border-t border-[#d5d7de] mt-auto">
                            <span class="text-[0.8rem] font-bold text-[#0B1528]">
                                Par {{ $post->author?->name ?? 'Initiative Urbaine' }}
                            </span>
                            <span class="text-[0.75rem] font-bold text-slate-400">
                                {{ $post->published_at ? $post->published_at->format('d M Y') : '' }}
                            </span>
                        </div>
                    </div>
                </article>
                @empty
                <div class="col-span-full py-16 text-center">
                    <div class="inline-flex size-16 items-center justify-center rounded-full bg-[#EAECEF] mb-4">
                        <svg class="size-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#0B1528] mb-2">Aucun article trouvé.</h3>
                    <p class="text-slate-500 font-medium">Essayez de modifier vos filtres de recherche.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div id="pagination-container">
            @if($posts->hasPages())
            <div class="mt-16 flex justify-center">
                {{ $posts->links() }}
            </div>
            @endif
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-12 lg:py-16">
        <div class="iu-container">
            <div class="bg-[#050C1F] rounded-[2.5rem] w-full overflow-hidden flex flex-col md:flex-row shadow-[0_20px_50px_rgba(0,0,0,0.15)] relative">
                <!-- Background decoration right -->
                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/3 w-96 h-96 bg-[#162D5A] rounded-full mix-blend-screen filter blur-[80px] opacity-70 pointer-events-none hidden md:block"></div>

                <div class="p-10 lg:p-16 flex-1 flex flex-col justify-center relative z-10">
                    <h2 class="text-3xl sm:text-[2.5rem] font-bold text-white tracking-tight leading-[1.2] mb-6">
                        Ne manquez aucune analyse urbaine.
                    </h2>
                    <p class="text-lg text-slate-400 font-medium mb-10 max-w-lg">
                        Inscrivez-vous à notre infolettre mensuelle pour recevoir une sélection exclusive
                        d'articles et de dossiers spéciaux directement dans votre boîte mail.
                    </p>
                    
                    @livewire('newsletter.subscribe-form')
                </div>
            </div>
        </div>
    </section>
</main>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('articlesFilter', () => ({
        search: new URLSearchParams(window.location.search).get('search') || '',
        category: new URLSearchParams(window.location.search).get('category') || '',
        loading: false,

        init() {
            this.$watch('search', () => {
                this.fetchArticles();
            });
        },

        setCategory(cat) {
            this.category = cat;
            this.fetchArticles();
        },

        fetchArticles() {
            this.loading = true;
            let url = new URL(window.location.href);
            
            if (this.search) {
                url.searchParams.set('search', this.search);
            } else {
                url.searchParams.delete('search');
            }
            
            if (this.category) {
                url.searchParams.set('category', this.category);
            } else {
                url.searchParams.delete('category');
            }
            
            // fetch via ajax
            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                
                let newGrid = doc.querySelector('#articles-grid');
                if (newGrid) {
                    document.querySelector('#articles-grid').innerHTML = newGrid.innerHTML;
                }
                
                let newPaginationContainer = doc.querySelector('#pagination-container');
                let oldPaginationContainer = document.querySelector('#pagination-container');
                if (oldPaginationContainer && newPaginationContainer) {
                    oldPaginationContainer.innerHTML = newPaginationContainer.innerHTML;
                }
                
                window.history.pushState({}, '', url.toString());
                this.loading = false;
            })
            .catch(() => {
                this.loading = false;
            });
        }
    }));
});
</script>
@endpush
@endsection
