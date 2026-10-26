@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
    $displayLabel = fn ($fr, $ar = null) => $isArabic && filled($ar) ? $ar : $fr;
    $fieldNum = 0;
@endphp

<div @if($isArabic) dir="rtl" @endif class="min-h-screen bg-gray-50 py-8 px-4">

    {{-- Page wrapper: centered card --}}
    <div class="max-w-2xl mx-auto">

        {{-- Flash Messages --}}
        @if(session()->has('success'))
        <div class="flex items-center gap-2 rounded-xl px-4 py-3 mb-4 bg-green-50 border border-green-100 text-green-800">
            <i class="ri-checkbox-circle-fill text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session()->has('error'))
        <div class="flex items-center gap-2 rounded-xl px-4 py-3 mb-4 bg-red-50 border border-red-100 text-red-700">
            <i class="ri-error-warning-fill text-xl"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        {{-- Draft Banner --}}
        @if($submissionId && !$isReadOnly)
        <div class="flex items-center gap-2 rounded-xl px-4 py-2 mb-4 bg-amber-50 border border-amber-100 text-amber-800 text-sm">
            <i class="ri-save-3-line"></i>
            <span>{{ $tr('Brouillon — votre progression est sauvegardée automatiquement.', 'مسودة — يتم حفظ تقدمك تلقائيًا.') }}</span>
        </div>
        @endif

        {{-- Read-Only Banner --}}
        @if($isReadOnly)
        <div class="flex items-center gap-2 rounded-xl px-4 py-2 mb-4 bg-blue-50 border border-blue-100 text-blue-800 text-sm">
            <i class="ri-eye-line"></i>
            <span>{{ $tr('Mode lecture seule — ce formulaire a déjà été soumis.', 'وضع القراءة فقط — تم إرسال هذه الاستمارة مسبقًا.') }}</span>
        </div>
        @endif

        {{-- Main card --}}
        <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">

            {{-- Header --}}
            <div class="text-center px-5 sm:px-8 pt-6 pb-4 border-b border-gray-100">
                <div class="rounded-xl inline-flex items-center justify-center mb-3"
                     style="width: 64px; height: 64px; background: {{ $form->color }}18; color: {{ $form->color }}; font-size: 1.8rem;">
                    <i class="{{ $form->icon }}"></i>
                </div>
                <h2 class="font-bold mb-2 text-gray-900" style="font-size: clamp(1.2rem, 3vw, 1.6rem);">
                    {{ $displayLabel($form->title, $form->title_ar) }}
                </h2>
                @if($form->introduction && $step == 1)
                <p class="text-gray-500 text-sm leading-relaxed max-w-2xl mx-auto">
                    {{ $displayLabel($form->introduction, $form->introduction_ar) }}
                </p>
                @endif
            </div>

            {{-- Step progress --}}
            @if($form->has_steps && $totalSteps > 1)
            <div class="px-5 sm:px-8 py-3 bg-gray-50 border-b border-gray-100">
                <div class="flex items-center gap-0">
                    @for($i = 1; $i <= $totalSteps; $i++)
                        <div class="flex align-items-center flex-grow">
                            <div class="flex flex-col items-center" style="min-width: 40px;">
                                <div class="rounded-full flex items-center justify-center font-bold"
                                     style="width: 36px; height: 36px; font-size: 0.78rem;
                                            background: {{ $step > $i ? $form->color : ($step == $i ? $form->color : '#e5e7eb') }};
                                            color: {{ $step >= $i ? '#fff' : '#9ca3af' }};
                                            transition: all 0.3s;">
                                    @if($step > $i)
                                        <i class="ri-check-line"></i>
                                    @else
                                        {{ $i }}
                                    @endif
                                </div>
                            </div>
                            @if($i < $totalSteps)
                                <div class="flex-grow" style="height: 3px; background: {{ $step > $i ? $form->color : '#e5e7eb' }}; transition: all 0.3s; margin: 0 4px;"></div>
                            @endif
                        </div>
                    @endfor
                </div>
                @if($currentStep)
                <p class="mb-0 mt-2 font-semibold text-center text-sm text-gray-500">
                    {{ $tr('Étape', 'الخطوة') }} {{ $step }}/{{ $totalSteps }}
                    @if($currentStep->title) — {{ $displayLabel($currentStep->title, $currentStep->title_ar) }} @endif
                </p>
                @endif
            </div>
            @endif

            {{-- Form body --}}
            @if($currentStep)
            <div class="px-4 sm:px-6 py-4">

                {{-- Step description --}}
                @if($currentStep->description)
                <p class="mb-6 text-gray-500 text-sm leading-relaxed">{{ $currentStep->description }}</p>
                @endif

                {{-- Fields and tables, in the shared order --}}
                @foreach($currentStep->elements as $element)

                    @if($element->element_type === 'field')
                    @php $field = $element; @endphp

                    @if($field->type === 'heading')
                        {{-- Section heading with left border accent --}}
                        <div class="flex items-center gap-3 mt-6 mb-3">
                            <div style="width: 4px; height: 28px; background: {{ $form->color }}; border-radius: 2px; flex-shrink: 0;"></div>
                            <h5 class="font-bold mb-0 text-gray-900" style="font-size: 1rem;">
                                {{ $displayLabel($field->label, $field->label_ar) }}
                            </h5>
                        </div>

                    @elseif($field->type === 'paragraph')
                        <p class="mb-4 whitespace-pre-line text-sm leading-relaxed text-gray-600">
                            {{ $displayLabel($field->label, $field->label_ar) }}
                        </p>

                    @else
                        @php $fieldNum++; @endphp
                        <div class="mb-4">
                            <label class="block font-semibold mb-2" for="field_{{ $field->id }}"
                                   style="color: #1e293b; font-size: 0.88rem;">
                                {{ $fieldNum }}. {{ $displayLabel($field->label, $field->label_ar) }}
                                @if($field->is_required)<span style="color: #ef4444; margin-left: 2px;">*</span>@endif
                            </label>

                            @if($field->type === 'text')
                                <input type="text" id="field_{{ $field->id }}"
                                    wire:model="answers.{{ $field->id }}"
                                    class="w-full iuhm_input rounded-xl border-0"
                                    style="background: #f5f6fa; padding: 0.75rem 1rem; font-size: 0.88rem; color: #374151;"
                                    placeholder="{{ $field->placeholder }}"
                                    @if($isReadOnly) readonly @endif>

                            @elseif($field->type === 'textarea')
                                <textarea id="field_{{ $field->id }}"
                                    wire:model="answers.{{ $field->id }}"
                                    class="w-full iuhm_textarea rounded-xl border-0"
                                    style="background: #f5f6fa; padding: 0.75rem 1rem; font-size: 0.88rem; color: #374151; min-height: 110px; resize: vertical;"
                                    placeholder="{{ $field->placeholder }}"
                                    @if($isReadOnly) readonly @endif></textarea>

                            @elseif($field->type === 'number')
                                <input type="number" id="field_{{ $field->id }}"
                                    wire:model="answers.{{ $field->id }}"
                                    class="w-full iuhm_input rounded-xl border-0"
                                    style="background: #f5f6fa; padding: 0.75rem 1rem; font-size: 0.88rem; color: #374151;"
                                    placeholder="{{ $field->placeholder }}"
                                    @if($isReadOnly) readonly @endif>

                            @elseif($field->type === 'email')
                                <input type="email" id="field_{{ $field->id }}"
                                    wire:model="answers.{{ $field->id }}"
                                    class="w-full iuhm_input rounded-xl border-0"
                                    style="background: #f5f6fa; padding: 0.75rem 1rem; font-size: 0.88rem; color: #374151;"
                                    placeholder="{{ $field->placeholder }}"
                                    @if($isReadOnly) readonly @endif>

                            @elseif($field->type === 'date')
                                <input type="date" id="field_{{ $field->id }}"
                                    wire:model="answers.{{ $field->id }}"
                                    class="w-full iuhm_input rounded-xl border-0"
                                    style="background: #f5f6fa; padding: 0.75rem 1rem; font-size: 0.88rem; color: #374151;"
                                    @if($isReadOnly) readonly @endif>

                            @elseif($field->type === 'select')
                                <select id="field_{{ $field->id }}"
                                    wire:model="answers.{{ $field->id }}"
                                    class="w-full iuhm_select rounded-xl border-0"
                                    style="background: #f5f6fa; padding: 0.75rem 1rem; font-size: 0.88rem; color: #374151;"
                                    @if($isReadOnly) disabled @endif>
                                    <option value="">{{ $field->placeholder ?: $tr('Sélectionner...', 'اختر...') }}</option>
                                    @foreach($field->options ?? [] as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>

                            @elseif($field->type === 'radio')
                                <div class="flex flex-wrap gap-3 mt-1">
                                    @foreach($field->options ?? [] as $opt)
                                        <label class="flex items-center gap-2 px-3 py-2 rounded-lg cursor-pointer"
                                               style="background: #f5f6fa; font-size: 0.86rem; color: #374151;">
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
                                <div class="flex flex-wrap gap-3 mt-1">
                                    @foreach($field->options ?? [] as $opt)
                                        <label class="flex items-center gap-2 px-3 py-2 rounded-lg cursor-pointer"
                                               style="background: #f5f6fa; font-size: 0.86rem; color: #374151;">
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
                                <div class="rounded-xl p-3" style="background: #f5f6fa; border: 2px dashed #d1d5db;">
                                    <input type="file" id="field_{{ $field->id }}"
                                        wire:model="answers.{{ $field->id }}"
                                        @if($field->allow_multiple_files) multiple @endif
                                        accept=".pdf,.xls,.xlsx,.csv,.doc,.docx,.jpg,.jpeg,.png,.gif,.webp"
                                        class="w-full border-0 bg-transparent p-0"
                                        style="font-size: 0.85rem;"
                                        @if($isReadOnly) disabled @endif>
                                    <p class="mb-0 mt-2 text-sm text-gray-400">
                                        {{ $field->allow_multiple_files ? $tr('Plusieurs fichiers autorisés.','يُسمح بملفات متعددة.') : $tr('Un seul fichier autorisé.','ملف واحد فقط.') }}
                                        {{ $tr('Types: PDF, Excel, DOC, images. Max 10MB/fichier.','الأنواع: PDF, Excel, DOC, صور. الحد الأقصى 10 ميجا/ملف.') }}
                                    </p>
                                </div>

                                @php
                                    $rawFileValue = $answers[$field->id] ?? null;
                                    $existingPaths = [];
                                    if (is_string($rawFileValue) && trim($rawFileValue) !== '') {
                                        $decoded = json_decode($rawFileValue, true);
                                        if (is_array($decoded)) {
                                            $existingPaths = collect($decoded)->filter(fn($p)=>is_string($p)&&trim($p)!=='')->values()->all();
                                        } else {
                                            $existingPaths = [trim($rawFileValue)];
                                        }
                                    } elseif (is_array($rawFileValue)) {
                                        $existingPaths = collect($rawFileValue)->filter(fn($p)=>is_string($p)&&trim($p)!=='')->values()->all();
                                    }
                                @endphp
                                @if(!empty($existingPaths))
                                <div class="mt-2 space-y-1">
                                    @foreach($existingPaths as $path)
                                        @php $cleanPath = ltrim(str_starts_with($path,'uploads/') ? substr($path,8) : $path,'/'); @endphp
                                        <div class="flex items-center gap-3 rounded-lg px-3 py-2" style="background: #f0fdf4; font-size: 0.82rem;">
                                            <i class="ri-file-line" style="color: {{ $form->color }};"></i>
                                            <span class="flex-1 truncate" style="color: #374151;">{{ basename($cleanPath) }}</span>
                                            <a href="{{ route('uploads.show', ['path' => $cleanPath]) }}" target="_blank" style="color: #2563eb; text-decoration: none;">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('uploads.download', ['path' => $cleanPath]) }}" style="color: #0f766e; text-decoration: none;">
                                                <i class="ri-download-2-line"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                            @endif

                            @if($field->help_text)
                                <p class="mb-0 mt-1 text-xs text-gray-400">
                                    <i class="ri-information-line mr-1"></i>{{ $field->help_text }}
                                </p>
                            @endif

                            @error('answers.' . $field->id)
                                <p class="mb-0 mt-1 text-xs text-red-600">
                                    <i class="ri-error-warning-line mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    @endif

                    @else
                    @php $table = $element; @endphp

                    {{-- Table --}}
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div style="width: 4px; height: 24px; background: {{ $form->color }}; border-radius: 2px; flex-shrink: 0;"></div>
                        <p class="font-semibold mb-0 text-gray-900" style="font-size: 0.9rem;">
                            {{ $displayLabel($table->title, $table->title_ar) }}
                        </p>
                    </div>

                    @if($table->columns->isNotEmpty())
                    <div class="rounded-xl overflow-hidden border border-gray-200">
                        <div class="overflow-x-auto">
                        <table class="w-full min-w-[600px] text-sm">
                            <thead>
                                <tr class="bg-[#0f2441] text-white">
                                    @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                        <th class="px-3 py-2 font-semibold border-0" style="white-space: nowrap;"></th>
                                    @endif
                                    @foreach($table->columns->sortBy('sort_order') as $col)
                                        <th class="px-3 py-2 font-semibold border-0" style="white-space: nowrap;" @if($col->width) style="width: {{ $col->width }};" @endif>
                                            {{ $displayLabel($col->header, $col->header_ar) }}
                                        </th>
                                    @endforeach
                                    @if($table->has_dynamic_rows && !$isReadOnly)
                                        <th class="border-0" style="width: 44px;"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                    @foreach($table->fixedRows->sortBy('sort_order') as $ri => $row)
                                    <tr style="background: {{ $ri % 2 === 0 ? '#fff' : '#f9fafb' }};">
                                        <td class="px-3 py-2 font-semibold text-gray-800 whitespace-nowrap" style="border-color: #f1f5f9;">
                                            {{ $displayLabel($row->label, $row->label_ar) }}
                                        </td>
                                        @foreach($table->columns->sortBy('sort_order') as $col)
                                        <td class="px-2 py-1" style="border-color: #f1f5f9;">
                                            @if($col->input_type === 'checkbox')
                                                <div class="text-center">
                                                    <input type="checkbox"
                                                        wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                        style="accent-color: {{ $form->color }};"
                                                        @if($isReadOnly) disabled @endif>
                                                </div>
                                            @elseif($col->input_type === 'radio')
                                                <div class="text-center">
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
                                                    class="w-full iuhm_input rounded-lg border-0"
                                                    style="background: #f5f6fa; font-size: 0.82rem;"
                                                    @if($isReadOnly) readonly @endif>
                                            @elseif($col->input_type === 'select')
                                                <select wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                    class="w-full iuhm_select rounded-lg border-0"
                                                    style="background: #f5f6fa; font-size: 0.82rem;"
                                                    @if($isReadOnly) disabled @endif>
                                                    <option value="">--</option>
                                                    @foreach($col->options ?? [] as $opt)
                                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif($col->input_type === 'readonly')
                                                <input type="text" readonly
                                                    class="w-full iuhm_input rounded-lg border-0"
                                                    style="background: #e9ecef; font-size: 0.82rem;"
                                                    value="{{ $tableData[$table->table_key][$ri][$col->column_key] ?? '' }}">
                                            @else
                                                <input type="text"
                                                    wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                    class="w-full iuhm_input rounded-lg border-0"
                                                    style="background: #f5f6fa; font-size: 0.82rem;"
                                                    @if($isReadOnly) readonly @endif>
                                            @endif
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                @else
                                    @for($ri = 0; $ri < ($tableRowCounts[$table->table_key] ?? $table->min_rows); $ri++)
                                    <tr style="background: {{ $ri % 2 === 0 ? '#fff' : '#f9fafb' }};">
                                        @foreach($table->columns->sortBy('sort_order') as $col)
                                        <td class="px-2 py-1" style="border-color: #f1f5f9;">
                                            @if($col->input_type === 'checkbox')
                                                <div class="text-center">
                                                    <input type="checkbox"
                                                        wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                        style="accent-color: {{ $form->color }};"
                                                        @if($isReadOnly) disabled @endif>
                                                </div>
                                            @elseif($col->input_type === 'radio')
                                                <div class="text-center">
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
                                                    class="w-full iuhm_input rounded-lg border-0"
                                                    style="background: #f5f6fa; font-size: 0.82rem;"
                                                    @if($isReadOnly) readonly @endif>
                                            @elseif($col->input_type === 'select')
                                                <select wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                    class="w-full iuhm_select rounded-lg border-0"
                                                    style="background: #f5f6fa; font-size: 0.82rem;"
                                                    @if($isReadOnly) disabled @endif>
                                                    <option value="">--</option>
                                                    @foreach($col->options ?? [] as $opt)
                                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="text"
                                                    wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                    class="w-full iuhm_input rounded-lg border-0"
                                                    style="background: #f5f6fa; font-size: 0.82rem;"
                                                    @if($isReadOnly) readonly @endif>
                                            @endif
                                        </td>
                                        @endforeach
                                        @if($table->has_dynamic_rows && !$isReadOnly)
                                        <td class="px-2 py-1 text-center" style="border-color: #f1f5f9;">
                                            @if(($tableRowCounts[$table->table_key] ?? $table->min_rows) > $table->min_rows)
                                                <button wire:click="removeTableRow('{{ $table->table_key }}', {{ $ri }})"
                                                    class="p-1 text-red-500 hover:bg-red-50 rounded-lg"
                                                    style="background: transparent; border: none;" title="{{ $tr('Supprimer', 'حذف') }}">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                    @endfor
                                @endif

                                {{-- Total Row --}}
                                @if($table->has_total_row)
                                <tr style="background: #f0f9ff; font-weight: 700;">
                                    @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                        <td class="px-3 py-2 text-gray-800" style="border-color: #e9ecef;">{{ $tr('Total', 'المجموع') }}</td>
                                    @endif
                                    @foreach($table->columns->sortBy('sort_order') as $col)
                                    <td class="px-3 py-2 text-gray-800" style="border-color: #e9ecef;">
                                        @if($col->is_totaled)
                                            {{ number_format($this->getTableTotal($table->table_key, $col->column_key), 2) }}
                                        @endif
                                    </td>
                                    @endforeach
                                    @if($table->has_dynamic_rows && !$isReadOnly)
                                        <td style="border-color: #e9ecef;"></td>
                                    @endif
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        </div>
                    </div>

                    @if($table->has_dynamic_rows && !$isReadOnly)
                    <button wire:click="addTableRow('{{ $table->table_key }}')"
                            class="btn-sm rounded-xl mt-2 inline-flex items-center gap-1 font-semibold transition-colors"
                            style="border: 1px dashed {{ $form->color }}; background: transparent; color: {{ $form->color }}; font-size: 0.8rem; padding: 0.5rem 1rem;">
                        <i class="ri-add-line"></i> {{ $tr('Ajouter une ligne', 'إضافة سطر') }}
                    </button>
                    @endif
                    @endif
                </div>
                    @endif
                @endforeach

            </div>
            @endif

            {{-- Navigation Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-4 sm:px-6 py-4 border-t border-gray-100 bg-gray-50">

                <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                    @if($step > 1)
                    <button wire:click="back"
                            class="btn-sm rounded-xl font-semibold inline-flex items-center gap-2 transition-colors"
                            style="border: 1px solid #e2e8f0; background: #fff; color: #374151; font-size: 0.85rem; padding: 0.5rem 1rem;">
                        <i class="ri-arrow-left-s-line"></i> {{ $tr('Précédent', 'السابق') }}
                    </button>
                    @endif

                    @if(!$isReadOnly && $step < $totalSteps)
                    <button wire:click="saveAsDraft"
                            class="btn-sm rounded-xl font-semibold inline-flex items-center gap-2 transition-colors"
                            style="border: 1px solid #e2e8f0; background: #fff; color: #6b7280; font-size: 0.85rem; padding: 0.5rem 1rem;">
                        <i class="ri-save-3-line"></i> {{ $tr('Sauvegarder', 'حفظ') }}
                    </button>
                    @endif

                    @if($step > 1)
                    <a href="{{ url()->previous() }}"
                       class="btn-sm rounded-xl font-semibold inline-flex items-center gap-2 transition-colors"
                       style="border: 1px solid #e2e8f0; background: #fff; color: #6b7280; font-size: 0.85rem; padding: 0.5rem 1rem;">
                        <i class="ri-close-line"></i> {{ $tr('Retour', 'رجوع') }}
                    </a>
                    @endif
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    @if($totalSteps > 1)
                    <span class="text-xs text-gray-400">{{ $step }}/{{ $totalSteps }}</span>
                    @endif

                    @if($step < $totalSteps)
                    <button wire:click="next"
                            wire:loading.attr="disabled"
                            class="btn-sm rounded-xl font-semibold inline-flex items-center gap-2 transition-colors"
                            style="background: {{ $form->color }}; color: #fff; border: 0; font-size: 0.85rem; padding: 0.5rem 1rem;">
                        <span wire:loading.remove wire:target="next">
                            {{ $tr('Suivant', 'التالي') }} <i class="ri-arrow-right-s-line"></i>
                        </span>
                        <span wire:loading wire:target="next">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                    </button>
                    @endif

                    @if($step == $totalSteps && !$isReadOnly)
                    <button wire:click="submit"
                            wire:loading.attr="disabled"
                            class="btn-sm rounded-xl font-bold inline-flex items-center gap-2 transition-colors"
                            style="background: #0f2441; color: #fff; border: 0; font-size: 0.88rem; padding: 0.55rem 1.4rem;">
                        <span wire:loading.remove wire:target="submit">
                            <i class="ri-send-plane-fill mr-1"></i> {{ $tr('Soumettre', 'إرسال') }}
                        </span>
                        <span wire:loading wire:target="submit">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            {{ $tr('Envoi...', 'جاري الإرسال...') }}
                        </span>
                    </button>
                    @endif
                </div>
            </div>

            {{-- Footer logos --}}
            <div class="flex items-center justify-center gap-4 px-4 py-3 border-t border-gray-100 flex-wrap">
                @if(isset($project) && $project->logo1)
                    <img src="{{ asset('uploads/' . $project->logo1) }}" alt="" style="height: 38px; object-fit: contain;">
                @else
                    <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="" style="height: 38px; object-fit: contain;">
                @endif
                @if(isset($project) && $project->logo2)
                    <img src="{{ asset('uploads/' . $project->logo2) }}" alt="" style="height: 38px; object-fit: contain;">
                @else
                    <img src="{{ asset('assets/site/images/indh_logo.png') }}" alt="" style="height: 38px; object-fit: contain;">
                @endif
                @if(isset($project) && $project->logo3)
                    <img src="{{ asset('uploads/' . $project->logo3) }}" alt="" style="height: 38px; object-fit: contain;">
                @else
                    <img src="{{ asset('assets/site/images/logo_zettat.png') }}" alt="" style="height: 38px; object-fit: contain;">
                @endif
            </div>

        </div>{{-- end main card --}}

        <p class="text-center mt-4 text-xs text-gray-400">
            &copy; {{ date('Y') }} {{ $tr('Tous droits réservés', 'جميع الحقوق محفوظة') }} —
            <a href="https://www.iuhm.org" target="_blank" style="color: {{ $form->color }}; text-decoration: none;">initiative urbaine hay mohammadi</a>
        </p>

    </div>{{-- end page wrapper --}}

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('scroll-to-top', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>

</div>