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
                <h2 class="text-2xl font-bold text-gray-900">Évaluation d'Idée</h2>
                <div class="flex gap-4 mt-2 text-sm text-gray-500">
                    <span>ID: #{{ $evaluationIdee->id }}</span>
                    <span>{{ $evaluationIdee->created_at->format('d M Y') }}</span>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                        {{ $evaluationIdee->form_type_label }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                <!-- Status Badge -->
                <div class="flex items-center gap-2">
                    @if($evaluationIdee->status === 'draft')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800">Brouillon</span>
                    @elseif($evaluationIdee->status === 'submitted')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">Soumis</span>
                    @elseif($evaluationIdee->status === 'in_review')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">En révision</span>
                    @elseif($evaluationIdee->status === 'approved')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">Approuvé</span>
                    @elseif($evaluationIdee->status === 'rejected')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">Rejeté</span>
                    @endif
                </div>
                
                <!-- Export PDF Button -->
                <a href="{{ route('admin.evaluation-idee.export-pdf', $evaluationIdee->id) }}" 
                   class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
                
                <a href="{{ route('admin.candidat.submissions', $evaluationIdee->candidat_id) }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    Retour
                </a>
            </div>
        </div>

        <!-- Candidat Info -->
        @if($evaluationIdee->candidat)
        <div class="bg-gray-50 rounded-lg p-4 flex items-center gap-4">
            @if($evaluationIdee->candidat->profile_image)
                <img src="{{ asset('uploads/'.$evaluationIdee->candidat->profile_image) }}" alt="{{ $evaluationIdee->candidat->nom }}" class="w-16 h-16 rounded-full object-cover">
            @else
                <div class="w-16 h-16 rounded-full bg-purple-500 flex items-center justify-center text-white text-xl font-bold">
                    {{ strtoupper(substr($evaluationIdee->candidat->nom, 0, 1)) }}
                </div>
            @endif
            <div>
                <h3 class="font-semibold text-gray-900">{{ $evaluationIdee->candidat->nom }} {{ $evaluationIdee->candidat->prenom }}</h3>
                <p class="text-gray-600">{{ $evaluationIdee->candidat->email }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Project Idea -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-lightbulb-line text-purple-600 text-xl"></i>
                Idée du Projet
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Idée du projet</label>
                    <p class="text-gray-900">{{ $evaluationIdee->idee_projet ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Résumé de l'idée</label>
                    <p class="text-gray-900">{{ $evaluationIdee->resume_idee ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Activité</label>
                    <p class="text-gray-900">{{ $evaluationIdee->activite ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Lieu d'implantation</label>
                    <p class="text-gray-900">{{ $evaluationIdee->lieu_implantation ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Project Need -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-question-line text-purple-600 text-xl"></i>
                Besoin du Projet
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Besoin identifié</label>
                    <p class="text-gray-900">{{ $evaluationIdee->besoin_projet ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Origine de l'idée</label>
                    <p class="text-gray-900">{{ $evaluationIdee->origine_idee ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Expérience nécessaire</label>
                    <p class="text-gray-900">{{ $evaluationIdee->experience_necessaire ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Products/Services -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-product-hunt-line text-purple-600 text-xl"></i>
                Produits/Services
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Produits/Services offerts</label>
                    <p class="text-gray-900">{{ $evaluationIdee->produits_services ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Description</label>
                    <p class="text-gray-900">{{ $evaluationIdee->description_produits ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Utilité</label>
                    <p class="text-gray-900">{{ $evaluationIdee->utilite_produits ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Target Customers -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-team-line text-purple-600 text-xl"></i>
                Clients Cibles
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Clients identifiés</label>
                    <p class="text-gray-900">{{ $evaluationIdee->clients_identifies ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Besoins des clients</label>
                    <p class="text-gray-900">{{ $evaluationIdee->besoins_clients ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Nombre de clients</label>
                    <p class="text-gray-900">{{ $evaluationIdee->nombre_clients ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Value Proposition -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-star-line text-purple-600 text-xl"></i>
                Proposition de Valeur
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Valeur ajoutée</label>
                    <p class="text-gray-900">{{ $evaluationIdee->valeur_ajoutee ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Différenciation</label>
                    <p class="text-gray-900">{{ $evaluationIdee->differenciation ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Avantages concurrentiels</label>
                    <p class="text-gray-900">{{ $evaluationIdee->avantages_concurrentiels ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Actions -->
    <div class="mt-6 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
        <div class="flex gap-3">
            @if($evaluationIdee->status !== 'approved')
            <button wire:click="updateStatus('approved')" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                <i class="ri-check-line mr-2"></i>Approuver
            </button>
            @endif
            @if($evaluationIdee->status !== 'rejected')
            <button wire:click="updateStatus('rejected')" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                <i class="ri-close-line mr-2"></i>Rejeter
            </button>
            @endif
            @if($evaluationIdee->status !== 'in_review')
            <button wire:click="updateStatus('in_review')" class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition">
                <i class="ri-time-line mr-2"></i>En révision
            </button>
            @endif
        </div>
    </div>
</div>
