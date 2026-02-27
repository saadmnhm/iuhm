<div>
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestion des Formulaires</h2>
            <p class="text-gray-500 text-sm mt-1">Créez et gérez vos formulaires dynamiques</p>
        </div>
        <a href="{{ route('admin.formulaires.create') }}"
            class="flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition shadow-sm">
            <i class="ri-add-line text-lg"></i>
            <span>Nouveau Formulaire</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[250px]">
                <div class="relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher un formulaire..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none text-sm">
                </div>
            </div>
            <select wire:model.live="filterStatus"
                class="px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none text-sm">
                <option value="">Tous les statuts</option>
                <option value="active">Actif</option>
                <option value="inactive">Inactif</option>
            </select>
        </div>
    </div>

    <!-- Forms Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($forms as $form)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition group">
                <!-- Card Header with Color -->
                <div class="p-4 flex items-center gap-4" style="background-color: {{ $form->bg_color }}; border-bottom: 3px solid {{ $form->color }};">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-xl"
                        style="background-color: {{ $form->color }};">
                        <i class="{{ $form->icon }}"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 text-lg">{{ $form->title }}</h3>
                        @if($form->title_ar)
                            <p class="text-gray-500 text-sm" dir="rtl">{{ $form->title_ar }}</p>
                        @endif
                    </div>
                    <div>
                        @if($form->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>Actif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>Inactif
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-4">
                    @if($form->introduction)
                        <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ Str::limit($form->introduction, 100) }}</p>
                    @endif

                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                        <div class="flex items-center gap-1">
                            <i class="ri-list-ordered text-gray-400"></i>
                            <span>{{ $form->steps_count }} étapes</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i class="ri-inbox-line text-gray-400"></i>
                            <span>{{ $form->submissions_count }} soumissions</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                        <div class="flex items-center gap-2">

                            <!-- <a href="{{ route('admin.formulaires.submission.detail', $form->id) }}"
                                class="px-3 py-1.5 text-sm text-purple-600 hover:bg-purple-50 rounded-lg transition flex items-center gap-1">
                                <i class="ri-eye-line"></i> Soumissions
                            </a> -->
                        </div>

                        <div class="flex items-center gap-1 relative" x-data="{ open: false }">
                            <button @click="open = !open" class="p-1.5 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-50">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute right-4 mt-32 w-44 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                <button wire:click="toggleActive({{ $form->id }})"
                                    class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="{{ $form->is_active ? 'ri-toggle-fill text-green-500' : 'ri-toggle-line text-gray-400' }}"></i>
                                    {{ $form->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                                <a href="{{ route('admin.formulaires.edit', $form->id) }}"
                                    class="px-3 py-1.5 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition flex items-center gap-1">
                                    <i class="ri-edit-line"></i> Modifier
                                </a>
                                <button wire:click="duplicateForm({{ $form->id }})"
                                    class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="ri-file-copy-line text-blue-500"></i> Dupliquer
                                </button>
                                <div class="border-t border-gray-100 my-1"></div>
                                <button wire:click="deleteForm({{ $form->id }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer ce formulaire ?"
                                    class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="ri-delete-bin-line"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class="ri-file-list-3-line text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Aucun formulaire</h3>
                    <p class="text-gray-500 mb-4">Commencez par créer votre premier formulaire dynamique</p>
                    <a href="{{ route('admin.formulaires.create') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                        <i class="ri-add-line"></i> Créer un formulaire
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $forms->links() }}
    </div>
</div>
