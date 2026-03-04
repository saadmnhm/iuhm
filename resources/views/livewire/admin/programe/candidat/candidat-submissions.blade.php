<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Flash success --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3">
        <i class="ri-checkbox-circle-line text-green-500 text-xl"></i>
        <span class="font-medium">{{ session('success') }}</span>
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
                        <h1 class="text-2xl font-bold text-gray-900">{{ $candidat->nom }} {{ $candidat->prenom }}</h1>
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
                   
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <a href="{{ route('admin.candidat.export-all', $candidat->id) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            <i class="ri-file-pdf-line"></i> Exporter tout (PDF)
                        </a>
                        {{-- Candidat-level reviewer badge --}}
                        @if(!$candidat->reviewer)
                        <button wire:click="openReviewModal()"
                                class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg border border-indigo-200 transition">
                            <i class="ri-user-star-line"></i> Assigner révision
                        </button>
                        @else 
                        @php
                            $crBadge = match($candidat->review_status) {
                                'in_review' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'approved'  => 'bg-green-100 text-green-800 border-green-200',
                                'rejected'  => 'bg-red-100 text-red-800 border-red-200',
                                default     => 'bg-gray-100 text-gray-700 border-gray-200',
                            };
                            $crLabel = match($candidat->review_status) {
                                'in_review' => 'En révision',
                                'approved'  => 'Approuvé',
                                'rejected'  => 'Rejeté',
                                default     => ucfirst($candidat->review_status ?? ''),
                            };
                        @endphp
                        
                        <span wire:click="openReviewModal()" class="inline-flex items-center gap-1 px-3 py-2 cursor-pointer text-xs font-semibold rounded-lg border {{ $crBadge }}">
                            <i class="ri-user-star-fill"></i>
                            {{ $candidat->reviewer->name }}
                            @if($crLabel) &nbsp;·&nbsp; {{ $crLabel }} @endif
                        </span>
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
            <div class="w-10 h-10 rounded-lg flex items-center justify-center ml-2 flex-shrink-0"
                 style="color:{{ $sf['color'] ?? '#6366f1' }};background-color:{{ $sf['color'] ?? '#6366f1' }}20;">
                <i class="{{ $sf['icon'] ?? 'ri-file-list-3-line' }} text-lg"></i>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- ═══════════════════════════ FORM CARDS ═══════════════════════════ -->
    @php $formForms = collect($statistics['form_attached'] ?? [])->where('is_active', 'active'); @endphp
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
                        @if($sub['review_notes'])
                        <div class="mt-2 p-2 bg-amber-50 border border-amber-200 rounded text-xs text-amber-800 italic">
                            <i class="ri-sticky-note-line mr-1"></i>{{ $sub['review_notes'] }}
                        </div>
                        @endif
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
    @if($showReviewModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click.self="$set('showReviewModal', false)">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

        {{-- Modal panel --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl z-10 overflow-hidden">
            {{-- Modal header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r f">
                <h2 class="text-lg font-bold text-green-800 flex items-center gap-2">
                    <i class="ri-user-star-line text-green-600 text-xl"></i>
                    Assigner une révision
                </h2>
                <button wire:click="$set('showReviewModal', false)"
                        class="text-gray-400 hover:text-gray-600 transition text-xl leading-none">
                    <i class="ri-close-line"></i>
                </button>
            </div>

            {{-- Modal body --}}
            <div class="px-6 py-5 space-y-5 max-h-[70vh] overflow-y-auto">
                {{-- Admin card selector --}}
                <div>
                    @php $selfAdmin = ['id' => auth()->id(), 'name' => auth()->user()->name, 'role' => auth()->user()->role ?? 'admin']; @endphp
                    <div class="grid grid-cols-3 gap-3">
                        {{-- "Moi-même" card --}}
                        <div wire:click="$set('reviewerId', {{ auth()->id() }})"
                             class="cursor-pointer rounded-xl border-2 p-3 text-center transition-all hover:shadow-md
                                    {{ $reviewerId == auth()->id() ? 'border-green-500  shadow-md' : 'border-gray-200 hover:border-indigo-300' }}">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 text-white flex items-center justify-center text-lg font-bold mx-auto mb-2 shadow">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <p class="text-xs font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full font-medium">Moi-même</span>
                            @if($reviewerId == auth()->id())
                            <div class="mt-1"><i class="ri-checkbox-circle-fill text-green-600 text-base"></i></div>
                            @endif
                        </div>

                        {{-- Other admins --}}
                        @foreach($admins as $admin)
                            @if($admin['id'] != auth()->id())
                            @php
                                $colors = ['from-blue-500 to-cyan-500','from-green-500 to-emerald-500','from-orange-500 to-amber-500','from-pink-500 to-rose-500','from-violet-500 to-purple-500','from-teal-500 to-green-500'];
                                $colorClass = $colors[$loop->index % count($colors)];
                            @endphp
                            <div wire:click="$set('reviewerId', {{ $admin['id'] }})"
                                 class="cursor-pointer rounded-xl border-2 p-3 text-center transition-all hover:shadow-md
                                        {{ $reviewerId == $admin['id'] ? 'border-green-500 bg-green-50 shadow-md' : 'border-gray-200 hover:border-indigo-300' }}">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br {{ $colorClass }} text-white flex items-center justify-center text-lg font-bold mx-auto mb-2 shadow">
                                    {{ strtoupper(substr($admin['name'], 0, 1)) }}
                                </div>
                                <p class="text-xs font-semibold text-gray-800 truncate">{{ $admin['name'] }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">
                                    {{ ucfirst($admin['role'] ?? 'Admin') }}
                                </span>
                                @if($reviewerId == $admin['id'])
                                <div class="mt-1"><i class="ri-checkbox-circle-fill text-green-600 text-base"></i></div>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    </div>
                    @error('reviewerId') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- Status selector --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="ri-checkbox-circle-line mr-1 text-indigo-500"></i> Nouveau statut
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['in_review' => ['En révision','bg-purple-100 text-purple-800 border-purple-300','ri-eye-line'], 'approved' => ['Approuvé','bg-green-100 text-green-800 border-green-300','ri-check-line'], 'rejected' => ['Rejeté','bg-red-100 text-red-800 border-red-300','ri-close-line']] as $val => $meta)
                        <button type="button" wire:click="$set('reviewStatus', '{{ $val }}')"
                                class="flex items-center gap-1.5 px-4 py-2 rounded-lg border-2 text-sm font-semibold transition-all
                                       {{ $reviewStatus === $val ? $meta[1].' border-current shadow' : 'border-gray-200 text-gray-600 hover:border-gray-300' }}">
                            <i class="{{ $meta[2] }}"></i> {{ $meta[0] }}
                        </button>
                        @endforeach
                    </div>
                    @error('reviewStatus') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        <i class="ri-sticky-note-line mr-1 text-indigo-500"></i> Notes (optionnel)
                    </label>
                    <textarea wire:model="reviewNotes" rows="3"
                              placeholder="Ajouter des notes de révision…"
                              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition resize-none"></textarea>
                </div>
            </div>

            {{-- Modal footer --}}
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 bg-gray-50">
                @if($reviewerId)
                <p class="text-xs text-indigo-600 flex items-center gap-1">
                    <i class="ri-user-check-line"></i>
                    Sélectionné : <strong class="ml-1">{{ collect($admins)->firstWhere('id', $reviewerId)['name'] ?? auth()->user()->name }}</strong>
                </p>
                @else
                <p class="text-xs text-gray-400">Sélectionnez un responsable ci-dessus</p>
                @endif
                <div class="flex items-center gap-3">
                    <button wire:click="$set('showReviewModal', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 bg-white border border-gray-300 rounded-lg transition hover:bg-gray-50">
                        Annuler
                    </button>
                    <button wire:click="submitReview" wire:loading.attr="disabled"
                            class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition disabled:opacity-60">
                        <span wire:loading.remove wire:target="submitReview">
                            <i class="ri-save-line mr-1"></i> Confirmer
                        </span>
                        <span wire:loading wire:target="submitReview">
                            <i class="ri-loader-4-line animate-spin mr-1"></i> Enregistrement…
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
