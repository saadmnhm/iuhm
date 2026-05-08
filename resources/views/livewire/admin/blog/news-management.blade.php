@push('head-scripts')
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<style>
    .ql-toolbar.ql-snow { border-color: #e2e8f0; background: #f8fafc; }
    .ql-container.ql-snow { border: none; font-size: 15px; font-family: inherit; }
    .ql-editor { min-height: 380px; padding: 1.25rem 1.5rem; }
    .ql-editor.ql-blank::before { color: #94a3b8; font-style: normal; }
</style>
@endpush

<div>
    @if($editMode || $showModal)
    {{-- ═══════ CREATE / EDIT FORM ═══════ --}}
    <form wire:submit.prevent="save">
    <div class="px-6 pb-10 pt-8 sm:px-8 sm:pt-10">

        {{-- Page Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <button type="button" wire:click="resetForm()"
                        class="mb-3 inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-700 transition">
                    <i class="ri-arrow-left-line"></i> Retour
                </button>
                <span class="block text-[11px] font-extrabold uppercase tracking-[0.28em] text-emerald-600">CONTENT STUDIO</span>
                <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
                    {{ $editMode ? 'Modifier l\'actualité' : 'Créer une nouvelle actualité' }}
                </h2>
                <p class="mt-2 text-sm text-slate-500 max-w-lg">
                    {{ $editMode ? 'Mettez à jour le contenu et les métadonnées.' : 'Rédigez, sélectionnez et publiez une actualité pour le réseau communautaire.' }}
                </p>
            </div>
            <div class="flex flex-shrink-0 items-center gap-3 sm:pt-8">
                <button type="button" wire:click="resetForm()"
                        class="rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                    Annuler
                </button>
                <button type="submit"
                        class="rounded-full bg-[#0f1d57] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#14256f] transition shadow-sm">
                    {{ $editMode ? 'Mettre à jour' : 'Enregistrer' }}
                </button>
            </div>
        </div>

        @if(session()->has('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            <i class="ri-check-line text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session()->has('error'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            <i class="ri-error-warning-line text-rose-500"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        {{-- Two-column grid --}}
        <div class="grid gap-6 lg:grid-cols-3">

            {{-- LEFT (2/3): Main content --}}
            <div class="space-y-5 lg:col-span-2">

                {{-- Title --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <label class="block text-[11px] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-3">
                        Titre de l'article (Anglais / Français)
                    </label>
                    <input type="text" wire:model="title"
                           placeholder="Enter a compelling headline..."
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-base font-medium text-slate-900 placeholder-slate-300 outline-none focus:border-[#0f1d57] focus:bg-white focus:ring-4 focus:ring-[#0f1d57]/10 transition">
                    @error('title') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Excerpt --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <label class="block text-[11px] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-3">
                        Résumé (English / French)
                    </label>
                    <textarea wire:model="excerpt" rows="3"
                              placeholder="Veuillez saisir le résumé de l'article ici..."
                              class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 placeholder-slate-300 outline-none focus:border-[#0f1d57] focus:bg-white focus:ring-4 focus:ring-[#0f1d57]/10 resize-none transition"></textarea>
                    @error('excerpt') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Quill Rich Text Editor --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                     x-data="{
                         quill: null,
                         init() {
                             const comp = this.$wire;
                             this.quill = new Quill(this.$refs.quillEditor, {
                                 theme: 'snow',
                                 placeholder: 'Begin your story here...',
                                 modules: {
                                     toolbar: [
                                         ['bold', 'italic', 'underline'],
                                         [{ list: 'ordered' }, { list: 'bullet' }],
                                         ['link', 'blockquote'],
                                         ['clean']
                                     ]
                                 }
                             });
                             const raw = @js($content ?? '');
                             if (raw) { this.quill.root.innerHTML = raw; }
                             this.quill.on('text-change', () => {
                                 comp.set('content', this.quill.root.innerHTML);
                             });
                             this.$cleanup(() => { this.quill = null; });
                         }
                     }"
                     wire:ignore>
                    <div x-ref="quillEditor"></div>
                </div>
                @error('content') <p class="mt-1 text-xs text-rose-500 px-1">{{ $message }}</p> @enderror

            </div>

            {{-- RIGHT (1/3): Metadata --}}
            <div class="space-y-5">

                {{-- Featured Image --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-4">Featured Image</p>
                    @if($image && !$file)
                    <div class="mb-3 overflow-hidden rounded-xl">
                        <img src="{{ \Illuminate\Support\Str::startsWith($image, ['http','https']) ? $image : asset($image) }}"
                             class="w-full h-32 object-cover" alt="">
                    </div>
                    @endif
                    <label class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 py-7 text-center hover:bg-slate-100 transition">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-white shadow-sm mb-2.5">
                            <i class="ri-image-add-line text-lg text-slate-400"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Upload Cover</p>
                        <input type="file" wire:model="file" accept="image/*" class="hidden">
                    </label>
                    <p class="mt-2 text-center text-[11px] text-slate-400">Recommended size: 1200×630px</p>
                    @error('file') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Publication Status --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-4">Statut de Publication</p>
                    <div class="flex gap-1.5 rounded-full bg-slate-100 p-1">
                        <button type="button" wire:click="$set('is_published', false)"
                                class="flex-1 rounded-full py-2 text-xs font-bold transition
                                       {{ !$is_published ? 'bg-[#0f1d57] text-white shadow' : 'text-slate-500 hover:text-slate-700' }}">
                            Draft
                        </button>
                        <button type="button" wire:click="$set('is_published', true)"
                                class="flex-1 rounded-full py-2 text-xs font-bold transition
                                       {{ $is_published ? 'bg-[#0f1d57] text-white shadow' : 'text-slate-500 hover:text-slate-700' }}">
                            Published
                        </button>
                    </div>
                </div>

                {{-- Category --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-3">Category</p>
                    <select wire:model="category"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
                        <option value="">Select category</option>
                        <option value="urban-planning">Urban Planning</option>
                        <option value="sustainability">Sustainability</option>
                        <option value="community">Community</option>
                        <option value="innovation">Innovation</option>
                    </select>
                    @error('category') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Enable Comments toggle --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">Enable Comments</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Allow readers to comment on this article</p>
                        </div>
                        <button type="button" wire:click="$set('showComments', !$showComments)"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none
                                       {{ $showComments ? 'bg-emerald-500' : 'bg-slate-200' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200
                                         {{ $showComments ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>

                {{-- Newsletter Push toggle --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">Newsletter Push</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Include in next newsletter send</p>
                        </div>
                        <button type="button" wire:click="$set('newsletter', !$newsletter)"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none
                                       {{ $newsletter ? 'bg-emerald-500' : 'bg-slate-200' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200
                                         {{ $newsletter ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>
    </form>

    @else
    {{-- ═══════ LIST VIEW ═══════ --}}
    <div class="px-6 pb-6 pt-8 sm:px-8 sm:pb-8 sm:pt-10">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-emerald-700">CONTENT STUDIO</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Gestion des Articles</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">Gérez vos articles et publications.</p>
            </div>
            <button type="button" wire:click="openCreate()"
                    class="inline-flex items-center gap-2 self-start rounded-full bg-[#0f1d57] px-5 py-3 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(15,23,42,0.22)] transition hover:bg-[#14256f]">
                <span class="flex h-6 w-6 items-center justify-center rounded-full border border-white/25 bg-white/10">
                    <i class="ri-add-line text-sm"></i>
                </span>
                Ajouter un article
            </button>
        </div>

        @if(session()->has('success'))
        <div class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
            <i class="ri-check-line text-lg text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        {{-- Statistics Cards --}}
        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-[16px] border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.05em] text-slate-500">TOTAL</p>
                        <p class="mt-2 text-3xl font-black text-slate-900">{{ $statistics['totalNews'] ?? 0 }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-2xl text-blue-600">
                        <i class="ri-article-line"></i>
                    </div>
                </div>
            </div>
            <div class="rounded-[16px] border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.05em] text-slate-500">PUBLIÉS</p>
                        <p class="mt-2 text-3xl font-black text-slate-900">{{ $statistics['publishedNews'] ?? 0 }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-2xl text-emerald-600">
                        <i class="ri-checkbox-circle-line"></i>
                    </div>
                </div>
            </div>
            <div class="rounded-[16px] border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.05em] text-slate-500">BROUILLONS</p>
                        <p class="mt-2 text-3xl font-black text-slate-900">{{ $statistics['draftNews'] ?? 0 }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-2xl text-amber-600">
                        <i class="ri-draft-line"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search & Filter --}}
        <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="relative flex-1">
                <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" wire:model.live.debounce="search" placeholder="Chercher un article..."
                       class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
            </div>
            <select wire:model.live="categoryFilter"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
                <option value="">Toutes les catégories</option>
                <option value="urban-planning">Urban Planning</option>
                <option value="sustainability">Sustainability</option>
                <option value="community">Community</option>
                <option value="innovation">Innovation</option>
            </select>
        </div>

        {{-- Data Table --}}
        <div class="mt-8 overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-[0_18px_40px_rgba(15,23,42,0.06)]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-[#04103a] text-white">
                        <tr>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">TITRE</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">AUTEUR</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">CATÉGORIE</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">DATE</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">STATUT</th>
                            <th class="px-6 py-5 text-center text-xs font-semibold text-white uppercase">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-slate-50/50">
                        @forelse($newsItems as $news)
                        <tr class="transition hover:bg-white">
                            <td class="px-6 py-5 text-[14px] font-semibold text-[#04103A]">{{ Str::limit($news->title, 35) }}</td>
                            <td class="px-6 py-5 text-[14px] text-slate-600">{{ $news->uploadedBy?->nom ?? 'N/A' }}</td>
                            <td class="px-6 py-5">
                                @if($news->category)
                                <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $news->category }}</span>
                                @else
                                <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-[14px] text-slate-500">
                                {{ $news->published_at ? $news->published_at->format('d M Y') : '—' }}
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ring-1
                                    {{ $news->is_published ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-amber-100 text-amber-700 ring-amber-200' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $news->is_published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                    {{ $news->is_published ? 'Publié' : 'Brouillon' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" wire:click="openEdit({{ $news->id }})"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#0f1d57]/5 text-[#0f1d57] transition hover:bg-[#0f1d57] hover:text-white" title="Modifier">
                                        <i class="ri-edit-2-line text-base"></i>
                                    </button>
                                    <button type="button" wire:click="togglePublish({{ $news->id }})"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full transition
                                                   {{ $news->is_published ? 'bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white' }}"
                                            title="{{ $news->is_published ? 'Dépublier' : 'Publier' }}">
                                        <i class="ri-{{ $news->is_published ? 'eye-off' : 'eye' }}-line text-base"></i>
                                    </button>
                                    <button type="button" wire:click="delete({{ $news->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer cet article ?"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-600 hover:text-white" title="Supprimer">
                                        <i class="ri-delete-bin-2-fill text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <i class="ri-article-line text-4xl text-slate-200 mb-3 block"></i>
                                <p class="text-sm font-semibold text-slate-500">Aucun article trouvé.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-4 border-t border-slate-200 bg-white px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Affichage de {{ $newsItems->firstItem() ?? 0 }} à {{ $newsItems->lastItem() ?? 0 }} sur {{ $newsItems->total() }} articles
                </p>
                <div>{{ $newsItems->links() }}</div>
            </div>
        </div>
    </div>
    @endif
</div>
