@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
    $displayLabel = fn ($fr, $ar = null) => $isArabic && filled($ar) ? $ar : $fr;
    $fieldNum = 0;
@endphp

<div @if($isArabic) dir="rtl" @endif style="min-height:100vh; background:#f4f5f7; padding: 2rem 1rem;">

    {{-- Page wrapper: centered card --}}
    <div style="max-width:720px; margin:0 auto;">

        {{-- Flash Messages --}}
        @if(session()->has('success'))
        <div class="d-flex align-items-center gap-2 rounded-4 px-4 py-3 mb-3" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;">
            <i class="ri-checkbox-circle-fill fs-5"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session()->has('error'))
        <div class="d-flex align-items-center gap-2 rounded-4 px-4 py-3 mb-3" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;">
            <i class="ri-error-warning-fill fs-5"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        {{-- Draft Banner --}}
        @if($submissionId && !$isReadOnly)
        <div class="d-flex align-items-center gap-2 rounded-4 px-4 py-2 mb-3" style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;font-size:.82rem;">
            <i class="ri-save-3-line"></i>
            <span>{{ $tr('Brouillon — votre progression est sauvegardée automatiquement.', 'مسودة — يتم حفظ تقدمك تلقائيًا.') }}</span>
        </div>
        @endif

        {{-- Read-Only Banner --}}
        @if($isReadOnly)
        <div class="d-flex align-items-center gap-2 rounded-4 px-4 py-2 mb-3" style="background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;font-size:.82rem;">
            <i class="ri-eye-line"></i>
            <span>{{ $tr('Mode lecture seule — ce formulaire a déjà été soumis.', 'وضع القراءة فقط — تم إرسال هذه الاستمارة مسبقًا.') }}</span>
        </div>
        @endif

        {{-- Main card --}}
        <div class="rounded-4" style="background:#fff;box-shadow:0 4px 24px rgba(0,0,0,.08);overflow:hidden;">

            {{-- Header --}}
            <div class="text-center px-5 pt-5 pb-4" style="border-bottom:1px solid #f1f5f9;">
                <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:64px;height:64px;background:{{ $form->color }}18;color:{{ $form->color }};font-size:1.8rem;">
                    <i class="{{ $form->icon }}"></i>
                </div>
                <h2 class="fw-bold mb-2" style="color:#0f172a;font-size:clamp(1.2rem,3vw,1.6rem);">
                    {{ $displayLabel($form->title, $form->title_ar) }}
                </h2>
                @if($form->introduction && $step == 1)
                <p style="color:#6b7280;font-size:.88rem;line-height:1.7;max-width:520px;margin:0 auto;">
                    {{ $displayLabel($form->introduction, $form->introduction_ar) }}
                </p>
                @endif
            </div>

            {{-- Step progress --}}
            @if($form->has_steps && $totalSteps > 1)
            <div class="px-5 py-3" style="background:#f8fafc;border-bottom:1px solid #f1f5f9;">
                <div class="d-flex align-items-center gap-0">
                    @for($i = 1; $i <= $totalSteps; $i++)
                        <div class="d-flex align-items-center flex-grow-1">
                            <div class="d-flex flex-column align-items-center" style="min-width:40px;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                     style="width:36px;height:36px;font-size:.78rem;
                                            background:{{ $step > $i ? $form->color : ($step == $i ? $form->color : '#e5e7eb') }};
                                            color:{{ $step >= $i ? '#fff' : '#9ca3af' }};
                                            transition:.3s;">
                                    @if($step > $i)
                                        <i class="ri-check-line"></i>
                                    @else
                                        {{ $i }}
                                    @endif
                                </div>
                            </div>
                            @if($i < $totalSteps)
                                <div class="flex-grow-1" style="height:3px;background:{{ $step > $i ? $form->color : '#e5e7eb' }};transition:.3s;margin: 0 4px;"></div>
                            @endif
                        </div>
                    @endfor
                </div>
                @if($currentStep)
                <p class="mb-0 mt-2 fw-semibold text-center" style="font-size:.8rem;color:#6b7280;">
                    {{ $tr('Étape', 'الخطوة') }} {{ $step }}/{{ $totalSteps }}
                    @if($currentStep->title) — {{ $displayLabel($currentStep->title, $currentStep->title_ar) }} @endif
                </p>
                @endif
            </div>
            @endif

            {{-- Form body --}}
            @if($currentStep)
            <div class="px-4 px-md-5 py-4">
                @php $fieldNum = 0; @endphp

                {{-- Step description --}}
                @if($currentStep->description)
                <p class="mb-4" style="color:#6b7280;font-size:.85rem;line-height:1.7;">{{ $currentStep->description }}</p>
                @endif

                {{-- Fields --}}
                @foreach($currentStep->fields->sortBy('sort_order') as $field)

                    @if($field->type === 'heading')
                        {{-- Section heading with left border accent --}}
                        <div class="d-flex align-items-center gap-3 mt-4 mb-3">
                            <div style="width:4px;height:28px;background:{{ $form->color }};border-radius:2px;flex-shrink:0;"></div>
                            <h5 class="fw-bold mb-0" style="color:#0f172a;font-size:1rem;">
                                {{ $displayLabel($field->label, $field->label_ar) }}
                            </h5>
                        </div>

                    @elseif($field->type === 'paragraph')
                        <p class="mb-3" style="color:#6b7280;font-size:.85rem;line-height:1.7;">
                            {{ $displayLabel($field->label, $field->label_ar) }}
                        </p>

                    @else
                        @php $fieldNum++; @endphp
                        <div class="mb-4">
                            <label class="d-block fw-semibold mb-2" for="field_{{ $field->id }}"
                                   style="color:#1e293b;font-size:.88rem;">
                                {{ $fieldNum }}. {{ $displayLabel($field->label, $field->label_ar) }}
                                @if($field->is_required)<span style="color:#ef4444;margin-left:2px;">*</span>@endif
                            </label>

                            @if($field->type === 'text')
                                <input type="text" id="field_{{ $field->id }}"
                                    wire:model="answers.{{ $field->id }}"
                                    class="form-control rounded-3 border-0"
                                    style="background:#f5f6fa;padding:.75rem 1rem;font-size:.88rem;color:#374151;"
                                    placeholder="{{ $field->placeholder }}"
                                    @if($isReadOnly) readonly @endif>

                            @elseif($field->type === 'textarea')
                                <textarea id="field_{{ $field->id }}"
                                    wire:model="answers.{{ $field->id }}"
                                    class="form-control rounded-3 border-0"
                                    style="background:#f5f6fa;padding:.75rem 1rem;font-size:.88rem;color:#374151;min-height:110px;resize:vertical;"
                                    placeholder="{{ $field->placeholder }}"
                                    @if($isReadOnly) readonly @endif></textarea>

                            @elseif($field->type === 'number')
                                <input type="number" id="field_{{ $field->id }}"
                                    wire:model="answers.{{ $field->id }}"
                                    class="form-control rounded-3 border-0"
                                    style="background:#f5f6fa;padding:.75rem 1rem;font-size:.88rem;color:#374151;"
                                    placeholder="{{ $field->placeholder }}"
                                    @if($isReadOnly) readonly @endif>

                            @elseif($field->type === 'email')
                                <input type="email" id="field_{{ $field->id }}"
                                    wire:model="answers.{{ $field->id }}"
                                    class="form-control rounded-3 border-0"
                                    style="background:#f5f6fa;padding:.75rem 1rem;font-size:.88rem;color:#374151;"
                                    placeholder="{{ $field->placeholder }}"
                                    @if($isReadOnly) readonly @endif>

                            @elseif($field->type === 'date')
                                <input type="date" id="field_{{ $field->id }}"
                                    wire:model="answers.{{ $field->id }}"
                                    class="form-control rounded-3 border-0"
                                    style="background:#f5f6fa;padding:.75rem 1rem;font-size:.88rem;color:#374151;"
                                    @if($isReadOnly) readonly @endif>

                            @elseif($field->type === 'select')
                                <select id="field_{{ $field->id }}"
                                    wire:model="answers.{{ $field->id }}"
                                    class="form-select rounded-3 border-0"
                                    style="background:#f5f6fa;padding:.75rem 1rem;font-size:.88rem;color:#374151;"
                                    @if($isReadOnly) disabled @endif>
                                    <option value="">{{ $field->placeholder ?: $tr('Sélectionner...', 'اختر...') }}</option>
                                    @foreach($field->options ?? [] as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>

                            @elseif($field->type === 'radio')
                                <div class="d-flex flex-wrap gap-3 mt-1">
                                    @foreach($field->options ?? [] as $opt)
                                        <label class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                                               style="background:#f5f6fa;cursor:pointer;font-size:.86rem;color:#374151;">
                                            <input type="radio"
                                                wire:model="answers.{{ $field->id }}"
                                                value="{{ $opt }}"
                                                style="accent-color:{{ $form->color }};"
                                                @if($isReadOnly) disabled @endif>
                                            {{ $opt }}
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($field->type === 'checkbox')
                                <div class="d-flex flex-wrap gap-3 mt-1">
                                    @foreach($field->options ?? [] as $opt)
                                        <label class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                                               style="background:#f5f6fa;cursor:pointer;font-size:.86rem;color:#374151;">
                                            <input type="checkbox"
                                                wire:model="answers.{{ $field->id }}"
                                                value="{{ $opt }}"
                                                style="accent-color:{{ $form->color }};"
                                                @if($isReadOnly) disabled @endif>
                                            {{ $opt }}
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($field->type === 'file')
                                <div class="rounded-3 p-3" style="background:#f5f6fa;border:2px dashed #d1d5db;">
                                    <input type="file" id="field_{{ $field->id }}"
                                        wire:model="answers.{{ $field->id }}"
                                        @if($field->allow_multiple_files) multiple @endif
                                        accept=".pdf,.xls,.xlsx,.csv,.doc,.docx,.jpg,.jpeg,.png,.gif,.webp"
                                        class="form-control border-0 bg-transparent p-0"
                                        style="font-size:.85rem;"
                                        @if($isReadOnly) disabled @endif>
                                    <p class="mb-0 mt-2" style="font-size:.75rem;color:#9ca3af;">
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
                                <div class="mt-2 d-flex flex-column gap-1">
                                    @foreach($existingPaths as $path)
                                        @php $cleanPath = ltrim(str_starts_with($path,'uploads/') ? substr($path,8) : $path,'/'); @endphp
                                        <div class="d-flex align-items-center gap-3 rounded-3 px-3 py-2" style="background:#f0fdf4;font-size:.82rem;">
                                            <i class="ri-file-line" style="color:{{ $form->color }};"></i>
                                            <span class="flex-grow-1 text-truncate" style="color:#374151;">{{ basename($cleanPath) }}</span>
                                            <a href="{{ route('uploads.show', ['path' => $cleanPath]) }}" target="_blank" style="color:#2563eb;text-decoration:none;">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('uploads.download', ['path' => $cleanPath]) }}" style="color:#0f766e;text-decoration:none;">
                                                <i class="ri-download-2-line"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                            @endif

                            @if($field->help_text)
                                <p class="mb-0 mt-1" style="font-size:.75rem;color:#9ca3af;">
                                    <i class="ri-information-line me-1"></i>{{ $field->help_text }}
                                </p>
                            @endif

                            @error('answers.' . $field->id)
                                <p class="mb-0 mt-1" style="font-size:.78rem;color:#dc2626;">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    @endif

                @endforeach

                {{-- Tables --}}
                @foreach($currentStep->tables->sortBy('sort_order') as $table)
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width:4px;height:24px;background:{{ $form->color }};border-radius:2px;flex-shrink:0;"></div>
                        <p class="fw-semibold mb-0" style="color:#1e293b;font-size:.9rem;">
                            {{ $displayLabel($table->title, $table->title_ar) }}
                        </p>
                    </div>

                    @if($table->columns->isNotEmpty())
                    <div class="rounded-3 overflow-hidden" style="border:1px solid #e9ecef;">
                        <div class="table-responsive">
                        <table class="table mb-0" style="font-size:.82rem;">
                            <thead>
                                <tr style="background:#0f2441;">
                                    @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                        <th class="px-3 py-2 fw-semibold border-0" style="color:#fff;white-space:nowrap;"></th>
                                    @endif
                                    @foreach($table->columns->sortBy('sort_order') as $col)
                                        <th class="px-3 py-2 fw-semibold border-0" style="color:#fff;white-space:nowrap;" @if($col->width) style="width:{{ $col->width }};" @endif>
                                            {{ $displayLabel($col->header, $col->header_ar) }}
                                        </th>
                                    @endforeach
                                    @if($table->has_dynamic_rows && !$isReadOnly)
                                        <th class="border-0" style="width:44px;"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                    @foreach($table->fixedRows->sortBy('sort_order') as $ri => $row)
                                    <tr style="background:{{ $ri % 2 === 0 ? '#fff' : '#f9fafb' }};">
                                        <td class="px-3 py-2 fw-semibold" style="color:#374151;white-space:nowrap;border-color:#f1f5f9;">
                                            {{ $displayLabel($row->label, $row->label_ar) }}
                                        </td>
                                        @foreach($table->columns->sortBy('sort_order') as $col)
                                        <td class="px-2 py-1" style="border-color:#f1f5f9;">
                                            @if($col->input_type === 'checkbox')
                                                <div class="text-center">
                                                    <input type="checkbox"
                                                        wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                        style="accent-color:{{ $form->color }};"
                                                        @if($isReadOnly) disabled @endif>
                                                </div>
                                            @elseif($col->input_type === 'radio')
                                                <div class="text-center">
                                                    <input type="radio"
                                                        wire:model="tableData.{{ $table->table_key }}.{{ $ri }}._radio"
                                                        value="{{ $col->column_key }}"
                                                        name="radio_{{ $table->table_key }}_{{ $ri }}"
                                                        style="accent-color:{{ $form->color }};"
                                                        @if($isReadOnly) disabled @endif>
                                                </div>
                                            @elseif($col->input_type === 'number')
                                                <input type="number"
                                                    wire:model.live="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                    class="form-control form-control-sm rounded-2 border-0"
                                                    style="background:#f5f6fa;font-size:.82rem;"
                                                    @if($isReadOnly) readonly @endif>
                                            @elseif($col->input_type === 'select')
                                                <select wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                    class="form-select form-select-sm rounded-2 border-0"
                                                    style="background:#f5f6fa;font-size:.82rem;"
                                                    @if($isReadOnly) disabled @endif>
                                                    <option value="">--</option>
                                                    @foreach($col->options ?? [] as $opt)
                                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif($col->input_type === 'readonly')
                                                <input type="text" readonly
                                                    class="form-control form-control-sm rounded-2 border-0"
                                                    style="background:#e9ecef;font-size:.82rem;"
                                                    value="{{ $tableData[$table->table_key][$ri][$col->column_key] ?? '' }}">
                                            @else
                                                <input type="text"
                                                    wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                    class="form-control form-control-sm rounded-2 border-0"
                                                    style="background:#f5f6fa;font-size:.82rem;"
                                                    @if($isReadOnly) readonly @endif>
                                            @endif
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                @else
                                    @for($ri = 0; $ri < ($tableRowCounts[$table->table_key] ?? $table->min_rows); $ri++)
                                    <tr style="background:{{ $ri % 2 === 0 ? '#fff' : '#f9fafb' }};">
                                        @foreach($table->columns->sortBy('sort_order') as $col)
                                        <td class="px-2 py-1" style="border-color:#f1f5f9;">
                                            @if($col->input_type === 'checkbox')
                                                <div class="text-center">
                                                    <input type="checkbox"
                                                        wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                        style="accent-color:{{ $form->color }};"
                                                        @if($isReadOnly) disabled @endif>
                                                </div>
                                            @elseif($col->input_type === 'radio')
                                                <div class="text-center">
                                                    <input type="radio"
                                                        wire:model="tableData.{{ $table->table_key }}.{{ $ri }}._radio"
                                                        value="{{ $col->column_key }}"
                                                        name="radio_{{ $table->table_key }}_{{ $ri }}"
                                                        style="accent-color:{{ $form->color }};"
                                                        @if($isReadOnly) disabled @endif>
                                                </div>
                                            @elseif($col->input_type === 'number')
                                                <input type="number"
                                                    wire:model.live="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                    class="form-control form-control-sm rounded-2 border-0"
                                                    style="background:#f5f6fa;font-size:.82rem;"
                                                    @if($isReadOnly) readonly @endif>
                                            @elseif($col->input_type === 'select')
                                                <select wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                    class="form-select form-select-sm rounded-2 border-0"
                                                    style="background:#f5f6fa;font-size:.82rem;"
                                                    @if($isReadOnly) disabled @endif>
                                                    <option value="">--</option>
                                                    @foreach($col->options ?? [] as $opt)
                                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="text"
                                                    wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}"
                                                    class="form-control form-control-sm rounded-2 border-0"
                                                    style="background:#f5f6fa;font-size:.82rem;"
                                                    @if($isReadOnly) readonly @endif>
                                            @endif
                                        </td>
                                        @endforeach
                                        @if($table->has_dynamic_rows && !$isReadOnly)
                                        <td class="px-2 py-1 text-center" style="border-color:#f1f5f9;">
                                            @if(($tableRowCounts[$table->table_key] ?? $table->min_rows) > $table->min_rows)
                                                <button wire:click="removeTableRow('{{ $table->table_key }}', {{ $ri }})"
                                                    class="btn btn-sm border-0 p-1"
                                                    style="color:#ef4444;background:transparent;" title="{{ $tr('Supprimer', 'حذف') }}">
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
                                <tr style="background:#f0f9ff;font-weight:700;">
                                    @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                        <td class="px-3 py-2" style="color:#1e293b;border-color:#e9ecef;">{{ $tr('Total', 'المجموع') }}</td>
                                    @endif
                                    @foreach($table->columns->sortBy('sort_order') as $col)
                                    <td class="px-3 py-2" style="color:#1e293b;border-color:#e9ecef;">
                                        @if($col->is_totaled)
                                            {{ number_format($this->getTableTotal($table->table_key, $col->column_key), 2) }}
                                        @endif
                                    </td>
                                    @endforeach
                                    @if($table->has_dynamic_rows && !$isReadOnly)
                                        <td style="border-color:#e9ecef;"></td>
                                    @endif
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        </div>
                    </div>

                    @if($table->has_dynamic_rows && !$isReadOnly)
                    <button wire:click="addTableRow('{{ $table->table_key }}')"
                            class="btn btn-sm rounded-3 mt-2 d-inline-flex align-items-center gap-1 fw-semibold"
                            style="border:1px dashed {{ $form->color }};background:transparent;color:{{ $form->color }};font-size:.8rem;">
                        <i class="ri-add-line"></i> {{ $tr('Ajouter une ligne', 'إضافة سطر') }}
                    </button>
                    @endif
                </div>
                @endforeach

            </div>
            @endif

            {{-- Navigation Buttons --}}
            <div class="d-flex align-items-center justify-content-between gap-3 px-4 px-md-5 py-4"
                 style="border-top:1px solid #f1f5f9;background:#fafafa;">

                <div class="d-flex gap-2">
                    @if($step > 1)
                    <button wire:click="back"
                            class="btn rounded-3 fw-semibold d-inline-flex align-items-center gap-2"
                            style="border:1px solid #e2e8f0;background:#fff;color:#374151;font-size:.85rem;">
                        <i class="ri-arrow-left-s-line fs-5"></i> {{ $tr('Précédent', 'السابق') }}
                    </button>
                    @endif

                    @if(!$isReadOnly && $step < $totalSteps)
                    <button wire:click="saveAsDraft"
                            class="btn rounded-3 fw-semibold d-inline-flex align-items-center gap-2"
                            style="border:1px solid #e2e8f0;background:#fff;color:#6b7280;font-size:.85rem;">
                        <i class="ri-save-3-line"></i> {{ $tr('Sauvegarder', 'حفظ') }}
                    </button>
                    @endif

                    @if($step > 1)
                    <a href="{{ url()->previous() }}"
                       class="btn rounded-3 fw-semibold d-inline-flex align-items-center gap-2"
                       style="border:1px solid #e2e8f0;background:#fff;color:#6b7280;font-size:.85rem;">
                        <i class="ri-close-line"></i> {{ $tr('Retour', 'رجوع') }}
                    </a>
                    @endif
                </div>

                <div class="d-flex gap-2 align-items-center">
                    @if($totalSteps > 1)
                    <span style="font-size:.78rem;color:#9ca3af;">{{ $step }}/{{ $totalSteps }}</span>
                    @endif

                    @if($step < $totalSteps)
                    <button wire:click="next"
                            wire:loading.attr="disabled"
                            class="btn rounded-3 fw-semibold d-inline-flex align-items-center gap-2"
                            style="background:{{ $form->color }};color:#fff;border:0;font-size:.85rem;">
                        <span wire:loading.remove wire:target="next">
                            {{ $tr('Suivant', 'التالي') }} <i class="ri-arrow-right-s-line fs-5"></i>
                        </span>
                        <span wire:loading wire:target="next">
                            <span class="spinner-border spinner-border-sm" style="width:.8rem;height:.8rem;"></span>
                        </span>
                    </button>
                    @endif

                    @if($step == $totalSteps && !$isReadOnly)
                    <button wire:click="submit"
                            wire:loading.attr="disabled"
                            class="btn rounded-3 fw-bold d-inline-flex align-items-center gap-2"
                            style="background:#0f2441;color:#fff;border:0;font-size:.88rem;padding:.55rem 1.4rem;">
                        <span wire:loading.remove wire:target="submit">
                            <i class="ri-send-plane-fill me-1"></i> {{ $tr('Soumettre', 'إرسال') }}
                        </span>
                        <span wire:loading wire:target="submit">
                            <span class="spinner-border spinner-border-sm" style="width:.8rem;height:.8rem;"></span>
                            {{ $tr('Envoi...', 'جاري الإرسال...') }}
                        </span>
                    </button>
                    @endif
                </div>
            </div>

            {{-- Footer logos --}}
            <div class="d-flex align-items-center justify-content-center gap-4 px-4 py-3"
                 style="border-top:1px solid #f1f5f9;">
                @if(isset($project) && $project->logo1)
                    <img src="{{ asset('uploads/' . $project->logo1) }}" alt="" style="height:38px;object-fit:contain;">
                @else
                    <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="" style="height:38px;object-fit:contain;">
                @endif
                @if(isset($project) && $project->logo2)
                    <img src="{{ asset('uploads/' . $project->logo2) }}" alt="" style="height:38px;object-fit:contain;">
                @else
                    <img src="{{ asset('assets/site/images/indh_logo.png') }}" alt="" style="height:38px;object-fit:contain;">
                @endif
                @if(isset($project) && $project->logo3)
                    <img src="{{ asset('uploads/' . $project->logo3) }}" alt="" style="height:38px;object-fit:contain;">
                @else
                    <img src="{{ asset('assets/site/images/logo_zettat.png') }}" alt="" style="height:38px;object-fit:contain;">
                @endif
            </div>

        </div>{{-- end main card --}}

        <p class="text-center mt-3" style="font-size:.75rem;color:#9ca3af;">
            &copy; {{ date('Y') }} {{ $tr('Tous droits réservés', 'جميع الحقوق محفوظة') }} —
            <a href="https://www.iuhm.org" target="_blank" style="color:{{ $form->color }};text-decoration:none;">initiative urbaine hay mohammadi</a>
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