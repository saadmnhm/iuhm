<div>

    {{-- ═══════ HEADER ═══════ --}}
    <div class="px-6 pb-6 pt-8 sm:px-8 sm:pb-8 sm:pt-10">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-emerald-700">MEDIA LIBRARY</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Gestion des Médias</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">
                    Gérez vos fichiers média : images, documents, vidéos, etc.
                </p>
            </div>
            <button type="button" @click="open = true"
                    class="inline-flex items-center gap-2 self-start rounded-full bg-[#0f1d57] px-5 py-3 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(15,23,42,0.22)] transition hover:bg-[#14256f]">
                <span class="flex h-6 w-6 items-center justify-center rounded-full border border-white/25 bg-white/10">
                    <i class="ri-add-line text-sm"></i>
                </span>
                Ajouter un média
            </button>
        </div>

        @if(session()->has('success'))
        <div class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
            <i class="ri-check-line text-lg text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        {{-- Stats Row --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 mb-10 mt-10">
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="flex justify-between mb-3">
                    <p class="text-[14px] font-bold uppercase tracking-[0.05em] text-[#45464E] mt-0.5">Total</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">
                        <i class="ri-file-line text-xl text-slate-600"></i>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="flex justify-between mb-3">
                    <p class="text-[14px] font-bold uppercase tracking-[0.05em] text-[#45464E] mt-0.5">Publics</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100">
                        <i class="ri-global-line text-xl text-green-600"></i>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">{{ $stats['public'] }}</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="flex justify-between mb-3">
                    <p class="text-[14px] font-bold uppercase tracking-[0.05em] text-[#45464E] mt-0.5">Privés</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100">
                        <i class="ri-lock-line text-xl text-amber-600"></i>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">{{ $stats['private'] }}</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="flex justify-between mb-3">
                    <p class="text-[14px] font-bold uppercase tracking-[0.05em] text-[#45464E] mt-0.5">Images</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">
                        <i class="ri-image-line text-xl text-blue-600"></i>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">{{ $stats['images'] }}</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="flex justify-between mb-3">
                    <p class="text-[14px] font-bold uppercase tracking-[0.05em] text-[#45464E] mt-0.5">Documents</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100">
                        <i class="ri-file-text-line text-xl text-purple-600"></i>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">{{ $stats['documents'] }}</p>
            </div>
        </div>

        {{-- Search & Filter --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="relative flex-1">
                    <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="Rechercher un média..."
                           class="w-full rounded-xl iuhm_input" style="padding: 0 40px;">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Type</label>
                    <select wire:model.live="typeFilter" class="rounded-xl iuhm_select">
                        <option value="">Tous les types</option>
                        <option value="image">Images</option>
                        <option value="application">Documents</option>
                        <option value="video">Vidéos</option>
                        <option value="audio">Audio</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Catégorie</label>
                    <select wire:model.live="categoryFilter" class="rounded-xl iuhm_select">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <select wire:model="perPage" class="w-full rounded-xl iuhm_select">
                        <option value="10">10 par page</option>
                        <option value="20">20 par page</option>
                        <option value="50">50 par page</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- View Mode Toggle --}}
        <div class="flex items-center justify-between mb-4">
            <div class="flex gap-2">
                <button wire:click="$set('viewMode', 'grid')"
                        class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors
                            {{ $viewMode === 'grid' ? 'bg-[#0f1d57] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <i class="ri-layout-grid-line mr-1"></i> Grille
                </button>
                <button wire:click="$set('viewMode', 'list')"
                        class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors
                            {{ $viewMode === 'list' ? 'bg-[#0f1d57] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <i class="ri-list-check-2 mr-1"></i> Liste
                </button>
            </div>
            <select wire:model="perPage" class="rounded-xl iuhm_select w-auto">
                <option value="10">10 par page</option>
                <option value="20">20 par page</option>
                <option value="50">50 par page</option>
            </select>
        </div>

        {{-- Media Grid/List --}}
        <div class="bg-white rounded-[22px] border border-slate-200 overflow-hidden">
            @if($viewMode === 'grid')
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-4">
                    @forelse($media as $item)
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg hover:border-green-300 transition-all duration-200 relative group">
                        <div class="aspect-video relative overflow-hidden bg-slate-100">
                            @if($item->mime_type && Str::startsWith($item->mime_type, 'image/'))
                                <img src="{{ asset($item->file_path) }}"
                                     alt="{{ $item->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @elseif($item->mime_type && Str::startsWith($item->mime_type, 'video/'))
                                <video class="w-full h-full object-cover" muted>
                                    <source src="{{ asset($item->file_path) }}" type="{{ $item->mime_type }}">
                                </video>
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    @if($item->mime_type && Str::startsWith($item->mime_type, 'video/'))
                                        <i class="ri-video-line text-5xl text-blue-300"></i>
                                    @elseif($item->mime_type && Str::startsWith($item->mime_type, 'audio/'))
                                        <i class="ri-music-2-line text-5xl text-purple-300"></i>
                                    @else
                                        <i class="ri-file-text-line text-5xl text-slate-300"></i>
                                    @endif
                                </div>
                            @endif
                            <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="openEdit({{ $item->id }})"
                                        class="p-2 rounded-full bg-white/90 backdrop-blur text-slate-600 hover:bg-green-100 text-green-700 transition-colors"
                                        title="Modifier">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button wire:click="delete({{ $item->id }})"
                                        wire:confirm="Supprimer ce média définitivement ?"
                                        class="p-2 rounded-full bg-white/90 backdrop-blur text-slate-600 hover:bg-red-100 hover:text-red-600 transition-colors"
                                        title="Supprimer">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                                <button wire:click="togglePublic({{ $item->id }})"
                                        class="p-2 rounded-full bg-white/90 backdrop-blur text-slate-600 hover:bg-blue-100 hover:text-blue-700 transition-colors"
                                        title="{{ $item->is_public ? 'Rendre privé' : 'Rendre public' }}">
                                    <i class="{{ $item->is_public ? 'ri-global-line' : 'ri-lock-line' }}"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <h4 class="font-semibold text-slate-900 truncate group-hover:text-green-700 transition-colors">
                                {{ $item->title }}
                            </h4>
                            <div class="flex items-center gap-2 mt-2 text-xs text-slate-500">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                    {{ $item->mime_type }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                    {{ $item->file_size_formatted }}
                                </span>
                            </div>
                            @if($item->category)
                                <span class="inline-block mt-2 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    {{ $item->category }}
                                </span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-16">
                        <i class="ri-image-line text-5xl text-slate-300 mb-3 block"></i>
                        <p class="text-slate-500">Aucun média trouvé.</p>
                        <button @click="open = true"
                                class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white font-medium">
                            <i class="ri-add-line"></i> Ajouter le premier média
                        </button>
                    </div>
                @endforelse
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-400 w-10"></th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Fichier</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Titre</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Catégorie</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Taille</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Visibilité</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Date</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-[0.14em] text-slate-400 w-40">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($media as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    @if($item->mime_type && Str::startsWith($item->mime_type, 'image/'))
                                        <img src="{{ asset($item->file_path) }}" alt="" class="w-12 h-12 rounded-lg object-cover">
                                    @elseif($item->mime_type && Str::startsWith($item->mime_type, 'video/'))
                                        <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class="ri-video-line text-xl text-blue-500"></i>
                                        </div>
                                    @elseif($item->mime_type && Str::startsWith($item->mime_type, 'audio/'))
                                        <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class="ri-music-2-line text-xl text-purple-500"></i>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center">
                                            <i class="ri-file-text-line text-xl text-slate-400"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900 truncate max-w-xs">{{ $item->title }}</div>
                                    @if($item->description)
                                        <div class="text-xs text-slate-500 truncate max-w-xs">{{ $item->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ Str::startsWith($item->mime_type, 'image/') ? 'bg-blue-100 text-blue-700' :
                                           (Str::startsWith($item->mime_type, 'video/') ? 'bg-purple-100 text-purple-700' :
                                              (Str::startsWith($item->mime_type, 'audio/') ? 'bg-pink-100 text-pink-700' : 'bg-slate-100 text-slate-700')) }}">
                                        {{ $item->mime_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $item->category ?: '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $item->file_size_formatted }}
                                </td>
                                <td class="px-6 py-4">
                                    <button wire:click="togglePublic({{ $item->id }})"
                                            class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-medium
                                                {{ $item->is_public ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $item->is_public ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                        {{ $item->is_public ? 'Public' : 'Privé' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $item->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="openEdit({{ $item->id }})"
                                                class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-green-100 hover:text-green-700 transition-colors"
                                                title="Modifier">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <button wire:click="delete({{ $item->id }})"
                                                wire:confirm="Supprimer ce média définitivement ?"
                                                class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-red-100 hover:text-red-600 transition-colors"
                                                title="Supprimer">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                        <button wire:click="togglePublic({{ $item->id }})"
                                                class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-blue-100 hover:text-blue-700 transition-colors"
                                                title="{{ $item->is_public ? 'Rendre privé' : 'Rendre public' }}">
                                            <i class="{{ $item->is_public ? 'ri-global-line' : 'ri-lock-line' }}"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <i class="ri-image-line text-4xl text-slate-200 mb-3 block"></i>
                                    <p class="text-slate-500">Aucun média trouvé.</p>
                                    <button @click="open = true"
                                            class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white font-medium">
                                        <i class="ri-add-line"></i> Ajouter le premier média
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Pagination --}}
        @if($media->hasPages())
        <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-6 py-4 border-t border-slate-200">
            <p class="text-sm text-slate-500">
                Affichage de {{ $media->firstItem() ?? 0 }} à {{ $media->lastItem() ?? 0 }} sur {{ $media->total() }} médias
            </p>
            <div>{{ $media->links() }}</div>
        </div>
        @endif

        {{-- Modal (Alpine - opens without backend request) --}}
        <div x-data="{ open: @entangle('showModal').live, editMode: @entangle('editMode').live }" x-cloak>
        <div
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
        >
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                    <h2 class="text-xl font-bold" x-text="editMode ? 'Modifier le Média' : 'Nouveau Média'"></h2>
                    <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <form @submit.prevent="save" class="p-6 space-y-6" enctype="multipart/form-data">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Titre (FR) <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="title" required
                               class="w-full px-4 py-3 rounded-xl iuhm_input"
                               placeholder="Titre du média">
                        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Titre (AR)</label>
                        <input type="text" wire:model="title_ar"
                               class="w-full px-4 py-3 rounded-xl iuhm_input"
                               placeholder="Titre en arabe">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Catégorie</label>
                            <input type="text" wire:model="category" list="categories"
                                   class="w-full px-4 py-3 rounded-xl iuhm_input"
                                   placeholder="Ex: Actualités, Événements, Rapports...">
                            <datalist id="categories">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tags (séparés par des virgules)</label>
                            <input type="text" wire:model="tags"
                                   class="w-full px-4 py-3 rounded-xl iuhm_input"
                                   placeholder="tag1, tag2, tag3">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Description (FR)</label>
                        <textarea wire:model="description" rows="3"
                                  class="w-full px-4 py-3 rounded-xl iuhm_textarea"
                                  placeholder="Description du média"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Description (AR)</label>
                        <textarea wire:model="description_ar" rows="3"
                                  class="w-full px-4 py-3 rounded-xl iuhm_textarea"
                                  placeholder="Description en arabe"></textarea>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="is_public" id="is_public"
                                   class="w-5 h-5 text-[#0f1d57] border-slate-300 rounded focus:ring-2 focus:ring-[#0f1d57]">
                            <label for="is_public" class="text-sm font-medium text-slate-700">Public (visible sur le site)</label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Fichier {{ $editMode ? '(laisser vide pour conserver l\'actuel)' : '<span class="text-red-500">*</span>' }}
                        </label>
                        <input type="file" wire:model="file"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-green-500"
                               accept="image/*,video/*,audio/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,.zip,.rar">
                        @if($existingFile)
                            <p class="mt-2 text-sm text-slate-500">Fichier actuel : {{ $existingFile }}</p>
                        @endif
                        @error('file') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                        <button type="button" @click="open = false"
                                class="px-5 py-3 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl font-medium transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                                class="px-5 py-3 text-white bg-[#0f1d57] hover:bg-[#14256f] rounded-xl font-semibold shadow-[0_12px_30px_rgba(15,23,42,0.22)] transition-colors">
                            {{ $editMode ? 'Mettre à jour' : 'Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </div>

</div>