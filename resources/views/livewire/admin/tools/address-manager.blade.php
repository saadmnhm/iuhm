<div>
    {{-- ═══ Header + Add Button ═══ --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestion des Adresses</h2>
            <p class="text-sm text-gray-500 mt-1">Gérer les adresses disponibles pour les candidats</p>
        </div>
        <button wire:click="openModal"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow font-semibold text-sm">
            <i class="ri-add-line text-lg"></i> Nouvelle Adresse
        </button>
    </div>

    {{-- ═══ Search ═══ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="relative">
            <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" wire:model.live="search" placeholder="Rechercher une adresse..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition text-sm">
        </div>
    </div>

    {{-- ═══ Address Cards Grid ═══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($addresses as $address)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition group">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <i class="ri-map-pin-line text-indigo-600 text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">{{ $address->address_line1 }}</h4>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $address->city }}
                            @if($address->state) · {{ $address->state }} @endif
                        </p>
                        @if($address->postal_code)
                        <span class="inline-block mt-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded font-medium">{{ $address->postal_code }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                    <button wire:click="edit({{ $address->id }})"
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Modifier">
                        <i class="ri-pencil-line"></i>
                    </button>
                    <button wire:click="delete({{ $address->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer cette adresse ?"
                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Supprimer">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
            <i class="ri-map-pin-line text-5xl text-gray-300 mb-3 block"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucune adresse trouvée</h3>
            <p class="text-sm text-gray-500">Ajoutez des adresses pour les rendre disponibles aux candidats.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">{{ $addresses->links() }}</div>

    {{-- ═══ MODAL ═══ --}}
    @if($modalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click.self="closeModal">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden">
            {{-- Modal header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="text-lg font-bold text-indigo-800 flex items-center gap-2">
                    <i class="ri-map-pin-line text-indigo-600"></i>
                    {{ $editMode ? 'Modifier l\'adresse' : 'Nouvelle adresse' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            {{-- Form --}}
            <form wire:submit.prevent="save" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Adresse *</label>
                    <input type="text" wire:model="address_line1" placeholder="Rue, quartier, bâtiment"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition text-sm">
                    @error('address_line1') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Ville *</label>
                        <input type="text" wire:model="city"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition text-sm">
                        @error('city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Région</label>
                        <input type="text" wire:model="state"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Code Postal</label>
                    <input type="text" wire:model="postal_code"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition text-sm">
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" wire:click="closeModal"
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 bg-white border border-gray-300 rounded-lg transition hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">
                        <i class="ri-save-line mr-1"></i> {{ $editMode ? 'Mettre à jour' : 'Enregistrer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

