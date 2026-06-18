<div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mx-8 mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
            <i class="ri-checkbox-circle-line text-green-600"></i>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mx-8 mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
            <i class="ri-error-warning-line text-red-600"></i>
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mx-8 mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
            <p class="font-semibold text-sm flex items-center gap-2">
                <i class="ri-error-warning-line"></i> Veuillez corriger les erreurs suivantes :
            </p>
            <ul class="list-disc list-inside mt-1 text-sm space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="px-8 pt-6 pb-5">
        <span class="text-xs font-bold text-emerald-600 tracking-widest uppercase mb-2 block">CONTENT STUDIO</span>
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h1 class="iuhm_title_1">{{ $programeId ? 'Modifier le Projet' : 'Créer un nouveau Projet' }}</h1>
                <p class="text-gray-500 mt-1 text-sm max-w-lg">
                    {{ $programeId
                        ? 'Mettez à jour les informations, critères et formulaires associés à ce projet.'
                        : 'Rédiger, sélectionner et publier des récits à fort impact pour le réseau communautaire Urban Unity.' }}
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('admin.programe') }}"
                   class="px-5 py-2.5 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition text-sm">
                    Aperçu
                </a>
                <button type="button"
                    wire:click="saveProjectList"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-60 cursor-not-allowed"
                    class="iuhm_btn_1">
                    <span wire:loading.remove wire:target="saveProjectList">
                        <i class="ri-save-line mr-1.5"></i>
                        {{ $programeId ? 'Mettre à jour' : 'Enregistrer' }}
                    </span>
                    <span wire:loading wire:target="saveProjectList">
                        <i class="ri-loader-4-line animate-spin mr-1.5"></i>
                        Enregistrement...
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         Main Form Area
    ════════════════════════════════════════ --}}
    <div id="create-project-form" class="px-8 py-6 space-y-6">

        {{-- Row 1: Informations + Critères --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ── Informations Générales ── --}}
            <div class="bg-[#F5F3F7] rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-base shrink-0"
                         style="background-color: #1a2e4a;">
                        <i class="ri-file-text-line"></i>
                    </div>
                    <h2 class="iuhm_title_2">Informations Générales</h2>
                </div>

                <div class="mb-4">
                    <label class="iuhm_label_2">Nom du Projet <span class="text-red-400">*</span></label>
                    <input type="text"
                           wire:model="project_name"
                           placeholder="Ex: École de l'Innovation Urbaine"
                           class="w-full iuhm_input px-4 py-2.5 border @error('project_name') border-red-400 bg-red-50 @else border-gray-200 bg-gray-50 @enderror rounded-xl text-sm focus:outline-none transition hover:bg-white">
                    @error('project_name')
                        <span class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <i class="ri-error-warning-line"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="iuhm_label_2">Description <span class="text-red-400">*</span></label>
                    <textarea wire:model="description" rows="4"
                        placeholder="Détaillez les objectifs et la vision de cette initiative..."
                        class="w-full iuhm_textarea px-4 py-2.5 border @error('description') border-red-400 bg-red-50 @else border-gray-200 bg-gray-50 @enderror rounded-xl text-sm focus:outline-none transition resize-none hover:bg-white"></textarea>
                    @error('description')
                        <span class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <i class="ri-error-warning-line"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="iuhm_label_2">Statut</label>
                    <select wire:model="is_active"
                        class="w-full px-4 py-2.5 iuhm_input border border-gray-200 rounded-xl text-sm focus:outline-none transition bg-gray-50">
                        <option value="1">Actif</option>
                        <option value="0">Inactif</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="iuhm_label_2">Couleurs du Projet</label>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model.live="color"
                                class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer p-0.5 bg-white">
                            <span class="text-xs text-gray-500">Principale</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model.live="bg_color"
                                class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer p-0.5 bg-white">
                            <span class="text-xs text-gray-500">Fond</span>
                        </div>
                        <div class="flex items-center gap-2 ml-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-white text-sm"
                                  style="background-color: {{ $color }};">
                                <i class="{{ $icon }}"></i>
                            </span>
                            <span class="text-xs text-gray-400">Aperçu</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="iuhm_label_2">Logos du Projet</label>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Logo 1 @if(!$programeId)<span class="text-red-400">*</span>@endif</p>
                            <label class="flex flex-col items-center justify-center w-full h-20 border-2 @error('logo1') border-red-400 @else border-dashed border-gray-200 @enderror rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition overflow-hidden">
                                @if ($logo1 && method_exists($logo1, 'temporaryUrl'))
                                    <img src="{{ $logo1->temporaryUrl() }}" class="h-full w-full object-contain p-1">
                                @elseif ($existingLogo1)
                                    <img src="{{ asset('uploads/' . $existingLogo1) }}" class="h-full w-full object-contain p-1">
                                @else
                                    <i class="ri-image-add-line text-gray-300 text-2xl"></i>
                                @endif
                                <input type="file" wire:model="logo1" accept="image/*" class="hidden">
                            </label>
                            @error('logo1')<span class="text-red-500 text-xs mt-0.5 block">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Logo 2</p>
                            <label class="flex flex-col items-center justify-center w-full h-20 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition overflow-hidden">
                                @if ($logo2 && method_exists($logo2, 'temporaryUrl'))
                                    <img src="{{ $logo2->temporaryUrl() }}" class="h-full w-full object-contain p-1">
                                @elseif ($existingLogo2)
                                    <img src="{{ asset('uploads/' . $existingLogo2) }}" class="h-full w-full object-contain p-1">
                                @else
                                    <i class="ri-image-add-line text-gray-300 text-2xl"></i>
                                @endif
                                <input type="file" wire:model="logo2" accept="image/*" class="hidden">
                            </label>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Logo 3</p>
                            <label class="flex flex-col items-center justify-center w-full h-20 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition overflow-hidden">
                                @if ($logo3 && method_exists($logo3, 'temporaryUrl'))
                                    <img src="{{ $logo3->temporaryUrl() }}" class="h-full w-full object-contain p-1">
                                @elseif ($existingLogo3)
                                    <img src="{{ asset('uploads/' . $existingLogo3) }}" class="h-full w-full object-contain p-1">
                                @else
                                    <i class="ri-image-add-line text-gray-300 text-2xl"></i>
                                @endif
                                <input type="file" wire:model="logo3" accept="image/*" class="hidden">
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Icon Picker --}}
                <div x-data="{
                    open: false,
                    icons: [
                        'ri-file-list-3-line','ri-file-text-line','ri-survey-line','ri-clipboard-line',
                        'ri-book-open-line','ri-draft-line','ri-article-line','ri-newspaper-line',
                        'ri-task-line','ri-todo-line','ri-list-check-2','ri-list-ordered',
                        'ri-pie-chart-line','ri-bar-chart-line','ri-line-chart-line','ri-funds-line',
                        'ri-briefcase-line','ri-building-line','ri-store-line','ri-shopping-bag-line',
                        'ri-user-line','ri-team-line','ri-group-line','ri-contacts-line',
                        'ri-lightbulb-line','ri-brain-line','ri-graduation-cap-line','ri-medal-line',
                        'ri-heart-line','ri-shield-check-line','ri-settings-line','ri-tools-line',
                        'ri-money-dollar-circle-line','ri-bank-line','ri-wallet-line','ri-coin-line',
                        'ri-calendar-line','ri-time-line','ri-map-pin-line','ri-earth-line',
                        'ri-star-line','ri-trophy-line','ri-flag-line','ri-bookmark-line',
                        'ri-rocket-line','ri-map-line','ri-global-line','ri-compass-line',
                        'ri-leaf-line','ri-plant-line','ri-recycle-line','ri-sun-line',
                        'ri-home-line','ri-hospital-line','ri-government-line','ri-community-line'
                    ]
                }">
                    <label class="iuhm_label_2">Icône <span class="text-red-400">*</span></label>
                    <button type="button" @click="open = true"
                        class="flex items-center iuhm_input gap-3 px-4 py-2.5 border @error('icon') border-red-400 @else border-gray-200 @enderror rounded-xl hover:border-blue-400 hover:bg-blue-50 transition w-full text-left bg-gray-50">
                        <span class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-sm shrink-0"
                              style="background-color: {{ $color }};"><i class="{{ $icon }}"></i></span>
                        <span class="text-sm text-gray-700 flex-1 truncate">{{ $icon }}</span>
                        <i class="ri-arrow-down-s-line text-gray-400"></i>
                    </button>
                    @error('icon')
                        <span class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <i class="ri-error-warning-line"></i> {{ $message }}
                        </span>
                    @enderror

                    {{-- Icon picker modal — teleported outside Livewire root to avoid morphing conflicts --}}
                    <template x-teleport="body">
                        <div x-show="open" x-cloak @keydown.escape.window="open = false"
                             class="fixed inset-0 z-[60] flex items-center justify-center"
                             style="background:rgba(0,0,0,0.45);">
                            <div @click.outside="open = false"
                                 class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
                                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                                        <i class="ri-remixicon-line text-blue-500"></i> Choisir une Icône
                                    </h3>
                                    <button type="button" @click="open = false"
                                        class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600">
                                        <i class="ri-close-line text-lg"></i>
                                    </button>
                                </div>
                                <div class="p-5">
                                    <div class="grid grid-cols-7 gap-2">
                                        <template x-for="ic in icons" :key="ic">
                                            <button type="button"
                                                @click="open = false; $wire.selectIcon(ic)"
                                                :class="'{{ $icon }}' === ic ? 'ring-2 ring-blue-500 text-white' : 'bg-gray-50 hover:bg-gray-100 text-gray-600'"
                                                :style="'{{ $icon }}' === ic ? 'background-color:{{ $color }};' : ''"
                                                class="w-10 h-10 rounded-lg flex items-center justify-center text-lg transition">
                                                <i :class="ic" style="pointer-events:none;"></i>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Candidature Types --}}
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <label class="iuhm_label_2">Types de Candidature</label>
                    <p class="text-xs text-gray-400 mb-3">Définissez les types de dossiers acceptés pour ce projet.</p>
                    @if(count($candidature_types) > 0)
                        <div class="flex flex-wrap gap-2 mb-3">
                            @foreach($candidature_types as $type)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 border border-blue-200 text-blue-700 rounded-full text-xs font-medium">
                                    {{ $type }}
                                    <button type="button" wire:click="removeCandidatureType('{{ $type }}')"
                                        class="w-4 h-4 flex items-center justify-center rounded-full hover:bg-blue-200 text-blue-500 hover:text-blue-700 transition">
                                        <i class="ri-close-line" style="font-size:10px;"></i>
                                    </button>
                                </span>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex gap-2">
                        <input type="text" wire:model="newCandidatureType"
                            wire:keydown.enter.prevent="addCandidatureType"
                            placeholder="Ex: Individuel, Association, Entreprise..."
                            class="flex-1 iuhm_input px-3 py-2 border border-gray-200 bg-gray-50 rounded-xl text-sm focus:outline-none hover:bg-white transition">
                        <button type="button" wire:click="addCandidatureType"
                            class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition shrink-0">
                            <i class="ri-add-line"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── Critères d'Éligibilité ── --}}
            {{-- Location modal is teleported to body to avoid Livewire morphing conflicts --}}
            <div
                x-data="{ locationOpen: false }"
                class="bg-[#F5F3F7] rounded-2xl p-6 shadow-sm border border-gray-100"
            >
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-base bg-emerald-500 shrink-0">
                        <i class="ri-filter-3-line"></i>
                    </div>
                    <h2 class="iuhm_title_2">Critères d'Éligibilité</h2>
                </div>

                {{-- Localisation --}}
                <div class="mb-6">
                    @php $locCount = count($selectedLocations ?? []); @endphp
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="iuhm_label_2 mb-0">Localisation (Quartiers)</label>
                        @if($locCount > 0)
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">
                                {{ $locCount }} sélectionné{{ $locCount > 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>
                    <button type="button" @click="locationOpen = true"
                        class="w-full rounded-xl flex iuhm_input flex-wrap items-center gap-1.5 cursor-pointer text-left py-2 px-3 min-h-[50px] border border-gray-200 bg-gray-50 hover:bg-white transition">
                        @if($locCount > 0)
                            @foreach($selectedLocations->take(3) as $loc)
                                <span class="inline-flex items-center gap-1 pl-2 pr-1 py-1 bg-white border border-blue-200 text-blue-700 rounded-lg text-xs font-medium shrink-0">
                                    <i class="ri-map-pin-2-line text-blue-400 text-xs"></i>
                                    {{ $loc->prefecture }}
                                    <span wire:click.stop="removeSelectedLocation({{ $loc->id }})"
                                        class="ml-0.5 w-4 h-4 flex items-center justify-center rounded-full hover:bg-red-100 text-gray-400 hover:text-red-500 transition cursor-pointer">
                                        <i class="ri-close-line" style="font-size:10px;"></i>
                                    </span>
                                </span>
                            @endforeach
                            @if($locCount > 3)
                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold shrink-0 border border-gray-200">
                                    +{{ $locCount - 3 }} autre{{ ($locCount - 3) > 1 ? 's' : '' }}
                                </span>
                            @endif
                            <i class="ri-arrow-down-s-line text-gray-400 ml-auto shrink-0"></i>
                        @else
                            <span class="text-sm text-gray-400 flex-1">Sélectionner des quartiers...</span>
                            <i class="ri-arrow-down-s-line text-gray-400"></i>
                        @endif
                    </button>
                    @error('allowed_location_ids')
                        <span class="text-red-500 text-xs block mt-1 flex items-center gap-1">
                            <i class="ri-error-warning-line"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Tranche d'Âge --}}
                <div>
                    <label class="iuhm_label_2">Tranche d'Âge <span class="text-red-400">*</span></label>
                    <div class="grid grid-cols-[1fr_32px_1fr] items-start gap-2">
                        <div>
                            <p class="iuhm_label_2 text-center">Âge Minimum</p>
                            <input type="number" wire:model="min_age" min="0" max="120" placeholder="18"
                                class="w-full iuhm_input rounded-xl text-center border @error('min_age') border-red-400 bg-red-50 @else border-gray-200 bg-gray-50 @enderror px-2 py-2.5 text-sm focus:outline-none">
                            @error('min_age')<span class="text-red-500 text-xs block text-center mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div class="flex items-center justify-center pt-8 text-gray-300 text-2xl font-light select-none">—</div>
                        <div>
                            <p class="iuhm_label_2 text-center">Âge Maximum</p>
                            <input type="number" wire:model="max_age" min="0" max="120" placeholder="35"
                                class="w-full iuhm_input rounded-xl text-center border @error('max_age') border-red-400 bg-red-50 @else border-gray-200 bg-gray-50 @enderror px-2 py-2.5 text-sm focus:outline-none">
                            @error('max_age')<span class="text-red-500 text-xs block text-center mt-1">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                {{-- Secteur --}}
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Secteur d'activité</label>
                    <p class="text-xs text-gray-400 mb-3">Le projet cible un secteur en particulier ? Laissez vide pour tous.</p>
                    <div class="flex flex-wrap gap-3">
                        <label class="flex items-center gap-2.5 px-4 py-2.5 border border-gray-200 rounded-xl cursor-pointer bg-gray-50 hover:bg-indigo-50 hover:border-indigo-300 transition has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-400">
                            <input type="checkbox" wire:model="crit_sector" value="industriel" class="w-4 h-4">
                            <span class="w-6 h-6 rounded-lg bg-indigo-100 flex items-center justify-center shrink-0">
                                <i class="ri-building-4-line text-indigo-600 text-xs"></i>
                            </span>
                            <div>
                                <p class="text-sm font-medium text-gray-800 leading-tight">Industriel</p>
                                <p class="text-xs text-gray-400">Fabrication, production, BTP</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-2.5 px-4 py-2.5 border border-gray-200 rounded-xl cursor-pointer bg-gray-50 hover:bg-amber-50 hover:border-amber-300 transition has-[:checked]:bg-amber-50 has-[:checked]:border-amber-400">
                            <input type="checkbox" wire:model="crit_sector" value="commercial" class="w-4 h-4">
                            <span class="w-6 h-6 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                                <i class="ri-store-2-line text-amber-600 text-xs"></i>
                            </span>
                            <div>
                                <p class="text-sm font-medium text-gray-800 leading-tight">Commercial</p>
                                <p class="text-xs text-gray-400">Vente, distribution, services</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Ordre --}}
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <label class="iuhm_label_2">Ordre d'affichage</label>
                    <input type="number" wire:model="sort_order" min="0"
                        class="w-full iuhm_input px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-xl text-sm focus:outline-none hover:bg-white transition">
                </div>

                {{-- ════ LOCATION MODAL — teleported to body ════ --}}
                <template x-teleport="body">
                    <div x-show="locationOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @keydown.escape.window="locationOpen = false"
                         class="fixed inset-0 z-[60] p-4"
                         style="background: rgba(0,0,0,0.53);">
                        <div class="flex min-h-full items-start justify-center py-6 md:py-10">
                            <div @click.outside="locationOpen = false"
                                 class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl max-h-[90vh] overflow-hidden flex flex-col">

                                <div class="px-6 py-4 flex items-center justify-between shrink-0 border-b border-gray-100">
                                    <div>
                                        <h3 class="iuhm_title_2">Sélectionner les Localisations</h3>
                                        <p class="text-sm text-gray-500 mt-0.5">
                                            ({{ $locations->count() }}) résultat(s) · {{ count($allowed_location_ids) }} sélectionné(s)
                                        </p>
                                    </div>
                                    <button type="button" @click="locationOpen = false"
                                        class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition">
                                        <i class="ri-close-line text-lg"></i>
                                    </button>
                                </div>

                                <div class="px-6 py-4 shrink-0">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <select wire:model.live="locationRegionFilter"
                                            class="w-full px-3 py-2 iuhm_select rounded-xl border border-gray-200 text-sm focus:outline-none">
                                            <option value="">Toutes les régions</option>
                                            @foreach($regions as $region)
                                                <option value="{{ $region }}">{{ $region }}</option>
                                            @endforeach
                                        </select>
                                        <select wire:model.live="locationCityFilter"
                                            class="w-full px-3 py-2 iuhm_select rounded-xl border border-gray-200 text-sm focus:outline-none">
                                            <option value="">Toutes les villes</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city }}">{{ $city }}</option>
                                            @endforeach
                                        </select>
                                        <div class="relative">
                                            <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                                            <input type="text"
                                                wire:model.live.debounce.300ms="locationSearch"
                                                placeholder="Rechercher région, ville, préfecture..."
                                                class="w-full pl-9 pr-4 py-2 iuhm_search rounded-3xl border border-gray-200 text-sm focus:outline-none">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-1 overflow-y-auto p-6">
                                    <div class="border border-gray-200 rounded-2xl overflow-hidden">
                                        <div class="overflow-y-auto max-h-[55vh] px-4 py-3">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                                @forelse($locations as $location)
                                                    <label class="flex items-start gap-2 p-3 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition
                                                        {{ in_array((string) $location->id, $allowed_location_ids) ? 'bg-blue-50 border-blue-200' : '' }}">
                                                        <input type="checkbox"
                                                            wire:model.live="allowed_location_ids"
                                                            value="{{ $location->id }}"
                                                            class="mt-0.5 w-4 h-4 rounded text-blue-600 focus:ring-1 focus:ring-blue-500 shrink-0">
                                                        <div class="min-w-0 text-sm text-gray-700">
                                                            <div class="font-medium text-gray-800 text-xs">{{ $location->prefecture }}</div>
                                                            <div class="text-gray-500 text-xs mt-0.5">{{ $location->city }} · {{ $location->region }}</div>
                                                        </div>
                                                    </label>
                                                @empty
                                                    <div class="col-span-4 h-full flex items-center justify-center p-6 text-gray-500 text-center text-sm">
                                                        Aucune localisation trouvée.
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-6 py-4 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shrink-0 border-t border-gray-100">
                                    <span class="text-sm text-[#1B264F] font-medium">
                                        {{ count($allowed_location_ids ?? []) }} localisation(s) sélectionnée(s)
                                    </span>
                                    <div class="flex gap-3">
                                        <button type="button" @click="locationOpen = false" class="iuhm_btn_close_1">
                                            Annuler
                                        </button>
                                        <button type="button" @click="locationOpen = false" class="iuhm_btn_1">
                                            Confirmer la sélection
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

            </div>{{-- end Critères card --}}
        </div>

        {{-- Row 2: Formulaires --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ── Formulaires Actifs ── --}}
            <div class="bg-[#F5F3F7] rounded-2xl shadow-sm border border-gray-100 flex flex-col"
                 x-data="{ filter: '' }">
                <div class="px-6 pt-5 pb-4 border-b border-gray-50">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#93D870] flex items-center justify-center shrink-0">
                                <i class="ri-file-list-line text-gray-600 text-lg"></i>
                            </div>
                            <h2 class="iuhm_title_2">Formulaires Actifs</h2>
                        </div>
                        <span class="px-2.5 py-1 bg-[#9AF893] text-[#137421] text-xs font-semibold rounded-full">
                            {{ count($availableFormulaires ?? []) }} Disponibles
                        </span>
                    </div>
                    <div class="relative">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        <input type="text" x-model="filter" placeholder="Filtrer les formulaires..."
                            class="w-full iuhm_search rounded-xl">
                    </div>
                </div>
                <div class="overflow-y-auto px-3 pb-4 pt-2 space-y-0.5 max-h-72">
                    @forelse($availableFormulaires ?? [] as $form)
                        <div x-show="filter === '' || '{{ strtolower($form['title']) }}'.includes(filter.toLowerCase())"
                            class="flex items-center bg-white gap-3 px-5 py-2.5 rounded-[50px] hover:bg-gray-50 transition group">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                <i class="ri-file-text-line text-indigo-500 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $form['title'] }}</p>
                                @if(!empty($form['title_ar']))
                                    <p class="text-xs text-gray-400 truncate">{{ $form['title_ar'] }}</p>
                                @endif
                            </div>
                            @if($programeId)
                                <button type="button" wire:click="selectFormulaire({{ $form['id'] }})"
                                    class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 transition hover:bg-blue-100 hover:text-blue-600"
                                    title="Attacher ce formulaire">
                                    <i class="ri-add-line text-sm"></i>
                                </button>
                            @else
                                <button type="button" disabled title="Disponible après enregistrement du projet"
                                    class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-300 cursor-not-allowed">
                                    <i class="ri-add-line text-sm"></i>
                                </button>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-400 text-sm">
                            <i class="ri-inbox-line text-3xl block mb-2 text-gray-200"></i>
                            Aucun formulaire disponible
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ── Formulaires Sélectionnés ── --}}
            @if($programeId)
                <div x-data="{ formulaireOpen: false }"
                     class="bg-[#F5F3F7] rounded-2xl shadow-sm border border-gray-100 flex flex-col">

                    <div class="px-6 pt-5 pb-4 border-b border-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center shrink-0">
                                    <i class="ri-list-ordered text-gray-600 text-sm"></i>
                                </div>
                                <h2 class="iuhm_title_2">Formulaires Sélectionnés</h2>
                            </div>
                            <button type="button"
                                @click="formulaireOpen = true; $wire.openFormulaireModal()"
                                class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs font-medium">
                                <i class="ri-add-line text-sm"></i>
                                Attacher
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto px-4 py-3 space-y-2 max-h-72">
                        @foreach($attachedFormulaires as $formulaire)
                            <div class="flex items-start gap-3 p-3 border border-gray-100 rounded-xl bg-gray-50 hover:bg-white hover:shadow-sm transition group">
                                <div class="text-gray-300 mt-0.5 cursor-grab shrink-0">
                                    <i class="ri-draggable text-base"></i>
                                </div>
                                <div class="w-6 h-6 rounded-md bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs font-bold shrink-0 mt-0.5">
                                    {{ $formulaire['order'] }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $formulaire['title'] }}</p>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        <span class="text-xs px-1.5 py-0.5 rounded-md font-medium
                                            {{ $formulaire['status'] === 'active'   ? 'bg-green-100 text-green-700'  :
                                               ($formulaire['status'] === 'draft'   ? 'bg-yellow-100 text-yellow-700' :
                                                'bg-gray-100 text-gray-500') }}">
                                            {{ ucfirst($formulaire['status']) }}
                                        </span>
                                        @if($formulaire['is_required'])
                                            <span class="text-xs px-1.5 py-0.5 bg-red-50 text-red-600 rounded-md font-medium">Obligatoire</span>
                                        @else
                                            <span class="text-xs px-1.5 py-0.5 bg-gray-100 text-gray-400 rounded-md">Optionnel</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                                        <div class="flex items-center gap-1">
                                            <label class="text-xs text-gray-400">Ordre</label>
                                            <input type="number" min="1" value="{{ $formulaire['order'] }}"
                                                x-on:change="$wire.updateFormulaireOrder({{ $formulaire['id'] }}, $event.target.value)"
                                                class="w-14 px-2 py-1 border border-gray-200 rounded-lg text-xs text-center focus:ring-1 focus:ring-blue-500 focus:outline-none">
                                        </div>
                                        <select x-on:change="$wire.updateFormulaireStatus({{ $formulaire['id'] }}, $event.target.value)"
                                            class="text-xs px-2 py-1 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none bg-white">
                                            <option value="active"   {{ $formulaire['status'] === 'active'   ? 'selected' : '' }}>Actif</option>
                                            <option value="inactive" {{ $formulaire['status'] === 'inactive' ? 'selected' : '' }}>Inactif</option>
                                            <option value="draft"    {{ $formulaire['status'] === 'draft'    ? 'selected' : '' }}>Brouillon</option>
                                        </select>
                                        <label class="flex items-center gap-1 text-xs text-gray-500 cursor-pointer">
                                            <input type="checkbox"
                                                {{ $formulaire['is_required'] ? 'checked' : '' }}
                                                wire:click="toggleFormulaireRequired({{ $formulaire['id'] }})"
                                                class="w-3.5 h-3.5 rounded text-blue-600 focus:ring-1 focus:ring-blue-500">
                                            Requis
                                        </label>
                                    </div>
                                </div>
                                <button type="button"
                                    wire:click="detachFormulaire({{ $formulaire['id'] }})"
                                    wire:confirm="Détacher ce formulaire ?"
                                    class="text-gray-300 hover:text-red-500 transition shrink-0 mt-0.5 opacity-0 group-hover:opacity-100">
                                    <i class="ri-delete-bin-line text-base"></i>
                                </button>
                            </div>
                        @endforeach
                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 flex items-center justify-center text-gray-300 text-xs">
                            <i class="ri-drag-drop-line mr-1.5"></i> Glissez un formulaire ici pour l'ajouter à la séquence
                        </div>
                    </div>

                    {{-- Formulaire Attach Modal — teleported to body --}}
                    <template x-teleport="body">
                        <div x-show="formulaireOpen" x-cloak
                             x-transition:enter="ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @keydown.escape.window="formulaireOpen = false"
                             class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50">
                            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden"
                                 @click.outside="formulaireOpen = false">

                                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                                    <h2 class="text-base font-semibold text-gray-900">Attacher un Formulaire</h2>
                                    <button type="button" @click="formulaireOpen = false"
                                        class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                                        <i class="ri-close-line text-lg"></i>
                                    </button>
                                </div>

                                <div class="p-6 space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                                            Formulaire <span class="text-red-400">*</span>
                                        </label>
                                        <select wire:model="selectedFormulaire"
                                            class="w-full px-4 py-2.5 border @error('selectedFormulaire') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50">
                                            <option value="">-- Choisir un formulaire --</option>
                                            @foreach($availableFormulaires as $form)
                                                <option value="{{ $form['id'] }}">
                                                    {{ $form['title'] }}@if($form['title_ar']) ({{ $form['title_ar'] }})@endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('selectedFormulaire')
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                                                Ordre <span class="text-red-400">*</span>
                                            </label>
                                            <input type="number" min="1" wire:model="formulaireOrder"
                                                class="w-full px-4 py-2.5 border @error('formulaireOrder') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50">
                                            @error('formulaireOrder')
                                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                                                Statut <span class="text-red-400">*</span>
                                            </label>
                                            <select wire:model="formulaireStatus"
                                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50">
                                                <option value="active">Actif</option>
                                                <option value="inactive">Inactif</option>
                                                <option value="draft">Brouillon</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                                            Débloquer le suivant quand
                                        </label>
                                        <select wire:model="formulaireUnlockStatus"
                                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50">
                                            <option value="submitted">Soumis</option>
                                            <option value="in_review">En révision</option>
                                            <option value="approved">Approuvé</option>
                                        </select>
                                    </div>

                                    <label class="flex items-center gap-2.5 cursor-pointer">
                                        <input type="checkbox" wire:model="formulaireRequired"
                                            class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">Obligatoire pour la soumission</span>
                                    </label>
                                </div>

                                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-50">
                                    <button type="button" @click="formulaireOpen = false"
                                        class="px-4 py-2 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition">
                                        Annuler
                                    </button>
                                    {{-- FIX: single handler — wire:click alone; Alpine closes on success via Livewire event --}}
                                    <button type="button"
                                        wire:click="attachFormulaire"
                                        wire:loading.attr="disabled"
                                        wire:loading.class="opacity-60"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition">
                                        <span wire:loading.remove wire:target="attachFormulaire">Attacher</span>
                                        <span wire:loading wire:target="attachFormulaire">Attachement...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Listen for Livewire event to close the formulaire modal from Alpine --}}
                    <div x-on:formulaire-attached.window="formulaireOpen = false" style="display:none;"></div>

                </div>{{-- end formulaires sélectionnés card --}}

            @else
                <div class="bg-[#F5F3F7] rounded-2xl shadow-sm border border-gray-100 flex flex-col">
                    <div class="px-6 pt-5 pb-4 border-b border-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center shrink-0">
                                <i class="ri-list-ordered text-gray-600 text-sm"></i>
                            </div>
                            <h2 class="iuhm_title_2">Formulaires Sélectionnés</h2>
                        </div>
                    </div>
                    <div class="flex-1 flex items-center justify-center px-6 py-8">
                        <div class="w-full border-2 border-dashed border-gray-200 rounded-2xl p-8 flex flex-col items-center text-center">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center mb-3">
                                <i class="ri-information-line text-indigo-400 text-xl"></i>
                            </div>
                            <p class="text-sm font-semibold text-gray-700 mb-1">Enregistrez d'abord le projet</p>
                            <p class="text-xs text-gray-400 leading-relaxed">
                                Après l'enregistrement, vous pourrez attacher et ordonner les formulaires directement sur cette page.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>



</div>