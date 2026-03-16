<div class="max-w-5xl mx-auto">
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-linear-to-r from-slate-50 to-white">
            <h3 class="text-lg font-semibold text-gray-900">Éditer Candidat</h3>
        </div>

        <form wire:submit.prevent="updateCandidat" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                    <input wire:model="nom" type="text" id="nom" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('nom') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                    <input wire:model="prenom" type="text" id="prenom" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('prenom') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="matricule" class="block text-sm font-medium text-gray-700 mb-2">Matricule</label>
                    <input wire:model="matricule" type="text" id="matricule" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('matricule') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input wire:model="email" type="email" id="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                    <input wire:model="phone" type="text" id="phone" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('phone') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700 mb-2">Âge</label>
                    <input wire:model="age" type="number" min="0" id="age" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('age') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                    <input wire:model="address" type="text" id="address" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('address') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe <span class="text-gray-400 text-xs">(laisser vide pour conserver)</span></label>
                    <input wire:model="password" type="password" id="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2 border-t border-gray-100 pt-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="text-sm font-medium text-gray-700">Compte actif</span>
                    </label>
                </div>

                <div class="md:col-span-2 border-t border-gray-100 pt-4">


                <div class="md:col-span-2 border-t border-gray-100 pt-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Paramètre candidat: ordre des formulaires par projet</h4>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Projet</label>
                        <select wire:model.live="selected_project_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                            <option value="">-- Sélectionner un projet --</option>
                            @foreach($projectOptions as $p)
                                <option value="{{ $p['id'] }}">{{ $p['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if(!empty($formOrderItems))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($formOrderItems as $item)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <div class="text-sm font-semibold text-gray-800 truncate" title="{{ $item['title'] }}">{{ $item['title'] }}</div>
                                        <span class="text-xs px-2 py-0.5 rounded {{ $item['has_custom_order'] ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $item['has_custom_order'] ? 'Personnalisé' : 'Global' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="text-xs text-gray-500">Ordre</label>
                                        <input type="number" min="1" wire:model.defer="customOrders.{{ $item['id'] }}"
                                               @disabled($item['locked'])
                                               class="w-24 px-2 py-1 border border-gray-300 rounded text-sm disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                                        <span class="text-xs text-gray-400">Global: {{ $item['global_order'] }}</span>
                                    </div>
                                    <p class="text-xs mt-2 {{ $item['locked'] ? 'text-amber-600' : 'text-gray-500' }}">
                                        {{ $item['locked'] ? 'Verrouillé (déjà soumis / en révision / approuvé)' : 'Modifiable' }} · {{ $item['status_label'] }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <button type="button" wire:click="saveProjectFormOrders" class="px-4 py-2 bg-slate-700 hover:bg-slate-800 text-white rounded-lg text-sm">
                                <i class="ri-save-line"></i> Enregistrer ordre des formulaires
                            </button>
                        </div>
                    @else
                        <div class="text-sm text-gray-500 bg-gray-50 border border-gray-200 rounded-lg p-3">
                            Aucun formulaire actif pour ce projet.
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                    Enregistrer
                </button>
                <a href="{{ route('admin.candidats.show', $candidatId) }}" 
                   class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 text-center font-medium rounded-lg transition-colors duration-200">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
