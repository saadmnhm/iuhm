<div class="max-w-7xl mx-auto">

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3">
        <i class="ri-checkbox-circle-line text-green-500 text-xl"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    {{-- ═══ Statistics ═══ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['Total articles', $stats['total'], 'ri-article-line', 'blue'],
            ['Publiés', $stats['published'], 'ri-checkbox-circle-line', 'green'],
            ['Brouillons', $stats['draft'], 'ri-draft-line', 'amber'],
            ['Vues totales', $stats['views'], 'ri-eye-line', 'purple'],
        ] as [$label, $value, $icon, $color])
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">{{ $label }}</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $value }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-{{ $color }}-100 flex items-center justify-center">
                    <i class="{{ $icon }} text-{{ $color }}-600 text-lg"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ═══ Toolbar ═══ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px] relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" wire:model.live="search" placeholder="Rechercher un article..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">
            </div>
            <select wire:model.live="statusFilter" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                <option value="all">Tous</option>
                <option value="published">Publiés</option>
                <option value="draft">Brouillons</option>
            </select>
            <button wire:click="openCreate" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <i class="ri-add-line mr-1"></i> Nouvel article
            </button>
        </div>
    </div>

    {{-- ═══ Posts Grid ═══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($posts as $post)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition overflow-hidden group">
            @if($post->image)
            <div class="h-40 bg-gray-100 overflow-hidden">
                <img src="{{ Str::startsWith($post->image, ['http','https','/storage']) ? $post->image : asset($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            @else
            <div class="h-40 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                <i class="ri-article-line text-4xl text-indigo-300"></i>
            </div>
            @endif

            <div class="p-5">
                <div class="flex items-start justify-between mb-2">
                    <h4 class="font-semibold text-gray-900 line-clamp-2 flex-1">{{ $post->title }}</h4>
                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full flex-shrink-0
                        {{ $post->is_published ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $post->is_published ? 'Publié' : 'Brouillon' }}
                    </span>
                </div>

                @if($post->category)
                <span class="inline-block px-2 py-0.5 bg-indigo-50 text-indigo-700 text-xs rounded font-medium mb-2">{{ $post->category }}</span>
                @endif

                @if($post->excerpt)
                <p class="text-sm text-gray-500 line-clamp-2 mb-3">{{ $post->excerpt }}</p>
                @endif

                <div class="flex items-center justify-between text-xs text-gray-400 mb-3">
                    <span><i class="ri-user-line mr-1"></i>{{ $post->author->name ?? 'N/A' }}</span>
                    <span><i class="ri-eye-line mr-1"></i>{{ $post->views_count }} vues</span>
                    <span>{{ $post->created_at->format('d/m/Y') }}</span>
                </div>

                <div class="flex gap-2 pt-3 border-t border-gray-100">
                    <button wire:click="openEdit({{ $post->id }})"
                            class="flex-1 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition">
                        <i class="ri-pencil-line mr-1"></i> Modifier
                    </button>
                    <button wire:click="togglePublish({{ $post->id }})"
                            class="px-3 py-2 {{ $post->is_published ? 'bg-amber-50 hover:bg-amber-100 text-amber-700' : 'bg-green-50 hover:bg-green-100 text-green-700' }} text-xs font-semibold rounded-lg transition">
                        <i class="{{ $post->is_published ? 'ri-eye-off-line' : 'ri-eye-line' }}"></i>
                    </button>
                    <button wire:click="delete({{ $post->id }})" wire:confirm="Supprimer cet article ?"
                            class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-lg transition">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
            <i class="ri-article-line text-5xl text-gray-300 mb-3 block"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucun article</h3>
            <p class="text-sm text-gray-500">Créez votre premier article de blog.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $posts->links() }}</div>

    {{-- ═══ MODAL ═══ --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click.self="$set('showModal', false)">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl z-10 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="text-lg font-bold text-indigo-800 flex items-center gap-2">
                    <i class="ri-article-line text-indigo-600"></i>
                    {{ $editMode ? 'Modifier l\'article' : 'Nouvel article' }}
                </h3>
                <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600"><i class="ri-close-line text-xl"></i></button>
            </div>
            <form wire:submit.prevent="save" class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Titre (FR) *</label>
                        <input type="text" wire:model="title" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Titre (AR)</label>
                        <input type="text" wire:model="title_ar" dir="rtl" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Résumé</label>
                    <textarea wire:model="excerpt" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Contenu *</label>
                    <textarea wire:model="content" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none resize-none"></textarea>
                    @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Catégorie</label>
                        <input type="text" wire:model="category" placeholder="ex: Actualités, Formation..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tags (séparés par virgule)</label>
                        <input type="text" wire:model="tags_input" placeholder="tag1, tag2, tag3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Image</label>
                    <input type="file" wire:model="newImage" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @if($image && !$newImage)
                    <img src="{{ $image }}" class="mt-2 h-20 rounded-lg object-cover" alt="Current">
                    @endif
                </div>
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" wire:model="is_published" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="text-sm font-medium text-gray-700">Publier immédiatement</span>
                    </label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Annuler</button>
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">
                        <i class="ri-save-line mr-1"></i> {{ $editMode ? 'Mettre à jour' : 'Créer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
