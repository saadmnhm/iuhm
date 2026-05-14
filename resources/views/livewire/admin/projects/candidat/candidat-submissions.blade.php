<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Flash success --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3">
        <i class="ri-checkbox-circle-line text-green-500 text-xl"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3">
        <i class="ri-error-warning-line text-red-500 text-xl"></i>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex lg:flex-row justify-between gap-4">
            <div>

                <div class="flex items-center gap-4">
                    <!-- Avatar + info -->
                        @if($candidat->profile_image)
                            <img class="w-20 h-20 rounded-full object-cover border-4 border-indigo-100"
                                src="{{ asset('uploads/'.$candidat->profile_image) }}"
                                alt="{{ $candidat->nom }}">
                        @else
                            <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-2xl font-bold border-4 border-indigo-200">
                                {{ strtoupper(substr($candidat->nom, 0, 1)) }}{{ strtoupper(substr($candidat->prenom, 0, 1)) }}
                            </div>
                        @endif
                        <a href="{{ route('admin.candidats.show', $candidat->id) }}" class="text-indigo-600 hover:text-indigo-800">
                            <h1 class="text-2xl hover:underline font-bold text-gray-900">{{ $candidat->nom }} {{ $candidat->prenom }}</h1>
                        </a>
                </div>
                <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-gray-600">
                    <span class="flex items-center gap-1"><i class="ri-mail-line text-gray-400"></i> {{ $candidat->email }}</span>
                    @if($candidat->phone)
                    <span class="flex items-center gap-1"><i class="ri-phone-line text-gray-400"></i> {{ $candidat->phone }}</span>
                    @endif
                    @if($candidat->address)
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        @if($candidat->address == 'Hay Mohamadi') bg-green-100 text-green-800
                        @elseif($candidat->address == 'Ain Sbaa') bg-purple-100 text-purple-800
                        @elseif($candidat->address == 'Roches noires') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        <i class="ri-map-pin-line"></i> {{ $candidat->address }}
                    </span>
                    @endif
                </div>
            </div>
                {{-- Quick contact buttons --}}
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <div x-data="{ open:false }" class="relative">
                        <button type="button" @click="open = !open"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            <i class="ri-menu-line"></i> Actions candidat
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak  class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 p-2 z-40">
                           
                            <a href="{{ route('admin.candidats.edit', $candidat->id) }}" class="w-full text-left px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700 block">
                                <i class="ri-sort-asc mr-1"></i>ordre des formulaires
                            </a>

                                @if($projectId)
                                <a href="{{ route('admin.candidat.evaluation.create', ['id' => $candidat->id, 'projectId' => $projectId]) }}"
                                   class="w-full text-left px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700 block">
                                    <i class="ri-survey-line mr-1"></i> Grille d'évaluation
                                </a>
                                @endif
                              
                              @if($is_evaluated)
                                <a href="{{ route('admin.project.print.evaluation', ['id' => $candidat->id, 'projectId' => $projectId]) }}" target="_blank"
                                    class="w-full text-left px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700 block">
                                    <i class="ri-printer-line mr-1"></i> Imprimer Grille
                                </a>
                              @endif
                                
                              @if($candidatSubmissions && $candidatSubmissions->formation_review_rating == null )
                              <button type="button" wire:click="toggleFormationReview" @click="open = false"
                                      class="w-full text-left px-3 py-2 rounded hover:bg-gray-50 text-sm {{ $candidatSubmissions->require_formation_review ? 'text-green-600 font-bold' : 'text-gray-700' }}">
                                  <i class="ri-feedback-line mr-1"></i> 
                                  {{ $candidatSubmissions->require_formation_review ? '✓ Avis de formation demandé' : 'Demander avis de formation' }}
                              </button>
                              @endif


                            @if($projectId)
                            <hr class="my-1 border-gray-200">
                            <a href="{{ route('admin.project.print.folder', ['id' => $candidat->id, 'projectId' => $projectId]) }}" target="_blank" class="block px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700">
                                <i class="ri-folder-zip-line mr-1"></i> Dossier Complet (Projet)
                            </a>
                            <a href="{{ route('admin.project.print.fiche', ['id' => $candidat->id, 'projectId' => $projectId]) }}" target="_blank" class="block px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700">
                                <i class="ri-file-user-line mr-1"></i> Fiche d'inscription
                            </a>
                            <a href="{{ route('admin.project.print.agreement', ['id' => $candidat->id, 'projectId' => $projectId]) }}" target="_blank" class="block px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700">
                                <i class="ri-file-paper-2-line mr-1"></i> Engagement
                            </a>
                            @if(!$candidatSubmissions->formation_review_rating == null)
                            <a href="{{ route('admin.project.print.avis_formation', ['id' => $candidat->id, 'projectId' => $projectId]) }}" target="_blank" class="block px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700">
                                  <i class="ri-file-paper-2-line mr-1"></i> Fiche d'avis de formation
                            </a>
                              @endif
                            @endif
                            
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        {{-- Candidat-level reviewer badge --}}
                        @if(!$candidatSubmissions->reviewer)
                        <button type="button" wire:click.prevent="openReviewModal"
                                class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg border border-indigo-200 transition">
                            <i class="ri-user-star-line"></i> Assigner révision
                        </button>
                        @else 
                        @php
                            $crBadge = match($candidatSubmissions->review_status) {
                                'in_review' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'approved'  => 'bg-green-100 text-green-800 border-green-200',
                                'rejected'  => 'bg-red-100 text-red-800 border-red-200',
                                default     => 'bg-gray-100 text-gray-700 border-gray-200',
                            };
                            $crLabel = match($candidatSubmissions->review_status) {
                                'in_review' => 'En révision',
                                'approved'  => 'Approuvé',
                                'rejected'  => 'Rejeté',
                                default     => ucfirst($candidatSubmissions->review_status ?? ''),
                            };
                        @endphp
                        
                        <button type="button" wire:click.prevent="openReviewModal" class="inline-flex items-center gap-1 px-3 py-2 cursor-pointer text-xs font-semibold rounded-lg border {{ $crBadge }}">
                            <i class="ri-user-star-fill"></i>
                            {{ $candidatSubmissions->reviewer->name }}
                            @if($crLabel) &nbsp;·&nbsp; {{ $crLabel }} @endif
                        </button>
                        @endif
                        <!-- <a href="{{ route('admin.candidats.index') }}"
                        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition">
                            <i class="ri-arrow-left-line"></i> Retour
                        </a> -->
                    </div>                       
                </div>
                
        </div>
    </div>

    <!-- ═══════════════════════════ QUICK STATUS STRIP ═══════════════════════════ -->
    @php
     $activeforms = collect($statistics['form_attached']);
     
     @endphp

    @if($activeforms->count())
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 mb-6">
        @foreach($activeforms as $sf)
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-3 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium truncate" style="max-width:100px" title="{{ $sf['title'] }}">{{ $sf['title'] }}</p>
                @if($sf['is_submitted'] == 1)
                <span class="mt-1 inline-block text-xs font-semibold px-2 py-0.5 rounded bg-green-100 text-green-800">
                    Soumis
                </span>
                @else
                <span class="mt-1 inline-block text-xs font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-500">
                    Non soumis
                </span>
                @endif
            </div>
            <div class="w-10 h-10 rounded-lg flex items-center justify-center ml-2 shrink-0"
                 style="color:{{ $sf['color'] ?? '#6366f1' }};background-color:{{ $sf['color'] ?? '#6366f1' }}20;">
                <i class="{{ $sf['icon'] ?? 'ri-file-list-3-line' }} text-lg"></i>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @php $formForms = collect($statistics['form_attached'] ?? [])->where('is_active', 'active'); @endphp


    <!-- ═══════════════════════════ FORM CARDS ═══════════════════════════ -->
    @if($formForms->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($formForms as $sub)
        @php
            $accentColor = $sub['color'] ?? '#6366f1';
            $badgeClass  = match($sub['actual_status'] ?? null) {
                'submitted'  => 'bg-blue-100 text-blue-800',
                'in_review'  => 'bg-purple-100 text-purple-800',
                'approved'   => 'bg-green-100 text-green-800',
                'rejected'   => 'bg-red-100 text-red-800',
                'draft'      => 'bg-yellow-100 text-yellow-800',
                default      => 'bg-gray-100 text-gray-500',
            };
            $isSubmitted = !is_null($sub['is_submitted']) && $sub['is_submitted'] == 1;
        @endphp
        <div class="bg-white rounded-xl shadow-sm border-2 overflow-hidden hover:shadow-md transition-all flex flex-col"
             style="border-color: {{ $accentColor }}30;">

            <!-- Card Header -->
            <div class="p-5" style="background: linear-gradient(135deg, {{ $accentColor }}15 0%, transparent 100%);">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-xl shadow"
                         style="background-color: {{ $accentColor }};">
                        <i class="{{ $sub['icon'] ?? 'ri-file-list-3-line' }}"></i>
                    </div>
                    {{-- Status badge --}}
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                        {{ $sub['status_label'] }}
                    </span>
                </div>
                <h3 class="text-base font-bold text-gray-900">{{ $sub['title'] }}</h3>
            </div>

            <!-- Card Body -->
            <div class="p-5 flex-1 flex flex-col">
                @if(!$isSubmitted)
                    {{-- Not yet submitted --}}
                    <div class="flex-1 flex flex-col items-center justify-center py-6 text-center">
                        <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="ri-file-add-line text-3xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-600 font-medium text-sm">Pas encore soumis</p>
                        <p class="text-xs text-gray-400 mt-1">Ce candidat n'a pas encore rempli ce formulaire</p>
                    </div>
                    <button disabled class="mt-4 w-full px-4 py-2 bg-gray-100 text-gray-400 text-sm font-semibold rounded-lg cursor-not-allowed">
                        <i class="ri-close-circle-line mr-1"></i> Non disponible
                    </button>
                @else
                    {{-- Has submission --}}
                    <div class="space-y-2 flex-1">
                        @if($sub['programe'])
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500 flex items-center gap-1"><i class="ri-folder-line"></i> Projet</span>
                            <span class="font-medium text-gray-800 truncate ml-2" style="max-width:130px">{{ $sub['programe']['project_name'] }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Créé le</span>
                            <span class="font-medium text-gray-800">{{ $sub['created_at'] ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Soumis le</span>
                            <span class="font-medium text-gray-800">{{ $sub['submitted_at'] ?? '—' }}</span>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="mt-4 space-y-2">
                        {{-- View details --}}
                        @if($sub['submission_id'])
                        <a href="{{ route('admin.formulaires.submission.detail', $sub['submission_id']) }}"
                           class="flex items-center justify-center gap-2 w-full px-4 py-2 text-white text-sm font-semibold rounded-lg transition hover:opacity-90"
                           style="background-color: {{ $accentColor }};">
                            <i class="ri-eye-line"></i> Voir les détails
                        </a>                          
                          <a href="{{ route('admin.formulaire.print.submission', $sub['submission_id']) }}" target="_blank"
                             class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                              <i class="ri-printer-line"></i> Imprimer form
                          </a>
                        <button type="button" wire:click="openWorkflowModal({{ $sub['submission_id'] }})"
                                class="w-full px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-lg border border-indigo-200 transition">
                            <i class="ri-route-line mr-1"></i> Gérer étapes / statut
                        </button>
                        @else
                        <button disabled class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-gray-200 text-gray-400 text-sm font-semibold rounded-lg cursor-not-allowed">
                            <i class="ri-eye-line"></i> Voir les détails
                        </button>
                        @endif

                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <i class="ri-file-list-3-line text-6xl text-gray-300 mb-4 block"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun formulaire associé</h3>
        <p class="text-gray-500">Ce candidat n'a soumis aucun formulaire pour ce programme.</p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Autorisation de passage au formulaire suivant</h2>

        @php
            $submittedForms = collect($statistics['form_attached'] ?? [])->filter(function ($item) {
                return !empty($item['submission_id'])
                    && (int) ($item['is_submitted'] ?? 0) === 1
                    && !($item['is_last_form'] ?? false)
                    && !($item['next_form_allowed'] ?? false);
            });
        @endphp

        @if($submittedForms->isEmpty())
            <p class="text-sm text-gray-500">Aucune soumission à autoriser pour le moment.</p>
        @else
            <div class="space-y-3">
                @foreach($submittedForms as $sub)
                    <div class="border border-gray-200 rounded-lg p-3 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                        <div>
                            <div class="text-sm font-semibold text-gray-800">{{ $sub['title'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                @if($sub['all_stages_validated'])
                                    Toutes les étapes sont validées.
                                @else
                                    Étapes incomplètes : impossible d'autoriser le passage.
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($sub['all_stages_validated'] ?? false)
                                <button type="button" wire:click="allowNextFormulaire({{ $sub['submission_id'] }})"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                                    <i class="ri-check-double-line mr-1"></i> Autoriser passage
                                </button>
                            @else
                                <button type="button" disabled
                                        class="px-4 py-2 bg-gray-100 text-gray-400 text-sm font-semibold rounded-lg cursor-not-allowed">
                                    <i class="ri-lock-line mr-1"></i> Étapes non validées
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Actions rapides</h2>
        <div class="flex lg:flex-row justify-between gap-5">
                {{-- Quick contact buttons --}}
                <div class="flex flex-wrap items-center gap-3 mt-3">
                    @if($candidat->phone)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $candidat->phone) }}" target="_blank"
                        class="inline-flex items-center gap-1 px-3 py-3 bg-green-500 hover:bg-green-600 text-white text-s font-medium rounded-lg transition">
                            <i class="ri-whatsapp-line text-sm"></i> WhatsApp
                        </a>
                        <a href="tel:{{ $candidat->phone }}"
                        class="inline-flex items-center gap-1 px-3 py-3 bg-blue-500 hover:bg-blue-600 text-white text-s font-medium rounded-lg transition">
                            <i class="ri-phone-line text-sm"></i> Appeler
                        </a>
                    @endif
                    <a href="mailto:{{ $candidat->email }}"
                    class="inline-flex items-center gap-1 px-3 py-3 bg-indigo-500 hover:bg-indigo-600 text-white text-s font-medium rounded-lg transition">
                        <i class="ri-mail-send-line text-sm"></i> Email
                    </a>
                </div>
                
        </div>
    </div>
    <!-- ═══════════════════════════ REVIEW MODAL ═══════════════════════════ -->
    <div x-data="{ open: @entangle('showReviewModal').live }" x-cloak>
        <div
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
        >
            <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

            <div
                x-show="open"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-4 z-10 overflow-hidden"
                @click.stop
            >
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <i class="ri-user-star-line text-indigo-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Assigner un reviewer</h3>
                    </div>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-5 max-h-[72vh] overflow-y-auto">
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-3">Choisir un administrateur</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <button type="button" wire:click="$set('reviewerId', {{ auth()->id() }})"
                                class="rounded-xl border-2 p-3 text-left transition-all hover:shadow-md {{ $reviewerId == auth()->id() ? 'border-indigo-500 bg-indigo-50 shadow-sm' : 'border-gray-200 hover:border-indigo-300' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs rounded-full font-medium">Moi-même</span>
                                    </div>
                                </div>
                            </button>

                            @foreach($admins as $admin)
                                @if($admin['id'] != auth()->id())
                                    <button type="button" wire:click="$set('reviewerId', {{ $admin['id'] }})"
                                        class="rounded-xl border-2 p-3 text-left transition-all hover:shadow-md {{ $reviewerId == $admin['id'] ? 'border-indigo-500 bg-indigo-50 shadow-sm' : 'border-gray-200 hover:border-indigo-300' }}">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gray-800 text-white flex items-center justify-center font-bold">
                                                {{ strtoupper(substr($admin['name'], 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">{{ $admin['name'] }}</p>
                                                <span class="inline-block mt-1 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">{{ ucfirst($admin['role'] ?? 'Admin') }}</span>
                                            </div>
                                        </div>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                        @error('reviewerId') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nouveau statut</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['in_review' => ['En révision','bg-purple-100 text-purple-800 border-purple-300','ri-eye-line'], 'approved' => ['Approuvé','bg-green-100 text-green-800 border-green-300','ri-check-line'], 'rejected' => ['Rejeté','bg-red-100 text-red-800 border-red-300','ri-close-line']] as $val => $meta)
                                <button type="button" wire:click="$set('reviewStatus', '{{ $val }}')"
                                    class="flex items-center gap-1.5 px-4 py-2 rounded-lg border-2 text-sm font-semibold transition-all {{ $reviewStatus === $val ? $meta[1].' border-current shadow' : 'border-gray-200 text-gray-600 hover:border-gray-300' }}">
                                    <i class="{{ $meta[2] }}"></i> {{ $meta[0] }}
                                </button>
                            @endforeach
                        </div>
                        @error('reviewStatus') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Notes (optionnel)</label>
                        <textarea wire:model="reviewNotes" rows="3"
                            placeholder="Ajouter des notes de révision..."
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition resize-none"></textarea>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
                    @if($reviewerId)
                        <p class="text-xs text-gray-600">
                            Reviewer sélectionné:
                            <span class="font-semibold text-gray-900">{{ collect($admins)->firstWhere('id', $reviewerId)['name'] ?? auth()->user()->name }}</span>
                        </p>
                    @else
                        <p class="text-xs text-gray-400">Sélectionnez un administrateur pour continuer.</p>
                    @endif

                    <div class="flex items-center gap-2 justify-end">
                        <button type="button" @click="open = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Annuler
                        </button>
                        <button wire:click="submitReview" wire:loading.attr="disabled"
                            class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors disabled:opacity-60">
                            <i wire:loading wire:target="submitReview" class="ri-loader-4-line animate-spin"></i>
                            <span>Enregistrer</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Workflow modal --}}
    @if($showWorkflowModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click.self="$set('showWorkflowModal', false)">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl z-10 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800"><i class="ri-route-line mr-1"></i> Workflow de validation du formulaire</h2>
                <button wire:click="$set('showWorkflowModal', false)" class="text-gray-400 hover:text-gray-600"><i class="ri-close-line text-xl"></i></button>
            </div>

            <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Statut</label>
                        <select wire:model="workflowStatus" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="draft">Brouillon</option>
                            <option value="submitted">Soumis</option>
                            <option value="in_review">En révision</option>
                            <option value="approved">Approuvé</option>
                            <option value="rejected">Rejeté</option>
                        </select>
                        @error('workflowStatus') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Étapes</label>
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="stageFormationValidated" class="rounded border-gray-300"> Formation validée</label>
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="stageCandidateInFormation" class="rounded border-gray-300"> Candidat en formation</label>
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="stageAdministrativeValidated" class="rounded border-gray-300"> Validation administrative finale</label>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Commentaire (obligatoire)</label>
                        <textarea wire:model="workflowComment" rows="4" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Justification du changement de statut / étape..."></textarea>
                        @error('workflowComment') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="saveWorkflowProgress" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm">
                            <i class="ri-save-line"></i> Enregistrer étapes/statut
                        </button>
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <h3 class="text-sm font-semibold text-green-900 mb-3">Passage au formulaire suivant</h3>
                    @if($workflowIsLastForm)
                        <p class="text-xs text-green-900/80">Ce formulaire est le dernier de la séquence. Aucun passage suivant à autoriser.</p>
                    @elseif($workflowAlreadyAllowed)
                        <p class="text-xs text-green-900/80">Le passage suivant est déjà autorisé pour ce formulaire.</p>
                    @elseif($stageFormationValidated && $stageCandidateInFormation && $stageAdministrativeValidated && $workflowSubmissionId)
                        <button type="button" wire:click="allowNextFormulaire({{ $workflowSubmissionId }})" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm">
                            <i class="ri-check-double-line"></i> Autoriser passage au suivant
                        </button>
                    @else
                        <p class="text-xs text-green-900/80">Validez d'abord toutes les étapes pour activer ce bouton.</p>
                    @endif
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Historique des changements</h3>
                    <div class="space-y-2 max-h-[55vh] overflow-y-auto pr-1">
                        @forelse($workflowHistory as $h)
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="text-xs font-semibold text-gray-700">{{ \App\Models\SubmissionHistory::ACTION_LABELS[$h['action']] ?? ucfirst(str_replace('_',' ', $h['action'])) }}</div>
                                    <div class="text-xs text-gray-400">{{ $h['at'] }}</div>
                                </div>
                                @if($h['old'] || $h['new'])
                                    <div class="text-xs text-gray-500 mt-1">{{ $h['old'] ?: '—' }} → {{ $h['new'] ?: '—' }}</div>
                                @endif
                                @if($h['notes'])
                                    <div class="text-xs text-gray-700 mt-1">{{ $h['notes'] }}</div>
                                @endif
                                <div class="text-[11px] text-gray-400 mt-1">Par {{ $h['by'] }}</div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">Aucun historique pour cette soumission.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
