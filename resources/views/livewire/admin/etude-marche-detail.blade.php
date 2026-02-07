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
                <h2 class="text-2xl font-bold text-gray-900">Étude de Marché</h2>
                <div class="flex gap-4 mt-2 text-sm text-gray-500">
                    <span>ID: #{{ $etudeMarche->id }}</span>
                    <span>{{ $etudeMarche->created_at->format('d M Y') }}</span>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">
                        {{ $etudeMarche->form_type_label }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                <!-- Status Badge and Buttons -->
                <div class="flex items-center gap-2">
                    @if($etudeMarche->status === 'draft')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800">Brouillon</span>
                    @elseif($etudeMarche->status === 'submitted')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">Soumis</span>
                    @elseif($etudeMarche->status === 'in_review')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">En révision</span>
                    @elseif($etudeMarche->status === 'approved')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">Approuvé</span>
                    @elseif($etudeMarche->status === 'rejected')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">Rejeté</span>
                    @endif
                </div>
                
                <!-- Export PDF Button -->
                <a href="{{ route('admin.etude-marche.export-pdf', $etudeMarche->id) }}" 
                   class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
                
                <a href="{{ route('admin.candidat.submissions', $etudeMarche->candidat_id) }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    Retour
                </a>
            </div>
        </div>

        <!-- Candidat Info -->
        @if($etudeMarche->candidat)
        <div class="bg-gray-50 rounded-lg p-4 flex items-center gap-4">
            @if($etudeMarche->candidat->profile_image)
                <img src="{{ asset('uploads/'.$etudeMarche->candidat->profile_image) }}" alt="{{ $etudeMarche->candidat->nom }}" class="w-16 h-16 rounded-full object-cover">
            @else
                <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center text-white text-xl font-bold">
                    {{ strtoupper(substr($etudeMarche->candidat->nom, 0, 1)) }}
                </div>
            @endif
            <div>
                <h3 class="font-semibold text-gray-900">{{ $etudeMarche->candidat->nom }} {{ $etudeMarche->candidat->prenom }}</h3>
                <p class="text-gray-600">{{ $etudeMarche->candidat->email }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Step 1: Product/Service -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-box-3-line text-green-600 text-xl"></i>
                Produit/Service
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Produit/Service</label>
                    <p class="text-gray-900">{{ $etudeMarche->produit_service ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Description de l'offre</label>
                    <p class="text-gray-900">{{ $etudeMarche->description_offre ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Bénéfices clients</label>
                    <p class="text-gray-900">{{ $etudeMarche->benefices_clients ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Prix marché</label>
                    <p class="text-gray-900">{{ $etudeMarche->prix_marche ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Contrôle prix</label>
                    <p class="text-gray-900">{{ $etudeMarche->controle_prix ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Step 2: Target Customers -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-group-line text-green-600 text-xl"></i>
                Clientèle
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Type de clients</label>
                    <p class="text-gray-900">{{ $etudeMarche->type_clients ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Caractéristiques</label>
                    <p class="text-gray-900">{{ $etudeMarche->caracteristiques_clientele ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Fréquence de consommation</label>
                    <p class="text-gray-900">{{ $etudeMarche->frequence_consommation ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Localisation</label>
                    <p class="text-gray-900">{{ $etudeMarche->localisation_clients ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Exigences principales</label>
                    <p class="text-gray-900">{{ $etudeMarche->exigences_principales ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Step 3: Competition -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-trophy-line text-green-600 text-xl"></i>
                Concurrence
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Concurrents directs</label>
                    <p class="text-gray-900">{{ $etudeMarche->nombre_concurrents_directs ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Concurrents indirects</label>
                    <p class="text-gray-900">{{ $etudeMarche->concurrents_indirects ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Taille des concurrents</label>
                    <p class="text-gray-900">{{ $etudeMarche->taille_concurrents ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Informations concurrents</label>
                    <p class="text-gray-900">{{ $etudeMarche->informations_concurrents ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Communication</label>
                    <p class="text-gray-900">{{ $etudeMarche->communication_concurrents ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Step 4: Suppliers -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-truck-line text-green-600 text-xl"></i>
                Fournisseurs
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Nombre de fournisseurs</label>
                    <p class="text-gray-900">{{ $etudeMarche->nombre_fournisseurs ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Origine</label>
                    <p class="text-gray-900">{{ $etudeMarche->origine_fournisseurs ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Prix</label>
                    <p class="text-gray-900">{{ $etudeMarche->prix_fournisseurs ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Délais de livraison</label>
                    <p class="text-gray-900">{{ $etudeMarche->delais_livraison ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Stabilité du marché</label>
                    <p class="text-gray-900">{{ $etudeMarche->stabilite_marche ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Actions -->
    <div class="mt-6 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
        <div class="flex gap-3">
            @if($etudeMarche->status !== 'approved')
            <button wire:click="updateStatus('approved')" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                <i class="ri-check-line mr-2"></i>Approuver
            </button>
            @endif
            @if($etudeMarche->status !== 'rejected')
            <button wire:click="updateStatus('rejected')" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                <i class="ri-close-line mr-2"></i>Rejeter
            </button>
            @endif
            @if($etudeMarche->status !== 'in_review')
            <button wire:click="updateStatus('in_review')" class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition">
                <i class="ri-time-line mr-2"></i>En révision
            </button>
            @endif
        </div>
    </div>
</div>
