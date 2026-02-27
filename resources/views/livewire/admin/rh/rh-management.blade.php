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
            ['Total', $statistics['total'], 'ri-team-line', 'blue'],
            ['Actifs', $statistics['active'], 'ri-checkbox-circle-line', 'green'],
            ['Inactifs', $statistics['inactive'], 'ri-close-circle-line', 'red'],
            ['En congé', $statistics['en_conge'], 'ri-calendar-event-line', 'amber'],
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
                <input type="text" wire:model.live="search" placeholder="Rechercher par nom, email, matricule, CIN..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">
            </div>
            <select wire:model.live="statusFilter" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                <option value="all">Tous les statuts</option>
                <option value="active">Actif</option>
                <option value="inactive">Inactif</option>
                <option value="en_conge">En congé</option>
                <option value="quitte">Quitté</option>
            </select>
            <select wire:model.live="departementFilter" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                <option value="all">Tous les départements</option>
                @foreach($departements as $dep)
                <option value="{{ $dep }}">{{ $dep }}</option>
                @endforeach
            </select>
            <button wire:click="openCreate" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <i class="ri-add-line mr-1"></i> Nouvel employé
            </button>
        </div>
    </div>

    {{-- ═══ Employee Cards ═══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($employees as $emp)
        @php
            $statusColors = [
                'active'   => 'bg-green-100 text-green-800',
                'inactive' => 'bg-red-100 text-red-800',
                'en_conge' => 'bg-amber-100 text-amber-800',
                'quitte'   => 'bg-gray-100 text-gray-600',
            ];
            $statusLabels = [
                'active'   => 'Actif',
                'inactive' => 'Inactif',
                'en_conge' => 'En congé',
                'quitte'   => 'Quitté',
            ];
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition group">
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-lg font-bold shadow">
                            {{ strtoupper(substr($emp->nom, 0, 1)) }}{{ strtoupper(substr($emp->prenom, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $emp->nom }} {{ $emp->prenom }}</h4>
                            @if($emp->poste)
                            <p class="text-xs text-gray-500">{{ $emp->poste }}</p>
                            @endif
                        </div>
                    </div>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $statusColors[$emp->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $statusLabels[$emp->status] ?? $emp->status }}
                    </span>
                </div>

                <div class="space-y-1.5 text-sm mb-4">
                    @if($emp->matricule)
                    <div class="flex items-center gap-2 text-gray-600"><i class="ri-hashtag text-gray-400 w-4"></i> {{ $emp->matricule }}</div>
                    @endif
                    @if($emp->departement)
                    <div class="flex items-center gap-2 text-gray-600"><i class="ri-building-line text-gray-400 w-4"></i> {{ $emp->departement }}</div>
                    @endif
                    @if($emp->email)
                    <div class="flex items-center gap-2 text-gray-600"><i class="ri-mail-line text-gray-400 w-4"></i> {{ $emp->email }}</div>
                    @endif
                    @if($emp->phone)
                    <div class="flex items-center gap-2 text-gray-600"><i class="ri-phone-line text-gray-400 w-4"></i> {{ $emp->phone }}</div>
                    @endif
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="ri-file-text-line text-gray-400 w-4"></i>
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded font-medium">{{ $emp->contrat_type }}</span>
                        @if($emp->date_embauche)
                        <span class="text-xs text-gray-400">depuis {{ $emp->date_embauche->format('d/m/Y') }}</span>
                        @endif
                    </div>
                </div>

                <div class="flex gap-2 pt-3 border-t border-gray-100">
                    <button wire:click="openEdit({{ $emp->id }})"
                            class="flex-1 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition">
                        <i class="ri-pencil-line mr-1"></i> Modifier
                    </button>
                    <button wire:click="delete({{ $emp->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer cet employé ?"
                            class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-lg transition">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
            <i class="ri-team-line text-5xl text-gray-300 mb-3 block"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucun employé trouvé</h3>
            <p class="text-sm text-gray-500">Ajoutez des employés pour commencer la gestion RH.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $employees->links() }}</div>

    {{-- ═══ MODAL ═══ --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click.self="$set('showModal', false)">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl z-10 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="text-lg font-bold text-indigo-800 flex items-center gap-2">
                    <i class="ri-user-settings-line text-indigo-600"></i>
                    {{ $editMode ? 'Modifier l\'employé' : 'Nouvel employé' }}
                </h3>
                <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600"><i class="ri-close-line text-xl"></i></button>
            </div>
            <form wire:submit.prevent="save" class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Matricule</label>
                        <input type="text" wire:model="matricule" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                        @error('matricule') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nom *</label>
                        <input type="text" wire:model="nom" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                        @error('nom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Prénom *</label>
                        <input type="text" wire:model="prenom" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                        @error('prenom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">CIN</label>
                        <input type="text" wire:model="cin" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Genre</label>
                        <select wire:model="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="">--</option>
                            <option value="homme">Homme</option>
                            <option value="femme">Femme</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Téléphone</label>
                        <input type="text" wire:model="phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Poste</label>
                        <input type="text" wire:model="poste" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Département</label>
                        <input type="text" wire:model="departement" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Type contrat *</label>
                        <select wire:model="contrat_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            @foreach(['CDI', 'CDD', 'Stage', 'Freelance', 'Autre'] as $ct)
                            <option value="{{ $ct }}">{{ $ct }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Date embauche</label>
                        <input type="date" wire:model="date_embauche" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Fin contrat</label>
                        <input type="date" wire:model="date_fin_contrat" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Salaire (MAD)</label>
                        <input type="number" step="0.01" wire:model="salaire" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Date naissance</label>
                        <input type="date" wire:model="date_naissance" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Statut *</label>
                        <select wire:model="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                            <option value="en_conge">En congé</option>
                            <option value="quitte">Quitté</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Adresse</label>
                    <input type="text" wire:model="address" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Notes</label>
                    <textarea wire:model="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none resize-none"></textarea>
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
