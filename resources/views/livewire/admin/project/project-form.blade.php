@push('head-scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@4/es2021/jodit.fat.min.css">
<script src="https://cdn.jsdelivr.net/npm/jodit@4/es2021/jodit.fat.min.js"></script>
<style>
    .jodit-container:not(.jodit_inline) { border: none !important; border-radius: 0 !important; }
    .jodit-toolbar__box:not(:empty) { background: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; }
    .jodit-wysiwyg { min-height: 460px !important; padding: 2rem 2.25rem !important; line-height: 1.85 !important; color: #4A5568 !important; font-family: inherit !important; font-size: 16px !important; }
    .jodit-status-bar { display: none !important; }
</style>

<script>
document.addEventListener('alpine:init', () => {
    if (window.__iuhmEditorRegistered) return;
    window.__iuhmEditorRegistered = true;

    Alpine.data('iuhmEditor', () => ({
        editor: null,
        init() {
            const wire     = this.$wire;
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
                        '|', 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript',
                        '|', 'brush', 'font', 'fontsize',
                        '|', 'ul', 'ol', 'indent', 'outdent',
                        '|', 'align',
                        '|', 'link', 'image', 'video', 'table', 'file',
                        '|', 'blockquote', 'hr', 'symbols',
                        '|', 'selectall', 'cut', 'copy', 'paste', 'copyformat', 'eraser',
                        '|', 'undo', 'redo',
                        '|', 'fullsize', 'source',
                    ],
                    uploader: {
                        insertImageAsBase64URI: true,
                    },
                    image: {
                        dialogWidth: 560,
                        openOnDblClick: true,
                    },
                    events: {
                        change: async (content) => {
                            const compressed = await compressBase64Images(content);
                            try {
                                await wire.set('description', compressed);
                            } catch (e) {
                                window.iuhmShowEditorError(
                                    e && e.message && e.message.toLowerCase().includes('payload')
                                        ? 'Image trop lourde même après compression.'
                                        : 'Erreur de synchronisation.'
                                );
                            }
                        },
                    },
                });

                const raw = wire.get('description');
                if (raw) this.editor.value = raw;

                wire.$watch('description', (val) => {
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

async function compressBase64Images(html) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    const imgs = doc.querySelectorAll('img[src^="data:image"]');
    if (!imgs.length) return html;
    await Promise.all(Array.from(imgs).map(img => new Promise(resolve => {
        const MAX_W = 1200;
        const image = new Image();
        image.onload = () => {
            let w = image.naturalWidth, h = image.naturalHeight;
            if (w > MAX_W) { h = Math.round(h * MAX_W / w); w = MAX_W; }
            const canvas = document.createElement('canvas');
            canvas.width = w; canvas.height = h;
            canvas.getContext('2d').drawImage(image, 0, 0, w, h);
            img.src = canvas.toDataURL('image/jpeg', 0.72);
            resolve();
        };
        image.onerror = resolve;
        image.src = img.src;
    })));
    return doc.body.innerHTML;
}

window.iuhmShowEditorError = function (msg) {
    const el = document.getElementById('iuhm-editor-toast');
    if (!el) return;
    el.querySelector('.toast-msg').textContent = msg;
    el.style.display = 'flex';
    clearTimeout(window.__iuhmToastTimer);
    window.__iuhmToastTimer = setTimeout(() => { el.style.display = 'none'; }, 7000);
};
</script>
@endpush

<div>
    <div id="iuhm-editor-toast" role="alert">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        <span class="toast-msg"></span>
        <button type="button" class="toast-close" onclick="document.getElementById('iuhm-editor-toast').style.display='none'" aria-label="Fermer">&#x2715;</button>
    </div>

    <form wire:submit.prevent="save">
    <div class="px-6 pb-12 pt-8 sm:px-8 sm:pt-10 bg-[#FAFAFC] min-h-screen">

        {{-- Page Header --}}
        <div class="mb-10">
            <button type="button" wire:click="resetForm()"
                    class="mb-5 inline-flex items-center gap-2 text-[0.8rem] font-extrabold uppercase tracking-widest text-[#4A5568] hover:text-[#0B1528] transition-colors">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                RETOUR AUX PROJETS
            </button>
            <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <span class="inline-flex items-center rounded-full bg-[#82E682] px-4 py-1.5 text-[0.7rem] font-extrabold uppercase tracking-widest text-[#0a471b] shadow-sm mb-4">
                        {{ $editMode ? 'MODIFICATION' : 'NOUVEAU PROJET' }}
                    </span>
                    <h2 class="text-[2rem] font-bold text-[#0B1528] leading-[1.1] sm:text-[2.5rem]">
                        {{ $editMode ? 'Modifier le projet' : 'Créer un nouveau projet' }}
                    </h2>
                    <p class="mt-2 text-[0.9rem] text-[#4A5568] max-w-lg leading-relaxed">
                        {{ $editMode ? 'Mettez à jour le contenu et les métadonnées.' : 'Rédigez, sélectionnez et publiez un projet pour le réseau communautaire.' }}
                    </p>
                </div>
                <div class="flex shrink-0 items-center gap-3 sm:pt-2">
                    <button type="button" wire:click="resetForm()"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-[0.85rem] font-bold text-[#4A5568] hover:bg-[#EAECEF] transition-colors shadow-sm">
                        Annuler
                    </button>
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-[#0B1528] px-7 py-2.5 text-[0.85rem] font-bold text-white hover:bg-[#162D5A] transition-colors shadow-md shadow-slate-800/20">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $editMode ? 'Mettre à jour' : 'Publier le projet' }}
                    </button>
                </div>
            </div>
        </div>

        @if(session()->has('success'))
        <div class="mb-6 flex items-center gap-3 rounded-3xl border border-[#82E682]/50 bg-[#f0fbf0] px-5 py-4 text-sm text-[#0a471b] shadow-sm">
            <svg class="size-5 text-[#23803B] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif
        @if(session()->has('error'))
        <div class="mb-6 flex items-center gap-3 rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800 shadow-sm">
            <svg class="size-5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        @endif

        {{-- Two-column grid --}}
        <div class="grid gap-6 lg:grid-cols-3">

            {{-- LEFT (2/3): Main content --}}
            <div class="space-y-6 lg:col-span-2">

                {{-- Title FR --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-3">
                        Titre du projet (FR)
                    </label>
                    <input type="text" wire:model="title"
                           placeholder="Saisissez un titre accrocheur…"
                           class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-5 py-4 text-[1.35rem] font-bold text-[#0B1528] placeholder:text-slate-300 placeholder:font-normal placeholder:text-base outline-none focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8 transition">
                    @error('title') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Title AR --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-3">
                        Titre du projet (AR)
                    </label>
                    <input type="text" wire:model="title_ar"
                           placeholder="اكتب عنوان المشروع…"
                           class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-5 py-4 text-[1.35rem] font-bold placeholder:text-slate-300 outline-none focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8 transition">
                    @error('title_ar') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Short Description FR --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-3">
                        Résumé court (FR)
                    </label>
                    <textarea wire:model="short_description" rows="3" maxlength="250"
                              placeholder="Rédigez une accroche qui donne envie de lire l'article complet…"
                              class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-5 py-3.5 text-[0.95rem] italic text-[#4A5568] placeholder:text-slate-300 placeholder:not-italic outline-none focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8 resize-none transition leading-relaxed"></textarea>
                    @error('short_description') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Short Description AR --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-3">
                        ملخص قصير (AR)
                    </label>
                    <textarea wire:model="short_description_ar" rows="3" maxlength="250"
                              placeholder="اكتب ملخصا قصيرا…"
                              class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-5 py-3.5 text-[0.95rem] italic text-[#4A5568] placeholder:text-slate-300 placeholder:not-italic outline-none focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8 resize-none transition leading-relaxed"></textarea>
                    @error('short_description_ar') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Rich Text Editor --}}
                <div class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white shadow-sm shadow-slate-200">
                    <div class="border-b border-slate-100 px-6 py-4 flex items-center gap-3">
                        <span class="size-2.5 rounded-full bg-[#82E682] shrink-0"></span>
                        <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Contenu du projet</span>
                    </div>
                    <div x-data="iuhmEditor"
                         wire:ignore>
                        <textarea x-ref="editorTextarea"></textarea>
                    </div>
                    @error('description') <p class="px-6 pb-4 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

            </div>

            {{-- RIGHT (1/3): Metadata --}}
            <div class="space-y-6">

                {{-- Featured Image --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-4">Image de couverture</p>
                    @if($featured_image && !$newImage)
                    <div class="mb-4 overflow-hidden rounded-[1.25rem] relative h-40 w-full">
                        <img src="{{ \Illuminate\Support\Str::startsWith($featured_image, ['http','https']) ? $featured_image : asset($featured_image) }}"
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
                    <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-4">Statut de publication</p>
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

                {{-- Slug --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-3">
                        Slug (URL)
                    </label>
                    <input type="text" wire:model="slug"
                           placeholder="mon-projet"
                           class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-[0.9rem] font-semibold text-[#0B1528] outline-none focus:border-[#0B1528] focus:ring-4 focus:ring-[#0B1528]/8 transition">
                    @error('slug') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-3">
                        Statut du projet
                    </label>
                    <select wire:model="status"
                            class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-[0.9rem] font-semibold text-[#0B1528] outline-none focus:border-[#0B1528] focus:ring-4 focus:ring-[#0B1528]/8 transition">
                        <option value="planned">Planifié</option>
                        <option value="in_progress">En cours</option>
                        <option value="completed">Terminé</option>
                        <option value="archived">Archivé</option>
                    </select>
                    @error('status') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Category --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-3">
                        Catégorie
                    </label>
                    <select wire:model="status"
                            class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-[0.9rem] font-semibold text-[#0B1528] outline-none focus:border-[#0B1528] focus:ring-4 focus:ring-[#0B1528]/8 transition">
                        <option value="">Sélectionner une catégorie</option>
                        <option value="urban-planning">Urban Planning</option>
                        <option value="sustainability">Durabilité</option>
                        <option value="community">Communauté</option>
                        <option value="innovation">Innovation</option>
                    </select>
                </div>

                {{-- Tags --}}
                <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
                    <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-3">
                        Tags (séparés par des virgules)
                    </label>
                    <input type="text" wire:model="tags_input"
                           placeholder="tag1, tag2, tag3"
                           class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-[0.9rem] font-semibold text-[#0B1528] outline-none focus:border-[#0B1528] focus:ring-4 focus:ring-[#0B1528]/8 transition">
                    @error('tags_input') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

    </div>
    </form>

</div>