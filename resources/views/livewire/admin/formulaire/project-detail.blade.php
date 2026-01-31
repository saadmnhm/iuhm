<div>
    @if(session()->has('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if($project)
    <!-- En-tête du Projet -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
<div class="flex justify-between items-start mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">{{ $project->project_name ?? $project->project_title ?? 'Sans titre' }}</h2>
        <div class="flex gap-4 mt-2 text-sm text-gray-500">
            <span>ID: #{{ $project->id }}</span>
            <span>{{ $project->created_at->format('d M Y') }}</span>
        </div>
    </div>
    <div class="flex gap-2">
        <!-- Export PDF Button -->
        <a href="{{ route('admin.projects.export.pdf', $project->id) }}" 
           class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export PDF
        </a>
        
        @if(!$project->registration)
        <button wire:click="$set('showModal', true)" class="px-4 py-2 bg-green-logo text-white rounded-lg hover:bg-green-600 transition">
            <i class="fas fa-plus-circle me-2"></i>
            Ajouter la Matriculation
        </button>
        @else
        <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg">
            <i class="fas fa-check-circle me-2"></i>
            Matriculation: {{ $project->registration }}
        </span>
        @endif
        
        <a href="{{ route('admin.projects') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
            Retour à la liste
        </a>
    </div>
</div>

        <!-- Informations Utilisateur -->
        <div class="bg-gray-50 rounded-lg p-4 flex items-center gap-4">
            @if($project->profile_image)
            <div class="w-16 h-16 rounded-full   flex items-center justify-center text-white text-xl font-bold">
                <img src="{{ $project->profile_image ? route('uploads.show', $project->profile_image) : asset('assets/images/default-avatar.png') }}" alt="{{ $project->ceo_name }}" class="w-14 h-14 rounded-full object-cover">
            </div>
            @else
            <div class="w-16 h-16 rounded-full bg-green-logo flex items-center justify-center text-white text-xl font-bold">
                {{ strtoupper(substr($candidat->nom, 0, 1)) }}
            </div>
            @endif

            <div>
                <h3 class="font-semibold text-gray-900">{{ $candidat->nom }} {{ $candidat->prenom }}</h3>
                <p class="text-gray-600">{{ $candidat->email }}</p>
            </div>
        </div>
    </div>

    <!-- Grille de Détails -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Informations Personnelles -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informations Personnelles
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Âge</span>
                    <span class="font-medium text-gray-900">{{ $candidat->age ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Genre</span>
                    <span class="font-medium text-gray-900">{{ $candidat->gender ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Adresse</span>
                    <span class="font-medium text-gray-900 text-right">{{ $candidat->address ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Email</span>
                    <span class="font-medium text-gray-900">{{ $candidat->email ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Téléphone</span>
                    <span class="font-medium text-gray-900">{{ $candidat->phone ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Informations du Projet -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Informations du Projet
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Nom du Projet</span>
                    <span class="font-medium text-gray-900 text-right">{{ $project->project_name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Nom du porteur de projet</span>
                    <span class="font-medium text-gray-900">{{ $candidat->nom }} {{ $candidat->prenom }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Structure Juridique</span>
                    <span class="font-medium text-gray-900">{{ $project->legal_structure ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Lieu du Projet</span>
                    <span class="font-medium text-gray-900 text-right">{{ $project->lieu_projet ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Analyse de Marché -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Analyse de Marché
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Public Cible</span>
                    <span class="font-medium text-gray-900 text-right">{{ $project->public_cible ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Concurrent</span>
                    <span class="font-medium text-gray-900 text-right">{{ $project->concurrent ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Volume Produits Locaux</span>
                    <span class="font-medium text-gray-900">{{ $project->volume_produits_locaux ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Volume Demande</span>
                    <span class="font-medium text-gray-900">{{ $project->volume_demande ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Demande/Offre</span>
                    <span class="font-medium text-gray-900 text-right">{{ $project->demande_offre ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Marketing & Chronologie -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                5- Le Calendrier
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Plan d'Affaires</span>
                    <span class="font-medium text-gray-900">{{ $project->plan_affaires ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Obtention Financement</span>
                    <span class="font-medium text-gray-900">{{ $project->obtention_financement ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Ouverture Procès</span>
                    <span class="font-medium text-gray-900">{{ $project->ouverture_proces ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Lancement Recrutement</span>
                    <span class="font-medium text-gray-900">{{ $project->lancement_recrutement ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Ouverture Définitive</span>
                    <span class="font-medium text-gray-900">{{ $project->ouverture_definitive ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Durée</span>
                    <span class="font-medium text-gray-900">{{ $project->duree ?? 'N/A' }}</span>
                    </div>                
                </div>
        </div>

        <!-- Programme d'Investissement -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Programme d'Investissement
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Coûts de Création</span>
                    <span class="font-medium text-gray-900">{{ $project->couts_creation ? number_format($project->couts_creation, 2) . ' DH' : 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Préparation Entreprise</span>
                    <span class="font-medium text-gray-900">{{ $project->preparation_entreprise ? number_format($project->preparation_entreprise, 2) . ' DH' : 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Achat Machines</span>
                    <span class="font-medium text-gray-900">{{ $project->achat_machines ? number_format($project->achat_machines, 2) . ' DH' : 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Achat Matières Premières</span>
                    <span class="font-medium text-gray-900">{{ $project->achat_matieres_premieres ? number_format($project->achat_matieres_premieres, 2) . ' DH' : 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Autres Coûts</span>
                    <span class="font-medium text-gray-900">{{ $project->autres_couts ? number_format($project->autres_couts, 2) . ' DH' : 'N/A' }}</span>
                </div>
                <div class="flex justify-between bg-gray-50 p-2 rounded">
                    <span class="text-gray-900 font-semibold">Total</span>
                    <span class="font-bold text-gray-900">{{ $project->total ? number_format($project->total, 2) . ' DH' : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Localisation & Avantages -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Localisation & Avantages
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Adaptation du Lieu</span>
                    <span class="font-medium text-gray-900 text-right">{{ $project->adaptation_lieu ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Bénéfices du Projet</span>
                    <span class="font-medium text-gray-900 text-right">{{ $project->benefices_from_projet ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Valeur du Projet</span>
                    <span class="font-medium text-gray-900 text-right">{{ $project->valeur_projet ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Détails Marketing -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
            Détails Marketing
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($project->motivations_achat)
            <div class="border-l-4 border-pink-500 pl-4">
                <p class="text-sm text-gray-600 mb-1">Motivations d'Achat</p>
                <p class="text-gray-900 font-medium">{{ $project->motivations_achat }}</p>
            </div>
            @endif
            @if($project->raison_choix_client)
            <div class="border-l-4 border-pink-500 pl-4">
                <p class="text-sm text-gray-600 mb-1">Raison du Choix Client</p>
                <p class="text-gray-900 font-medium">{{ $project->raison_choix_client }}</p>
            </div>
            @endif
            @if($project->méthodes_marketing)
            <div class="border-l-4 border-pink-500 pl-4">
                <p class="text-sm text-gray-600 mb-1">Méthodes Marketing</p>
                <p class="text-gray-900 font-medium">{{ $project->méthodes_marketing }}</p>
            </div>
            @endif
            @if($project->adaptation_methodes)
            <div class="border-l-4 border-pink-500 pl-4">
                <p class="text-sm text-gray-600 mb-1">Adaptation des Méthodes</p>
                <p class="text-gray-900 font-medium">{{ $project->adaptation_methodes }}</p>
            </div>
            @endif
            @if($project->differenciation_marketing)
            <div class="border-l-4 border-pink-500 pl-4">
                <p class="text-sm text-gray-600 mb-1">Différenciation Marketing</p>
                <p class="text-gray-900 font-medium">{{ $project->differenciation_marketing }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Capacités Marketing -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
            Capacités
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($project->step_8_1)
            <div class="border-l-4 border-pink-500 pl-4">
                <p class="text-sm text-gray-600 mb-1">les compétences nécessaires</p>
                <p class="text-gray-900 font-medium">{{ $project->step_8_1 }}</p>
            </div>
            @endif
            @if($project->step_8_2)
            <div class="border-l-4 border-pink-500 pl-4">
                <p class="text-sm text-gray-600 mb-1">le matériel et les outils nécessaires</p>
                <p class="text-gray-900 font-medium">{{ $project->step_8_2 }}</p>
            </div>
            @endif
            @if($project->step_8_3)
            <div class="border-l-4 border-pink-500 pl-4">
                <p class="text-sm text-gray-600 mb-1"> l’expérience nécessaire</p>
                <p class="text-gray-900 font-medium">{{ $project->step_8_3 }}</p>
            </div>
            @endif
            @if($project->step_8_4)
            <div class="border-l-4 border-pink-500 pl-4">
                <p class="text-sm text-gray-600 mb-1">fonds nécessaires au démarrage</p>
                <p class="text-gray-900 font-medium">{{ $project->step_8_4 }}</p>
            </div>
            @endif
        </div>
    </div>


    <!-- Description du Projet -->
    @if($project->description)
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Description du Projet
        </h3>
        <p class="text-gray-700 leading-relaxed">{{ $project->description }}</p>
    </div>
    @endif

    <!-- Résumé Exécutif -->
    @if($project->resume_executif)
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Résumé Exécutif
        </h3>
        <p class="text-gray-700 leading-relaxed">{{ $project->resume_executif }}</p>
    </div>
    @endif



    <!-- Questions Financières -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Questions Financières
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($project->generer_profits)
            <div class="bg-green-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Comment Générer des Profits?</p>
                <p class="text-gray-900 font-medium">{{ $project->generer_profits }}</p>
            </div>
            @endif
            @if($project->projet_durable)
            <div class="bg-green-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Projet Durable?</p>
                <p class="text-gray-900 font-medium">{{ $project->projet_durable }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Tableau des Produits -->
    @if($project->products->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Produits et Services
            </h3>

            
        </div>
        <p class="text-lg font-semibold text-gray-900 flex items-center gap-2 mt-4 mb-2 ms-3">1- Services Proposerez</p>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom du Produit</th>
                        <th class="border px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($project->products as $product)
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">{{ $product->product_name }}</td>
                        <td class="border px-6 py-4 text-sm font-medium text-gray-900">{{ $product->description }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
            <p class="text-lg font-semibold text-gray-900 flex items-center gap-2 mt-4 mb-2 ms-3">2- Presentation Des Produits</p>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom du Produit</th>
                        <th class="border px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mode de presentation</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($project->presentations as $product)
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">{{ $product->product_name_presentation }}</td>
                        <td class="border px-6 py-4 text-sm font-medium text-gray-900">{{ $product->presentation_methode }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="text-lg font-semibold text-gray-900 flex items-center gap-2 mt-4 mb-2 ms-3">3- Distribution Méthode</p>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom du Produit</th>
                        <th class="border px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mode de livraison</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($project->deliveries as $product)
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">{{ $product->product_name_livraison }}</td>
                        <td class="border px-6 py-4 text-sm font-medium text-gray-900">{{ $product->livraison_methode }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Tableau des Employés -->
    @if($project->employees->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Employés
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full ">
                <thead class="bg-gray-50">
                    <tr>
                        <th rowspan="2" class="border px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre d'emploi</th>
                        <th colspan="2" class="border px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase text-center">nombre</th>
                    </tr>
                    <tr>
                        <th class="border px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase text-center ">Première année</th>
                        <th class="border px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase text-center">deuxième année</th>
                    </tr>

                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($project->employees as $employee)
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">{{ $employee->item }}</td>
                        <td class="border px-6 py-4 text-sm text-gray-900">{{ $employee->total_employee_1 }}</td>
                        <td class="border px-6 py-4 text-sm text-gray-900">{{ $employee->total_employee_2 }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="border px-6 py-4 text-sm font-medium text-gray-900">Total</td>
                        <td class="border px-6 py-4 text-sm font-medium text-gray-900">{{ $project->employees->sum('total_employee_1') }}</td>
                        <td class="border px-6 py-4 text-sm font-medium text-gray-900">{{ $project->employees->sum('total_employee_2') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif
    
    <!-- Prévisions financières -->
    @if($project->financials)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                Prévisions financières
            </h3>
        </div>
        @php
            $financial = $project->financials;
        @endphp
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="border px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Première année</th>
                        <th class="border px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Deuxième année</th>
                        <th class="border px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Troisième année</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Revenus Section -->
                    <tr class="bg-green-50">
                        <td colspan="4" class="border px-6 py-3 text-sm font-bold text-gray-900">FRAIS PRÉVUS</td>
                    </tr>
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">Achats Prévus</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->achat_prevue_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->achat_prevue_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->achat_prevue_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">Frais de Fonctionnement</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->frais_fonctionnement_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->frais_fonctionnement_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->frais_fonctionnement_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>   
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">Charges de Personnel</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->charges_personnel_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->charges_personnel_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->charges_personnel_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">Dettes</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->dettes_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->dettes_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->dettes_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr> 
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">Établissement bancaire</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->etablissement_bancaire_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->etablissement_bancaire_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->etablissement_bancaire_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">Fournisseurs</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->fournisseurs_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->fournisseurs_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->fournisseurs_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>   
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">Autres dettes</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->autres_dettes_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->autres_dettes_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->autres_dettes_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">Autres Charges</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->autres_charges_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->autres_charges_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->autres_charges_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>                                                                                                                       



                    <tr class="bg-green-100">
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900">Total Revenus</td>
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900 text-center">{{ number_format($financial->total_prevus_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900 text-center">{{ number_format($financial->total_prevus_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900 text-center">{{ number_format($financial->total_prevus_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>

                    <!-- Dépenses Section -->
                    <tr class="bg-red-50">
                        <td colspan="4" class="border px-6 py-3 text-sm font-bold text-gray-900">PROGRAMME D'INVESTISSEMENT</td>
                    </tr>
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">Ventes</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->ventes_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->ventes_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->ventes_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">Services</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->services_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->services_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->services_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">Aide Financière</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->aide_financiere_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->aide_financiere_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->aide_financiere_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">Revenus Financiers</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->revenus_financiers_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->revenus_financiers_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->revenus_financiers_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>
                    <tr>
                        <td class="border px-6 py-4 text-sm text-gray-900">Autres Revenus</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->autres_revenus_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->autres_revenus_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm text-gray-900 text-center">{{ number_format($financial->autres_revenus_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>
                    <tr class="bg-red-100">
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900">Total Dépenses</td>
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900 text-center">{{ number_format($financial->total_investissements_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900 text-center">{{ number_format($financial->total_investissements_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900 text-center">{{ number_format($financial->total_investissements_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>

                    <!-- RÉSULTAT Section -->
                    <tr class="bg-blue-50">
                        <td colspan="4" class="border px-6 py-3 text-sm font-bold text-gray-900">RÉSULTAT</td>
                    </tr>      
                    <tr >
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900">Revenus</td>
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900 text-center">{{ number_format($financial->revenus_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900 text-center">{{ number_format($financial->revenus_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900 text-center">{{ number_format($financial->revenus_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>                                 
                    <tr >
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900">Dépenses</td>
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900 text-center">{{ number_format($financial->depenses_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900 text-center">{{ number_format($financial->depenses_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900 text-center">{{ number_format($financial->depenses_troisieme_annee ?? 0, 2) }} DH</td>
                    </tr>                   

                    <!-- Résultat Section -->
                    <tr class="bg-blue-50">
                        <td class="border px-6 py-4 text-sm font-bold text-gray-900">Résultat (Revenus - Dépenses) RÉSULTAT (REVENUS - DÉPENSES)</td>
                        <td class="border px-6 py-4 text-sm font-bold text-blue-700 text-center">{{ number_format($financial->resultat_premiere_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm font-bold text-blue-700 text-center">{{ number_format($financial->resultat_deuxieme_annee ?? 0, 2) }} DH</td>
                        <td class="border px-6 py-4 text-sm font-bold text-blue-700 text-center">{{ number_format($financial->resultat_troisieme_annee ?? 0, 2) }} DH </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Tableau des Équipements -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Équipements & Outils
            </h3>
        </div>
        <div class="overflow-x-auto">
        @if($project->equipment->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Machines et équipements</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix Prix</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($project->equipment as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->equipement }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->reference }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ number_format($item->prix_equipement) }} DH</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        @if($project->rawMaterials->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Matières premières</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comment se les procurer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fournisseur</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($project->rawMaterials as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->matiere_premiere }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->comment_procurer }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->fournisseur_matiere }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        </div>
    </div>


    @endif

    <!-- Modal pour ajouter la matriculation -->
    @if($showModal ?? false)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="$set('showModal', false)">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" wire:click.stop>
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-id-card me-2 text-green-600"></i>
                        Ajouter la Matriculation
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveRegistration">
                    <div class="mb-4">
                        <label for="registration" class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro de Matriculation <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="registration" 
                            wire:model="registration"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Ex: MAT-2025-001"
                            required
                        >
                        @error('registration')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button 
                            type="button"
                            wire:click="$set('showModal', false)"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition"
                        >
                            Annuler
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition"
                        >
                            <i class="fas fa-save me-2"></i>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
