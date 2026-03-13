@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
    $displayLabel = fn ($fr, $ar = null) => $isArabic && filled($ar) ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif>
    <div class="parent-steps container">

        {{-- Read-Only Banner --}}
        @if($isReadOnly && $existingSubmission)
            <div class="alert alert-info d-flex align-items-center gap-2 rounded-3 shadow-sm mb-4" role="alert">
                <i class="ri-information-fill fs-4"></i>
                <div>
                    <p class="fw-bold mb-0">{{ $tr('Formulaire soumis — Mode lecture seule', 'تم إرسال الاستمارة — وضع القراءة فقط') }}</p>
                    <p class="mb-0 small">
                        {{ $tr('Statut', 'الحالة') }}: 
                        <span class="badge rounded-pill" style="background-color: {{ $form->color ?? '#2f5496' }};">
                            {{ [
                                'draft' => $tr('Brouillon', 'مسودة'),
                                'submitted' => $tr('Soumis', 'مرسل'),
                                'in_review' => $tr('En révision', 'قيد المراجعة'),
                                'approved' => $tr('Approuvé', 'مقبول'),
                                'rejected' => $tr('Rejeté', 'مرفوض')
                            ][$existingSubmission->status] ?? ucfirst($existingSubmission->status) }}
                        </span>
                        &middot; {{ $tr('Soumis le', 'تاريخ الإرسال') }} {{ $existingSubmission->submitted_at?->format('d/m/Y à H:i') }}
                    </p>
                </div>
            </div>
        @endif

        {{-- Draft Banner --}}
        @if($submissionId && !$isReadOnly)
            <div class="alert alert-success d-flex align-items-center gap-2 rounded-3 shadow-sm mb-4" role="alert">
                <i class="ri-save-line fs-4"></i>
                <p class="mb-0 small">{{ $tr('Mode brouillon — Votre progression est sauvegardée automatiquement.', 'وضع المسودة — يتم حفظ تقدمك تلقائيًا.') }}</p>
            </div>
        @endif

        {{-- Flash Messages --}}
        @if(session()->has('message'))
            <div class="alert alert-success rounded-3 shadow-sm mb-4">{{ session('message') }}</div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger rounded-3 shadow-sm mb-4">{{ session('error') }}</div>
        @endif

        {{-- Form Header --}}
        <div class="header-form" style="border-bottom: 3px solid {{ $form->color ?? '#2f5496' }};">
            <div style="margin-bottom: 15px;">
                <div style="width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; margin: 0 auto; background-color: {{ $form->color ?? '#2f5496' }};">
                    <i class="{{ $form->icon ?? 'ri-file-list-3-line' }}"></i>
                </div>
            </div>
            <h1 style="color: {{ $form->color ?? '#2f5496' }};">{{ $displayLabel($form->title, $form->title_ar) }}</h1>
            <p class="text-sm text-gray-500 mt-2">
                <i class="ri-folder-line mr-1"></i> {{ $project->project_name }}
            </p>
        </div>

        {{-- Introduction Page --}}
        @if($showIntroduction && $formulaire->has_introduction)
            <div class="mt-4 bg-white rounded-3 p-3 p-md-5 shadow-sm border">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                         style="width: 70px; height: 70px; background-color: {{ $form->color ?? '#2f5496' }}15;">
                        <i class="ri-book-open-line fs-2" style="color: {{ $form->color ?? '#2f5496' }};"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-center mb-3" style="color: {{ $form->color ?? '#2f5496' }};">
                    {{ $displayLabel($formulaire->introduction_title, $formulaire->introduction_title_ar) }}
                </h2>

                <div class="bg-light rounded-3 p-3 p-md-4 mb-4" style="border-left: 4px solid {{ $form->color ?? '#2f5496' }};">
                    <div class="text-secondary" style=" line-height: 1.8;">
                        {!! nl2br(e($displayLabel($formulaire->introduction_content, $formulaire->introduction_content_ar))) !!}
                    </div>
                </div>

                <div class="text-center mt-4">
                        <button wire:click="skipIntroduction"
                            class="btn text-white px-4 px-md-5 py-2 py-md-3 rounded-pill shadow-sm" 
                            style="background-color: {{ $form->color ?? '#2f5496' }};">
                        <i class="ri-play-line me-2"></i> {{ $tr('Commencer le formulaire', 'بدء الاستمارة') }}
                    </button>
                </div>
            </div>
        @else
            {{-- Step Progress --}}
            @if($form->has_steps && $totalSteps > 1)
                <div class="step-progress-container">
                    <div class="step-progress">
                        @for($i = 1; $i <= $totalSteps; $i++)
                            <div class="step-item {{ $currentStep >= $i ? 'active' : '' }} {{ $currentStep == $i ? 'current' : '' }}">
                                <div class="step-circle">
                                    @if($currentStep > $i)
                                        <i class="ri-check-line"></i>
                                    @else
                                        <span>{{ $i }}</span>
                                    @endif
                                </div>
                                @if($i < $totalSteps)
                                    <div class="step-line"></div>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
            @endif

            {{-- Step Content --}}
            @if($currentStepData)
                <div class="mt-4">
                    <h3 class="step-title" style="color: {{ $form->color ?? '#2f5496' }};">{{ $displayLabel($currentStepData->title, $currentStepData->title_ar) }}</h3>
                    @if($currentStepData->description)
                        <p class="instructions">{{ $currentStepData->description }}</p>
                    @endif

                    {{-- Render Fields --}}
                    @foreach($currentStepData->fields->sortBy('sort_order') as $field)
                        <div class="mt-4 {{ $field->is_full_width ? '' : 'w-full md:w-1/2 md:inline-block' }}" style="vertical-align: top;">
                            @if($field->type === 'heading')
                                <h4 class="step-title mt-3" style="color: {{ $form->color ?? '#2f5496' }}; font-size: 1.2rem;">{{ $displayLabel($field->label, $field->label_ar) }}</h4>
                            @elseif($field->type === 'paragraph')
                                <p class="instructions">{{ $displayLabel($field->label, $field->label_ar) }}</p>
                            @else
                                <label class="disc mb-2" for="field_{{ $field->id }}">
                                    {{ $displayLabel($field->label, $field->label_ar) }}
                                    @if($field->is_required)<span style="color: red;">*</span>@endif
                                </label>

                                @if($field->type === 'text')
                                    <input type="text" id="field_{{ $field->id }}"
                                        wire:model="answers.{{ $field->id }}"
                                        class="form-control"
                                        placeholder="{{ $field->placeholder }}"
                                        @if($isReadOnly) readonly @endif>

                                @elseif($field->type === 'textarea')
                                    <textarea id="field_{{ $field->id }}"
                                        wire:model="answers.{{ $field->id }}"
                                        class="form-control"
                                        placeholder="{{ $field->placeholder }}"
                                        @if($isReadOnly) readonly @endif></textarea>

                                @elseif($field->type === 'number')
                                    <input type="number" id="field_{{ $field->id }}"
                                        wire:model="answers.{{ $field->id }}"
                                        class="form-control border w-full p-1"
                                        placeholder="{{ $field->placeholder }}"
                                        @if($isReadOnly) readonly @endif>

                                @elseif($field->type === 'email')
                                    <input type="email" id="field_{{ $field->id }}"
                                        wire:model="answers.{{ $field->id }}"
                                        class="form-control"
                                        placeholder="{{ $field->placeholder }}"
                                        @if($isReadOnly) readonly @endif>

                                @elseif($field->type === 'date')
                                    <input type="date" id="field_{{ $field->id }}"
                                        wire:model="answers.{{ $field->id }}"
                                        class="form-control border w-full p-1"
                                        @if($isReadOnly) readonly @endif>

                                @elseif($field->type === 'select')
                                    <select id="field_{{ $field->id }}"
                                        wire:model="answers.{{ $field->id }}"
                                        class="form-control"
                                        @if($isReadOnly) disabled @endif>
                                        <option value="">{{ $field->placeholder ?: $tr('Sélectionner...', 'اختر...') }}</option>
                                        
                                        @foreach($field->options ?? [] as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>

                                @elseif($field->type === 'radio')
                                    <div class="checktf" style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 5px;">
                                        @foreach($field->options ?? [] as $opt)
                                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                                <input type="radio"
                                                    wire:model="answers.{{ $field->id }}"
                                                    value="{{ $opt }}"
                                                    style="accent-color: {{ $form->color ?? '#2f5496' }};"
                                                    @if($isReadOnly) disabled @endif>
                                                {{ $opt }}
                                            </label>
                                        @endforeach
                                    </div>

                                @elseif($field->type === 'checkbox')
                                    <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 5px;">
                                        @foreach($field->options ?? [] as $opt)
                                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                                <input type="checkbox"
                                                    wire:model="answers.{{ $field->id }}"
                                                    value="{{ $opt }}"
                                                    style="accent-color: {{ $form->color ?? '#2f5496' }};"
                                                    @if($isReadOnly) disabled @endif>
                                                {{ $opt }}
                                            </label>
                                        @endforeach
                                    </div>

                                @elseif($field->type === 'file')
                                    <input type="file" id="field_{{ $field->id }}"
                                        class="form-control"
                                        @if($isReadOnly) disabled @endif>
                                @endif

                                @if($field->help_text)
                                    <p class="instructions" style="font-size: 13px; margin-top: 4px;">{{ $field->help_text }}</p>
                                @endif

                                @error('answers.' . $field->id)
                                    <span style="color: red; font-size: 13px;">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                    @endforeach

                    {{-- Render Tables --}}
                    @foreach($currentStepData->tables->sortBy('sort_order') as $table)
                        <div class="mt-4">
                            <p class="disc mb-2" style="font-weight: 600;">{{ $displayLabel($table->title, $table->title_ar) }}</p>

                            @if($table->columns->isNotEmpty())
                                <div class="overflow-x-auto">
                                <table class="table-auto border-collapse border border-gray-300 w-full" style="min-width: 760px;">
                                    <thead>
                                        <tr>
                                            @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                                <th class="title-table border px-4 py-2"></th>
                                            @endif
                                            @foreach($table->columns->sortBy('sort_order') as $col)
                                                <th class="title-table border px-4 py-2" @if($col->width) style="width: {{ $col->width }};" @endif>
                                                    {{ $displayLabel($col->header, $col->header_ar) }}
                                                </th>
                                            @endforeach
                                            @if($table->has_dynamic_rows && !$isReadOnly)
                                                <th class="border px-2 py-2" style="width: 50px;"></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                            {{-- Fixed rows table --}}
                                            @foreach($table->fixedRows->sortBy('sort_order') as $ri => $row)
                                                <tr>
                                                    <td class="border px-4 py-2 title-table">{{ $displayLabel($row->label, $row->label_ar) }}</td>
                                                    @foreach($table->columns->sortBy('sort_order') as $col)
                                                        <td class="border px-2 py-1">
                                                            @if($col->input_type === 'checkbox')
                                                                <div style="text-align: center;">
                                                                    <input type="checkbox"
                                                                        wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                                        style="accent-color: {{ $form->color ?? '#2f5496' }};"
                                                                        @if($isReadOnly) disabled @endif>
                                                                </div>
                                                            @elseif($col->input_type === 'radio')
                                                                <div style="text-align: center;">
                                                                    <input type="radio"
                                                                        wire:model="tableData.{{ $table->table_key }}.{{ $ri }}._radio"
                                                                        value="{{ $col->column_key }}"
                                                                        name="radio_{{ $table->table_key }}_{{ $ri }}"
                                                                        style="accent-color: {{ $form->color ?? '#2f5496' }};"
                                                                        @if($isReadOnly) disabled @endif>
                                                                </div>
                                                            @elseif($col->input_type === 'number')
                                                                <input type="number"
                                                                    wire:model.live="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                                    class="form-control border w-full p-1"
                                                                    @if($isReadOnly) readonly @endif>
                                                            @elseif($col->input_type === 'select')
                                                                <select wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                                    class="form-control border w-full p-1"
                                                                    @if($isReadOnly) disabled @endif>
                                                                    <option value="">--</option>
                                                                    @foreach($col->options ?? [] as $opt)
                                                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @elseif($col->input_type === 'readonly')
                                                                <input type="text" readonly
                                                                    class="form-control border w-full p-1 bg-gray-100"
                                                                    value="{{ $tableData[$table->table_key][$ri][$col->column_key] ?? '' }}">
                                                            @else
                                                                <input type="text"
                                                                    wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                                    class="form-control border w-full p-1"
                                                                    @if($isReadOnly) readonly @endif>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @else
                                            {{-- Dynamic rows table --}}
                                            @for($ri = 0; $ri < ($tableRowCounts[$table->table_key] ?? $table->min_rows); $ri++)
                                                <tr>
                                                    @foreach($table->columns->sortBy('sort_order') as $col)
                                                        <td class="border px-2 py-1">
                                                            @if($col->input_type === 'checkbox')
                                                                <div style="text-align: center;">
                                                                    <input type="checkbox"
                                                                        wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                                        style="accent-color: {{ $form->color ?? '#2f5496' }};"
                                                                        @if($isReadOnly) disabled @endif>
                                                                </div>
                                                            @elseif($col->input_type === 'radio')
                                                                <div style="text-align: center;">
                                                                    <input type="radio"
                                                                        wire:model="tableData.{{ $table->table_key }}.{{ $ri }}._radio"
                                                                        value="{{ $col->column_key }}"
                                                                        name="radio_{{ $table->table_key }}_{{ $ri }}"
                                                                        style="accent-color: {{ $form->color ?? '#2f5496' }};"
                                                                        @if($isReadOnly) disabled @endif>
                                                                </div>
                                                            @elseif($col->input_type === 'number')
                                                                <input type="number"
                                                                    wire:model.live="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                                    class="form-control border w-full p-1"
                                                                    @if($isReadOnly) readonly @endif>
                                                            @elseif($col->input_type === 'select')
                                                                <select wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                                    class="form-control border w-full p-1"
                                                                    @if($isReadOnly) disabled @endif>
                                                                    <option value="">--</option>
                                                                    @foreach($col->options ?? [] as $opt)
                                                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                <input type="text"
                                                                    wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                                    class="form-control border w-full p-1"
                                                                    @if($isReadOnly) readonly @endif>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                    @if($table->has_dynamic_rows && !$isReadOnly)
                                                        <td class="border px-2 py-2">
                                                            @if(($tableRowCounts[$table->table_key] ?? $table->min_rows) > $table->min_rows)
                                                                <button wire:click="removeTableRow('{{ $table->table_key }}', {{ $ri }})"
                                                                    class="text-red-500" title="{{ $tr('Supprimer', 'حذف') }}">✕</button>
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endfor
                                        @endif

                                        {{-- Total Row --}}
                                        @if($table->has_total_row)
                                            <tr class="bg-gray-100 font-bold">
                                                @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                                    <td class="border px-3 py-2 text-right title-table">Total</td>
                                                    
                                                @endif
                                                @foreach($table->columns->sortBy('sort_order') as $col)
                                                    <td class="border px-3 py-2">
                                                        @if($col->is_totaled)
                                                            {{ number_format($this->getTableTotal($table->table_key, $col->column_key), 2) }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                                @if($table->has_dynamic_rows && !$isReadOnly)
                                                    <td class="border px-2 py-2"></td>
                                                @endif
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                </div>

                                @if($table->has_dynamic_rows && !$isReadOnly)
                                    <button wire:click="addTableRow('{{ $table->table_key }}')" class="more-row mt-2">
                                        + {{ $tr('Ajouter une ligne', 'إضافة سطر') }}
                                    </button>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Step Indicator --}}
            @if($totalSteps > 1)
                <p class="steps-indicateur mt-4">( {{ $currentStep }} / {{ $totalSteps }} )</p>
            @endif

            {{-- Navigation Buttons --}}
            <div class="navigation-buttons mt-4 d-flex flex-wrap justify-content-center gap-2 gap-md-3">
                @if($currentStep > 1)
                    <button wire:click="previousStep" class="navigation-btn btn-back w-100 w-md-auto">
                        <i class="ri-arrow-left-circle-fill me-1 ms-1"></i> {{ $tr('Précédent', 'السابق') }}
                    </button>
                @else
                    <a href="{{ route('user.project.detail', $projectId) }}" class="navigation-btn btn-back w-100 w-md-auto text-center">
                        <i class="ri-arrow-left-circle-fill me-1 ms-1"></i> {{ $tr('Retour au projet', 'العودة إلى المشروع') }}
                    </a>
                @endif

                @if($currentStep < $totalSteps)
                    <button wire:click="nextStep" class="navigation-btn btn-next w-100 w-md-auto" @if($isReadOnly && $currentStep >= $totalSteps) disabled @endif>
                        {{ $tr('Suivant', 'التالي') }} <i class="ri-arrow-right-circle-fill me-1 ms-1"></i>
                    </button>
                @endif

                @if(!$isReadOnly && $currentStep < $totalSteps)
                    <button wire:click="saveProgress" class="navigation-btn w-100 w-md-auto" style="background-color: #28a745;">
                        <i class="ri-save-line me-1 ms-1"></i> {{ $tr('Sauvegarder', 'حفظ') }}
                    </button>
                @endif

                @if($currentStep == $totalSteps && !$isReadOnly)
                    <button wire:click="submit" class="navigation-btn btn-submit w-100 w-md-auto">
                        {{ $tr('Soumettre', 'إرسال') }} <i class="ri-send-plane-fill me-1 ms-1"></i>
                    </button>
                @endif
            </div>
        @endif

        {{-- Footer --}}
        <div class="wizard-footer-content mt-5">
            <div class="wizard-logo-footer">
                <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="iuhm-logo-footer">
                <img src="{{ asset('assets/site/images/indh_logo.png') }}" alt="indh-logo-footer">
                <img src="{{ asset('assets/site/images/logo_zettat.png') }}" alt="zettat-logo-footer">
            </div>
            <p class="text-center mt-5">&copy; {{ date('Y') }} {{ $tr('Tous droits réservés par', 'جميع الحقوق محفوظة لدى') }} <a href="https://www.iuhm.org" target="_blank" style="color: {{ $form->color ?? '#2f5496' }};">Initiative Urbaine Hay Mohammadi</a></p>
        </div>

        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('scroll-to-top', () => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });
        </script>
    </div>
</div>
