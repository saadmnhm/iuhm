@include('livewire.admin.partials.jodit-editor-assets')

<div>
    {{-- Error toast notification --}}
    <div id="iuhm-editor-toast" role="alert">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        <span class="toast-msg"></span>
        <button type="button" class="toast-close" onclick="const el = document.getElementById('iuhm-editor-toast'); if(el) { el.classList.add('hidden'); el.classList.remove('visible'); }" aria-label="Fermer">&#x2715;</button>
    </div>

    {{-- ═══════ CREATE / EDIT FORM ═══════ --}}
    <form wire:submit.prevent="save">
        <div class="min-h-screen bg-[#FAFAFC] px-6 pb-12 pt-8 sm:px-8 sm:pt-10">

            {{-- Page Header --}}
            <div class="mb-10">
                <button type="button" wire:click="resetForm()"
                        class="mb-5 inline-flex items-center gap-2 text-[0.8rem] font-extrabold uppercase tracking-widest text-[#4A5568] transition-colors hover:text-[#0B1528]">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à la gestion {{ $sectionLabel }}
                </button>

                <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <span class="mb-4 inline-flex items-center gap-2 rounded-full bg-[#82E682] px-4 py-1.5 text-[0.7rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-sm">
                            <i class="{{ $meta['icon'] }} text-base"></i>
                            {{ $editMode ? 'MODIFICATION' : 'NOUVEAU CONTENU' }}
                        </span>
                        <h2 class="text-[2rem] font-bold leading-[1.1] text-[#0B1528] sm:text-[2.5rem]">
                            {{ $editMode ? 'Modifier le contenu ' : 'Créer un contenu ' }}{{ $sectionLabel }}
                        </h2>
                        <p class="mt-2 max-w-lg text-[0.9rem] leading-relaxed text-[#4A5568]">
                            {{ $editMode
                                ? 'Mettez à jour le contenu, la galerie et les métadonnées.'
                                : 'Rédigez, illustrez et publiez un contenu pour la section '.$sectionLabel.'.' }}
                        </p>
                    </div>

                    <div class="flex shrink-0 items-center gap-3 sm:pt-2">
                        <button type="button" wire:click="resetForm()"
                                class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-[0.85rem] font-bold text-[#4A5568] shadow-sm transition-colors hover:bg-[#EAECEF]">
                            Annuler
                        </button>
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-full bg-[#0B1528] px-7 py-2.5 text-[0.85rem] font-bold text-white shadow-md shadow-slate-800/20 transition-colors hover:bg-[#162D5A]">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $editMode ? 'Mettre à jour' : 'Publier le contenu' }}
                        </button>
                    </div>
                </div>
            </div>

            @if(session()->has('success'))
                <div class="mb-6 flex items-center gap-3 rounded-3xl border border-[#82E682]/50 bg-[#f0fbf0] px-5 py-4 text-sm text-[#0a471b] shadow-sm">
                    <svg class="size-5 shrink-0 text-[#23803B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session()->has('error'))
                <div class="mb-6 flex items-center gap-3 rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800 shadow-sm">
                    <svg class="size-5 shrink-0 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Form tabs --}}
            <div x-data="{ formTab: 'content' }">
                <div class="mb-6 inline-flex flex-wrap gap-1 rounded-full border border-slate-200 bg-white p-1 shadow-sm">
                    <button type="button" @click="formTab = 'content'"
                            :class="formTab === 'content' ? 'bg-[#0B1528] text-white shadow-sm' : 'text-[#4A5568] hover:text-[#0B1528]'"
                            class="rounded-full px-5 py-2 text-[0.78rem] font-extrabold transition-colors">
                        Contenu &amp; médias
                    </button>
                    <button type="button" @click="formTab = 'seo'"
                            :class="formTab === 'seo' ? 'bg-[#0B1528] text-white shadow-sm' : 'text-[#4A5568] hover:text-[#0B1528]'"
                            class="inline-flex items-center gap-2 rounded-full px-5 py-2 text-[0.78rem] font-extrabold transition-colors">
                        SEO &amp; partage
                        <span class="rounded-full bg-[#82E682] px-2 py-0.5 text-[0.6rem] font-extrabold text-[#0a471b]">{{ $seo['analysis']['score'] }}</span>
                    </button>
                </div>

                <div x-show="formTab === 'content'" class="grid gap-6 lg:grid-cols-3">

                    {{-- LEFT (2/3): Main content --}}
                    <div class="space-y-6 lg:col-span-2">

                        {{-- Title --}}
                        <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                            <label class="mb-3 block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">
                                Titre du contenu
                            </label>
                            <input type="text" wire:model="title"
                                   placeholder="Saisissez un titre accrocheur…"
                                   class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-5 py-4 text-[1.35rem] font-bold text-[#0B1528] outline-none transition placeholder:text-base placeholder:font-normal placeholder:text-slate-300 focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8">
                            @error('title') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Excerpt --}}
                        <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                            <label class="mb-3 block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">
                                Résumé / Introduction
                            </label>
                            <textarea wire:model="excerpt" rows="3" maxlength="500"
                                      placeholder="Rédigez une accroche qui présente le contenu…"
                                      class="w-full resize-none rounded-2xl border border-slate-100 bg-[#FAFAFC] px-5 py-3.5 text-[0.95rem] italic leading-relaxed text-[#4A5568] outline-none transition placeholder:not-italic placeholder:text-slate-300 focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8"></textarea>
                            @error('excerpt') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Rich Text Editor --}}
                        <div class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white shadow-sm shadow-slate-200">
                            <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
                                <span class="size-2.5 shrink-0 rounded-full bg-[#82E682]"></span>
                                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Contenu {{ $sectionLabel }}</span>
                            </div>
                            <div x-data="iuhmEditor" wire:ignore>
                                <textarea x-ref="editorTextarea"></textarea>
                            </div>
                            @error('content') <p class="px-6 pb-4 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Arabic localisation --}}
                        <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                            <div class="mb-4 flex items-center gap-3">
                                <span class="flex size-9 items-center justify-center rounded-xl bg-[#EAECEF] text-sm font-extrabold text-slate-600">AR</span>
                                <div>
                                    <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Version arabe (optionnel)</p>
                                    <p class="text-[0.72rem] text-slate-400">Affichée automatiquement quand la langue du site est l'arabe.</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <input type="text" wire:model="title_ar" dir="rtl"
                                       placeholder="عنوان المحتوى"
                                       class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-5 py-3.5 text-[1rem] font-bold text-[#0B1528] outline-none transition placeholder:text-slate-300 focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8">
                                @error('title_ar') <p class="text-xs font-medium text-rose-500">{{ $message }}</p> @enderror

                                <textarea wire:model="excerpt_ar" rows="3" dir="rtl"
                                          placeholder="ملخص المحتوى"
                                          class="w-full resize-none rounded-2xl border border-slate-100 bg-[#FAFAFC] px-5 py-3.5 text-[0.95rem] leading-relaxed text-[#4A5568] outline-none transition placeholder:text-slate-300 focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8"></textarea>
                                @error('excerpt_ar') <p class="text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                    </div>

                    {{-- RIGHT (1/3): Metadata --}}
                    <div class="space-y-6">

                        {{-- Cover Image --}}
                        <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                            <p class="mb-4 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Image de couverture</p>

                            @if($newImage)
                                <div class="relative mb-4 h-40 w-full overflow-hidden rounded-[1.25rem]">
                                    <img src="{{ $newImage->temporaryUrl() }}" class="h-full w-full object-cover" alt="Aperçu de la nouvelle image">
                                    <span class="absolute left-3 top-3 inline-flex items-center rounded-full bg-[#214f95] px-3 py-1 text-[0.6rem] font-extrabold uppercase tracking-widest text-white shadow-sm">Nouvelle image</span>
                                </div>
                            @elseif($image)
                                <div class="relative mb-4 h-40 w-full overflow-hidden rounded-[1.25rem]">
                                    <img src="{{ $this->mediaUrl($image) }}" class="h-full w-full object-cover" alt="Image de couverture actuelle">
                                    <span class="absolute left-3 top-3 inline-flex items-center rounded-full bg-[#82E682] px-3 py-1 text-[0.6rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-sm">Image actuelle</span>
                                </div>
                            @endif

                            <label class="group flex cursor-pointer flex-col items-center justify-center rounded-[1.25rem] border-2 border-dashed border-slate-200 bg-[#FAFAFC] py-8 text-center transition hover:border-[#82E682] hover:bg-[#f0fbf0]">
                                <div class="mb-3 flex size-14 items-center justify-center rounded-full bg-white shadow-sm transition group-hover:bg-[#82E682]/10">
                                    <i class="ri-image-add-line text-xl text-slate-400 transition group-hover:text-[#23803B]"></i>
                                </div>
                                <p class="text-sm font-bold text-[#0B1528]">{{ $image || $newImage ? 'Remplacer la couverture' : 'Télécharger la couverture' }}</p>
                                <p class="mt-1 text-xs text-slate-400">JPG, PNG ou WebP — 2 Mo max</p>
                                <input type="file" wire:model="newImage" accept="image/jpeg,image/png,image/webp" class="hidden">
                            </label>
                            <div wire:loading wire:target="newImage" class="mt-2 text-xs font-semibold text-[#214f95]">Téléversement en cours…</div>
                            @error('newImage') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror

                            @if($image || $newImage)
                                <button type="button" wire:click="removeImage"
                                        class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-100">
                                    <i class="ri-delete-bin-6-line text-sm"></i>
                                    Supprimer l'image
                                </button>
                            @endif
                        </div>

                        @include('livewire.admin.partials.gallery-manager')

                        {{-- Publication Status --}}
                        <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                            <p class="mb-4 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Statut</p>
                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" wire:click="$set('is_published', false)"
                                        class="flex flex-col items-center gap-2 rounded-2xl border-2 px-4 py-4 text-center transition
                                               {{ !$is_published ? 'border-[#0B1528] bg-[#0B1528] text-white' : 'border-slate-200 bg-[#FAFAFC] text-slate-500 hover:border-slate-300' }}">
                                    <i class="ri-draft-line text-lg"></i>
                                    <span class="text-xs font-extrabold uppercase tracking-wider">Brouillon</span>
                                </button>
                                <button type="button" wire:click="$set('is_published', true)"
                                        class="flex flex-col items-center gap-2 rounded-2xl border-2 px-4 py-4 text-center transition
                                               {{ $is_published ? 'border-[#82E682] bg-[#82E682] text-[#0a471b]' : 'border-slate-200 bg-[#FAFAFC] text-slate-500 hover:border-[#82E682]/50' }}">
                                    <i class="ri-check-line text-lg"></i>
                                    <span class="text-xs font-extrabold uppercase tracking-wider">Publié</span>
                                </button>
                            </div>
                        </div>

                        {{-- Category --}}
                        <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                            <p class="mb-3 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Catégorie</p>
                            <input type="text" wire:model="category" list="section-categories"
                                   placeholder="Ex. communauté, éducation…"
                                   class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-[0.9rem] font-semibold text-[#0B1528] outline-none transition focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8">
                            <datalist id="section-categories">
                                <option value="actualite"></option>
                                <option value="evenement"></option>
                                <option value="projet"></option>
                                <option value="communaute"></option>
                                <option value="formation"></option>
                            </datalist>
                            @if($category)
                                <div class="mt-3">
                                    <span class="inline-flex items-center rounded-full bg-[#82E682] px-4 py-1.5 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-sm">
                                        {{ $category }}
                                    </span>
                                </div>
                            @endif
                            @error('category') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tags --}}
                        <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                            <p class="mb-3 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Mots-clés</p>
                            <input type="text" wire:model="tags_input"
                                   placeholder="clc, communauté, initiative"
                                   class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-[0.9rem] font-semibold text-[#0B1528] outline-none transition focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8">
                            <p class="mt-2 text-[0.7rem] text-slate-400">Séparez les mots-clés par des virgules.</p>
                            @error('tags_input') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Sort order --}}
                        <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                            <p class="mb-3 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Ordre d'affichage</p>
                            <input type="number" min="0" wire:model="sort_order"
                                   class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-[0.9rem] font-semibold text-[#0B1528] outline-none transition focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8">
                            <p class="mt-2 text-[0.7rem] text-slate-400">Les valeurs les plus basses apparaissent d'abord.</p>
                            @error('sort_order') <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>

                <div x-show="formTab === 'seo'" x-cloak class="mt-6">
                    @include('livewire.admin.partials.seo-editor', ['seo' => $seo])
                </div>
            </div>

        </div>
    </form>
</div>
