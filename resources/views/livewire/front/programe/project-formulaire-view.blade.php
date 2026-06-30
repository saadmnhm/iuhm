@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
    $displayLabel = fn ($fr, $ar = null) => $isArabic && filled($ar) ? $ar : $fr;
    $fColor = $form->color ?? '#2f5496';
    if (! str_starts_with($fColor, '#')) {
        $fColor = '#' . $fColor;
    }
@endphp

<div x-data="{
    showSubmitConfirm: false,
    openSubmitConfirm() { this.showSubmitConfirm = true; },
    closeSubmitConfirm() { this.showSubmitConfirm = false; },
    confirmSubmit() {
        this.showSubmitConfirm = false;
        this.$dispatch('submit-confirmed');
    }
}" @if($isArabic) dir="rtl" @endif >

    {{-- ===== FORM CARD ===== --}}
    <div >

        {{-- ---- HEADER ---- --}}
        <div style="padding:1.5rem 2rem 1.25rem;border-bottom:1px solid #f1f5f9;flex-shrink:0;">

            {{-- Flash --}}
            @if(session()->has('message') || session()->has('success'))
            <div class="d-flex align-items-center gap-2 rounded-3 px-3 py-2 mb-3" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;font-size:.82rem;">
                <i class="ri-checkbox-circle-fill"></i><span>{{ session('message') ?? session('success') }}</span>
            </div>
            @endif
            @if(session()->has('error'))
            <div class="d-flex align-items-center gap-2 rounded-3 px-3 py-2 mb-3" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;font-size:.82rem;">
                <i class="ri-error-warning-fill"></i><span>{{ session('error') }}</span>
            </div>
            @endif

            {{-- Title --}}
            <h3 class="fw-bold text-center mb-1" style="color:#0f172a;font-size:clamp(1.1rem,2.5vw,1.45rem);">
                {{ $displayLabel($form->title, $form->title_ar) }}
            </h3>

            @if($form->description)
            <p class="text-center mb-0" style="color:#6b7280;font-size:.83rem;line-height:1.6;">
                {{ $displayLabel($form->description, $form->description_ar) }}
            </p>
            @endif

            {{-- Status banners --}}
            @if($isReadOnly && $existingSubmission)
            <div class="d-flex align-items-center gap-2 rounded-3 px-3 py-2 mt-2" style="background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;font-size:.8rem;">
                <i class="ri-eye-line"></i>
                <span class="fw-semibold">{{ $tr('Mode lecture seule','وضع القراءة فقط') }}</span>
                &middot;
                {{ [
                    'draft'     => $tr('Brouillon','مسودة'),
                    'submitted' => $tr('Soumis','مرسل'),
                    'in_review' => $tr('En révision','قيد المراجعة'),
                    'approved'  => $tr('Approuvé','مقبول'),
                    'refused'   => $tr('Refusé','مرفوض'),
                    'rejected'  => $tr('Rejeté','مرفوض'),
                ][$existingSubmission->status] ?? ucfirst($existingSubmission->status) }}
                @if($existingSubmission->submitted_at) &middot; {{ $existingSubmission->submitted_at->format('d/m/Y') }} @endif
            </div>
            @endif
            @if($submissionId && !$isReadOnly)
            <div class="d-flex align-items-center gap-2 rounded-3 px-3 py-2 mt-2" style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;font-size:.79rem;">
                <i class="ri-save-3-line"></i><span>{{ $tr('Brouillon sauvegardé','مسودة محفوظة') }}</span>
            </div>
            @endif
        </div>

        {{-- ---- SCROLLABLE BODY ---- --}}
        <div style="flex:1;padding:1.5rem 2rem;">

            {{-- INTRODUCTION PAGE --}}
            @if($showIntroduction && $formulaire->has_introduction)
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3"
                     style="width:56px;height:56px;background:{{ $fColor }}18;color:{{ $fColor }};font-size:1.6rem;">
                    <i class="ri-book-open-line"></i>
                </div>
                <h5 class="fw-bold" style="color:#0f172a;">
                    {{ $displayLabel($formulaire->introduction_title, $formulaire->introduction_title_ar) }}
                </h5>
            </div>
            <div class="rounded-3 p-4 mb-4" style="background:#f8fafc;border-left:4px solid {{ $fColor }};color:#374151;font-size:.87rem;line-height:1.9;">
                {!! nl2br(e($displayLabel($formulaire->introduction_content, $formulaire->introduction_content_ar))) !!}
            </div>

            {{-- FORM FIELDS --}}
            @else
            @if($currentStepData)

                @if($currentStepData->description)
                <p class="mb-4" style="color:#6b7280;font-size:.84rem;line-height:1.6;">{{ $currentStepData->description }}</p>
                @endif

                {{-- ---- FIELDS ---- --}}
                @php $fieldNum = 0; $halfFields = []; @endphp

                @foreach($currentStepData->fields->sortBy('sort_order') as $field)

                    {{-- HEADING --}}
                    @if($field->type === 'heading')
                    <div class="d-flex align-items-center gap-3 mt-4 mb-3">
                        <div style="width:4px;height:26px;background:{{ $fColor }};border-radius:2px;flex-shrink:0;"></div>
                        <h6 class="fw-bold mb-0" style="color:#0f172a;font-size:.95rem;">
                            {{ $displayLabel($field->label, $field->label_ar) }}
                        </h6>
                    </div>

                    {{-- PARAGRAPH --}}
                    @elseif($field->type === 'paragraph')
                    <p class="mb-3" style="color:#6b7280;font-size:.84rem;line-height:1.6;">
                        {{ $displayLabel($field->label, $field->label_ar) }}
                    </p>

                    {{-- REGULAR FIELDS --}}
                    @else
                    @php $fieldNum++; $isHalf = !$field->is_full_width; @endphp

                    <div class="{{ $isHalf ? 'd-inline-block pe-2 align-top' : 'w-100' }} mb-4"
                         style="{{ $isHalf ? 'width:48%;' : '' }}">
                        <label class="d-block fw-semibold mb-2" for="f_{{ $field->id }}"
                               style="color:#1e293b;font-size:.86rem;">
                            {{ $fieldNum }}. {{ $displayLabel($field->label, $field->label_ar) }}
                            @if($field->is_required)<span style="color:#ef4444;margin-left:2px;">*</span>@endif
                        </label>

                        @if($field->type === 'text')
                            <input type="text" id="f_{{ $field->id }}" wire:model="answers.{{ $field->id }}"
                                   class="form-control iuhm_input rounded-3 border-0"
                                   placeholder="{{ $field->placeholder }}"
                                   @if($isReadOnly) readonly @endif>

                        @elseif($field->type === 'textarea')
                            <textarea id="f_{{ $field->id }}" wire:model="answers.{{ $field->id }}"
                                      class="form-control iuhm_textarea rounded-3 border-0"
                                      placeholder="{{ $field->placeholder }}"
                                      @if($isReadOnly) readonly @endif></textarea>

                        @elseif($field->type === 'number')
                            <input type="number" id="f_{{ $field->id }}" wire:model="answers.{{ $field->id }}"
                                   class="form-control iuhm_input rounded-3 border-0"
                                   placeholder="{{ $field->placeholder }}"
                                   @if($isReadOnly) readonly @endif>

                        @elseif($field->type === 'email')
                            <input type="email" id="f_{{ $field->id }}" wire:model="answers.{{ $field->id }}"
                                   class="form-control iuhm_input rounded-3 border-0"
                                   placeholder="{{ $field->placeholder }}"
                                   @if($isReadOnly) readonly @endif>

                        @elseif($field->type === 'date')
                            <input type="date" id="f_{{ $field->id }}" wire:model="answers.{{ $field->id }}"
                                   class="form-control iuhm_input rounded-3 border-0"
                                   @if($isReadOnly) readonly @endif>

                        @elseif($field->type === 'select')
                            <select id="f_{{ $field->id }}" wire:model="answers.{{ $field->id }}"
                                    class="form-select iuhm_select rounded-3 border-0"
                                    @if($isReadOnly) disabled @endif>
                                <option value="">{{ $field->placeholder ?: $tr('Sélectionner...','اختر...') }}</option>
                                @foreach($field->options ?? [] as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>

                        @elseif($field->type === 'radio')
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                @foreach($field->options ?? [] as $opt)
                                    <label class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                                           style="background:#f5f6fa;cursor:pointer;font-size:.84rem;color:#374151;">
                                        <input type="radio" wire:model="answers.{{ $field->id }}" value="{{ $opt }}"
                                               style="accent-color:{{ $fColor }};" @if($isReadOnly) disabled @endif>
                                        {{ $opt }}
                                    </label>
                                @endforeach
                            </div>

                        @elseif($field->type === 'checkbox')
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                @foreach($field->options ?? [] as $opt)
                                    <label class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                                           style="background:#f5f6fa;cursor:pointer;font-size:.84rem;color:#374151;">
                                        <input type="checkbox" wire:model="answers.{{ $field->id }}" value="{{ $opt }}"
                                               style="accent-color:{{ $fColor }};" @if($isReadOnly) disabled @endif>
                                        {{ $opt }}
                                    </label>
                                @endforeach
                            </div>

                        @elseif($field->type === 'file')
                            <div class="rounded-3 p-3" style="background:#f5f6fa;border:2px dashed #d1d5db;">
                                <input type="file" id="f_{{ $field->id }}" wire:model="answers.{{ $field->id }}"
                                       @if($field->allow_multiple_files) multiple @endif
                                       accept=".pdf,.xls,.xlsx,.csv,.doc,.docx,.jpg,.jpeg,.png,.gif,.webp"
                                       class="form-control border-0 bg-transparent p-0"
                                       style="font-size:.84rem;" @if($isReadOnly) disabled @endif>
                                <p class="mb-0 mt-1" style="font-size:.73rem;color:#9ca3af;">
                                    {{ $field->allow_multiple_files ? $tr('Plusieurs fichiers autorisés.','يُسمح بملفات متعددة.') : $tr('Un seul fichier.','ملف واحد فقط.') }}
                                    PDF, Excel, DOC, images — max 10MB
                                </p>
                            </div>
                            @php
                                $rawFileValue = $answers[$field->id] ?? null;
                                $existingPaths = [];
                                if (is_string($rawFileValue) && trim($rawFileValue) !== '') {
                                    $decoded = json_decode($rawFileValue, true);
                                    if (is_array($decoded)) {
                                        $existingPaths = collect($decoded)->filter(fn($p)=>is_string($p)&&trim($p)!=='')->values()->all();
                                    } else { $existingPaths = [trim($rawFileValue)]; }
                                } elseif (is_array($rawFileValue)) {
                                    $existingPaths = collect($rawFileValue)->filter(fn($p)=>is_string($p)&&trim($p)!=='')->values()->all();
                                }
                            @endphp
                            @if(!empty($existingPaths))
                            <div class="mt-2 d-flex flex-column gap-1">
                                @foreach($existingPaths as $path)
                                @php $cleanPath = ltrim(str_starts_with($path,'uploads/') ? substr($path,8) : $path,'/'); @endphp
                                <div class="d-flex align-items-center gap-3 rounded-3 px-3 py-2"
                                     style="background:#f0fdf4;font-size:.8rem;">
                                    <i class="ri-file-line" style="color:{{ $fColor }};"></i>
                                    <span class="flex-grow-1 text-truncate" style="color:#374151;">{{ basename($cleanPath) }}</span>
                                    <a href="{{ route('uploads.show', ['path' => $cleanPath]) }}" target="_blank" style="color:#2563eb;text-decoration:none;"><i class="ri-eye-line"></i></a>
                                    <a href="{{ route('uploads.download', ['path' => $cleanPath]) }}" style="color:#0f766e;text-decoration:none;"><i class="ri-download-2-line"></i></a>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        @endif

                        @if($field->help_text)
                            <p class="mb-0 mt-1" style="font-size:.73rem;color:#9ca3af;"><i class="ri-information-line me-1"></i>{{ $field->help_text }}</p>
                        @endif
                        @error('answers.' . $field->id)
                            <p class="mb-0 mt-1" style="font-size:.76rem;color:#dc2626;"><i class="ri-error-warning-line me-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                @endforeach

                {{-- ---- TABLES ---- --}}
                @foreach($currentStepData->tables->sortBy('sort_order') as $table)
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width:4px;height:22px;background:{{ $fColor }};border-radius:2px;flex-shrink:0;"></div>
                        <p class="fw-semibold mb-0" style="color:#1e293b;font-size:.88rem;">{{ $displayLabel($table->title, $table->title_ar) }}</p>
                    </div>
                    @if($table->columns->isNotEmpty())
                    <div class="rounded-3 overflow-hidden" style="border:1px solid #e9ecef;">
                        <div class="table-responsive">
                        <table class="table mb-0" style="font-size:.8rem;">
                            <thead>
                                <tr style="background:#0f2441;">
                                    @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                        <th class="px-3 py-2 fw-semibold border-0" style="color:#fff;"></th>
                                    @endif
                                    @foreach($table->columns->sortBy('sort_order') as $col)
                                        <th class="pt-3 pb-3 text-center text-[14px] font-black text-slate-900 " >
                                            {{ $displayLabel($col->header, $col->header_ar) }}
                                        </th>
                                    @endforeach
                                    @if($table->has_dynamic_rows && !$isReadOnly)
                                        <th class="border-0" style="width:40px;"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                    @foreach($table->fixedRows->sortBy('sort_order') as $ri => $row)
                                    <tr style="background:{{ $ri%2===0?'#fff':'#f9fafb' }};">
                                        <td class="text-[14px] font-black text-slate-900 text-center content-center " >{{ $displayLabel($row->label, $row->label_ar) }}</td>
                                        @foreach($table->columns->sortBy('sort_order') as $col)
                                        <td class="px-2 py-1" style="border-color:#f1f5f9;">
                                            @if($col->input_type==='checkbox')
                                                <div class="text-center"><input type="checkbox" wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" style="accent-color:{{ $fColor }};" @if($isReadOnly) disabled @endif></div>
                                            @elseif($col->input_type==='radio')
                                                <div class="text-center"><input type="radio" wire:model="tableData.{{ $table->table_key }}.{{ $ri }}._radio" value="{{ $col->column_key }}" name="radio_{{ $table->table_key }}_{{ $ri }}" style="accent-color:{{ $fColor }};" @if($isReadOnly) disabled @endif></div>
                                            @elseif($col->input_type==='number')
                                                <input type="number" wire:model.live="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" class="form-control iuhm_input form-control-sm rounded-2 border-0"  @if($isReadOnly) readonly @endif>
                                            @elseif($col->input_type==='select')
                                                <select wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" class="form-select form-select-sm rounded-2 iuhm_select border-0"  @if($isReadOnly) disabled @endif>
                                                    <option value="">--</option>
                                                    @foreach($col->options??[] as $opt)<option value="{{ $opt }}">{{ $opt }}</option>@endforeach
                                                </select>
                                            @elseif($col->input_type==='readonly')
                                                <input type="text" readonly class="form-control iuhm_input form-control-sm rounded-2 border-0" style="background:#e9ecef;" value="{{ $tableData[$table->table_key][$ri][$col->column_key]??'' }}">
                                            @else
                                                <input type="text" wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" class="form-control iuhm_input form-control-sm rounded-2 border-0"  @if($isReadOnly) readonly @endif>
                                            @endif
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                @else
                                    @for($ri = 0; $ri < ($tableRowCounts[$table->table_key] ?? $table->min_rows); $ri++)
                                    <tr style="background:{{ $ri%2===0?'#fff':'#f9fafb' }};">
                                        @foreach($table->columns->sortBy('sort_order') as $col)
                                        <td class="px-2 py-1" style="border-color:#f1f5f9;">
                                            @if($col->input_type==='checkbox')
                                                <div class="text-center"><input type="checkbox" wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" style="accent-color:{{ $fColor }};" @if($isReadOnly) disabled @endif></div>
                                            @elseif($col->input_type==='radio')
                                                <div class="text-center"><input type="radio" wire:model="tableData.{{ $table->table_key }}.{{ $ri }}._radio" value="{{ $col->column_key }}" name="radio_{{ $table->table_key }}_{{ $ri }}" style="accent-color:{{ $fColor }};" @if($isReadOnly) disabled @endif></div>
                                            @elseif($col->input_type==='number')
                                                <input type="number" wire:model.live="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" class="form-control iuhm_input form-control-sm rounded-2 border-0"  @if($isReadOnly) readonly @endif>
                                            @elseif($col->input_type==='select')
                                                <select wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" class="form-select form-select-sm rounded-2 iuhm_select border-0"  @if($isReadOnly) disabled @endif>
                                                    <option value="">--</option>
                                                    @foreach($col->options??[] as $opt)<option value="{{ $opt }}">{{ $opt }}</option>@endforeach
                                                </select>
                                            @else
                                                <input type="text" wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" class="form-control iuhm_input form-control-sm rounded-2 border-0"  @if($isReadOnly) readonly @endif>
                                            @endif
                                        </td>
                                        @endforeach
                                        @if($table->has_dynamic_rows && !$isReadOnly)
                                        <td class="px-1 py-1 text-center" style="border-color:#f1f5f9;">
                                            @if(($tableRowCounts[$table->table_key]??$table->min_rows) > $table->min_rows)
                                                <button wire:click="removeTableRow('{{ $table->table_key }}', {{ $ri }})" class="btn btn-sm border-0 p-1" style="color:#ef4444;background:transparent;"><i class="ri-delete-bin-line"></i></button>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                    @endfor
                                @endif
                                @if($table->has_total_row)
                                <tr style="background:#f0f9ff;font-weight:700;">
                                    @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                        <td class="px-3 py-2" style="border-color:#e9ecef;">{{ $tr('Total','المجموع') }}</td>
                                    @endif
                                    @foreach($table->columns->sortBy('sort_order') as $col)
                                    <td class="px-3 py-2" style="border-color:#e9ecef;">
                                        @if($col->is_totaled){{ number_format($this->getTableTotal($table->table_key, $col->column_key), 2) }}@endif
                                    </td>
                                    @endforeach
                                    @if($table->has_dynamic_rows && !$isReadOnly)<td style="border-color:#e9ecef;"></td>@endif
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        </div>
                    </div>
                    @if($table->has_dynamic_rows && !$isReadOnly)
                    <button wire:click="addTableRow('{{ $table->table_key }}')"
                            class="btn btn-sm rounded-3 mt-2 d-inline-flex align-items-center gap-1 fw-semibold"
                            style="border:1px dashed {{ $fColor }};background:transparent;color:{{ $fColor }};font-size:.78rem;">
                        <i class="ri-add-line"></i> {{ $tr('Ajouter une ligne','إضافة سطر') }}
                    </button>
                    @endif
                    @endif
                </div>
                @endforeach

            @endif
            @endif
            {{-- END introduction/else --}}

        </div>
        {{-- END scrollable body --}}

        {{-- ---- FOOTER: navigation & step pagination ---- --}}
        <div class="d-flex align-items-center justify-content-between gap-2 px-4 py-3"
             style="border-top:1px solid #f1f5f9;background:#fafafa;flex-shrink:0;">

            {{-- Left: back / save --}}
            <div class="d-flex gap-2 flex-wrap align-items-center">

                @if($showIntroduction && $formulaire->has_introduction)
                    {{-- intro page: only back to project --}}
                @elseif($currentStep > 1)
                    <button wire:click="previousStep"
                            class="btn btn-sm rounded-3 fw-semibold d-inline-flex align-items-center gap-1"
                            style="border:1px solid #e2e8f0;background:#fff;color:#374151;font-size:.82rem;">
                        <i class="ri-arrow-left-s-line fs-6"></i>{{ $tr('Précédent','السابق') }}
                    </button>
                @else
                    <a href="{{ route('user.project.detail', $projectId) }}"
                       class="btn btn-sm rounded-3 fw-semibold d-inline-flex align-items-center gap-1"
                       style="border:1px solid #e2e8f0;background:#fff;color:#374151;font-size:.82rem;">
                        <i class="ri-arrow-left-s-line fs-6"></i>{{ $tr('Retour','رجوع') }}
                    </a>
                @endif

                @if(!$isReadOnly && !($showIntroduction && $formulaire->has_introduction))
                <button wire:click="saveProgress" wire:loading.attr="disabled"
                        class="btn btn-sm rounded-3 fw-semibold d-inline-flex align-items-center gap-1"
                        style="border:1px solid #e2e8f0;background:#fff;color:#6b7280;font-size:.82rem;">
                    <span wire:loading.remove wire:target="saveProgress"><i class="ri-save-3-line"></i>{{ $tr('Sauvegarder','حفظ') }}</span>
                    <span wire:loading wire:target="saveProgress"><span class="spinner-border spinner-border-sm" style="width:.65rem;height:.65rem;"></span></span>
                </button>
                @endif
            </div>

            {{-- Right: step pagination + next/submit --}}
            <div class="d-flex align-items-center gap-2">

                @if($showIntroduction && $formulaire->has_introduction)
                    {{-- Intro: start button --}}
                    <button wire:click="skipIntroduction"
                            class="btn btn-sm rounded-pill fw-semibold px-4"
                            style="background:{{ $fColor }};color:#fff;border:0;font-size:.85rem;">
                        <i class="ri-play-line me-1"></i>{{ $tr('Commencer','بدء') }}
                    </button>
                @else
                    {{-- Step pagination (image-style: < 1 2 3 > ) --}}
                    @if($totalSteps > 1)
                    <div class="d-flex align-items-center gap-1">
                        {{-- prev arrow --}}
                        <button wire:click="previousStep" @if($currentStep <= 1) disabled @endif
                                class="btn btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center border-0"
                                style="width:30px;height:30px;background:{{ $currentStep>1 ? '#f1f5f9' : 'transparent' }};color:#374151;">
                            <i class="ri-arrow-left-s-line"></i>
                        </button>
                        @for($s = 1; $s <= $totalSteps; $s++)
                            @if($s === $currentStep)
                                <button type="button" disabled
                                        class="btn btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center fw-bold"
                                        style="width:30px;height:30px;font-size:.75rem;background:#0f2441;color:#fff;border:0;">
                                    {{ $s }}
                                </button>
                            @elseif($s < $currentStep)
                                <button type="button" wire:click="previousStep"
                                        class="btn btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center fw-bold"
                                        style="width:30px;height:30px;font-size:.75rem;background:#f1f5f9;color:#374151;border:0;">
                                    {{ $s }}
                                </button>
                            @else
                                <button type="button" wire:click="nextStep"
                                        class="btn btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center fw-bold"
                                        style="width:30px;height:30px;font-size:.75rem;background:#f1f5f9;color:#374151;border:0;">
                                    {{ $s }}
                                </button>
                            @endif
                        @endfor
                        {{-- next arrow --}}
                        @php
                            $isLastStep = $currentStep >= $totalSteps;
                        @endphp

                        <button
                            wire:click="nextStep"
                            {{ $isLastStep ? 'disabled' : '' }}
                            class="btn btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center border-0"
                            style="width:30px;height:30px;background:{{ !$isLastStep ? '#f1f5f9' : 'transparent' }};color:#374151;">
                            <i class="ri-arrow-right-s-line"></i>
                        </button>
                    </div>
                    @endif

                    {{-- Next / Submit --}}
                    @if($currentStep < $totalSteps)
                    <button wire:click="nextStep" wire:loading.attr="disabled"
                            class="btn btn-sm rounded-3 fw-semibold d-inline-flex align-items-center gap-1"
                            style="background:{{ $fColor }};color:#fff;border:0;font-size:.82rem;">
                        <span wire:loading.remove wire:target="nextStep">{{ $tr('Suivant','التالي') }}<i class="ri-arrow-right-s-line ms-1"></i></span>
                        <span wire:loading wire:target="nextStep"><span class="spinner-border spinner-border-sm" style="width:.65rem;height:.65rem;"></span></span>
                    </button>
                    @endif

                    @if($currentStep == $totalSteps && !$isReadOnly)
                    <button type="button" @click="openSubmitConfirm()"
                            class="btn btn-sm rounded-3 fw-bold d-inline-flex align-items-center gap-1"
                            style="background:#0f2441;color:#fff;border:0;font-size:.84rem;padding:.42rem 1.1rem;">
                        <i class="ri-send-plane-fill me-1"></i>{{ $tr('Soumettre','إرسال') }}
                    </button>
                    @endif
                @endif

            </div>
        </div>
        {{-- END footer --}}

        {{-- ---- LOGOS ---- --}}
        <div class="d-flex align-items-center justify-content-center gap-7 px-4 py-2"
             style="border-top:1px solid #f1f5f9;">
            @if(isset($project) && $project->logo1)
                <img src="{{ asset('uploads/' . $project->logo1) }}" alt="" style="height:90px;object-fit:contain;">
            @else
                <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="" style="height:90px;object-fit:contain;">
            @endif
            @if(isset($project) && $project->logo2)
                <img src="{{ asset('uploads/' . $project->logo2) }}" alt="" style="height:90px;object-fit:contain;">
            @else
                <img src="{{ asset('assets/site/images/indh_logo.png') }}" alt="" style="height:90px;object-fit:contain;">
            @endif
            @if(isset($project) && $project->logo3)
                <img src="{{ asset('uploads/' . $project->logo3) }}" alt="" style="height:90px;object-fit:contain;">
            @else
                <img src="{{ asset('assets/site/images/logo_zettat.png') }}" alt="" style="height:90px;object-fit:contain;">
            @endif
        </div>

    </div>
    {{-- END modal card --}}
 @if(!$isReadOnly)
            <div x-show="showSubmitConfirm"
                 x-cloak
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-3"
                 style="z-index: 9999;">
                <div class="absolute inset-0 bg-black/55" @click="showSubmitConfirm = false"></div>

                <div x-show="showSubmitConfirm"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="relative bg-white rounded-4 shadow-xl w-full" style="max-width: 520px;"
                     @click.stop>
                    <div class="px-4 px-md-5 py-4 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold" style="color: #1f2937;">
                            <i class="ri-shield-check-line me-2" style="color: {{ $form->color ?? '#2f5496' }};"></i>
                            {{ $tr('Confirmer l\'envoi', 'تأكيد الإرسال') }}
                        </h5>
                        <button type="button" class="btn-close" @click="closeSubmitConfirm()"></button>
                    </div>

                    <div class="px-4 px-md-5 py-4 text-secondary" style="line-height: 1.7;">
                        {{ $tr('Vous allez soumettre ce formulaire. Après envoi, vous ne pourrez plus modifier vos réponses.', 'أنت على وشك إرسال هذه الاستمارة. بعد الإرسال لن تتمكن من تعديل إجاباتك.') }}
                    </div>

                    <div class="px-4 rounded-b-xl px-md-5 py-3 bg-light border-top d-flex flex-column flex-md-row justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" @click="closeSubmitConfirm()">
                            {{ $tr('Annuler', 'إلغاء') }}
                        </button>
                        <button type="button"
                                class="btn text-white"
                                style="background-color: {{ $form->color ?? '#2f5496' }};"
                                @click="confirmSubmit()">
                            <i class="ri-send-plane-fill me-1"></i> {{ $tr('Oui, soumettre', 'نعم، إرسال') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif


</div>
