@php
    $analysis = $seo['analysis'];
    $score = $analysis['score'];
    $status = $analysis['status'];
    $titleLength = $seo['titleLength'];
    $descriptionLength = $seo['descriptionLength'];

    $scoreRing = match ($status) {
        'good' => 'border-emerald-500 text-emerald-600 bg-emerald-50',
        'improvement' => 'border-amber-500 text-amber-600 bg-amber-50',
        default => 'border-rose-500 text-rose-600 bg-rose-50',
    };
    $scoreBadge = match ($status) {
        'good' => 'bg-emerald-100 text-emerald-700',
        'improvement' => 'bg-amber-100 text-amber-700',
        default => 'bg-rose-100 text-rose-700',
    };
    $titleCounter = $titleLength === 0
        ? 'text-rose-500'
        : ($titleLength >= 30 && $titleLength <= 60 ? 'text-emerald-600' : ($titleLength <= 70 ? 'text-amber-600' : 'text-rose-500'));
    $descriptionCounter = $descriptionLength === 0
        ? 'text-rose-500'
        : ($descriptionLength >= 120 && $descriptionLength <= 156 ? 'text-emerald-600' : ($descriptionLength <= 170 ? 'text-amber-600' : 'text-rose-500'));

    $checkStyles = [
        'good' => ['bg-emerald-50 text-emerald-600 border-emerald-100', 'M5 13l4 4L19 7', 'text-emerald-700'],
        'improvement' => ['bg-amber-50 text-amber-600 border-amber-100', 'M12 9v3m0 4h.01', 'text-amber-700'],
        'error' => ['bg-rose-50 text-rose-600 border-rose-100', 'M6 18L18 6M6 6l12 12', 'text-rose-700'],
    ];

    $inputClass = 'w-full rounded-2xl border border-slate-200 bg-[#FAFAFC] px-4 py-3 text-[0.9rem] font-medium text-[#0B1528] placeholder:text-slate-300 outline-none focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8 transition';
    $labelClass = 'block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-2';
@endphp

<div x-data="{ seoTab: 'general' }" class="rounded-[2rem] border border-slate-200/80 bg-white shadow-sm shadow-slate-200 overflow-hidden">

    {{-- Header --}}
    <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <div class="flex size-11 items-center justify-center rounded-2xl bg-[#0B1528] text-[#82E682] shadow-sm">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.6-5.4a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-[1.05rem] font-bold text-[#0B1528]">SEO &amp; Optimisation</h3>
                <p class="text-[0.75rem] font-medium text-slate-400">Référencement, aperçus Google et partage social</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-2 rounded-full {{ $scoreBadge }} px-4 py-1.5 text-[0.68rem] font-extrabold uppercase tracking-widest">
                <span class="size-2 rounded-full bg-current"></span>
                {{ $analysis['label'] }} · {{ $score }}/100
            </span>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex flex-wrap gap-1 border-b border-slate-100 bg-[#FAFAFC] px-4 py-2">
        @foreach([
            'general' => 'SEO de base',
            'analysis' => 'Analyse',
            'social' => 'Réseaux sociaux',
            'advanced' => 'Avancé',
        ] as $key => $label)
            <button type="button" @click="seoTab = '{{ $key }}'"
                    :class="seoTab === '{{ $key }}' ? 'bg-[#0B1528] text-white shadow-sm' : 'text-[#4A5568] hover:bg-white hover:text-[#0B1528]'"
                    class="rounded-full px-4 py-2 text-[0.75rem] font-extrabold transition-colors">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- ── General ── --}}
    <div x-show="seoTab === 'general'" class="grid gap-8 p-6 lg:grid-cols-[minmax(0,1fr)_360px] lg:p-8">

        <div class="space-y-5">
            <div>
                <label class="{{ $labelClass }}">Mot-clé principal</label>
                <input type="text" wire:model.live.debounce.500ms="focus_keyword"
                       placeholder="ex. urbanisme durable Casablanca"
                       class="{{ $inputClass }}">
                @error('focus_keyword') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                <p class="mt-2 text-[0.7rem] text-slate-400">Le mot-clé sur lequel cette page doit se positionner.</p>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label class="{{ $labelClass }}">Titre SEO (meta title)</label>
                    <span class="text-[0.68rem] font-bold {{ $titleCounter }}">{{ $titleLength }} / 60</span>
                </div>
                <input type="text" wire:model.live.debounce.500ms="seo_title"
                       placeholder="{{ $title ?: 'Titre affiché dans Google' }}"
                       class="{{ $inputClass }}">
                @error('seo_title') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                <p class="mt-2 text-[0.7rem] text-slate-400">Laissez vide pour utiliser automatiquement le titre du contenu.</p>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label class="{{ $labelClass }}">Méta description</label>
                    <span class="text-[0.68rem] font-bold {{ $descriptionCounter }}">{{ $descriptionLength }} / 156</span>
                </div>
                <textarea wire:model.live.debounce.500ms="meta_description" rows="4"
                          placeholder="Décrivez la page en 120 à 156 caractères pour inciter au clic…"
                          class="{{ $inputClass }} resize-none leading-relaxed"></textarea>
                @error('meta_description') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Slug de l’URL</label>
                <div class="flex items-center overflow-hidden rounded-2xl border border-slate-200 bg-[#FAFAFC] focus-within:border-[#0B1528] focus-within:bg-white focus-within:ring-4 focus-within:ring-[#0B1528]/8 transition">
                    <span class="shrink-0 border-r border-slate-200 px-3 py-3 text-[0.72rem] font-semibold text-slate-400">{{ rtrim(config('app.url'), '/') }}/</span>
                    <input type="text" wire:model.live.debounce.500ms="slug"
                           placeholder="mon-slug-seo"
                           class="w-full border-0 bg-transparent px-3 py-3 text-[0.9rem] font-medium text-[#0B1528] outline-none">
                </div>
                @error('slug') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                <p class="mt-2 text-[0.7rem] text-slate-400">Généré automatiquement depuis le titre, modifiable manuellement. Doit rester unique.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">URL canonique</label>
                    <input type="url" wire:model.live.debounce.500ms="canonical_url"
                           placeholder="Laisser vide = URL automatique"
                           class="{{ $inputClass }}">
                    @error('canonical_url') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Mots-clés (facultatif)</label>
                    <input type="text" wire:model.live.debounce.500ms="meta_keywords"
                           placeholder="urbanisme, casablanca, durabilité"
                           class="{{ $inputClass }}">
                    @error('meta_keywords') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-[#FAFAFC] p-4">
                    <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Indexation</p>
                    <div class="mt-3 flex gap-2">
                        <button type="button" wire:click="$set('robots_index', true)"
                                class="flex-1 rounded-xl border-2 px-3 py-2 text-xs font-extrabold transition {{ $robots_index ? 'border-[#82E682] bg-[#82E682] text-[#0a471b]' : 'border-slate-200 bg-white text-slate-500' }}">
                            Index
                        </button>
                        <button type="button" wire:click="$set('robots_index', false)"
                                class="flex-1 rounded-xl border-2 px-3 py-2 text-xs font-extrabold transition {{ ! $robots_index ? 'border-[#0B1528] bg-[#0B1528] text-white' : 'border-slate-200 bg-white text-slate-500' }}">
                            No index
                        </button>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-[#FAFAFC] p-4">
                    <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Suivi des liens</p>
                    <div class="mt-3 flex gap-2">
                        <button type="button" wire:click="$set('robots_follow', true)"
                                class="flex-1 rounded-xl border-2 px-3 py-2 text-xs font-extrabold transition {{ $robots_follow ? 'border-[#82E682] bg-[#82E682] text-[#0a471b]' : 'border-slate-200 bg-white text-slate-500' }}">
                            Follow
                        </button>
                        <button type="button" wire:click="$set('robots_follow', false)"
                                class="flex-1 rounded-xl border-2 px-3 py-2 text-xs font-extrabold transition {{ ! $robots_follow ? 'border-[#0B1528] bg-[#0B1528] text-white' : 'border-slate-200 bg-white text-slate-500' }}">
                            No follow
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Google preview --}}
        <div class="space-y-5">
            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm">
                <p class="mb-4 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Aperçu Google — ordinateur</p>
                <div class="rounded-xl bg-white p-2">
                    <div class="flex items-center gap-2">
                        <span class="flex size-7 items-center justify-center rounded-full bg-slate-100 text-[0.55rem] font-bold text-slate-500">IU</span>
                        <div class="min-w-0">
                            <p class="truncate text-[0.72rem] font-medium leading-tight text-slate-600">{{ $seo['displayUrl'] }}</p>
                            <p class="text-[0.62rem] text-slate-400">{{ config('app.name') }}</p>
                        </div>
                    </div>
                    <p class="mt-2 line-clamp-2 text-[1.05rem] font-medium leading-snug text-[#1a0dab]">{{ $seo['metaTitleWithBrand'] }}</p>
                    <p class="mt-1 line-clamp-2 text-[0.8rem] leading-snug text-[#4d5156]">
                        {{ $seo['description'] ?: 'Ajoutez une méta description pour contrôler cet aperçu.' }}
                    </p>
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm">
                <p class="mb-4 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Aperçu Google — mobile</p>
                <div class="mx-auto max-w-[260px] rounded-xl border border-slate-100 bg-white p-3 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="flex size-6 items-center justify-center rounded-full bg-slate-100 text-[0.5rem] font-bold text-slate-500">IU</span>
                        <div class="min-w-0">
                            <p class="truncate text-[0.68rem] font-medium text-slate-600">{{ $seo['displayUrl'] }}</p>
                            <p class="text-[0.58rem] text-slate-400">{{ config('app.name') }}</p>
                        </div>
                    </div>
                    <p class="mt-2 line-clamp-2 text-[0.92rem] font-medium leading-snug text-[#1a0dab]">{{ $seo['metaTitleWithBrand'] }}</p>
                    <p class="mt-1 line-clamp-2 text-[0.72rem] leading-snug text-[#4d5156]">
                        {{ $seo['description'] ?: 'Ajoutez une méta description.' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-[#FAFAFC] p-4">
                <div class="flex size-11 items-center justify-center rounded-full border-4 {{ $scoreRing }} text-sm font-extrabold">
                    {{ $score }}
                </div>
                <div>
                    <p class="text-sm font-bold text-[#0B1528]">{{ $analysis['label'] }}</p>
                    <p class="text-[0.72rem] text-slate-400">Score SEO global</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Analysis ── --}}
    <div x-show="seoTab === 'analysis'" class="p-6 lg:p-8">
        <div class="mb-6 flex flex-col gap-4 rounded-[1.5rem] border border-slate-100 bg-[#FAFAFC] p-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex size-16 items-center justify-center rounded-full border-4 {{ $scoreRing }} text-xl font-extrabold">{{ $score }}</div>
                <div>
                    <p class="text-[1.05rem] font-bold text-[#0B1528]">Score d’optimisation : {{ $analysis['label'] }}</p>
                    <p class="text-[0.8rem] text-slate-500">Appliquez les recommandations ci-dessous pour améliorer le référencement.</p>
                </div>
            </div>
            <span class="inline-flex w-fit items-center rounded-full {{ $scoreBadge }} px-4 py-1.5 text-[0.68rem] font-extrabold uppercase tracking-widest">
                {{ $score }} / 100
            </span>
        </div>

        <div class="space-y-3">
            @foreach($analysis['checks'] as $check)
                @php
                    [$pillClasses, $iconPath, $textClass] = $checkStyles[$check['status']] ?? $checkStyles['improvement'];
                @endphp
                <div class="flex items-start gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-full border {{ $pillClasses }}">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
                        </svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="text-[0.88rem] font-bold text-[#0B1528]">{{ $check['label'] }}</p>
                            <span class="rounded-full px-2 py-0.5 text-[0.6rem] font-extrabold uppercase tracking-wider {{ $pillClasses }}">
                                {{ $check['status'] === 'good' ? 'Bon' : ($check['status'] === 'improvement' ? 'À améliorer' : 'Erreur') }}
                            </span>
                        </div>
                        <p class="mt-1 text-[0.8rem] leading-relaxed {{ $textClass }}">{{ $check['message'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── Social ── --}}
    <div x-show="seoTab === 'social'" class="grid gap-8 p-6 lg:grid-cols-[minmax(0,1fr)_360px] lg:p-8">
        <div class="space-y-5">
            <div class="rounded-2xl border border-slate-200 bg-[#FAFAFC] p-4">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Open Graph (Facebook / LinkedIn)</p>
                <div class="mt-3 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="{{ $labelClass }}">Titre Open Graph</label>
                        <input type="text" wire:model.live.debounce.500ms="og_title" class="{{ $inputClass }}">
                        @error('og_title') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Type Open Graph</label>
                        <select wire:model.live="og_type" class="{{ $inputClass }}">
                            <option value="article">Article</option>
                            <option value="website">Site web</option>
                            <option value="product">Produit</option>
                            <option value="profile">Profil</option>
                            <option value="book">Livre</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="{{ $labelClass }}">Description Open Graph</label>
                    <textarea wire:model.live.debounce.500ms="og_description" rows="3" class="{{ $inputClass }} resize-none"></textarea>
                    @error('og_description') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                </div>
                <div class="mt-4">
                    <label class="{{ $labelClass }}">Image Open Graph</label>
                    <input type="text" wire:model.live.debounce.500ms="og_image" placeholder="uploads/seo/og-image.jpg" class="{{ $inputClass }}">
                    @error('og_image') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                    <div class="mt-2 flex flex-wrap items-center gap-3">
                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-bold text-[#4A5568] border border-slate-200 hover:border-[#82E682]">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 7h16v10H4z"/></svg>
                            Téléverser
                            <input type="file" wire:model="newOgImage" accept="image/jpeg,image/png,image/webp" class="hidden">
                        </label>
                        @if($og_image || $newOgImage)
                            <button type="button" wire:click="$set('og_image', null)"
                                    class="text-xs font-bold text-rose-500 hover:text-rose-700">Retirer</button>
                        @endif
                        <span wire:loading wire:target="newOgImage" class="text-xs font-semibold text-[#214f95]">Envoi…</span>
                    </div>
                    @error('newOgImage') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-[#FAFAFC] p-4">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Twitter / X</p>
                <div class="mt-3 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="{{ $labelClass }}">Type de carte</label>
                        <select wire:model.live="twitter_card" class="{{ $inputClass }}">
                            <option value="summary_large_image">Grande image</option>
                            <option value="summary">Résumé</option>
                            <option value="app">Application</option>
                            <option value="player">Lecteur</option>
                        </select>
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Titre Twitter</label>
                        <input type="text" wire:model.live.debounce.500ms="twitter_title" class="{{ $inputClass }}">
                        @error('twitter_title') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label class="{{ $labelClass }}">Description Twitter</label>
                    <textarea wire:model.live.debounce.500ms="twitter_description" rows="3" class="{{ $inputClass }} resize-none"></textarea>
                    @error('twitter_description') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                </div>
                <div class="mt-4">
                    <label class="{{ $labelClass }}">Image Twitter</label>
                    <input type="text" wire:model.live.debounce.500ms="twitter_image" placeholder="uploads/seo/twitter-image.jpg" class="{{ $inputClass }}">
                    @error('twitter_image') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                    <div class="mt-2 flex flex-wrap items-center gap-3">
                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-bold text-[#4A5568] border border-slate-200 hover:border-[#82E682]">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 7h16v10H4z"/></svg>
                            Téléverser
                            <input type="file" wire:model="newTwitterImage" accept="image/jpeg,image/png,image/webp" class="hidden">
                        </label>
                        @if($twitter_image || $newTwitterImage)
                            <button type="button" wire:click="$set('twitter_image', null)"
                                    class="text-xs font-bold text-rose-500 hover:text-rose-700">Retirer</button>
                        @endif
                        <span wire:loading wire:target="newTwitterImage" class="text-xs font-semibold text-[#214f95]">Envoi…</span>
                    </div>
                    @error('newTwitterImage') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="space-y-5">
            {{-- Facebook preview --}}
            <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-4 py-3">
                    <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Aperçu Facebook</p>
                </div>
                <div class="aspect-[1.91/1] w-full overflow-hidden bg-slate-100">
                    @if($seo['ogImage'])
                        <img src="{{ $seo['ogImage'] }}" alt="Aperçu de l’image de partage" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full items-center justify-center text-xs font-semibold text-slate-400">Aucune image sélectionnée</div>
                    @endif
                </div>
                <div class="bg-[#f2f3f5] px-4 py-3">
                    <p class="text-[0.65rem] font-medium uppercase tracking-wide text-slate-500">{{ parse_url($seo['url'], PHP_URL_HOST) ?: config('app.name') }}</p>
                    <p class="mt-1 line-clamp-2 text-[0.95rem] font-bold leading-snug text-[#1c1e21]">{{ $seo['ogTitle'] }}</p>
                    <p class="mt-1 line-clamp-2 text-[0.78rem] leading-snug text-[#606770]">{{ $seo['ogDescription'] }}</p>
                </div>
            </div>

            {{-- Twitter preview --}}
            <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-4 py-3">
                    <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Aperçu Twitter / X</p>
                </div>
                <div class="p-4">
                    <div class="overflow-hidden rounded-2xl border border-slate-200">
                        <div class="aspect-[1.91/1] w-full overflow-hidden bg-slate-100">
                            @if($seo['twitterImage'])
                                <img src="{{ $seo['twitterImage'] }}" alt="Aperçu de l’image Twitter" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center text-xs font-semibold text-slate-400">Aucune image sélectionnée</div>
                            @endif
                        </div>
                        <div class="px-3 py-2">
                            <p class="line-clamp-1 text-[0.82rem] font-bold text-[#0f1419]">{{ $seo['twitterTitle'] }}</p>
                            <p class="mt-0.5 line-clamp-2 text-[0.72rem] leading-snug text-[#536471]">{{ $seo['twitterDescription'] }}</p>
                            <p class="mt-1 text-[0.65rem] text-[#536471]">{{ parse_url($seo['url'], PHP_URL_HOST) ?: config('app.name') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Advanced ── --}}
    <div x-show="seoTab === 'advanced'" class="grid gap-8 p-6 lg:grid-cols-2 lg:p-8">
        <div class="space-y-5">
            <div>
                <label class="{{ $labelClass }}">Image SEO / image de partage par défaut</label>
                <input type="text" wire:model.live.debounce.500ms="seo_image" placeholder="uploads/seo/image-seo.jpg" class="{{ $inputClass }}">
                @error('seo_image') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                <div class="mt-3 flex flex-wrap items-center gap-3">
                    <label class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-[#4A5568] hover:border-[#82E682]">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 7h16v10H4z"/></svg>
                        Téléverser une image SEO
                        <input type="file" wire:model="newSeoImage" accept="image/jpeg,image/png,image/webp" class="hidden">
                    </label>
                    @if($seo_image || $newSeoImage)
                        <button type="button" wire:click="$set('seo_image', null)" class="text-xs font-bold text-rose-500 hover:text-rose-700">Retirer</button>
                    @endif
                    <span wire:loading wire:target="newSeoImage" class="text-xs font-semibold text-[#214f95]">Envoi…</span>
                </div>
                @error('newSeoImage') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                @if($seo['seoImage'])
                    <div class="mt-3 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                        <img src="{{ $seo['seoImage'] }}" alt="Aperçu de l’image SEO" class="h-32 w-full object-cover">
                    </div>
                @endif
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Type de données structurées</label>
                    <select wire:model.live="schema_type" class="{{ $inputClass }}">
                        <option value="">Automatique</option>
                        <option value="Article">Article</option>
                        <option value="NewsArticle">NewsArticle</option>
                        <option value="BlogPosting">BlogPosting</option>
                        <option value="Project">Project</option>
                        <option value="CreativeWork">CreativeWork</option>
                        <option value="WebPage">WebPage</option>
                    </select>
                    @error('schema_type') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Libellé du fil d’Ariane</label>
                    <input type="text" wire:model.live.debounce.500ms="breadcrumb_label" placeholder="Titre affiché dans le fil d’Ariane" class="{{ $inputClass }}">
                    @error('breadcrumb_label') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-[#FAFAFC] px-4 py-3">
                <div>
                    <p class="text-sm font-bold text-[#0B1528]">Fil d’Ariane structuré</p>
                    <p class="text-[0.7rem] text-slate-400">Ajoute un BreadcrumbList dans les données structurées.</p>
                </div>
                <button type="button" wire:click="$set('breadcrumb_enabled', {{ $breadcrumb_enabled ? 'false' : 'true' }})"
                        class="relative inline-flex h-6 w-11 shrink-0 rounded-full border-2 border-transparent transition-colors {{ $breadcrumb_enabled ? 'bg-[#82E682]' : 'bg-slate-200' }}">
                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow transition {{ $breadcrumb_enabled ? 'translate-x-5' : 'translate-x-0' }}"></span>
                </button>
            </div>

            <div>
                <label class="{{ $labelClass }}">Données structurées personnalisées (JSON-LD)</label>
                <textarea wire:model.live.debounce.700ms="schema_data" rows="5" placeholder='{"@type":"Article","about":"..."}'
                          class="{{ $inputClass }} font-mono text-[0.78rem] leading-relaxed"></textarea>
                @error('schema_data') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                <p class="mt-2 text-[0.7rem] text-slate-400">Fusionnées avec le schéma généré automatiquement. Laissez vide pour utiliser le schéma par défaut.</p>
            </div>
        </div>

        <div class="space-y-5">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Auteur (schema)</label>
                    <input type="text" wire:model.live.debounce.500ms="seo_author_name" placeholder="Nom de l’auteur" class="{{ $inputClass }}">
                    @error('seo_author_name') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Éditeur / Publisher</label>
                    <input type="text" wire:model.live.debounce.500ms="seo_publisher_name" placeholder="{{ config('app.name') }}" class="{{ $inputClass }}">
                    @error('seo_publisher_name') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Date de publication (schema)</label>
                    <input type="datetime-local" wire:model.live="schema_published_at" class="{{ $inputClass }}">
                    @error('schema_published_at') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Dernière modification (schema)</label>
                    <input type="datetime-local" wire:model.live="schema_modified_at" class="{{ $inputClass }}">
                    @error('schema_modified_at') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-slate-200 bg-[#FAFAFC] p-5">
                <p class="mb-3 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Aperçu du fil d’Ariane</p>
                <div class="flex flex-wrap items-center gap-2 text-[0.8rem] font-semibold text-[#4A5568]">
                    <span>{{ __('ui.home') }}</span>
                    <span class="text-slate-300">›</span>
                    <span>{{ $seo['section'] ?: 'Section' }}</span>
                    @if($breadcrumb_enabled)
                        <span class="text-slate-300">›</span>
                        <span class="text-[#0B1528]">{{ $breadcrumb_label ?: $title }}</span>
                    @endif
                </div>
                <p class="mt-3 text-[0.7rem] leading-relaxed text-slate-400">
                    Le fil d’Ariane est généré automatiquement (Accueil › Section › Titre) et exposé en JSON-LD pour Google.
                </p>
            </div>

            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5">
                <p class="mb-3 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">État de l’indexation</p>
                <div class="flex flex-wrap gap-2">
                    <span class="rounded-full px-3 py-1 text-[0.68rem] font-extrabold uppercase tracking-wider {{ $robots_index ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        {{ $robots_index ? 'index' : 'noindex' }}
                    </span>
                    <span class="rounded-full px-3 py-1 text-[0.68rem] font-extrabold uppercase tracking-wider {{ $robots_follow ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        {{ $robots_follow ? 'follow' : 'nofollow' }}
                    </span>
                    @if($canonical_url)
                        <span class="rounded-full bg-[#EAECEF] px-3 py-1 text-[0.68rem] font-bold text-slate-600">Canonique personnalisée</span>
                    @else
                        <span class="rounded-full bg-[#EAECEF] px-3 py-1 text-[0.68rem] font-bold text-slate-600">Canonique automatique</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
