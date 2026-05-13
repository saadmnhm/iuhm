@push('head-scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@4/es2021/jodit.fat.min.css">
<script src="https://cdn.jsdelivr.net/npm/jodit@4/es2021/jodit.fat.min.js"></script>
<style>
    /* ── Jodit container ── */
    .jodit-container:not(.jodit_inline) { border: none !important; border-radius: 0 !important; }
    .jodit-toolbar__box:not(:empty) { background: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; }
    .jodit-toolbar-button__button:hover, .jodit-toolbar-button_variant_initial.jodit-toolbar-button_isActive_true > .jodit-toolbar-button__button { background: rgba(130,230,130,.15) !important; color: #23803B !important; }
    .jodit-toolbar-button__icon svg { fill: currentColor; }

    /* ── Editor body ── */
    .jodit-wysiwyg { min-height: 460px !important; padding: 2rem 2.25rem !important; line-height: 1.85 !important; color: #4A5568 !important; font-family: inherit !important; font-size: 16px !important; }
    .jodit-wysiwyg h2 { font-size: 1.875rem; font-weight: 700; color: #0B1528; margin-top: 2.5rem; margin-bottom: 1.25rem; line-height: 1.2; }
    .jodit-wysiwyg h3 { font-size: 1.375rem; font-weight: 700; color: #0B1528; margin-top: 2rem; margin-bottom: 0.75rem; }
    .jodit-wysiwyg p { margin-bottom: 1.25rem; }
    .jodit-wysiwyg a { color: #214f95; }
    .jodit-wysiwyg blockquote { background: #F0F2F5; border-radius: 1.5rem; border-left: 6px solid #82E682; border-top: none; border-right: none; border-bottom: none; padding: 1.5rem 2rem; margin: 2rem 0; color: #0B1528; font-size: 1.05rem; font-style: normal; }
    .jodit-wysiwyg img { max-width: 100%; border-radius: 1.5rem; }
    .jodit-status-bar { display: none !important; }
</style>

<script>
document.addEventListener('alpine:init', () => {
    if (window.__iuhmEditorRegistered) return;
    window.__iuhmEditorRegistered = true;

    Alpine.data('iuhmEditor', () => ({
        editor: null,
        init() {
            const wire    = this.$wire;
            const textarea = this.$refs.editorTextarea;
            this.$nextTick(() => {
                this.editor = Jodit.make(textarea, {
                    language: 'fr',
                    minHeight: 460,
                    toolbarAdaptive: false,
                    showWordsCounter: false,
                    showCharsCounter: false,
                    showXPathInStatusbar: false,
                    buttons: [
                        'paragraph',
                        '|', 'bold', 'italic', 'underline', 'strikethrough',
                        '|', 'brush',
                        '|', 'ul', 'ol',
                        '|', 'link', 'image',
                        '|', 'blockquote',
                        '|', 'eraser',
                        '|', 'undo', 'redo',
                        '|', 'fullsize', 'source',
                    ],
                    uploader: {
                        insertImageAsBase64URI: true,
                    },
                    image: {
                        dialogWidth: 460,
                    },
                    events: {
                        change: (content) => {
                            wire.set('content', content);
                        },
                    },
                });

                const raw = wire.get('content');
                if (raw) this.editor.value = raw;

                // Sync when Livewire loads a post for editing
                wire.$watch('content', (val) => {
                    if (this.editor && val !== this.editor.value) {
                        this.editor.value = val || '';
                    }
                });
            });
        },
        destroy() {
            if (this.editor) {
                this.editor.destruct();
                this.editor = null;
            }
        },
    }));
});
</script>
@endpush

<div>
    <form wire:submit.prevent="save">
    <div class="px-6 pb-12 pt-8 sm:px-8 sm:pt-10 bg-[#FAFAFC] min-h-screen">

        {{-- Page Header --}}
        <div class="mb-10">
            <button type="button" wire:click="resetForm()"
                    class="mb-5 inline-flex items-center gap-2 text-[0.8rem] font-extrabold uppercase tracking-widest text-[#4A5568] hover:text-[#0B1528] transition-colors">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                RETOUR AUX ARTICLES
            </button>
            <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <span class="inline-flex items-center rounded-full bg-[#82E682] px-4 py-1.5 text-[0.7rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-sm mb-4">
                        {{ $editMode ? 'MODIFICATION' : 'NOUVEL ARTICLE' }}
                    </span>
                    <h2 class="text-[2rem] font-bold text-[#0B1528] leading-[1.1] sm:text-[2.5rem]">
                        {{ $editMode ? 'Modifier l\'article' : 'Créer un nouvel article' }}
                    </h2>
                    <p class="mt-2 text-[0.9rem] text-[#4A5568] max-w-lg leading-relaxed">
                        {{ $editMode ? 'Mettez à jour le contenu et les métadonnées de l\'article.' : 'Rédigez, sélectionnez et publiez des récits à fort impact pour le réseau communautaire.' }}
                    </p>
                </div>
                <div class="flex flex-shrink-0 items-center gap-3 sm:pt-2">
                    <button type="button" wire:click="resetForm()"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-[0.85rem] font-bold text-[#4A5568] hover:bg-[#EAECEF] transition-colors shadow-sm">
                        Annuler
                    </button>
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-[#0B1528] px-7 py-2.5 text-[0.85rem] font-bold text-white hover:bg-[#162D5A] transition-colors shadow-md shadow-slate-800/20">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $editMode ? 'Mettre à jour' : 'Publier l\'article' }}
                    </button>
                </div>
            </div>
        </div>

        @if(session()->has('success'))
        <div class="mb-6 flex items-center gap-3 rounded-[1.5rem] border border-[#82E682]/50 bg-[#f0fbf0] px-5 py-4 text-sm text-[#0a471b] shadow-sm">
            <svg class="size-5 text-[#23803B] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif
        @if(session()->has('error'))
        <div class="mb-6 flex items-center gap-3 rounded-[1.5rem] border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800 shadow-sm">
            <svg class="size-5 text-rose-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        @endif

        {{-- Two-column grid --}}
        <div class="grid gap-6 lg:grid-cols-3">

            {{-- LEFT (2/3): Main content --}}
            <div class="space-y-6 lg:col-span-2">

                {{-- Title --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-3">
                        Titre de l'article
                    </label>
                    <input type="text" wire:model="title"
                           placeholder="Saisissez un titre accrocheur…"
                           class="w-full rounded-2xl  border border-slate-100 bg-[#FAFAFC] px-5 py-4 text-[1.35rem] font-bold text-[#0B1528] placeholder:text-slate-300 placeholder:font-normal placeholder:text-base outline-none focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8 transition">
                    @error('title') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Excerpt --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-3">
                        Résumé / Introduction
                    </label>
                    <textarea wire:model="excerpt" rows="3"
                              placeholder="Rédigez une accroche qui donne envie de lire l'article complet…"
                              class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-5 py-3.5 text-[0.95rem] italic text-[#4A5568] placeholder:text-slate-300 placeholder:not-italic outline-none focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8 resize-none transition leading-relaxed"></textarea>
                    @error('excerpt') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Rich Text Editor --}}
                <div class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white shadow-sm shadow-slate-200">
                    <div class="border-b border-slate-100 px-6 py-4 flex items-center gap-3">
                        <span class="size-2.5 rounded-full bg-[#82E682] shrink-0"></span>
                        <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Contenu de l'article</span>
                    </div>
                    <div x-data="iuhmEditor"
                         wire:ignore>
                        <textarea x-ref="editorTextarea"></textarea>
                    </div>
                    @error('content') <p class="px-6 pb-4 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

            </div>

            {{-- RIGHT (1/3): Metadata --}}
            <div class="space-y-6">

                {{-- Featured Image --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-4">Image de couverture</p>
                    @if($image && !$newImage)
                    <div class="mb-4 overflow-hidden rounded-[1.25rem] relative h-40 w-full">
                        <img src="{{ \Illuminate\Support\Str::startsWith($image, ['http','https']) ? $image : asset($image) }}"
                             class="w-full h-full object-cover" alt="">
                        <span class="absolute top-3 left-3 inline-flex items-center rounded-full bg-[#82E682] px-3 py-1 text-[0.6rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-sm">Image actuelle</span>
                    </div>
                    @endif
                    <label class="flex cursor-pointer flex-col items-center justify-center rounded-[1.25rem] border-2 border-dashed border-slate-200 bg-[#FAFAFC] py-8 text-center hover:border-[#82E682] hover:bg-[#f0fbf0] transition group">
                        <div class="flex size-14 items-center justify-center rounded-full bg-white shadow-sm mb-3 group-hover:bg-[#82E682]/10 transition">
                            <svg class="size-6 text-slate-400 group-hover:text-[#23803B] transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-[#0B1528]">Télécharger la couverture</p>
                        <p class="mt-1 text-xs text-slate-400">1200 × 630px recommandé</p>
                        <input type="file" wire:model="newImage" accept="image/*" class="hidden">
                    </label>
                    @error('newImage') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Publication Status --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-4">Statut</p>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" wire:click="$set('is_published', false)"
                                class="flex flex-col items-center gap-2 rounded-2xl border-2 px-4 py-4 transition text-center
                                       {{ !$is_published ? 'border-[#0B1528] bg-[#0B1528] text-white' : 'border-slate-200 bg-[#FAFAFC] text-slate-500 hover:border-slate-300' }}">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span class="text-xs font-extrabold uppercase tracking-wider">Brouillon</span>
                        </button>
                        <button type="button" wire:click="$set('is_published', true)"
                                class="flex flex-col items-center gap-2 rounded-2xl border-2 px-4 py-4 transition text-center
                                       {{ $is_published ? 'border-[#82E682] bg-[#82E682] text-[#0a471b]' : 'border-slate-200 bg-[#FAFAFC] text-slate-500 hover:border-[#82E682]/50' }}">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-xs font-extrabold uppercase tracking-wider">Publié</span>
                        </button>
                    </div>
                </div>

                {{-- Category --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-3">Catégorie</p>
                    <select wire:model="category"
                            class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-[0.9rem] font-semibold text-[#0B1528] outline-none focus:border-[#0B1528] focus:ring-4 focus:ring-[#0B1528]/8 transition">
                        <option value="">Sélectionner une catégorie</option>
                        <option value="Urban Planning">Urban Planning</option>
                        <option value="Sustainability">Durabilité</option>
                        <option value="Community">Communauté</option>
                        <option value="Innovation">Innovation</option>
                        <option value="Architecture">Architecture</option>
                    </select>
                    @if($category)
                    <div class="mt-3">
                        <span class="inline-flex items-center rounded-full bg-[#82E682] px-4 py-1.5 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-sm">
                            {{ $category }}
                        </span>
                    </div>
                    @endif
                    @error('category') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Tags --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-3">Tags</p>
                    <input type="text" wire:model="tags_input"
                           placeholder="architecture, urbanisme, société…"
                           class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-sm outline-none focus:border-[#0B1528] focus:ring-4 focus:ring-[#0B1528]/8 transition">
                    <p class="mt-2 text-[0.7rem] text-slate-400 font-medium">Séparés par des virgules</p>
                    @if($tags_input)
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach(array_filter(array_map('trim', explode(',', $tags_input))) as $tag)
                        <span class="inline-flex rounded-full bg-[#EAECEF] px-3 py-1 text-[0.7rem] font-bold text-slate-600">#{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>

             

                {{-- Newsletter Push toggle --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold text-[#0B1528]">Newsletter Push</p>
                            <p class="text-[0.7rem] text-slate-400 mt-0.5">Inclure dans le prochain envoi</p>
                        </div>
                        <button type="button" wire:click="$set('newsletter', !$newsletter)"
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none
                                       {{ $newsletter ? 'bg-[#82E682]' : 'bg-slate-200' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200
                                         {{ $newsletter ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>
    </form>

</div>