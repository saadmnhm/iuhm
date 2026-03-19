@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
    $displayLabel = fn ($fr, $ar = null) => $isArabic && filled($ar) ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif>
    <div class="parent-steps container">
        {{-- Read-Only Banner --}}


        {{-- Draft Banner --}}
        @if($submissionId && !$isReadOnly)
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <div class="flex items-center">
                    <i class="ri-save-line mr-2"></i>
                    <p class="text-sm">{{ $tr('Mode brouillon - Votre progression est automatiquement sauvegardée.', 'وضع المسودة - يتم حفظ تقدمك تلقائيًا.') }}</p>
                </div>
            </div>
        @endif

        {{-- Form Header --}}
        <div class="header-form" style="border-bottom: 3px solid {{ $form->color }};">
            <div style="margin-bottom: 15px;">
                <div style="width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; margin: 0 auto; background-color: {{ $form->color }};">
                    <i class="{{ $form->icon }}"></i>
                </div>
            </div>
            <h1 style="color: {{ $form->color }};">{{ $displayLabel($form->title, $form->title_ar) }}</h1>
            @if($form->introduction && $step == 1)
                <p class="instructions mt-3">{{ $displayLabel($form->introduction, $form->introduction_ar) }}</p>
            @endif
        </div>

        {{-- Step Progress --}}
        @if($form->has_steps && $totalSteps > 1)
            <div class="step-progress-container">
                <div class="step-progress">
                    @for($i = 1; $i <= $totalSteps; $i++)
                        <div class="step-item {{ $step >= $i ? 'active' : '' }} {{ $step == $i ? 'current' : '' }}">
                            <div class="step-circle">
                                @if($step > $i)
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
        @if($currentStep)
            <div class="mt-4">
                <h3 class="step-title" style="color: {{ $form->color }};">{{ $displayLabel($currentStep->title, $currentStep->title_ar) }}</h3>
                @if($currentStep->description)
                    <p class="instructions">{{ $currentStep->description }}</p>
                @endif

                {{-- Render Fields --}}
                @foreach($currentStep->fields->sortBy('sort_order') as $field)
                    <div class="mt-4 {{ $field->is_full_width ? '' : 'w-1/2 inline-block' }}">
                        @if($field->type === 'heading')
                            <h4 class="step-title mt-3" style="color: {{ $form->color }}; font-size: 1.2rem;">{{ $displayLabel($field->label, $field->label_ar) }}</h4>
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
                                                style="accent-color: {{ $form->color }};"
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
                                                style="accent-color: {{ $form->color }};"
                                                @if($isReadOnly) disabled @endif>
                                            {{ $opt }}
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($field->type === 'file')
                                <input type="file" id="field_{{ $field->id }}"
                                    wire:model="answers.{{ $field->id }}"
                                    multiple
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
                @foreach($currentStep->tables->sortBy('sort_order') as $table)
                    <div class="mt-4">
                        <p class="disc mb-2" style="font-weight: 600;">{{ $displayLabel($table->title, $table->title_ar) }}</p>

                        @if($table->columns->isNotEmpty())
                            <table class="table-auto border-collapse border border-gray-300 w-full">
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
                                                                    style="accent-color: {{ $form->color }};"
                                                                    @if($isReadOnly) disabled @endif>
                                                            </div>
                                                        @elseif($col->input_type === 'radio')
                                                            <div style="text-align: center;">
                                                                <input type="radio"
                                                                    wire:model="tableData.{{ $table->table_key }}.{{ $ri }}._radio"
                                                                    value="{{ $col->column_key }}"
                                                                    name="radio_{{ $table->table_key }}_{{ $ri }}"
                                                                    style="accent-color: {{ $form->color }};"
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
                                                                    style="accent-color: {{ $form->color }};"
                                                                    @if($isReadOnly) disabled @endif>
                                                            </div>
                                                        @elseif($col->input_type === 'radio')
                                                            <div style="text-align: center;">
                                                                <input type="radio"
                                                                    wire:model="tableData.{{ $table->table_key }}.{{ $ri }}._radio"
                                                                    value="{{ $col->column_key }}"
                                                                    name="radio_{{ $table->table_key }}_{{ $ri }}"
                                                                    style="accent-color: {{ $form->color }};"
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

                            @if($table->has_dynamic_rows && !$isReadOnly)
                                <button wire:click="addTableRow('{{ $table->table_key }}')" class="more-row mt-2">
                                    {{ __('messages.ajouter_lignes') }}
                                </button>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Step Indicator --}}
        @if($totalSteps > 1)
            <p class="steps-indicateur mt-4">( {{ $step }} / {{ $totalSteps }} )</p>
        @endif

        {{-- Navigation Buttons --}}
        <div class="navigation-buttons mt-4 flex justify-center gap-4">
            @if($step > 1)
                <button wire:click="back" class="navigation-btn btn-back">
                    <i class="ri-arrow-left-circle-fill me-1 ms-1"></i> {{ $tr('Précédent', 'السابق') }}
                </button>
            @endif

            @if($step < $totalSteps)
                <button wire:click="next" class="navigation-btn btn-next" @if($isReadOnly && $step >= $totalSteps) disabled @endif>
                    {{ $tr('Suivant', 'التالي') }} <i class="ri-arrow-right-circle-fill me-1 ms-1"></i>
                </button>
            @endif

            @if(!$isReadOnly && $step < $totalSteps)
                <button wire:click="saveAsDraft" class="navigation-btn" style="background-color: #28a745;">
                    <i class="ri-save-line me-1 ms-1"></i> {{ $tr('Sauvegarder', 'حفظ') }}
                </button>
            @endif

            @if($step == $totalSteps && !$isReadOnly)
                <button wire:click="submit" class="navigation-btn btn-submit">
                    {{ $tr('Soumettre', 'إرسال') }} <i class="ri-send-plane-fill me-1 ms-1"></i>
                </button>
            @endif
        </div>

        {{-- Flash Messages --}}
        @if(session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mt-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mt-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Footer --}}
        <div class="wizard-footer-content mt-5">
            <div class="wizard-logo-footer">
                @if(isset($project) && $project->logo1)
                    <img src="{{ asset('uploads/' . $project->logo1) }}" alt="logo1-footer">
                @else
                    <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="iuhm-logo-footer">
                @endif

                @if(isset($project) && $project->logo2)
                    <img src="{{ asset('uploads/' . $project->logo2) }}" alt="logo2-footer">
                @else
                    <img src="{{ asset('assets/site/images/indh_logo.png') }}" alt="indh-logo-footer">
                @endif

                @if(isset($project) && $project->logo3)
                    <img src="{{ asset('uploads/' . $project->logo3) }}" alt="logo3-footer">
                @else
                    <img src="{{ asset('assets/site/images/logo_zettat.png') }}" alt="zettat-logo-footer">
                @endif
            </div>
            <p class="text-center mt-5">&copy; {{ date('Y') }} {{ $tr('Tous droits réservés par', 'جميع الحقوق محفوظة لدى') }} <a href="https://www.iuhm.org" target="_blank" style="color: {{ $form->color }};">initiative urbaine hay mohammadi</a></p>
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
