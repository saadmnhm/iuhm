<div class="max-w-4xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('admin.material.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
            <i class="ri-arrow-left-line mr-1"></i> Retour à l'inventaire
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="ri-pencil-line text-indigo-600"></i> Modifier Matériel
        </h2>

        <form wire:submit.prevent="save" class="space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nom *</label>
                    <input type="text" wire:model="name" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catégorie</label>
                    <select wire:model="category_id" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                        <option value="">-- Sélectionner --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Description</label>
                <textarea wire:model="description" rows="3" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none resize-none"></textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Quantité *</label>
                    <input type="number" wire:model="quantity" min="0" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Stock min.</label>
                    <input type="number" wire:model="quantity_min" min="0" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Prix unitaire (MAD)</label>
                    <input type="number" step="0.01" wire:model="prix_unitaire" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">État</label>
                    <select wire:model="etat" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                        <option value="neuf">Neuf</option>
                        <option value="bon">Bon</option>
                        <option value="usage">Usagé</option>
                        <option value="defectueux">Défectueux</option>
                        <option value="hors_service">Hors service</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Statut</label>
                    <select wire:model="status" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                        <option value="disponible">Disponible</option>
                        <option value="en_utilisation">En utilisation</option>
                        <option value="en_maintenance">En maintenance</option>
                        <option value="retire">Retiré</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Emplacement</label>
                    <input type="text" wire:model="emplacement" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Fournisseur</label>
                    <input type="text" wire:model="fournisseur" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">N° de série</label>
                    <input type="text" wire:model="numero_serie" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Date acquisition</label>
                    <input type="date" wire:model="date_acquisition" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Fin garantie</label>
                    <input type="date" wire:model="date_garantie" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Notes</label>
                <textarea wire:model="notes" rows="2" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none resize-none"></textarea>
            </div>

            {{-- Existing photos --}}
            @if(count($existingPhotos) > 0)
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">Photos existantes</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($existingPhotos as $photo)
                    <div class="relative group">
                        <img src="{{ asset('uploads/' . $photo['file_path']) }}" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                        <button type="button" wire:click="removePhoto({{ $photo['id'] }})" wire:confirm="Supprimer cette photo ?"
                                class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">Ajouter des photos</label>
                <input type="file" wire:model="newPhotos" multiple accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.material.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Annuler</a>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">
                    <i class="ri-save-line mr-1"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
