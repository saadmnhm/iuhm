<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $submission->form_type_label }}</h1>
                <p class="mt-1 text-sm text-gray-500">Soumis le {{ $submission->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Retour au dashboard
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Candidate Info Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <h2 class="text-lg font-semibold text-white">Informations du Candidat</h2>
                </div>
                <div class="p-6">
                    @if($submission->candidat)
                    <div class="text-center mb-4">
                        @if($submission->candidat->profile_image)
                            <img class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-gray-100" src="{{ asset('uploads/'.$submission->candidat->profile_image) }}" alt="{{ $submission->candidat->nom }}">
                        @else
                            <div class="w-24 h-24 rounded-full mx-auto bg-indigo-100 flex items-center justify-center border-4 border-gray-100">
                                <span class="text-3xl font-bold text-indigo-600">{{ strtoupper(substr($submission->candidat->nom, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Nom complet</label>
                            <p class="text-sm font-medium text-gray-900">{{ $submission->candidat->nom }} {{ $submission->candidat->prenom }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Email</label>
                            <p class="text-sm text-gray-900">{{ $submission->candidat->email }}</p>
                        </div>
                        @if($submission->candidat->phone)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Téléphone</label>
                            <p class="text-sm text-gray-900">{{ $submission->candidat->phone }}</p>
                        </div>
                        @endif
                        @if($submission->candidat->address)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Adresse</label>
                            <p class="text-sm text-gray-900">{{ $submission->candidat->address }}</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <p class="text-sm text-gray-500">Informations du candidat non disponibles</p>
                    @endif
                </div>
            </div>

            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900">Statut de la Soumission</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center mb-4">
                        @if($submission->status === 'draft')
                            <span class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Brouillon</span>
                        @elseif($submission->status === 'submitted')
                            <span class="px-4 py-2 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">Soumis</span>
                        @elseif($submission->status === 'in_review')
                            <span class="px-4 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">En révision</span>
                        @elseif($submission->status === 'approved')
                            <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">Approuvé</span>
                        @elseif($submission->status === 'rejected')
                            <span class="px-4 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-800">Rejeté</span>
                        @endif
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Créé le:</span>
                            <span class="font-medium">{{ $submission->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Mis à jour:</span>
                            <span class="font-medium">{{ $submission->updated_at->format('d/m/Y') }}</span>
                        </div>
                        @if($submission->submitted_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Soumis le:</span>
                            <span class="font-medium">{{ $submission->submitted_at->format('d/m/Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Details Cards -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Détails du Formulaire</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- View Different Form Types Card -->
                        @if($submission->form_type === 'business_plan' || $submission instanceof \App\Models\BusinessPlan)
                            <a href="{{ route('admin.projects.show', $submission->id) }}" class="block group">
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 border-2 border-blue-200 hover:border-blue-400 transition-all hover:shadow-lg">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <svg class="w-5 h-5 text-blue-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Business Plan Complet</h3>
                                    <p class="text-sm text-gray-600">Voir tous les détails du business plan</p>
                                    @if(isset($submission->project_name))
                                    <p class="text-xs text-blue-600 font-medium mt-3">{{ $submission->project_name }}</p>
                                    @endif
                                </div>
                            </a>
                        @elseif($submission->form_type === 'etude_marche' || $submission instanceof \App\Models\EtudeMarche)
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-6 border-2 border-green-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Étude de Marché</h3>
                                @if(isset($submission->produit_service))
                                <p class="text-sm text-gray-600 mb-2"><strong>Produit/Service:</strong> {{ $submission->produit_service }}</p>
                                @endif
                                @if(isset($submission->description_offre))
                                <p class="text-xs text-gray-500">{{ Str::limit($submission->description_offre, 100) }}</p>
                                @endif
                            </div>
                        @elseif($submission->form_type === 'evaluation_idee' || $submission instanceof \App\Models\EvaluationIdee)
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-6 border-2 border-purple-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Évaluation d'Idée</h3>
                                @if(isset($submission->idee_projet))
                                <p class="text-sm text-gray-600 mb-2"><strong>Idée:</strong> {{ $submission->idee_projet }}</p>
                                @endif
                                @if(isset($submission->resume_idee))
                                <p class="text-xs text-gray-500">{{ Str::limit($submission->resume_idee, 100) }}</p>
                                @endif
                            </div>
                        @elseif($submission->form_type === 'bmc' || $submission instanceof \App\Models\Bmc)
                            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg p-6 border-2 border-yellow-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Business Model Canvas</h3>
                                @if(isset($submission->proposition_valeur))
                                <p class="text-xs text-gray-500">{{ Str::limit($submission->proposition_valeur, 100) }}</p>
                                @endif
                            </div>
                        @elseif($submission->form_type === 'bilan_competence' || $submission instanceof \App\Models\BilanCompetence)
                            <div class="bg-gradient-to-br from-pink-50 to-rose-50 rounded-lg p-6 border-2 border-pink-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-pink-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Bilan de Compétences</h3>
                                @if(isset($submission->niveau_etude))
                                <p class="text-sm text-gray-600"><strong>Niveau d'étude:</strong> {{ $submission->niveau_etude }}</p>
                                @endif
                            </div>
                        @endif

                        <!-- Quick Info Card -->
                        <div class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-lg p-6 border-2 border-gray-200">
                            <div class="w-12 h-12 bg-gray-500 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Informations Rapides</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">ID:</span>
                                    <span class="font-medium">#{{ $submission->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Type:</span>
                                    <span class="font-medium">{{ $submission->form_type_label }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Étape actuelle:</span>
                                    <span class="font-medium">{{ $submission->current_step ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Notes -->
                    @if($submission->review_notes)
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Notes de révision</h4>
                        <p class="text-sm text-gray-700">{{ $submission->review_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
