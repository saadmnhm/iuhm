<div>
    @if(session()->has('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        {{ session('success') }}
    </div>
    @endif

    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Business Model Canvas (BMC)</h2>
                <div class="flex gap-4 mt-2 text-sm text-gray-500">
                    <span>ID: #{{ $bmc->id }}</span>
                    <span>{{ $bmc->created_at->format('d M Y') }}</span>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">
                        {{ $bmc->form_type_label }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                <!-- Status Badge -->
                <div class="flex items-center gap-2">
                    @if($bmc->status === 'draft')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800">Brouillon</span>
                    @elseif($bmc->status === 'submitted')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">Soumis</span>
                    @elseif($bmc->status === 'in_review')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">En révision</span>
                    @elseif($bmc->status === 'approved')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">Approuvé</span>
                    @elseif($bmc->status === 'rejected')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">Rejeté</span>
                    @endif
                </div>
                
                <!-- Export PDF Button -->
                <a href="{{ route('admin.bmc.export-pdf', $bmc->id) }}" 
                   class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
                
                <a href="{{ route('admin.candidat.submissions', $bmc->candidat_id) }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    Retour
                </a>
            </div>
        </div>

        <!-- Candidat Info -->
        @if($bmc->candidat)
        <div class="bg-gray-50 rounded-lg p-4 flex items-center gap-4">
            @if($bmc->candidat->profile_image)
                <img src="{{ asset('uploads/'.$bmc->candidat->profile_image) }}" alt="{{ $bmc->candidat->nom }}" class="w-16 h-16 rounded-full object-cover">
            @else
                <div class="w-16 h-16 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xl font-bold">
                    {{ strtoupper(substr($bmc->candidat->nom, 0, 1)) }}
                </div>
            @endif
            <div>
                <h3 class="font-semibold text-gray-900">{{ $bmc->candidat->nom }} {{ $bmc->candidat->prenom }}</h3>
                <p class="text-gray-600">{{ $bmc->candidat->email }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- BMC 9 Blocks Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Key Partners -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-group-line text-indigo-600 text-xl"></i>
                Partenaires Clés
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Partenaires clés</label>
                    <p class="text-gray-900">{{ $bmc->partenaires_cles ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Rôle</label>
                    <p class="text-gray-900">{{ $bmc->role_partenaires ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Key Activities -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-settings-3-line text-indigo-600 text-xl"></i>
                Activités Clés
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Activités clés</label>
                    <p class="text-gray-900">{{ $bmc->activites_cles ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Description</label>
                    <p class="text-gray-900">{{ $bmc->description_activites ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Value Proposition -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-star-line text-indigo-600 text-xl"></i>
                Proposition de Valeur
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Proposition de valeur</label>
                    <p class="text-gray-900">{{ $bmc->proposition_valeur ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Avantages</label>
                    <p class="text-gray-900">{{ $bmc->avantages_offerts ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Customer Relationships -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-user-heart-line text-indigo-600 text-xl"></i>
                Relations Clients
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Type de relation</label>
                    <p class="text-gray-900">{{ $bmc->relations_clients ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Description</label>
                    <p class="text-gray-900">{{ $bmc->type_relation ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Customer Segments -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-team-line text-indigo-600 text-xl"></i>
                Segments de Clientèle
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Segments de clientèle</label>
                    <p class="text-gray-900">{{ $bmc->segments_clientele ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Caractéristiques</label>
                    <p class="text-gray-900">{{ $bmc->caracteristiques_segments ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Key Resources -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-tools-line text-indigo-600 text-xl"></i>
                Ressources Clés
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Ressources clés</label>
                    <p class="text-gray-900">{{ $bmc->ressources_cles ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Type</label>
                    <p class="text-gray-900">{{ $bmc->type_ressources ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Channels -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 lg:col-span-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-route-line text-indigo-600 text-xl"></i>
                Canaux de Distribution
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Canaux</label>
                    <p class="text-gray-900">{{ $bmc->canaux ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Description</label>
                    <p class="text-gray-900">{{ $bmc->description_canaux ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Cost Structure -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-money-dollar-circle-line text-indigo-600 text-xl"></i>
                Structure des Coûts
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Coûts principaux</label>
                    <p class="text-gray-900">{{ $bmc->structure_couts ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Type de coûts</label>
                    <p class="text-gray-900">{{ $bmc->type_couts ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Revenue Streams -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-coins-line text-indigo-600 text-xl"></i>
                Flux de Revenus
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Sources de revenus</label>
                    <p class="text-gray-900">{{ $bmc->flux_revenus ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Type</label>
                    <p class="text-gray-900">{{ $bmc->type_revenus ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Actions -->
    <div class="mt-6 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
        <div class="flex gap-3">
            @if($bmc->status !== 'approved')
            <button wire:click="updateStatus('approved')" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                <i class="ri-check-line mr-2"></i>Approuver
            </button>
            @endif
            @if($bmc->status !== 'rejected')
            <button wire:click="updateStatus('rejected')" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                <i class="ri-close-line mr-2"></i>Rejeter
            </button>
            @endif
            @if($bmc->status !== 'in_review')
            <button wire:click="updateStatus('in_review')" class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition">
                <i class="ri-time-line mr-2"></i>En révision
            </button>
            @endif
        </div>
    </div>
</div>
