@extends('layouts.site')

@section('title', $post->getLocalized('title').' | '.config('app.name'))

@php
    $image = $post->featured_image ?: 'https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?auto=format&fit=crop&w=2000&q=80';

    $author = $post->author?->name ?? 'Dr. Amina Mansouri';
    $authorRole = 'Urbaniste Senior';

    $date = $post->published_at?->translatedFormat('d F Y') ?? '14 Octobre 2024';
    $readTime = '8 min de lecture';

    // For static demo matching image
    if ($post->slug === 'la-preservation-du-patrimoine-dans-la-modernite') {
        $title = "La préservation du patrimoine dans la modernité";
        $category = "URBANISME & CULTURE";
    } else {
        $title = $post->getLocalized('title');
        $category = $post->page?->getLocalized('title') ?? "ARTICLE";
    }
@endphp

@section('content')
<main class="bg-[#FAFAFC] pb-20 pt-6">
    <div class="iu-container">
        
        <!-- Back Link -->
        <div class="mb-8">
            <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 text-[0.8rem] font-extrabold uppercase tracking-widest text-[#4A5568] hover:text-[#0B1528] transition-colors">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                RETOUR AUX ARTICLES
            </a>
        </div>

        <!-- Featured Image -->
        <div class="relative w-full h-[400px] md:h-[500px] lg:h-[600px] rounded-[2rem] overflow-hidden shadow-lg mb-10 md:mb-16">
            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover">
            <div class="absolute bottom-6 md:bottom-10 left-6 md:left-10 z-10">
                <span class="inline-flex items-center rounded-full bg-[#82E682] px-4 py-2 text-[0.7rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-md backdrop-blur-sm">
                    {{ $category }}
                </span>
            </div>
            <div class="absolute inset-0 bottom-0 top-1/2 bg-gradient-to-t from-black/40 to-transparent pointer-events-none"></div>
        </div>

        <!-- Content Layout -->
        <div class="grid lg:grid-cols-[1fr_320px] gap-12 lg:gap-20">
            
            <!-- Main Content Area -->
            <article class="lg:pl-4 mx-auto lg:mx-0 w-full">
                <!-- Title & Meta -->
                <header class="mb-12">
                    <h1 class="text-[2.5rem] md:text-[3.2rem] font-bold text-[#0B1528] leading-[1.1] mb-8">
                        {{ $title }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-6 md:gap-8 pt-6 border-t border-slate-200/80">
                        <!-- Author -->
                        <div class="flex items-center gap-3">
                            <div class="size-12 rounded-full overflow-hidden flex-shrink-0 bg-slate-200">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($author) }}&background=0A1A3A&color=fff" alt="{{ $author }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <div class="text-[0.95rem] font-bold text-[#0B1528]">{{ $author }}</div>
                                <div class="text-[0.8rem] font-medium text-slate-500">{{ $authorRole }}</div>
                            </div>
                        </div>
                        
                        <!-- Date -->
                        <div class="flex items-center gap-2 text-slate-500 font-medium text-[0.85rem]">
                            <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $date }}
                        </div>
                        
                        <!-- Read Time -->
                        <div class="flex items-center gap-2 text-slate-500 font-medium text-[0.85rem]">
                            <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $readTime }}
                        </div>
                    </div>
                </header>

                <div class="prose prose-lg prose-slate max-w-none prose-headings:text-[#0B1528] prose-headings:font-bold prose-p:text-[#4A5568] prose-p:leading-relaxed prose-a:text-[#214f95] marker:bg-[#82E682]">
                    @if($post->slug === 'la-preservation-du-patrimoine-dans-la-modernite')
                        <!-- Static matching content -->
                        <p class="text-[1.2rem] italic font-medium text-[#0A1A3A] leading-relaxed mb-10">
                            "Comment concilier l'héritage ancestral de nos villes avec les impératifs d'une métropole moderne et durable ? C'est le défi architectural du siècle pour Initiative Urbaine."
                        </p>

                        <h2 class="text-3xl mt-12 mb-6">Le paradoxe urbain</h2>
                        
                        <p class="mb-8">
                            La ville marocaine contemporaine se trouve à un carrefour historique. Entre les murs chargés d'histoire des médinas et les structures d'acier des nouveaux centres d'affaires, un dialogue doit s'instaurer. La préservation ne doit plus être vue comme un frein au développement, mais comme son ancrage nécessaire.
                        </p>

                        <div class="my-12 px-8 py-8 bg-[#F0F2F5] rounded-3xl border-l-[6px] border-[#82E682]">
                            <p class="text-xl md:text-2xl font-medium text-[#0B1528] leading-snug mb-4 !mt-0">
                                "Le patrimoine n'est pas un musée de cendres, mais la transmission d'un feu qui éclaire notre futur urbain."
                            </p>
                            <p class="text-sm font-bold text-slate-500 !mb-0">— Rapport Annuel de l'Initiative Urbaine</p>
                        </div>

                        <h2 class="text-3xl mt-12 mb-6">Stratégies de réhabilitation</h2>

                        <p class="mb-8">
                            Notre approche repose sur l'intégration technologique. En utilisant la modélisation 3D pour cartographier les structures historiques, nous pouvons renforcer les fondations tout en intégrant des systèmes de gestion d'énergie intelligents. Cette fusion permet aux bâtiments anciens de répondre aux normes écologiques actuelles.
                        </p>

                        <figure class="my-12">
                            <img src="https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?auto=format&fit=crop&w=1000&q=80" alt="Réhabilitation" class="w-full rounded-3xl shadow-sm">
                            <figcaption class="text-center text-[0.8rem] font-medium text-slate-500 mt-4">
                                Restauration minutieuse des détails ornementaux à Casablanca.
                            </figcaption>
                        </figure>

                        <p class="mb-8">
                            Enfin, la dimension humaine reste primordiale. Les projets qui réussissent sont ceux qui impliquent les résidents locaux dès la phase de conception. Créer des espaces hybrides, où le commerce traditionnel côtoie les espaces de coworking, assure la vitalité économique du patrimoine.
                        </p>
                    @else
                        {!! $post->getTranslatedContent() !!}
                    @endif
                </div>

                <!-- Tags -->
                <div class="mt-16 pt-8 flex flex-wrap gap-3">
                    <span class="inline-flex rounded-full bg-[#EAECEF] px-4 py-2 text-[0.8rem] font-bold text-slate-600 transition hover:bg-slate-200 cursor-pointer">#Architecture</span>
                    <span class="inline-flex rounded-full bg-[#EAECEF] px-4 py-2 text-[0.8rem] font-bold text-slate-600 transition hover:bg-slate-200 cursor-pointer">#Patrimoine</span>
                    <span class="inline-flex rounded-full bg-[#EAECEF] px-4 py-2 text-[0.8rem] font-bold text-slate-600 transition hover:bg-slate-200 cursor-pointer">#Modernité</span>
                    <span class="inline-flex rounded-full bg-[#EAECEF] px-4 py-2 text-[0.8rem] font-bold text-slate-600 transition hover:bg-slate-200 cursor-pointer">#Casablanca</span>
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="space-y-10 w-full">
                <!-- Share -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm shadow-slate-200 border border-slate-100">
                    <h3 class="text-[1.1rem] font-bold text-[#0B1528] mb-6">Partager l'article</h3>
                    <div class="flex gap-4">
                        <button class="size-11 rounded-full bg-[#0B1528] text-white flex items-center justify-center hover:bg-[#162D5A] shadow-md shadow-slate-800/20 transition">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        </button>
                        <button class="size-11 rounded-full bg-[#EAECEF] text-slate-600 flex items-center justify-center hover:bg-slate-200 transition">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </button>
                        <button class="size-11 rounded-full bg-[#EAECEF] text-slate-600 flex items-center justify-center hover:bg-slate-200 transition">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Related Articles -->
                <div>
                    <h3 class="text-[1.1rem] font-bold text-[#0B1528] mb-6">Articles similaires</h3>
                    <div class="flex flex-col gap-4">
                        <a href="#" class="bg-[#F8F9FA] rounded-[1.5rem] p-6 transition hover:bg-[#EAECEF] hover:shadow-sm group border border-slate-100">
                            <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-2 block">DURABILITÉ</span>
                            <h4 class="text-[1rem] font-bold text-[#0A1A3A] leading-[1.3] group-hover:text-[#214f95] transition-colors">Les toits verts de Marrakech : une révolution</h4>
                        </a>
                        <a href="#" class="bg-[#F8F9FA] rounded-[1.5rem] p-6 transition hover:bg-[#EAECEF] hover:shadow-sm group border border-slate-100">
                            <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-2 block">SOCIÉTÉ</span>
                            <h4 class="text-[1rem] font-bold text-[#0A1A3A] leading-[1.3] group-hover:text-[#214f95] transition-colors">L'inclusion sociale par l'espace public</h4>
                        </a>
                    </div>
                </div>

                <!-- Newsletter Sidebar Box -->
                <div class="bg-[#0A1633] rounded-[2rem] p-8 relative overflow-hidden shadow-[0_20px_40px_rgba(0,0,0,0.1)]">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-[#162D5A] rounded-full mix-blend-screen filter blur-[40px] opacity-70 pointer-events-none"></div>
                    <div class="relative z-10">
                        <h3 class="text-[1.3rem] font-bold text-white leading-tight mb-3">Restez informé</h3>
                        <p class="text-[0.85rem] text-slate-400 font-medium mb-6 leading-relaxed">
                            Recevez nos dernières analyses sur l'urbanisme chaque mois.
                        </p>
                        @livewire('newsletter.subscribe-form', ['stack' => true])
                    </div>
                </div>

            </aside>
        </div>
    </div>
</main>
@endsection
