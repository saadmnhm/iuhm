<div class="max-w-4xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('admin.rh.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
            <i class="ri-arrow-left-line mr-1"></i> Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="ri-user-settings-line text-indigo-600"></i> Modifier Employé
        </h2>

        <form wire:submit.prevent="save" class="space-y-5">
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Matricule</label>
                    <input type="text" wire:model="matricule" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    @error('matricule') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nom *</label>
                    <input type="text" wire:model="nom" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    @error('nom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Prénom *</label>
                    <input type="text" wire:model="prenom" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    @error('prenom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">CIN</label>
                    <input type="text" wire:model="cin" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Genre</label>
                    <select wire:model="gender" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                        <option value="">--</option>
                        <option value="homme">Homme</option>
                        <option value="femme">Femme</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" wire:model="email" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Téléphone</label>
                    <input type="text" wire:model="phone" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Poste</label>
                    <input type="text" wire:model="poste" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Département</label>
                    <input type="text" wire:model="departement" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Type contrat *</label>
                    <select wire:model="contrat_type" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                        @foreach(['CDI', 'CDD', 'Stage', 'Freelance', 'Autre'] as $ct)
                        <option value="{{ $ct }}">{{ $ct }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Date embauche</label>
                    <input type="date" wire:model="date_embauche" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Fin contrat</label>
                    <input type="date" wire:model="date_fin_contrat" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Salaire (MAD)</label>
                    <input type="number" step="0.01" wire:model="salaire" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Date naissance</label>
                    <input type="date" wire:model="date_naissance" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Statut *</label>
                    <select wire:model="status" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                        <option value="en_conge">En congé</option>
                        <option value="quitte">Quitté</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Adresse</label>
                <input type="text" wire:model="address" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Notes</label>
                <textarea wire:model="notes" rows="3" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none resize-none"></textarea>
            </div>

            @if($existingPhoto)
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">Photo actuelle</label>
                <img src="{{ asset('uploads/' . $existingPhoto) }}" alt="Photo" class="w-24 h-24 rounded-lg object-cover border border-gray-200">
            </div>
            @endif

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">{{ $existingPhoto ? 'Changer la photo' : 'Photo de l\'employé' }}</label>
                <input type="file" wire:model="photo" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.rh.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Annuler</a>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">
                    <i class="ri-save-line mr-1"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
