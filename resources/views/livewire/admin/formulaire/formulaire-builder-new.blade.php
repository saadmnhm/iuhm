@php
    $initialPage = $formId ? 'builder' : 'settings';
    $initialStepId = $activeStep?->id ?? optional($steps->first())->id;
@endphp

<div class="min-h-screen ">
    <div
        x-data="{
            page: @js($initialPage),
            editorTab: 'question',
            selectedStepId: @js($initialStepId),
            tableHasDynamicRows: @js($tableForm['has_dynamic_rows'] ?? false),
            inlineLignes: [],
            inlineColonnes: [],
            showFieldModal: false,
            showTableModal: false,
            showColumnModal: false,
            showRowModal: false,
            addInlineLigne() { this.inlineLignes.push({label: '', label_ar: '', input_type: ''}) },
            removeInlineLigne(i) { this.inlineLignes.splice(i, 1) },
            addInlineColonne() { this.inlineColonnes.push({header: '', header_ar: '', input_type: ''}) },
            removeInlineColonne(i) { this.inlineColonnes.splice(i, 1) },
        }"
        x-cloak
        @close-field-modal.window="showFieldModal = false"
        @close-table-modal.window="showTableModal = false"
        @close-column-modal.window="showColumnModal = false"
        @close-row-modal.window="showRowModal = false"
        class="mx-auto flex min-h-screen w-full items-center justify-center "
    >
        <div class="w-full overflow-hidden rounded-4xl bg-white ring-1 ring-white/10">
            <div class="flex items-start justify-between border-b border-slate-100 bg-white px-6 py-5 lg:px-8">
                <div>
                    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-[#0f1d57]/55">Formulaire modal</div>
                    <h2 class="mt-1 text-2xl font-black text-[#0f1d57] lg:text-3xl">
                        {{ $formId ? 'Modifier le formulaire' : 'Nouveau formulaire' }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">Définissez les caractéristiques et les étapes du formulaire</p>
                </div>

                <a href="{{ route('admin.formulaires.index') }}" class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                    <i class="ri-close-line text-2xl"></i>
                </a>
            </div>

            <div class="border-b border-slate-100 px-6 py-5 lg:px-8">
                <div class="flex items-center gap-4">
                    <button
                        type="button"
                        @click="page = 'settings'"
                        class="flex flex-1 items-center gap-3 text-left"
                    >
                        <span class="flex h-10 w-10 items-center justify-center rounded-full font-bold transition"
                            :class="page === 'settings' ? 'bg-[#0f1d57] text-white shadow-lg shadow-[#0f1d57]/20' : 'bg-slate-200 text-slate-500'">1</span>
                        <span class="text-sm font-semibold transition" :class="page === 'settings' ? 'text-[#0f1d57]' : 'text-slate-400'">Informations Générales</span>
                        <span class="mx-2 hidden h-px flex-1 bg-slate-200 md:block"></span>
                    </button>

                    <button
                        type="button"
                        @click="@if($formId) page = 'builder' @endif"
                        class="flex flex-1 items-center gap-3 text-left"
                        @if(!$formId) disabled @endif
                    >
                        <span class="flex h-10 w-10 items-center justify-center rounded-full font-bold transition"
                            :class="page === 'builder' ? 'bg-[#0f1d57] text-white shadow-lg shadow-[#0f1d57]/20' : 'bg-slate-200 text-slate-500'">2</span>
                        <span class="text-sm font-semibold transition" :class="page === 'builder' ? 'text-[#0f1d57]' : 'text-slate-400'">Etapes</span>
                    </button>
                </div>
            </div>

            <div class="px-6 py-6 lg:px-8 lg:py-8">
                <div x-show="page === 'settings'" x-cloak class="space-y-6">
                    <div class="grid gap-6 lg:grid-cols-2">
                        <div class="rounded-3xl ">
                            <div class="mb-5 flex items-center justify-between">
                                <div class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-3 py-1 text-xs font-black uppercase tracking-[0.18em] text-white">FR</div>
                                <div class="text-right text-sm font-bold text-[#0f1d57]">Version Française</div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="mb-2 block text-sm font-bold text-slate-700">Titre du formulaire</label>
                                    <input
                                        type="text"
                                        wire:model.blur="title"
                                        placeholder="Ex: Inscription au programme de tutorat"
                                        class="w-full iuhm_input rounded-[18px] h-15"
                                    >
                                    @error('title') <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-bold text-slate-700">Description</label>
                                    <textarea
                                        wire:model.blur="introduction"
                                        rows="8"
                                        placeholder="Présentez l'objectif de ce formulaire aux résidents..."
                                        class="w-full resize-none rounded-[18px] iuhm_textarea"
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3xl " dir="rtl">
                            <div class="mb-5 flex items-center justify-between">
                                <div class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-3 py-1 text-xs font-black uppercase tracking-[0.18em] text-white">AR</div>
                                <div class="text-right text-sm font-bold text-[#0f1d57]">النسخة العربية</div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="mb-2 block text-sm font-bold text-slate-700">عنوان النموذج</label>
                                    <input
                                        type="text"
                                        wire:model.blur="title_ar"
                                        placeholder="مثال: التسجيل في برنامج الدروس الخصوصية"
                                        class="w-full rounded-[18px] iuhm_input h-[60px]" 
                                    >
                                    @error('title_ar') <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-bold text-slate-700">الوصف</label>
                                    <textarea
                                        wire:model.blur="introduction_ar"
                                        rows="8"
                                        placeholder="اشرح هدف هذا النموذج للمستخدمين..."
                                        class="w-full resize-none rounded-[18px] iuhm_textarea"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-emerald-200 bg-linear-to-r from-emerald-50 to-white px-5 py-4 shadow-sm">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-600 text-white shadow-lg shadow-emerald-600/20">
                                    <i class="ri-flashlight-line"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">État du formulaire</p>
                                    <p class="text-xs text-slate-500">Déterminez si ce formulaire est immédiatement accessible.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input type="checkbox" wire:model.live="is_active" class="peer sr-only">
                                    <div class="h-6 w-11 rounded-full bg-slate-200 transition peer-checked:bg-emerald-600 peer-checked:after:translate-x-full after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-slate-200 after:bg-white after:transition-all after:content-['']"></div>
                                </label>
                                <span class="text-sm font-bold text-emerald-700">{{ $is_active ? 'Actif' : 'Inactif' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row">
                        <button
                            type="button"
                            wire:click="cancelCreate"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50"
                        >
                            <i class="ri-close-line"></i>
                            Annuler
                        </button>
                        <button
                            type="button"
                            wire:click="saveSettings"
                            wire:loading.attr="disabled"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-full bg-[#0f1d57] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#0f1d57]/20 transition hover:bg-[#16286f] disabled:opacity-60"
                        >
                            <i class="ri-arrow-right-line" wire:loading.remove wire:target="saveSettings"></i>
                            <svg wire:loading wire:target="saveSettings" class="h-5 w-5 animate-spin text-white" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveSettings">{{ $formId ? 'Etapes' : 'Créer le formulaire' }}</span>
                            <span wire:loading wire:target="saveSettings">Enregistrement...</span>
                        </button>
                    </div>
                </div>

                <div x-show="page === 'builder'" x-cloak class="space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                wire:click="addStep"
                                class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white px-4 py-2.5 text-sm font-bold text-emerald-700 shadow-sm transition hover:bg-emerald-50"
                            >
                                <span class="flex h-5 w-5 items-center justify-center rounded-full border border-emerald-500 text-emerald-600">+</span>
                                Ajouter une étape
                            </button>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-black uppercase tracking-[0.14em] text-emerald-700">
                                {{ $steps->count() }} étape(s) ajoutée(s)
                            </span>
                        </div>

                        <div class="inline-flex rounded-full bg-slate-100 p-1 shadow-inner">
                            <button type="button" @click="editorTab = 'question'" class="rounded-full px-4 py-2 text-sm font-bold transition" :class="editorTab === 'question' ? 'bg-white text-[#0f1d57] shadow-sm' : 'text-slate-500'">Question</button>
                            <button type="button" @click="editorTab = 'table'" class="rounded-full px-4 py-2 text-sm font-bold transition" :class="editorTab === 'table' ? 'bg-white text-[#0f1d57] shadow-sm' : 'text-slate-500'">Tableau</button>
                        </div>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-3">
                        @foreach($steps as $step)
                            <button
                                type="button"
                                wire:click="selectStep({{ $step->id }})"
                                @click="selectedStepId = {{ $step->id }}"
                                class="group rounded-3xl border p-4 text-left transition"
                                :class="selectedStepId === {{ $step->id }} ? 'border-[#0f1d57] bg-[#0f1d57] text-white shadow-lg shadow-[#0f1d57]/15' : 'border-slate-200 bg-white hover:border-emerald-300 hover:bg-emerald-50/60'"
                            >
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full font-black"
                                            :class="selectedStepId === {{ $step->id }} ? 'bg-white text-[#0f1d57]' : 'bg-emerald-50 text-emerald-700'">
                                            {{ $step->step_number }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-black">{{ $step->title }}</div>
                                            <div class="text-xs opacity-70">Étape {{ $step->step_number }}</div>
                                        </div>
                                    </div>
                                    <i class="ri-arrow-right-s-line text-lg opacity-50"></i>
                                </div>
                            </button>
                        @endforeach
                    </div>

                    @if($activeStep)
                        <div class="rounded-[28px] border border-slate-100 bg-white p-5 shadow-sm lg:p-6">
                            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-4">
                                <div>
                                    <div class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">Étape active</div>
                                    <h3 class="mt-1 text-xl font-black text-[#0f1d57]">{{ $activeStep->title }}</h3>
                                </div>

                                <!-- <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        @click="editorTab = 'question'"
                                        class="rounded-full px-4 py-2 text-sm font-bold transition"
                                        :class="editorTab === 'question' ? 'bg-[#0f1d57] text-white shadow-lg shadow-[#0f1d57]/15' : 'bg-slate-100 text-slate-600'"
                                    >
                                        Question
                                    </button>
                                    <button
                                        type="button"
                                        @click="editorTab = 'table'"
                                        class="rounded-full px-4 py-2 text-sm font-bold transition"
                                        :class="editorTab === 'table' ? 'bg-[#0f1d57] text-white shadow-lg shadow-[#0f1d57]/15' : 'bg-slate-100 text-slate-600'"
                                    >
                                        Tableau
                                    </button>
                                </div> -->
                            </div>

                            <div x-show="editorTab === 'question'" x-cloak class="mt-5 space-y-5">
                                {{-- Add question button --}}
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-black text-slate-900">Questions</p>
                                        <p class="text-xs text-slate-500">Ajoutez et gérez les questions de cette étape.</p>
                                    </div>
                                    <button type="button" @click="showFieldModal = true; $wire.openFieldModal({{ $activeStep->id }})"
                                        class="inline-flex items-center gap-2 rounded-full bg-[#0f1d57] px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-[#0f1d57]/20 transition hover:bg-[#16286f]">
                                        <i class="ri-add-line"></i>
                                        Ajouter une question
                                    </button>
                                </div>
                                {{-- Existing fields list --}}
                                @if(($activeStep->fields ?? collect())->count() > 0)
                                    <div class="space-y-3 border-t border-slate-100 pt-4">
                                        @foreach($activeStep->fields as $field)
                                            <div class="group rounded-[22px] border border-slate-100 bg-slate-50 px-4 py-4 transition hover:border-emerald-200 hover:bg-emerald-50/40">
                                                <div class="flex flex-wrap items-center justify-between gap-3">
                                                    <div>
                                                        <div class="text-sm font-black text-slate-900">{{ $field->label }}</div>
                                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                                            <span class="rounded-full bg-white px-2.5 py-1 font-semibold text-[#0f1d57] shadow-sm">{{ $field->type }}</span>
                                                            <span class="font-mono">{{ $field->field_key }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <button type="button" @click="showFieldModal = true; $wire.openFieldModal({{ $activeStep->id }}, {{ $field->id }})" class="rounded-full p-2 text-[#0f1d57] transition hover:bg-white"><i class="ri-edit-line"></i></button>
                                                        <button type="button" wire:click="deleteField({{ $field->id }})" wire:confirm="Supprimer cette question ?" class="rounded-full p-2 text-rose-600 transition hover:bg-white"><i class="ri-delete-bin-line"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div x-show="editorTab === 'table'" x-cloak class="mt-5 space-y-5">

                                {{-- MODE DE FONCTIONNEMENT --}}

                                <div>
                                    <div class="mb-3 text-xs font-black uppercase tracking-[0.16em] text-slate-500">Mode de fonctionnement</div>
                                    <div class="grid gap-3 md:grid-cols-2">
                                        <button type="button" @click="tableHasDynamicRows = false"
                                            class="rounded-[20px] border p-4 text-left shadow-sm transition"
                                            :class="tableHasDynamicRows === false ? 'border-emerald-500 bg-white ring-2 ring-emerald-500/20' : 'border-slate-200 bg-white hover:border-emerald-300'">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-11 w-11 items-center justify-center rounded-xl"
                                                    :class="tableHasDynamicRows === false ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'">
                                                    <i class="ri-grid-line text-xl"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-black text-[#0f1d57]">Tableau Statique</div>
                                                    <div class="text-xs text-slate-500">Lignes et colonnes fixes</div>
                                                </div>
                                            </div>
                                        </button>
                                        <button type="button" @click="tableHasDynamicRows = true"
                                            class="rounded-[20px] border p-4 text-left shadow-sm transition"
                                            :class="tableHasDynamicRows === true ? 'border-emerald-500 bg-white ring-2 ring-emerald-500/20' : 'border-slate-200 bg-white hover:border-emerald-300'">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-11 w-11 items-center justify-center rounded-xl"
                                                    :class="tableHasDynamicRows === true ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'">
                                                    <i class="ri-database-2-line text-xl"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-black text-[#0f1d57]">Tableau Dynamique</div>
                                                    <div class="text-xs text-slate-500">Ajout de lignes par l utilisateur</div>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                                {{-- DÉFINITIONS DES LIGNES (static only) --}}
                                <div x-show="tableHasDynamicRows === false">
                                    <div class="mb-3 flex items-center justify-between">
                                        <div class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Définitions des lignes</div>
                                        <button type="button" @click="addInlineLigne()" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 transition hover:text-emerald-600">
                                            <span class="flex h-4 w-4 items-center justify-center rounded-full border border-emerald-500 text-[10px] leading-none text-emerald-600">+</span>
                                            Ajouter
                                        </button>
                                    </div>
                                    <div class="space-y-2">
                                        <template x-for="(ligne, i) in inlineLignes" :key="i">
                                            <div class="grid items-center gap-2" style="grid-template-columns: 1fr 1fr auto auto;">
                                                <input type="text" x-model="ligne.label" placeholder="Saisir le titre de la ligne" class="rounded-[14px] border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-[#0f1d57] focus:ring-2 focus:ring-[#0f1d57]/10">
                                                <input type="text" x-model="ligne.label_ar" placeholder="أدخل عنوان السطر" dir="rtl" class="rounded-[14px] border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-[#0f1d57] focus:ring-2 focus:ring-[#0f1d57]/10">
                                                <select x-model="ligne.input_type" class="rounded-[14px] border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-[#0f1d57] focus:ring-2 focus:ring-[#0f1d57]/10">
                                                    <option value="">Sélectionner le type</option>
                                                    <option value="text">Texte</option>
                                                    <option value="number">Nombre</option>
                                                    <option value="checkbox">Case à cocher</option>
                                                    <option value="select">Menu déroulant</option>
                                                    <option value="readonly">Lecture seule</option>
                                                    <option value="label">Libellé</option>
                                                    <option value="radio">Radio</option>
                                                </select>
                                                <button type="button" @click="removeInlineLigne(i)" class="flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-rose-50 hover:text-rose-600"><i class="ri-delete-bin-line"></i></button>
                                            </div>
                                        </template>
                                        <template x-if="inlineLignes.length === 0">
                                            <div class="rounded-[14px] border border-dashed border-slate-200 bg-slate-50 px-4 py-3 text-center text-xs text-slate-400">Aucune ligne définie. Cliquez sur Ajouter.</div>
                                        </template>
                                    </div>
                                </div>

                                {{-- DÉFINITIONS DES COLONNES --}}
                                <div>
                                    <div class="mb-3 flex items-center justify-between">
                                        <div class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Définitions des colonnes</div>
                                        <button type="button" @click="addInlineColonne()" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 transition hover:text-emerald-600">
                                            <span class="flex h-4 w-4 items-center justify-center rounded-full border border-emerald-500 text-[10px] leading-none text-emerald-600">+</span>
                                            Ajouter
                                        </button>
                                    </div>
                                    <div class="space-y-2">
                                        <template x-for="(col, i) in inlineColonnes" :key="i">
                                            <div class="grid items-center gap-2" style="grid-template-columns: 1fr 1fr auto auto;">
                                                <input type="text" x-model="col.header" placeholder="Saisir le titre de la col..." class="rounded-[14px] border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-[#0f1d57] focus:ring-2 focus:ring-[#0f1d57]/10">
                                                <input type="text" x-model="col.header_ar" placeholder="أدخل عنوان العمود" dir="rtl" class="rounded-[14px] border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-[#0f1d57] focus:ring-2 focus:ring-[#0f1d57]/10">
                                                <select x-model="col.input_type" class="rounded-[14px] border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-[#0f1d57] focus:ring-2 focus:ring-[#0f1d57]/10">
                                                    <option value="">Sélectionner le type</option>
                                                    <option value="text">Texte</option>
                                                    <option value="number">Nombre</option>
                                                    <option value="checkbox">Case à cocher</option>
                                                    <option value="select">Menu déroulant</option>
                                                    <option value="readonly">Lecture seule</option>
                                                    <option value="label">Libellé</option>
                                                    <option value="radio">Radio</option>
                                                </select>
                                                <button type="button" @click="removeInlineColonne(i)" class="flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-rose-50 hover:text-rose-600"><i class="ri-delete-bin-line"></i></button>
                                            </div>
                                        </template>
                                        <template x-if="inlineColonnes.length === 0">
                                            <div class="rounded-[14px] border border-dashed border-slate-200 bg-slate-50 px-4 py-3 text-center text-xs text-slate-400">Aucune colonne définie. Cliquez sur Ajouter.</div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Save tableau button --}}
                                <div class="flex items-center justify-end border-t border-slate-100 pt-4">
                                    <button type="button" @click="showTableModal = true; $wire.openTableModal({{ $activeStep->id }})"
                                        class="inline-flex items-center gap-2 rounded-full bg-[#0f1d57] px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-[#0f1d57]/20 transition hover:bg-[#16286f]">
                                        <i class="ri-add-line"></i>
                                        Ajouter le tableau
                                    </button>
                                </div>

                                {{-- Existing tables compact list --}}

                                @if(($activeStep->tables ?? collect())->count() > 0)

                                    <div class="space-y-2 border-t border-slate-100 pt-4">

                                        @foreach($activeStep->tables as $table)

                                            <div class="flex items-center justify-between rounded-[18px] border border-slate-100 bg-slate-50 px-4 py-3">

                                                <div>

                                                    <div class="text-sm font-black text-slate-900">{{ $table->title }}</div>

                                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">

                                                        <span class="rounded-full bg-white px-2 py-0.5 font-semibold shadow-sm">{{ $table->table_key }}</span>

                                                        @if($table->has_dynamic_rows)

                                                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 font-semibold text-emerald-700">Dynamique</span>

                                                        @else

                                                            <span class="rounded-full bg-sky-100 px-2 py-0.5 font-semibold text-sky-700">Statique</span>

                                                        @endif

                                                    </div>

                                                </div>

                                                <div class="flex items-center gap-1">

                                                    <button type="button" @click="showTableModal = true; $wire.openTableModal({{ $activeStep->id }}, {{ $table->id }})" class="rounded-full p-2 text-[#0f1d57] transition hover:bg-white"><i class="ri-settings-3-line text-sm"></i></button>

                                                    <button type="button" wire:click="deleteTable({{ $table->id }})" wire:confirm="Supprimer ce tableau ?" class="rounded-full p-2 text-rose-600 transition hover:bg-white"><i class="ri-delete-bin-line text-sm"></i></button>

                                                </div>

                                            </div>

                                        @endforeach

                                    </div>

                                @endif

                            </div>
                        </div>
                    @else
                        <div class="rounded-4xl border border-dashed border-slate-200 bg-slate-50 px-5 py-10 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                <i class="ri-stack-line text-2xl"></i>
                            </div>
                            <p class="mt-4 text-sm font-bold text-slate-700">Aucune étape active</p>
                            <p class="mt-1 text-sm text-slate-500">Créez ou sélectionnez une étape pour continuer.</p>
                        </div>
                    @endif

                    <div class="flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-between">
                        <button
                            type="button"
                            @click="page = 'settings'"
                            class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50"
                        >
                            <i class="ri-arrow-left-line"></i>
                            Précédent
                        </button>
                        <button
                            type="button"
                            wire:click="cancelCreate"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-[#0f1d57] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#0f1d57]/20 transition hover:bg-[#16286f]"
                        >
                            Terminer &amp; Créer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Field Modal --}}
        <div x-show="showFieldModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/55 px-4 py-6" x-transition>
            <div class="absolute inset-0" @click="showFieldModal = false"></div>
                <div class="relative z-10 w-full max-w-3xl overflow-hidden rounded-4xl bg-white shadow-[0_30px_90px_rgba(0,0,0,0.28)]">
                    <div class="flex items-center justify-between px-6 py-4 lg:px-7">
                        <div>
                            <h3 class="text-[24px] font-black  text-[#0f1d57]">{{ $fieldForm['id'] ? 'Modifier la question' : 'Ajouter une question' }}</h3>
                            <p class="text-[14px] text-[#45464E]">Définissez le texte, le type et les options de la question</p>
                        </div>
                        <button type="button" @click="showFieldModal = false" class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"><i class="ri-close-line text-xl"></i></button>
                    </div>

                    <div class="grid gap-5 px-6 py-6 lg:grid-cols-2 lg:px-7">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Libellé (Français)</label>
                            <input type="text" wire:model.blur="fieldForm.label" class="w-full rounded-[18px] iuhm_input" placeholder="Entrez votre question ici...">
                            @error('fieldForm.label') <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div dir="rtl">
                            <label class="mb-2 block text-sm font-bold text-slate-700">التسمية (العربية)</label>
                            <input type="text" wire:model.blur="fieldForm.label_ar" class="w-full rounded-2xl iuhm_input" placeholder="أدخل سؤالك هنا...">
                        </div>

                        <div class="lg:col-span-2">
                            <label class="w-full mb-2 block text-sm font-bold text-slate-700">Type de champ</label>
                            <select wire:model.live="fieldForm.type" class="w-full resize-none rounded-[18px] iuhm_input">
                                <option value="text">Texte</option>
                                <option value="textarea">Zone de texte</option>
                                <option value="number">Nombre</option>
                                <option value="email">E-mail</option>
                                <option value="date">Date</option>
                                <option value="select">Sélection</option>
                                <option value="radio">Boutons radio</option>
                                <option value="checkbox">Case à cocher</option>
                                <option value="file">Fichier</option>
                                <option value="heading">Titre</option>
                                <option value="paragraph">Paragraphe</option>
                            </select>
                            @error('fieldForm.type') <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p> @enderror
                        </div>
                       

                        <div class="lg:col-span-2">
                            <label class="mb-2 block text-sm font-bold text-slate-700">Bulle d'aide (Français)</label>
                            <textarea wire:model.blur="fieldForm.help_text" rows="4" class="w-full resize-none rounded-[18px] iuhm_textarea" placeholder="Information complémentaire..."></textarea>
                        </div>

                        <div class="lg:col-span-2 flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4">
                            <div>
                                <p class="text-sm font-bold text-slate-900">Réponse obligatoire</p>
                                <p class="text-xs text-slate-500">Activez si cette réponse doit être remplie par l’utilisateur.</p>
                            </div>
                            <label class="relative inline-flex cursor-pointer items-center">
                                <input type="checkbox" wire:model.live="fieldForm.is_required" class="peer sr-only">
                                <div class="h-6 w-11 rounded-full bg-slate-200 transition peer-checked:bg-emerald-600 peer-checked:after:translate-x-full after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-['']"></div>
                            </label>
                        </div>

                        @if(in_array($fieldForm['type'], ['select', 'radio', 'checkbox'], true))
                            <div class="lg:col-span-2 rounded-3xl border border-slate-100 bg-slate-50 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-black text-slate-900">Options</p>
                                        <p class="text-xs text-slate-500">Ajoutez les choix disponibles pour ce champ.</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input type="text" wire:model.defer="newOption" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm outline-none focus:border-[#0f1d57]" placeholder="Nouvelle option">
                                        <button type="button" wire:click="addFieldOption" class="rounded-full bg-[#0f1d57] px-4 py-2 text-sm font-bold text-white">Ajouter</button>
                                    </div>
                                </div>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach($fieldForm['options'] as $i => $option)
                                        <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm">
                                            {{ $option }}
                                            <button type="button" wire:click="removeFieldOption({{ $i }})" class="text-rose-500">×</button>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 py-4 lg:px-7">
                        <button type="button" @click="showFieldModal = false" class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600">Annuler</button>
                        <button type="button" wire:click="saveField" class="rounded-full bg-[#0f1d57] px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-[#0f1d57]/20">{{ $fieldForm['id'] ? 'Mettre à jour' : 'Ajouter' }}</button>
                    </div>
            </div>
        </div>

        {{-- Table Modal --}}
        <div x-show="showTableModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/55 px-4 py-6" x-transition>
            <div class="absolute inset-0" @click="showTableModal = false"></div>
                <div class="relative z-10 w-full max-w-4xl overflow-hidden rounded-4xl bg-white shadow-[0_30px_90px_rgba(0,0,0,0.28)]">
                    <div class="flex items-center justify-between  px-6 py-4 lg:px-7">
                        <div>
                            <h3 class="text-lg font-black text-[#0f1d57]">{{ $tableForm['id'] ? 'Modifier le tableau' : 'Nouveau tableau' }}</h3>
                            <p class="text-sm text-slate-500">Créez des tableaux statiques ou dynamiques</p>
                        </div>
                        <button type="button" @click="showTableModal = false" class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"><i class="ri-close-line text-xl"></i></button>
                    </div>

                    <div class="grid gap-5 px-6 py-6 lg:grid-cols-2 lg:px-7">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Titre</label>
                            <input type="text" wire:model.blur="tableForm.title" class="w-full rounded-2xl iuhm_input" placeholder="Type de Service">
                            @error('tableForm.title') <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div dir="rtl">
                            <label class="mb-2 block text-sm font-bold text-slate-700">العنوان</label>
                            <input type="text" wire:model.blur="tableForm.title_ar" class="w-full rounded-2xl iuhm_input" placeholder="نوع الخدمة">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Clé du tableau</label>
                            <input type="text" wire:model.blur="tableForm.table_key" class="w-full rounded-2xl iuhm_input" placeholder="table_key">
                            @error('tableForm.table_key') <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <div class="mb-2 text-xs font-black uppercase tracking-[0.16em] text-slate-400">Type</div>
                                <label class="flex items-center gap-2 text-sm font-bold text-slate-700">
                                    <input type="checkbox" x-model="tableHasDynamicRows" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                    Lignes dynamiques
                                </label>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <div class="mb-2 text-xs font-black uppercase tracking-[0.16em] text-slate-400">Total</div>
                                <label class="flex items-center gap-2 text-sm font-bold text-slate-700">
                                    <input type="checkbox" wire:model.live="tableForm.has_total_row" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                    Ligne total
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Nombre minimum de lignes</label>
                            <input type="number" wire:model.blur="tableForm.min_rows" class="w-full rounded-2xl iuhm_input">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Nombre maximum de lignes</label>
                            <input type="number" wire:model.blur="tableForm.max_rows" class="w-full rounded-2xl iuhm_input">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4 lg:px-7">
                        <button type="button" @click="showTableModal = false" class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600">Annuler</button>
                        <button type="button" @click.prevent="$wire.saveTable(tableHasDynamicRows)" class="rounded-full bg-[#0f1d57] px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-[#0f1d57]/20">{{ $tableForm['id'] ? 'Mettre à jour' : 'Ajouter' }}</button>
                    </div>
            </div>
        </div>

        {{-- Column Modal --}}
        <div x-show="showColumnModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/55 px-4 py-6" x-transition>
            <div class="absolute inset-0" @click="showColumnModal = false"></div>
                <div class="relative z-10 w-full max-w-4xl overflow-hidden rounded-4xl bg-white shadow-[0_30px_90px_rgba(0,0,0,0.28)]">
                    <div class="flex items-center justify-between  px-6 py-4 lg:px-7">
                        <div>
                            <h3 class="text-lg font-black text-[#0f1d57]">{{ $columnForm['id'] ? 'Modifier la colonne' : 'Nouvelle colonne' }}</h3>
                            <p class="text-sm text-slate-500">Définissez les colonnes du tableau</p>
                        </div>
                        <button type="button" @click="showColumnModal = false" class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"><i class="ri-close-line text-xl"></i></button>
                    </div>

                    <div class="grid gap-5 px-6 py-6 lg:grid-cols-2 lg:px-7">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Titre</label>
                            <input type="text" wire:model.blur="columnForm.header" class="w-full rounded-2xl iuhm_input" placeholder="Type de Service">
                        </div>
                        <div dir="rtl">
                            <label class="mb-2 block text-sm font-bold text-slate-700">العنوان</label>
                            <input type="text" wire:model.blur="columnForm.header_ar" class="w-full rounded-2xl iuhm_input" placeholder="نوع الخدمة">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Clé de colonne</label>
                            <input type="text" wire:model.blur="columnForm.column_key" class="w-full rounded-2xl iuhm_input" placeholder="column_key">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Type</label>
                            <select wire:model.live="columnForm.input_type" class="w-full rounded-[18px] iuhm_input">
                                <option value="text">Texte</option>
                                <option value="number">Nombre</option>
                                <option value="checkbox">Case à cocher</option>
                                <option value="select">Menu déroulant</option>
                                <option value="readonly">Lecture seule</option>
                                <option value="label">Libellé</option>
                                <option value="radio">Radio</option>
                            </select>
                        </div>

                        <div class="lg:col-span-2 flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4">
                            <div>
                                <p class="text-sm font-bold text-slate-900">Colonne totalisée</p>
                                <p class="text-xs text-slate-500">Utilisez cette colonne pour calculer un total</p>
                            </div>
                            <label class="relative inline-flex cursor-pointer items-center">
                                <input type="checkbox" wire:model.live="columnForm.is_totaled" class="peer sr-only">
                                <div class="h-6 w-11 rounded-full bg-slate-200 transition peer-checked:bg-emerald-600 peer-checked:after:translate-x-full after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-['']"></div>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4 lg:px-7">
                        <button type="button" @click="showColumnModal = false" class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600">Annuler</button>
                        <button type="button" wire:click="saveColumn" class="rounded-full bg-[#0f1d57] px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-[#0f1d57]/20">{{ $columnForm['id'] ? 'Mettre à jour' : 'Ajouter' }}</button>
                    </div>
            </div>
        </div>

        {{-- Row Modal --}}
        <div x-show="showRowModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/55 px-4 py-6" x-transition>
            <div class="absolute inset-0" @click="showRowModal = false"></div>
                <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-4xl bg-white shadow-[0_30px_90px_rgba(0,0,0,0.28)]">
                    <div class="flex items-center justify-between  px-6 py-4 lg:px-7">
                        <div>
                            <h3 class="text-lg font-black text-[#0f1d57]">{{ $rowForm['id'] ? 'Modifier la ligne' : 'Nouvelle ligne fixe' }}</h3>
                            <p class="text-sm text-slate-500">Définissez les libellés de la ligne fixe</p>
                        </div>
                        <button type="button" @click="showRowModal = false" class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"><i class="ri-close-line text-xl"></i></button>
                    </div>

                    <div class="grid gap-5 px-6 py-6 lg:grid-cols-2 lg:px-7">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Libellé (Français)</label>
                            <input type="text" wire:model.blur="rowForm.label" class="w-full rounded-2xl iuhm_input" placeholder="Saisir le titre de la ligne">
                        </div>
                        <div dir="rtl">
                            <label class="mb-2 block text-sm font-bold text-slate-700">التسمية (العربية)</label>
                            <input type="text" wire:model.blur="rowForm.label_ar" class="w-full rounded-2xl iuhm_input" placeholder="أدخل عنوان السطر">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4 lg:px-7">
                        <button type="button" @click="showRowModal = false" class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600">Annuler</button>
                        <button type="button" wire:click="saveRow" class="rounded-full bg-[#0f1d57] px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-[#0f1d57]/20">{{ $rowForm['id'] ? 'Mettre à jour' : 'Ajouter' }}</button>
                    </div>
            </div>
        </div>
    </div>
</div>