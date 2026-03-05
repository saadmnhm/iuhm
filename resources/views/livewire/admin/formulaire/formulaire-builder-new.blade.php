<div x-data="{ activeTab: 'settings' }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.formulaires.index') }}"
                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <i class="ri-arrow-left-line text-xl"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    {{ $formId ? 'Modifier le Formulaire' : 'Créer un Formulaire' }}
                </h2>
                <p class="text-gray-500 text-sm mt-1">Construisez votre formulaire étape par étape</p>
            </div>
        </div>
        @if($formId && $form)
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                    {{ $is_active ? 'Actif' : 'Inactif' }}
                </span>
                <a href="{{ route('user.dynamic_form', $form->slug) }}" target="_blank"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition border border-blue-200">
                    <i class="ri-external-link-line"></i> Prévisualiser
                </a>
            </div>
        @endif
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex border-b border-gray-100">
            <button type="button" @click="activeTab = 'settings'"
                :class="activeTab === 'settings' ? 'border-green-500 text-green-600 bg-green-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                class="flex items-center gap-2 px-6 py-3.5 text-sm font-medium border-b-2 transition">
                <i class="ri-settings-3-line"></i> Paramètres
            </button>
            <button type="button" @click="activeTab = 'steps'"
                :class="activeTab === 'steps' ? 'border-green-500 text-green-600 bg-green-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                class="flex items-center gap-2 px-6 py-3.5 text-sm font-medium border-b-2 transition {{ !$formId ? 'opacity-50 pointer-events-none' : '' }}">
                <i class="ri-list-ordered"></i> Étapes & Questions
            </button>
            <button type="button" @click="activeTab = 'preview'"
                :class="activeTab === 'preview' ? 'border-green-500 text-green-600 bg-green-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                class="flex items-center gap-2 px-6 py-3.5 text-sm font-medium border-b-2 transition {{ !$formId ? 'opacity-50 pointer-events-none' : '' }}">
                <i class="ri-eye-line"></i> Aperçu
            </button>
        </div>
    </div>

    {{-- ==================== SETTINGS TAB ==================== --}}
    <div x-show="activeTab === 'settings'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Settings -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="ri-information-line text-blue-500"></i> Informations générales
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{ slugPreview: '{{ addslashes($slug) }}' }">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Titre du formulaire <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.blur="title"
                                @input="slugPreview = $event.target.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/[^a-z0-9\s-]/g,'').trim().replace(/\s+/g,'-').replace(/-+/g,'-')"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none text-sm"
                                placeholder="Ex: Business Plan">
                            @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Titre en arabe</label>
                            <input type="text" wire:model.blur="title_ar" dir="rtl"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none text-sm"
                                placeholder="العنوان بالعربية">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
                            <div class="w-full px-4 py-2.5 rounded-lg border border-gray-100 bg-gray-50 text-sm font-mono text-gray-600 min-h-[42px]" x-text="slugPreview || '—'"></div>
                            @error('slug') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center gap-6 pt-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_active"
                                    class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                                <span class="text-sm text-gray-700">Formulaire actif</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="has_steps"
                                    class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                                <span class="text-sm text-gray-700">Multi-étapes</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Introduction</label>
                        <textarea wire:model="introduction" rows="3"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none text-sm"
                            placeholder="Description ou introduction affichée au candidat..."></textarea>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Introduction en arabe</label>
                        <textarea wire:model="introduction_ar" rows="3" dir="rtl"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none text-sm"
                            placeholder="المقدمة بالعربية..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Appearance -->
            <div class="space-y-6">
                <!-- Colors -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="ri-palette-line text-purple-500"></i> Apparence
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Couleur principale</label>
                            <div class="flex items-center gap-3">
                                <input type="color" wire:model.live="color"
                                    class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer">
                                <input type="text" wire:model.live="color"
                                    class="flex-1 px-3 py-2 rounded-lg border border-gray-200 text-sm font-mono">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Couleur de fond</label>
                            <div class="flex items-center gap-3">
                                <input type="color" wire:model.live="bg_color"
                                    class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer">
                                <input type="text" wire:model.live="bg_color"
                                    class="flex-1 px-3 py-2 rounded-lg border border-gray-200 text-sm font-mono">
                            </div>
                        </div>

                        <!-- Preview swatch -->
                        <div class="mt-3 rounded-lg p-4 border" style="background-color: {{ $bg_color }}; border-color: {{ $color }};">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white"
                                    style="background-color: {{ $color }};">
                                    <i class="{{ $icon }}"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm" style="color: {{ $color }};">{{ $title ?: 'Titre du formulaire' }}</p>
                                    <p class="text-xs text-gray-500">Aperçu de la carte</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Icon Selection -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="ri-remixicon-line text-orange-500"></i> Icône
                    </h3>
                    <div class="grid grid-cols-5 gap-2">
                        @foreach($availableIcons as $ic)
                            <button type="button" wire:click="selectIcon('{{ $ic }}')" wire:key="icon-{{ $loop->index }}"
                                class="w-10 h-10 rounded-lg flex items-center justify-center text-lg transition
                                    {{ $icon === $ic ? 'text-white' : 'text-gray-600 bg-gray-50 hover:bg-gray-100' }}"
                                style="{{ $icon === $ic ? 'background-color: ' . $color : '' }}">
                                <i class="{{ $ic }}" style="pointer-events: none;"></i>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Save Button -->
                <button type="button" wire:click="saveSettings" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed"
                    class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl transition font-medium shadow-sm flex items-center justify-center gap-2">
                    <i class="ri-save-line" wire:loading.remove wire:target="saveSettings"></i>
                    <svg wire:loading wire:target="saveSettings" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="saveSettings">{{ $formId ? 'Mettre à jour les paramètres' : 'Créer le formulaire' }}</span>
                    <span wire:loading wire:target="saveSettings">Enregistrement...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== STEPS & QUESTIONS TAB ==================== --}}
    <div x-show="activeTab === 'steps'" x-transition>
        @if($formId)
        
        <!-- Introduction Page Section -->
        <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-sm border border-blue-100 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="ri-book-open-line text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Page d'Introduction</h3>
                        <p class="text-sm text-gray-600">
                            @if($has_introduction)
                                Introduction page is active - it will be shown before the first step
                            @else
                                Add an introduction page to display before the form steps
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($has_introduction)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="ri-check-line mr-1"></i> Active
                        </span>
                        <button wire:click="openIntroductionModal"
                            class="px-4 py-2 text-sm text-blue-600 hover:bg-blue-100 rounded-lg transition">
                            <i class="ri-edit-line"></i> Edit
                        </button>
                        <button wire:click="deleteIntroductionPage"
                            wire:confirm="Are you sure you want to delete the introduction page?"
                            class="px-4 py-2 text-sm text-red-600 hover:bg-red-100 rounded-lg transition">
                            <i class="ri-delete-bin-line"></i> Delete
                        </button>
                    @else
                        <button wire:click="openIntroductionModal"
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="ri-add-line"></i> Create Introduction Page
                        </button>
                    @endif
                </div>
            </div>
            
            @if($has_introduction && $introduction_title)
                <div class="mt-4 p-4 bg-white rounded-lg border border-blue-200">
                    <h4 class="font-medium text-gray-800">{{ $introduction_title }}</h4>
                    @if($introduction_title_ar)
                        <p class="text-sm text-gray-500 mt-1" dir="rtl">{{ $introduction_title_ar }}</p>
                    @endif
                    <div class="mt-2 text-sm text-gray-600 line-clamp-3">
                        {!! nl2br(e(Str::limit($introduction_content, 200))) !!}
                    </div>
                </div>
            @endif
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Steps Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800">Étapes</h3>
                        <button wire:click="addStep"
                            class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition" title="Ajouter une étape">
                            <i class="ri-add-circle-line text-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-2">
                        @foreach($steps as $step)
                            <div wire:click="selectStep({{ $step->id }})"
                                class="flex items-center gap-3 p-3 rounded-lg cursor-pointer transition group
                                    {{ $activeStepId == $step->id ? 'bg-green-50 border border-green-200' : 'hover:bg-gray-50 border border-transparent' }}">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium shrink-0
                                    {{ $activeStepId == $step->id ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $step->step_number }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-700 truncate">{{ $step->title }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $step->fields->count() }} questions · {{ $step->tables->count() }} tableaux
                                    </p>
                                </div>
                                <div class="hidden group-hover:flex items-center gap-0.5">
                                    <button wire:click.stop="moveStep({{ $step->id }}, 'up')"
                                        class="p-1 text-gray-400 hover:text-gray-600 rounded">
                                        <i class="ri-arrow-up-s-line text-sm"></i>
                                    </button>
                                    <button wire:click.stop="moveStep({{ $step->id }}, 'down')"
                                        class="p-1 text-gray-400 hover:text-gray-600 rounded">
                                        <i class="ri-arrow-down-s-line text-sm"></i>
                                    </button>
                                    <button wire:click.stop="deleteStep({{ $step->id }})"
                                        wire:confirm="Supprimer cette étape et tout son contenu ?"
                                        class="p-1 text-red-400 hover:text-red-600 rounded">
                                        <i class="ri-delete-bin-line text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Step Content --}}
            <div class="lg:col-span-3 space-y-6">
                @if($activeStep)
                    {{-- Step Header --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"
                         x-data="{ editing: false }">
                        <div x-show="!editing" class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">
                                    Étape {{ $activeStep->step_number }}: {{ $activeStep->title }}
                                </h3>
                                @if($activeStep->title_ar)
                                    <p class="text-sm text-gray-500" dir="rtl">{{ $activeStep->title_ar }}</p>
                                @endif
                                @if($activeStep->description)
                                    <p class="text-sm text-gray-500 mt-1">{{ $activeStep->description }}</p>
                                @endif
                            </div>
                            <button @click="editing = true" wire:click="editStep({{ $activeStep->id }})"
                                class="px-3 py-1.5 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                <i class="ri-edit-line"></i> Modifier
                            </button>
                        </div>

                        <div x-show="editing" x-cloak class="space-y-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Titre</label>
                                    <input type="text" wire:model="stepTitle"
                                        class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Titre (Arabe)</label>
                                    <input type="text" wire:model="stepTitleAr" dir="rtl"
                                        class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                                <textarea wire:model="stepDescription" rows="2"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm"></textarea>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button @click="editing = false"
                                    class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition">Annuler</button>
                                <button wire:click="updateStep({{ $activeStep->id }})" @click="editing = false"
                                    class="px-4 py-2 text-sm text-white bg-green-600 hover:bg-green-700 rounded-lg transition">Sauvegarder</button>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-3">
                        <button wire:click="openFieldModal({{ $activeStep->id }})"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                            <i class="ri-add-line"></i> Ajouter une question
                        </button>
                        <button wire:click="openTableModal({{ $activeStep->id }})"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-purple-600 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                            <i class="ri-table-line"></i> Ajouter un tableau
                        </button>
                    </div>

                    {{-- Fields List --}}
                    @if($activeStep->fields->isNotEmpty())
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                                <h4 class="font-medium text-gray-700 flex items-center gap-2">
                                    <i class="ri-questionnaire-line text-blue-500"></i> Questions ({{ $activeStep->fields->count() }})
                                </h4>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @foreach($activeStep->fields->sortBy('sort_order') as $field)
                                    <div class="px-6 py-4 flex items-center justify-between group hover:bg-gray-50/50 transition">
                                        <div class="flex items-center gap-4">
                                            <div class="flex flex-col items-center gap-0.5">
                                                <button wire:click="moveField({{ $field->id }}, 'up')"
                                                    class="p-0.5 text-gray-300 hover:text-gray-600 rounded">
                                                    <i class="ri-arrow-up-s-line text-xs"></i>
                                                </button>
                                                <span class="text-xs text-gray-400 font-mono">{{ $field->sort_order }}</span>
                                                <button wire:click="moveField({{ $field->id }}, 'down')"
                                                    class="p-0.5 text-gray-300 hover:text-gray-600 rounded">
                                                    <i class="ri-arrow-down-s-line text-xs"></i>
                                                </button>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <p class="text-sm font-medium text-gray-700">{{ $field->label }}</p>
                                                    @if($field->is_required)
                                                        <span class="text-red-500 text-xs">*obligatoire</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                                        {{ $field->type }}
                                                    </span>
                                                    <span class="text-xs text-gray-400 font-mono">{{ $field->field_key }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                                            <button wire:click="openFieldModal({{ $activeStep->id }}, {{ $field->id }})"
                                                class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition">
                                                <i class="ri-edit-line text-sm"></i>
                                            </button>
                                            <button wire:click="deleteField({{ $field->id }})"
                                                wire:confirm="Supprimer cette question ?"
                                                class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                                                <i class="ri-delete-bin-line text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Tables List --}}
                    @if($activeStep->tables->isNotEmpty())
                        @foreach($activeStep->tables->sortBy('sort_order') as $table)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-100 bg-purple-50/50 flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-700 flex items-center gap-2">
                                            <i class="ri-table-line text-purple-500"></i> {{ $table->title }}
                                        </h4>
                                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                            <span class="font-mono">{{ $table->table_key }}</span>
                                            @if($table->has_dynamic_rows)
                                                <span class="px-2 py-0.5 bg-green-50 text-green-700 rounded">Lignes dynamiques</span>
                                            @endif
                                            @if($table->has_total_row)
                                                <span class="px-2 py-0.5 bg-orange-50 text-orange-700 rounded">Avec total</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button wire:click="openColumnModal({{ $table->id }})"
                                            class="px-3 py-1.5 text-xs text-blue-600 hover:bg-blue-50 rounded-lg transition flex items-center gap-1">
                                            <i class="ri-add-line"></i> Colonne
                                        </button>
                                        @if(!$table->has_dynamic_rows)
                                            <button wire:click="openRowModal({{ $table->id }})"
                                                class="px-3 py-1.5 text-xs text-green-600 hover:bg-green-50 rounded-lg transition flex items-center gap-1">
                                                <i class="ri-add-line"></i> Ligne fixe
                                            </button>
                                        @endif
                                        <button wire:click="openTableModal({{ $activeStep->id }}, {{ $table->id }})"
                                            class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition">
                                            <i class="ri-settings-3-line text-sm"></i>
                                        </button>
                                        <button wire:click="deleteTable({{ $table->id }})"
                                            wire:confirm="Supprimer ce tableau ?"
                                            class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition">
                                            <i class="ri-delete-bin-line text-sm"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Table Preview --}}
                                @if($table->columns->isNotEmpty())
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm">
                                            <thead>
                                                <tr class="bg-gray-50">
                                                    @if(!$table->has_dynamic_rows)
                                                        <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Ligne</th>
                                                    @endif
                                                    @foreach($table->columns->sortBy('sort_order') as $col)
                                                        <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    {{ $col->header }}
                                                                    <span class="text-gray-400 font-normal lowercase">({{ $col->input_type }})</span>
                                                                    @if($col->is_totaled) <i class="ri-calculator-line text-orange-500 ml-1"></i> @endif
                                                                </div>
                                                                <div class="flex items-center gap-0.5">
                                                                    <button wire:click="openColumnModal({{ $table->id }}, {{ $col->id }})"
                                                                        class="p-1 text-gray-400 hover:text-blue-500 rounded">
                                                                        <i class="ri-edit-line text-xs"></i>
                                                                    </button>
                                                                    <button wire:click="deleteColumn({{ $col->id }})"
                                                                        wire:confirm="Supprimer cette colonne ?"
                                                                        class="p-1 text-gray-400 hover:text-red-500 rounded">
                                                                        <i class="ri-close-line text-xs"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                                    @foreach($table->fixedRows->sortBy('sort_order') as $row)
                                                        <tr class="group">
                                                            <td class="px-4 py-2 text-gray-700 font-medium">
                                                                <div class="flex items-center justify-between">
                                                                    {{ $row->label }}
                                                                    <div class="hidden group-hover:flex items-center gap-0.5">
                                                                        <button wire:click="openRowModal({{ $table->id }}, {{ $row->id }})"
                                                                            class="p-1 text-gray-400 hover:text-blue-500 rounded">
                                                                            <i class="ri-edit-line text-xs"></i>
                                                                        </button>
                                                                        <button wire:click="deleteRow({{ $row->id }})"
                                                                            class="p-1 text-gray-400 hover:text-red-500 rounded">
                                                                            <i class="ri-close-line text-xs"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            @foreach($table->columns->sortBy('sort_order') as $col)
                                                                <td class="px-4 py-2">
                                                                    <div class="px-3 py-1.5 bg-gray-50 rounded border border-gray-200 text-gray-400 text-xs">
                                                                        {{ $col->input_type }}
                                                                    </div>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        @foreach($table->columns->sortBy('sort_order') as $col)
                                                            <td class="px-4 py-2">
                                                                <div class="px-3 py-1.5 bg-gray-50 rounded border border-gray-200 text-gray-400 text-xs">
                                                                    {{ $col->input_type }}
                                                                </div>
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                    @if($table->has_dynamic_rows)
                                                        <tr>
                                                            <td colspan="{{ $table->columns->count() }}" class="px-4 py-2 text-center">
                                                                <span class="text-xs text-gray-400 italic">+ lignes dynamiques ({{ $table->min_rows }} - {{ $table->max_rows }})</span>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                                @if($table->has_total_row)
                                                    <tr class="bg-gray-50 font-semibold">
                                                        @if(!$table->has_dynamic_rows)
                                                            <td class="px-4 py-2 text-gray-700">Total</td>
                                                        @endif
                                                        @foreach($table->columns->sortBy('sort_order') as $col)
                                                            <td class="px-4 py-2">
                                                                @if($col->is_totaled)
                                                                    <span class="text-orange-600 text-xs">Σ auto-calcul</span>
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="p-8 text-center text-gray-400">
                                        <i class="ri-table-line text-2xl mb-2"></i>
                                        <p class="text-sm">Ajoutez des colonnes à ce tableau</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    {{-- Empty state --}}
                    @if($activeStep->fields->isEmpty() && $activeStep->tables->isEmpty())
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                <i class="ri-layout-grid-line text-2xl text-gray-400"></i>
                            </div>
                            <h4 class="text-gray-600 font-medium mb-1">Cette étape est vide</h4>
                            <p class="text-gray-400 text-sm mb-4">Ajoutez des questions ou des tableaux</p>
                            <div class="flex items-center justify-center gap-3">
                                <button wire:click="openFieldModal({{ $activeStep->id }})"
                                    class="px-4 py-2 text-sm text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                                    <i class="ri-add-line"></i> Question
                                </button>
                                <button wire:click="openTableModal({{ $activeStep->id }})"
                                    class="px-4 py-2 text-sm text-purple-600 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                                    <i class="ri-table-line"></i> Tableau
                                </button>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                        <i class="ri-hand-coin-line text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Sélectionnez une étape pour voir son contenu</p>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- ==================== PREVIEW TAB ==================== --}}
    <div x-show="activeTab === 'preview'" x-transition>
        @if($form && $steps->isNotEmpty())
            <div class="max-w-4xl mx-auto">
                <div class="rounded-2xl shadow-sm border overflow-hidden" style="background-color: {{ $bg_color }};">
                    {{-- Form Header --}}
                    <div class="text-center py-8 px-6" style="border-bottom: 3px solid {{ $color }};">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl mx-auto mb-4"
                            style="background-color: {{ $color }};">
                            <i class="{{ $icon }}"></i>
                        </div>
                        <h1 class="text-3xl font-bold" style="color: {{ $color }}; font-family: 'EB Garamond', serif;">{{ $title }}</h1>
                        @if($title_ar)
                            <p class="text-xl mt-1" style="color: {{ $color }};" dir="rtl">{{ $title_ar }}</p>
                        @endif
                        @if($introduction)
                            <p class="text-gray-600 mt-3 max-w-2xl mx-auto">{{ $introduction }}</p>
                        @endif
                    </div>

                    {{-- Step Progress Preview --}}
                    @if($has_steps && $steps->count() > 1)
                        <div class="flex items-center justify-center gap-2 py-6 px-6">
                            @foreach($steps as $step)
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium
                                        {{ $loop->first ? 'text-white' : 'bg-gray-200 text-gray-600' }}"
                                        style="{{ $loop->first ? 'background-color: ' . $color : '' }}">
                                        {{ $step->step_number }}
                                    </div>
                                    @if(!$loop->last)
                                        <div class="w-12 h-0.5 {{ $loop->first ? '' : 'bg-gray-200' }}"
                                            style="{{ $loop->first ? 'background-color: ' . $color : '' }}"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Steps Content Preview --}}
                    @foreach($steps as $step)
                        <div class="px-8 py-6 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                            <h3 class="text-xl font-semibold mb-1" style="color: {{ $color }};">
                                {{ $step->title }}
                            </h3>
                            @if($step->description)
                                <p class="text-gray-500 text-sm mb-4">{{ $step->description }}</p>
                            @endif

                            {{-- Fields Preview --}}
                            <div class="space-y-4 mt-4">
                                @foreach($step->fields->sortBy('sort_order') as $field)
                                    <div class="{{ $field->is_full_width ? 'w-full' : 'w-1/2' }}">
                                        @if($field->type === 'heading')
                                            <h4 class="font-semibold text-gray-800 text-base mt-2">{{ $field->label }}</h4>
                                        @elseif($field->type === 'paragraph')
                                            <p class="text-gray-600 text-sm">{{ $field->label }}</p>
                                        @else
                                            <label class="block text-sm font-medium text-gray-700 mb-1" style="display: list-item; margin-left: 1rem;">
                                                {{ $field->label }}
                                                @if($field->is_required)<span class="text-red-500">*</span>@endif
                                            </label>
                                            @if($field->type === 'textarea')
                                                <textarea class="w-full px-3 py-2 rounded border border-gray-300 bg-white text-sm" rows="3"
                                                    placeholder="{{ $field->placeholder }}" readonly></textarea>
                                            @elseif(in_array($field->type, ['select']))
                                                <select class="w-full px-3 py-2 rounded border border-gray-300 bg-white text-sm" disabled>
                                                    <option>{{ $field->placeholder ?: 'Sélectionner...' }}</option>
                                                    @foreach($field->options ?? [] as $opt)
                                                        <option>{{ $opt }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif($field->type === 'radio')
                                                <div class="flex flex-wrap gap-4">
                                                    @foreach($field->options ?? [] as $opt)
                                                        <label class="flex items-center gap-2 text-sm text-gray-700">
                                                            <input type="radio" disabled style="accent-color: {{ $color }}">
                                                            {{ $opt }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @elseif($field->type === 'checkbox')
                                                <div class="flex flex-wrap gap-4">
                                                    @foreach($field->options ?? [] as $opt)
                                                        <label class="flex items-center gap-2 text-sm text-gray-700">
                                                            <input type="checkbox" disabled style="accent-color: {{ $color }}">
                                                            {{ $opt }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @else
                                                <input type="{{ $field->type }}" class="w-full px-3 py-2 rounded border border-gray-300 bg-white text-sm"
                                                    placeholder="{{ $field->placeholder }}" readonly>
                                            @endif
                                            @if($field->help_text)
                                                <p class="text-xs text-gray-400 mt-1">{{ $field->help_text }}</p>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            {{-- Tables Preview --}}
                            @foreach($step->tables->sortBy('sort_order') as $table)
                                <div class="mt-6">
                                    <h4 class="font-semibold text-gray-800 mb-2" style="display: list-item; margin-left: 1rem;">{{ $table->title }}</h4>
                                    @if($table->columns->isNotEmpty())
                                        <table class="w-full border-collapse border border-gray-300 text-sm">
                                            <thead>
                                                <tr>
                                                    @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                                        <th class="border px-4 py-2 text-left" style="background-color: #f5f8ff;"></th>
                                                    @endif
                                                    @foreach($table->columns->sortBy('sort_order') as $col)
                                                        <th class="border px-4 py-2 text-left" style="background-color: #f5f8ff;">{{ $col->header }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                                    @foreach($table->fixedRows->sortBy('sort_order') as $row)
                                                        <tr>
                                                            <td class="border px-4 py-2 font-medium" style="background-color: #f5f8ff;">{{ $row->label }}</td>
                                                            @foreach($table->columns->sortBy('sort_order') as $col)
                                                                <td class="border px-2 py-1">
                                                                    @if($col->input_type === 'checkbox')
                                                                        <div class="text-center"><input type="checkbox" disabled></div>
                                                                    @elseif($col->input_type === 'number')
                                                                        <input type="number" class="w-full px-2 py-1 border rounded text-sm bg-white" readonly>
                                                                    @elseif($col->input_type === 'readonly')
                                                                        <span class="text-gray-400 text-xs">lecture seule</span>
                                                                    @else
                                                                        <input type="text" class="w-full px-2 py-1 border rounded text-sm bg-white" readonly>
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    @for($r = 0; $r < min($table->min_rows, 3); $r++)
                                                        <tr>
                                                            @foreach($table->columns->sortBy('sort_order') as $col)
                                                                <td class="border px-2 py-1">
                                                                    @if($col->input_type === 'checkbox')
                                                                        <div class="text-center"><input type="checkbox" disabled></div>
                                                                    @elseif($col->input_type === 'number')
                                                                        <input type="number" class="w-full px-2 py-1 border rounded text-sm bg-white" readonly>
                                                                    @else
                                                                        <input type="text" class="w-full px-2 py-1 border rounded text-sm bg-white" readonly>
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endfor
                                                @endif
                                                @if($table->has_total_row)
                                                    <tr class="bg-gray-100 font-bold">
                                                        @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                                            <td class="border px-4 py-2 text-right" style="background-color: #f5f8ff;">Total</td>
                                                        @endif
                                                        @foreach($table->columns->sortBy('sort_order') as $col)
                                                            <td class="border px-4 py-2">
                                                                @if($col->is_totaled) <span class="text-gray-400">0.00</span> @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                        @if($table->has_dynamic_rows)
                                            <button class="mt-2 px-3 py-1.5 text-sm text-white rounded" style="background-color: {{ $color }};">
                                                + Ajouter une ligne
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    {{-- Navigation Preview --}}
                    <div class="flex items-center justify-center gap-4 p-6 border-t border-gray-200">
                        <button class="px-6 py-2.5 text-sm text-white rounded-lg" style="background-color: {{ $color }};">
                            <i class="ri-arrow-left-circle-fill mr-1"></i> Précédent
                        </button>
                        <button class="px-6 py-2.5 text-sm text-white rounded-lg bg-green-600">
                            Suivant <i class="ri-arrow-right-circle-fill ml-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <i class="ri-eye-off-line text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">Sauvegardez d'abord les paramètres et ajoutez des étapes pour voir l'aperçu</p>
            </div>
        @endif
    </div>

    {{-- ==================== FIELD MODAL ==================== --}}
    @if($showFieldModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" wire:click.self="$set('showFieldModal', false)">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto mx-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $fieldForm['id'] ? 'Modifier la question' : 'Nouvelle question' }}
                    </h3>
                    <button wire:click="$set('showFieldModal', false)" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Libellé <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.blur="fieldForm.label"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm"
                                placeholder="Ex: Quel est votre nom ?">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Libellé (Arabe)</label>
                            <input type="text" wire:model="fieldForm.label_ar" dir="rtl"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Clé du champ</label>
                            <div class="w-full px-4 py-2.5 rounded-lg border border-gray-100 bg-gray-50 text-sm font-mono text-gray-600 min-h-[42px] overflow-x-auto">{{ $fieldForm['field_key'] ?: '—' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                            <select wire:model.live="fieldForm.type"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                                <option value="text">Texte court</option>
                                <option value="textarea">Texte long</option>
                                <option value="number">Nombre</option>
                                <option value="email">Email</option>
                                <option value="date">Date</option>
                                <option value="select">Liste déroulante</option>
                                <option value="radio">Boutons radio</option>
                                <option value="file">Fichier</option>
                                <option value="heading">Titre (décoratif)</option>
                                <option value="paragraph">Paragraphe (décoratif)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Placeholder</label>
                        <input type="text" wire:model="fieldForm.placeholder"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Texte d'aide</label>
                        <input type="text" wire:model="fieldForm.help_text"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                    </div>

                    {{-- Options for select/radio/checkbox --}}
                    @if(in_array($fieldForm['type'], ['select', 'radio', 'checkbox']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                            <div class="space-y-2 mb-2">
                                @foreach($fieldForm['options'] as $i => $opt)
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-600 flex-1">{{ $opt }}</span>
                                        <button wire:click="removeFieldOption({{ $i }})"
                                            class="p-1 text-red-400 hover:text-red-600 rounded">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="text" wire:model="newOption" wire:keydown.enter="addFieldOption"
                                    class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm"
                                    placeholder="Nouvelle option...">
                                <button wire:click="addFieldOption"
                                    class="px-3 py-2 text-sm text-green-600 hover:bg-green-50 rounded-lg transition">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ordre</label>
                            <input type="number" wire:model="fieldForm.sort_order"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                        </div>
                        <div class="flex items-end gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="fieldForm.is_required"
                                    class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                                <span class="text-sm text-gray-700">Obligatoire</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="fieldForm.is_full_width"
                                    class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                                <span class="text-sm text-gray-700">Pleine largeur</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button wire:click="$set('showFieldModal', false)"
                        class="px-5 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition">Annuler</button>
                    <button wire:click="saveField"
                        class="px-5 py-2.5 text-sm text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                        {{ $fieldForm['id'] ? 'Mettre à jour' : 'Ajouter' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ==================== TABLE MODAL ==================== --}}
    @if($showTableModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" wire:click.self="$set('showTableModal', false)">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $tableForm['id'] ? 'Modifier le tableau' : 'Nouveau tableau' }}
                    </h3>
                    <button wire:click="$set('showTableModal', false)" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.blur="tableForm.title"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Titre (Arabe)</label>
                            <input type="text" wire:model="tableForm.title_ar" dir="rtl"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Clé du tableau</label>
                        <div class="w-full px-4 py-2.5 rounded-lg border border-gray-100 bg-gray-50 text-sm font-mono text-gray-600 min-h-[42px]">{{ $tableForm['table_key'] ?: '—' }}</div>
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model.live="tableForm.has_dynamic_rows"
                                class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                            <span class="text-sm text-gray-700">Lignes dynamiques (le candidat peut ajouter/supprimer)</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="tableForm.has_total_row"
                                class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                            <span class="text-sm text-gray-700">Ligne de total automatique</span>
                        </label>
                    </div>

                    @if($tableForm['has_dynamic_rows'])
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lignes minimum</label>
                                <input type="number" wire:model="tableForm.min_rows" min="1"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lignes maximum</label>
                                <input type="number" wire:model="tableForm.max_rows" min="1"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                        <input type="number" wire:model="tableForm.sort_order"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button wire:click="$set('showTableModal', false)"
                        class="px-5 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition">Annuler</button>
                    <button wire:click="saveTable"
                        class="px-5 py-2.5 text-sm text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                        {{ $tableForm['id'] ? 'Mettre à jour' : 'Ajouter' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ==================== COLUMN MODAL ==================== --}}
    @if($showColumnModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" wire:click.self="$set('showColumnModal', false)">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $columnForm['id'] ? 'Modifier la colonne' : 'Nouvelle colonne' }}
                    </h3>
                    <button wire:click="$set('showColumnModal', false)" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">En-tête <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.blur="columnForm.header"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">En-tête (Arabe)</label>
                            <input type="text" wire:model="columnForm.header_ar" dir="rtl"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Clé</label>
                            <div class="w-full px-4 py-2.5 rounded-lg border border-gray-100 bg-gray-50 text-sm font-mono text-gray-600 min-h-[42px]">{{ $columnForm['column_key'] ?: '—' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type d'entrée <span class="text-red-500">*</span></label>
                            <select wire:model.live="columnForm.input_type"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                                <option value="text">Texte</option>
                                <option value="number">Nombre</option>
                                <option value="checkbox">Case à cocher</option>
                                <option value="radio">Case à cocher (radio)</option>
                                <option value="select">Liste déroulante</option>
                                <option value="readonly">Lecture seule</option>
                                <option value="label">Label (texte fixe)</option>
                            </select>
                        </div>
                    </div>

                    @if(in_array($columnForm['input_type'], ['select']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                            <div class="space-y-2 mb-2">
                                @foreach($columnForm['options'] ?? [] as $i => $opt)
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-600 flex-1">{{ $opt }}</span>
                                        <button wire:click="removeColOption({{ $i }})"
                                            class="p-1 text-red-400 hover:text-red-600 rounded">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="text" wire:model="newColOption" wire:keydown.enter="addColOption"
                                    class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm"
                                    placeholder="Nouvelle option...">
                                <button wire:click="addColOption"
                                    class="px-3 py-2 text-sm text-green-600 hover:bg-green-50 rounded-lg transition">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Largeur</label>
                            <input type="text" wire:model="columnForm.width" placeholder="Ex: 200px ou 30%"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ordre</label>
                            <input type="number" wire:model="columnForm.sort_order"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                        </div>
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="columnForm.is_totaled"
                            class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                        <span class="text-sm text-gray-700">Calculer le total de cette colonne</span>
                    </label>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button wire:click="$set('showColumnModal', false)"
                        class="px-5 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition">Annuler</button>
                    <button wire:click="saveColumn"
                        class="px-5 py-2.5 text-sm text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                        {{ $columnForm['id'] ? 'Mettre à jour' : 'Ajouter' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ==================== ROW MODAL ==================== --}}
    @if($showRowModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" wire:click.self="$set('showRowModal', false)">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $rowForm['id'] ? 'Modifier la ligne' : 'Nouvelle ligne fixe' }}
                    </h3>
                    <button wire:click="$set('showRowModal', false)" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Libellé <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="rowForm.label"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm"
                            placeholder="Ex: Coûts de création d'entreprise">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Libellé (Arabe)</label>
                        <input type="text" wire:model="rowForm.label_ar" dir="rtl"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ordre</label>
                        <input type="number" wire:model="rowForm.sort_order"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button wire:click="$set('showRowModal', false)"
                        class="px-5 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition">Annuler</button>
                    <button wire:click="saveRow"
                        class="px-5 py-2.5 text-sm text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                        {{ $rowForm['id'] ? 'Mettre à jour' : 'Ajouter' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ==================== INTRODUCTION PAGE MODAL ==================== --}}
    @if($showIntroductionModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" 
             wire:click.self="closeIntroductionModal"
             x-data x-transition>
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="ri-book-open-line text-blue-600"></i>
                        {{ $has_introduction ? 'Modifier' : 'Créer' }} la Page d'Introduction
                    </h3>
                    <button wire:click="closeIntroductionModal" 
                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg transition">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Info Banner -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex gap-3">
                            <i class="ri-information-line text-blue-600 text-xl"></i>
                            <div class="flex-1">
                                <h4 class="font-medium text-blue-900 mb-1">About Introduction Pages</h4>
                                <p class="text-sm text-blue-700">
                                    The introduction page will be displayed first when users open this formulaire, 
                                    before they see the form steps. Use it to explain the purpose, provide instructions, 
                                    or list requirements.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Title Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="introduction_title"
                                   placeholder="e.g., Welcome to the Business Plan Form"
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm">
                            @error('introduction_title') 
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Title (Arabic)
                            </label>
                            <input type="text" 
                                   wire:model="introduction_title_ar"
                                   dir="rtl"
                                   placeholder="العنوان بالعربية"
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm">
                        </div>
                    </div>

                    <!-- Content Fields -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="introduction_content"
                                      rows="12"
                                      placeholder="Enter the introduction content here. You can include:&#10;• Instructions for filling the form&#10;• Required documents list&#10;• Eligibility criteria&#10;• Important notes&#10;&#10;Use line breaks for better formatting."
                                      class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm font-mono"></textarea>
                            @error('introduction_content') 
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="ri-lightbulb-line"></i> 
                                Tip: Use bullet points (•) and numbered lists for better readability
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Content (Arabic)
                            </label>
                            <textarea wire:model="introduction_content_ar"
                                      rows="12"
                                      dir="rtl"
                                      placeholder="أدخل محتوى المقدمة هنا"
                                      class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm"></textarea>
                        </div>
                    </div>

                    <!-- Preview -->
                    @if($introduction_title || $introduction_content)
                        <div class="border-t pt-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="ri-eye-line"></i> Preview
                            </h4>
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                @if($introduction_title)
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $introduction_title }}</h2>
                                @endif
                                @if($introduction_content)
                                    <div class="prose prose-sm max-w-none text-gray-700 whitespace-pre-wrap">{{ $introduction_content }}</div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 sticky bottom-0 bg-white">
                    <button wire:click="closeIntroductionModal"
                            class="px-5 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition">
                        Cancel
                    </button>
                    <button wire:click="saveIntroductionPage"
                            class="px-6 py-2.5 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition flex items-center gap-2">
                        <i class="ri-save-line"></i>
                        {{ $has_introduction ? 'Update' : 'Create' }} Introduction Page
                    </button>
                </div>
            </div>
        </div>
    @endif
