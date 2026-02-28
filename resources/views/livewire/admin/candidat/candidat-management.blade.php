<div>
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Candidats</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['total'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Actifs</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $statistics['active'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Inactifs</p>
                    <p class="text-3xl font-bold text-red-500 mt-2">{{ $statistics['inactive'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Nouveaux ce mois</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $statistics['new_this_month'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Cards -->
    <div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">All Users</h3>
                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                <button wire:click="openCreateModal" class="px-4 py-2 bg-green-logo text-white rounded-lg transition">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nouveau Candidat
                    </span>
                </button>
                @endif
            </div>

        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($candidats as $candidat)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <!-- User Avatar and Info -->
                    <div class="flex items-center mb-4 pb-4 border-b border-gray-100">
                        <div class="w-14 h-14 rounded-full bg-green-logo flex items-center justify-center text-white text-xl font-semibold mr-4">
                            @if($candidat->profile_image)
                            <img src="{{ asset('uploads/' . $candidat->profile_image) }}" alt="Profile" class="w-full h-full rounded-full object-cover">
                        @else
                            <i class="ri-user-line"></i>
                        @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-base font-semibold text-gray-900 truncate">{{ $candidat->nom }} {{ $candidat->prenom }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ $candidat->email }}</div>
                            @if($candidat->matricule)
                            <span class="inline-block mt-1 px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs rounded-full font-medium">
                                <i class="ri-hashtag"></i> {{ $candidat->matricule }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="px-3 py-1 text-xs font-medium rounded-full ">
                            
                        </span>
                        @if(!($candidat->is_active ?? true))
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 ml-2">
                            Disabled
                        </span>
                        @endif
                    </div>

                    <!-- Join Date -->
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Joined {{ $candidat->created_at->format('M d, Y') }}
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <a href="{{ route('admin.candidats.show', $candidat->id) }}" 
                           class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 text-center">
                            View
                        </a>
                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                        <!-- <button wire:click="generateNewPassword({{ $candidat->id }})" 
                                class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                title="Generate new password">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </button> -->
                        <button wire:click="toggleStatus({{ $candidat->id }})" 
                                class="px-3 py-2 {{ ($candidat->is_active ?? true) ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                title="{{ ($candidat->is_active ?? true) ? 'Disable' : 'Enable' }} account">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($candidat->is_active ?? true)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @endif
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <p class="text-gray-500">No candidats found</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $candidats->links() }}
        </div>
    </div>

    <!-- Create / Edit Candidat Modal -->
    @if($showCreateModal || $showEditModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click.self="closeModals">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="text-lg font-bold text-indigo-800 flex items-center gap-2">
                    <i class="ri-user-add-line text-indigo-600"></i>
                    {{ $showEditModal ? 'Modifier le candidat' : 'Nouveau candidat' }}
                </h3>
                <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600"><i class="ri-close-line text-xl"></i></button>
            </div>
            <form wire:submit.prevent="{{ $showEditModal ? 'updateCandidat' : 'createCandidat' }}" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Matricule</label>
                    <input type="text" wire:model="matricule" placeholder="Ex: MAT-2024-001"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition text-sm">
                    @error('matricule') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nom *</label>
                        <input type="text" wire:model="nom" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 outline-none transition text-sm">
                        @error('nom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Prénom *</label>
                        <input type="text" wire:model="prenom" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 outline-none transition text-sm">
                        @error('prenom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email *</label>
                    <input type="email" wire:model="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 outline-none transition text-sm">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <!-- Address -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Adresse</label>
                    <select wire:model.live="address_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 outline-none transition text-sm bg-white">
                        <option value="">— Sélectionner une adresse —</option>
                        @foreach($addresses as $addr)
                            <option value="{{ $addr->id }}">{{ $addr->address_line1 }}{{ $addr->city ? ' — '.$addr->city : '' }}</option>
                        @endforeach
                        <option value="other">Autre (saisir manuellement)</option>
                    </select>
                    @error('address_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                @if($address_id === 'other')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Adresse personnalisée *</label>
                    <input type="text" wire:model="address_custom" placeholder="Saisir l'adresse complète..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 outline-none transition text-sm">
                    @error('address_custom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                @endif
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ $showEditModal ? 'Mot de passe (laisser vide pour garder)' : 'Mot de passe *' }}</label>
                    <input type="password" wire:model="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 outline-none transition text-sm">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" wire:click="closeModals" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Annuler</button>
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">
                        <i class="ri-save-line mr-1"></i> {{ $showEditModal ? 'Mettre à jour' : 'Créer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Password Generated Modal -->
    @if($showPasswordModal && $selectedCandidat)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click.self="$set('showPasswordModal', false)">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6 z-10">
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Nouveau mot de passe généré</h3>
                <p class="text-sm text-gray-500 mt-1">Pour {{ $selectedCandidat->nom }} {{ $selectedCandidat->prenom }}</p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                <div class="flex items-center gap-2">
                    <code class="flex-1 bg-white border border-gray-300 rounded-lg px-4 py-2 text-lg font-mono text-center tracking-wider select-all">{{ $generatedPassword }}</code>
                    <button onclick="navigator.clipboard.writeText('{{ $generatedPassword }}')" 
                            class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition" title="Copier">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <p class="text-sm text-yellow-800">Notez ce mot de passe maintenant. Il ne sera plus affiché après fermeture.</p>
                </div>
            </div>
            
            <button wire:click="$set('showPasswordModal', false)" class="w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                Fermer
            </button>
        </div>
    </div>
    @endif
</div>
