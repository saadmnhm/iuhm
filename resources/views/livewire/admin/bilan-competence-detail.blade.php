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
                <h2 class="text-2xl font-bold text-gray-900">Bilan de Compétences</h2>
                <div class="flex gap-4 mt-2 text-sm text-gray-500">
                    <span>ID: #{{ $bilanCompetence->id }}</span>
                    <span>{{ $bilanCompetence->created_at->format('d M Y') }}</span>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                        {{ $bilanCompetence->form_type_label }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                <!-- Status Badge -->
                <div class="flex items-center gap-2">
                    @if($bilanCompetence->status === 'draft')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800">Brouillon</span>
                    @elseif($bilanCompetence->status === 'submitted')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">Soumis</span>
                    @elseif($bilanCompetence->status === 'in_review')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">En révision</span>
                    @elseif($bilanCompetence->status === 'approved')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">Approuvé</span>
                    @elseif($bilanCompetence->status === 'rejected')
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">Rejeté</span>
                    @endif
                </div>
                
                <!-- Export PDF Button -->
                <a href="{{ route('admin.bilan-competence.export-pdf', $bilanCompetence->id) }}" 
                   class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
                
                <a href="{{ route('admin.candidat.submissions', $bilanCompetence->candidat_id) }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    Retour
                </a>
            </div>
        </div>

        <!-- Candidat Info -->
        @if($bilanCompetence->candidat)
        <div class="bg-gray-50 rounded-lg p-4 flex items-center gap-4">
            @if($bilanCompetence->candidat->profile_image)
                <img src="{{ asset('uploads/'.$bilanCompetence->candidat->profile_image) }}" alt="{{ $bilanCompetence->candidat->nom }}" class="w-16 h-16 rounded-full object-cover">
            @else
                <div class="w-16 h-16 rounded-full bg-yellow-500 flex items-center justify-center text-white text-xl font-bold">
                    {{ strtoupper(substr($bilanCompetence->candidat->nom, 0, 1)) }}
                </div>
            @endif
            <div>
                <h3 class="font-semibold text-gray-900">{{ $bilanCompetence->candidat->nom }} {{ $bilanCompetence->candidat->prenom }}</h3>
                <p class="text-gray-600">{{ $bilanCompetence->candidat->email }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Personal Qualities -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-user-star-line text-yellow-600 text-xl"></i>
                Qualités et Défauts
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Qualités et Défauts</label>
                    <p class="text-gray-900">
                        @if(is_array($bilanCompetence->qualites_defauts) && count($bilanCompetence->qualites_defauts) > 0)
                            @foreach($bilanCompetence->qualites_defauts as $item)
                                @if(isset($item['qualite']))
                                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs mr-2 mb-2">✓ {{ $item['qualite'] }}</span>
                                @endif
                                @if(isset($item['defaut']))
                                    <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded text-xs mr-2 mb-2">✗ {{ $item['defaut'] }}</span>
                                @endif
                            @endforeach
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Contribution des qualités</label>
                    <p class="text-gray-900">{{ $bilanCompetence->qualites_contribution ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Freins liés aux défauts</label>
                    <p class="text-gray-900">{{ $bilanCompetence->defauts_freins ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Loisirs</label>
                    <p class="text-gray-900">{{ $bilanCompetence->loisirs ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Education -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-graduation-cap-line text-yellow-600 text-xl"></i>
                Formation
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Niveau d'études</label>
                    <p class="text-gray-900">{{ $bilanCompetence->niveau_etude ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Diplômes obtenus</label>
                    <p class="text-gray-900">{{ $bilanCompetence->diplomes_obtenus ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Année d'obtention</label>
                    <p class="text-gray-900">{{ $bilanCompetence->annee_obtention ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Établissement</label>
                    <p class="text-gray-900">{{ $bilanCompetence->etablissement_obtention ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Compétences acquises</label>
                    <p class="text-gray-900">
                        @if(is_array($bilanCompetence->competences_formation) && count($bilanCompetence->competences_formation) > 0)
                            @php
                                $skills = array_filter($bilanCompetence->competences_formation, fn($item) => is_string($item));
                            @endphp
                            {{ implode(', ', $skills) ?: 'N/A' }}
                        @elseif(is_string($bilanCompetence->competences_formation))
                            {{ $bilanCompetence->competences_formation }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Besoins en formations</label>
                    <p class="text-gray-900">{{ $bilanCompetence->besoin_formations ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Skills & Competences -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-brain-line text-yellow-600 text-xl"></i>
                Compétences Formation
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Compétences de formation</label>
                    <p class="text-gray-900">
                        @if(is_array($bilanCompetence->competences_formation) && count($bilanCompetence->competences_formation) > 0)
                            @php
                                $skills = array_filter($bilanCompetence->competences_formation, fn($item) => is_string($item));
                            @endphp
                            {{ implode(', ', $skills) ?: 'N/A' }}
                        @elseif(is_string($bilanCompetence->competences_formation))
                            {{ $bilanCompetence->competences_formation }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Type de formations</label>
                    <p class="text-gray-900">{{ $bilanCompetence->type_formations ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Professional Environment -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-briefcase-line text-yellow-600 text-xl"></i>
                Environnement Professionnel
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Type d'environnement</label>
                    <p class="text-gray-900">
                        @if(is_array($bilanCompetence->environnement_professionnel))
                            {{ implode(', ', $bilanCompetence->environnement_professionnel) }}
                        @else
                            {{ $bilanCompetence->environnement_professionnel ?? 'N/A' }}
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Secteurs d'activité</label>
                    <p class="text-gray-900">
                        @if(is_array($bilanCompetence->secteurs_activite))
                            {{ implode(', ', $bilanCompetence->secteurs_activite) }}
                        @else
                            {{ $bilanCompetence->secteurs_activite ?? 'N/A' }}
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Fonctions envisagées</label>
                    <p class="text-gray-900">
                        @if(is_array($bilanCompetence->fonctions_envisagees))
                            {{ implode(', ', $bilanCompetence->fonctions_envisagees) }}
                        @else
                            {{ $bilanCompetence->fonctions_envisagees ?? 'N/A' }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Professional Experience -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-time-line text-yellow-600 text-xl"></i>
                Expérience Professionnelle
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Société</label>
                    <p class="text-gray-900">{{ $bilanCompetence->exp_societe ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Lieu</label>
                    <p class="text-gray-900">{{ $bilanCompetence->exp_lieu ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Secteur</label>
                    <p class="text-gray-900">{{ $bilanCompetence->exp_secteur ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Durée</label>
                    <p class="text-gray-900">{{ $bilanCompetence->exp_duree ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Responsabilités</label>
                    <p class="text-gray-900">{{ $bilanCompetence->exp_responsabilites ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Compétences acquises</label>
                    <p class="text-gray-900">{{ $bilanCompetence->exp_competences ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Career Goals -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-rocket-line text-yellow-600 text-xl"></i>
                Objectifs et Aspirations
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-600">Contraintes acceptées</label>
                    <p class="text-gray-900">
                        @if(is_array($bilanCompetence->contraintes_acceptees))
                            {{ implode(', ', $bilanCompetence->contraintes_acceptees) }}
                        @else
                            {{ $bilanCompetence->contraintes_acceptees ?? 'N/A' }}
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Exigences</label>
                    <p class="text-gray-900">
                        @if(is_array($bilanCompetence->exigences))
                            {{ implode(', ', $bilanCompetence->exigences) }}
                        @else
                            {{ $bilanCompetence->exigences ?? 'N/A' }}
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Réflexions personnelles</label>
                    <p class="text-gray-900">{{ $bilanCompetence->reflexions_personnelles ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Actions -->
    <div class="mt-6 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
        <div class="flex gap-3">
            @if($bilanCompetence->status !== 'approved')
            <button wire:click="updateStatus('approved')" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                <i class="ri-check-line mr-2"></i>Approuver
            </button>
            @endif
            @if($bilanCompetence->status !== 'rejected')
            <button wire:click="updateStatus('rejected')" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                <i class="ri-close-line mr-2"></i>Rejeter
            </button>
            @endif
            @if($bilanCompetence->status !== 'in_review')
            <button wire:click="updateStatus('in_review')" class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition">
                <i class="ri-time-line mr-2"></i>En révision
            </button>
            @endif
        </div>
    </div>
</div>
