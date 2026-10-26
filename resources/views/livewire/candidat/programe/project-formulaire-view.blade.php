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
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col min-h-[calc(100vh-4rem)] max-h-[calc(100vh-4rem)]">

        {{-- ---- HEADER ---- --}}
        <div class="px-5 sm:px-8 py-4 border-b border-gray-100 flex-shrink-0">

            {{-- Flash --}}
            @if(session()->has('message') || session()->has('success'))
            <div class="flex items-center gap-2 rounded-lg px-4 py-3 mb-4 bg-green-50 border border-green-100 text-green-800 text-sm">
                <i class="ri-checkbox-circle-fill"></i><span>{{ session('message') ?? session('success') }}</span>
            </div>
            @endif
            @if(session()->has('error'))
            <div class="flex items-center gap-2 rounded-lg px-4 py-3 mb-4 bg-red-50 border border-red-100 text-red-700 text-sm">
                <i class="ri-error-warning-fill"></i><span>{{ session('error') }}</span>
            </div>
            @endif

            {{-- Title --}}
            <h3 class="font-bold text-center mb-1 text-gray-900" style="font-size: clamp(1.1rem, 2.5vw, 1.45rem);">
                {{ $displayLabel($form->title, $form->title_ar) }}
            </h3>

            @if($form->introduction)
            <p class="font-bold text-justify mb-1 text-gray-900" style="font-size: 0.83rem; line-height: 1.6;">
                {{ $displayLabel($form->introduction, $form->introduction_ar) }}
            </p>
            @endif

            {{-- Status banners --}}
            @if($isReadOnly && $existingSubmission)
            <div class="flex items-center gap-2 rounded-lg px-4 py-2 mt-3 bg-blue-50 border border-blue-100 text-blue-800 text-sm">
                <i class="ri-eye-line"></i>
                <span class="font-semibold">{{ $tr('Mode lecture seule','وضع القراءة فقط') }}</span>
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
            <div class="flex items-center gap-2 rounded-lg px-4 py-2 mt-3 bg-amber-50 border border-amber-100 text-amber-800 text-sm">
                <i class="ri-save-3-line"></i><span>{{ $tr('Brouillon sauvegardé','مسودة محفوظة') }}</span>
            </div>
            @endif
        </div>

        {{-- ---- SCROLLABLE BODY ---- --}}
        <div class="flex-1 overflow-y-auto px-5 sm:px-8 py-4">

            {{-- INTRODUCTION PAGE --}}
            @if($showIntroduction && $formulaire->has_introduction)
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center rounded-xl mb-3"
                     style="width: 56px; height: 56px; background: {{ $fColor }}18; color: {{ $fColor }}; font-size: 1.6rem;">
                    <i class="ri-book-open-line"></i>
                </div>
                <h5 class="font-bold text-gray-900">
                    {{ $displayLabel($formulaire->introduction_title, $formulaire->introduction_title_ar) }}
                </h5>
            </div>
            <div class="rounded-xl p-5 mb-6" style="background: #f8fafc; border-left: 4px solid {{ $fColor }}; color: #374151; font-size: 0.87rem; line-height: 1.9;">
                {!! nl2br(e($displayLabel($formulaire->introduction_content, $formulaire->introduction_content_ar))) !!}
            </div>

            {{-- FORM FIELDS --}}
            @else
            @if($currentStepData)

                @if($currentStepData->description)
                <p class="mb-6 text-gray-500" style="font-size: 0.84rem; line-height: 1.6;">{{ $currentStepData->description }}</p>
                @endif

                {{-- ---- FIELDS ---- --}}
                @php $fieldNum = 0; $halfFields = []; @endphp

                @foreach($currentStepData->fields->sortBy('sort_order') as $field)

                    {{-- HEADING --}}
                    @if($field->type === 'heading')
                    <div class="flex items-center gap-3 mt-6 mb-3">
                        <div style="width: 4px; height: 26px; background: {{ $fColor }}; border-radius: 2px; flex-shrink: 0;"></div>
                        <h6 class="font-bold mb-0 text-gray-900" style="font-size: 0.95rem;">
                            {{ $displayLabel($field->label, $field->label_ar) }}
                        </h6>
                    </div>

                    {{-- PARAGRAPH --}}
                    @elseif($field->type === 'paragraph')
                    <p class="mb-4 whitespace-pre-line text-gray-600" style="font-size: 0.9rem; line-height: 1.75;">
                        {{ $displayLabel($field->label, $field->label_ar) }}
                    </p>

                    {{-- REGULAR FIELDS --}}
                    @else
                    @php $fieldNum++; $isHalf = !$field->is_full_width; @endphp

                    <div class="{{ $isHalf ? 'inline-block pr-2 align-top' : 'w-full' }} mb-4"
                         style="{{ $isHalf ? 'width: 48%;' : '' }}">
                        <label class="block font-semibold mb-2" for="f_{{ $field->id }}"
                               style="color: #1e293b; font-size: 0.86rem;">
                            {{ $fieldNum }}. {{ $displayLabel($field->label, $field->label_ar) }}
                            @if($field->is_required)<span style="color: #ef4444; margin-left: 2px;">*</span>@endif
                        </label>

                        @if($field->type === 'text')
                            <input type="text" id="f_{{ $field->id }}" wire:model="answers.{{ $field->id }}"
                                   class="w-full iuhm_input rounded-xl border-0"
                                   placeholder="{{ $field->placeholder }}"
                                   @if($isReadOnly) readonly @endif>

                        @elseif($field->type === 'textarea')
                            <textarea id="f_{{ $field->id }}" wire:model="answers.{{ $field->id }}"
                                      class="w-full iuhm_textarea rounded-xl border-0"
                                      placeholder="{{ $field->placeholder }}"
                                      @if($isReadOnly) readonly @endif></textarea>

                        @elseif($field->type === 'number')
                            <input type="number" id="f_{{ $field->id }}" wire:model="answers.{{ $field->id }}"
                                   class="w-full iuhm_input rounded-xl border-0"
                                   placeholder="{{ $field->placeholder }}"
                                   @if($isReadOnly) readonly @endif>

                        @elseif($field->type === 'email')
                            <input type="email" id="f_{{ $field->id }}" wire:model="answers.{{ $field->id }}"
                                   class="w-full iuhm_input rounded-xl border-0"
                                   placeholder="{{ $field->placeholder }}"
                                   @if($isReadOnly) readonly @endif>

                        @elseif($field->type === 'date')
                            <input type="date" id="f_{{ $field->id }}" wire:model="answers.{{ $field->id }}"
                                   class="w-full iuhm_input rounded-xl border-0"
                                   @if($isReadOnly) readonly @endif>

                        @elseif($field->type === 'select')
                            <select id="f_{{ $field->id }}" wire:model="answers.{{ $field->id }}"
                                    class="w-full iuhm_select rounded-xl border-0"
                                    @if($isReadOnly) disabled @endif>
                                <option value="">{{ $field->placeholder ?: $tr('Sélectionner...','اختر...') }}</option>
                                @foreach($field->options ?? [] as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>

                        @elseif($field->type === 'radio')
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($field->options ?? [] as $opt)
                                    <label class="flex items-center gap-2 px-3 py-2 rounded-lg cursor-pointer"
                                           style="background: #f5f6fa; font-size: 0.84rem; color: #374151;">
                                        <input type="radio" wire:model="answers.{{ $field->id }}" value="{{ $opt }}"
                                               style="accent-color: {{ $fColor }};" @if($isReadOnly) disabled @endif>
                                        {{ $opt }}
                                    </label>
                                @endforeach
                            </div>

                        @elseif($field->type === 'checkbox')
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($field->options ?? [] as $opt)
                                    <label class="flex items-center gap-2 px-3 py-2 rounded-lg cursor-pointer"
                                           style="background: #f5f6fa; font-size: 0.84rem; color: #374151;">
                                        <input type="checkbox" wire:model="answers.{{ $field->id }}" value="{{ $opt }}"
                                               style="accent-color: {{ $fColor }};" @if($isReadOnly) disabled @endif>
                                        {{ $opt }}
                                    </label>
                                @endforeach
                            </div>

                        @elseif($field->type === 'file')
                            <div class="rounded-xl p-3" style="background: #f5f6fa; border: 2px dashed #d1d5db;">
                                <input type="file" id="f_{{ $field->id }}" wire:model="answers.{{ $field->id }}"
                                       @if($field->allow_multiple_files) multiple @endif
                                       accept=".pdf,.xls,.xlsx,.csv,.doc,.docx,.jpg,.jpeg,.png,.gif,.webp"
                                       class="w-full border-0 bg-transparent p-0"
                                       style="font-size: 0.84rem;" @if($isReadOnly) disabled @endif>
                                <p class="mb-0 mt-1" style="font-size: 0.73rem; color: #9ca3af;">
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
                            <div class="mt-2 space-y-1">
                                @foreach($existingPaths as $path)
                                @php $cleanPath = ltrim(str_starts_with($path,'uploads/') ? substr($path,8) : $path,'/'); @endphp
                                <div class="flex items-center gap-3 rounded-lg px-3 py-2"
                                     style="background: #f0fdf4; font-size: 0.8rem;">
                                    <i class="ri-file-line" style="color: {{ $fColor }};"></i>
                                    <span class="flex-1 truncate" style="color: #374151;">{{ basename($cleanPath) }}</span>
                                    <a href="{{ route('uploads.show', ['path' => $cleanPath]) }}" target="_blank" style="color: #2563eb; text-decoration: none;"><i class="ri-eye-line"></i></a>
                                    <a href="{{ route('uploads.download', ['path' => $cleanPath]) }}" style="color: #0f766e; text-decoration: none;"><i class="ri-download-2-line"></i></a>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        @endif

                        @if($field->help_text)
                            <p class="mb-0 mt-1" style="font-size: 0.73rem; color: #9ca3af;"><i class="ri-information-line mr-1"></i>{{ $field->help_text }}</p>
                        @endif
                        @error('answers.' . $field->id)
                            <p class="mb-0 mt-1" style="font-size: 0.76rem; color: #dc2626;"><i class="ri-error-warning-line mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                @endforeach

                {{-- ---- TABLES ---- --}}
                @foreach($currentStepData->tables->sortBy('sort_order') as $table)
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div style="width: 4px; height: 22px; background: {{ $fColor }}; border-radius: 2px; flex-shrink: 0;"></div>
                        <p class="font-semibold mb-0 text-gray-900" style="font-size: 0.88rem;">{{ $displayLabel($table->title, $table->title_ar) }}</p>
                    </div>
                    @if($table->columns->isNotEmpty())
                    <div class="rounded-xl overflow-hidden border border-gray-200">
                        <div class="overflow-x-auto">
                        <table class="w-full min-w-[600px] text-sm">
                            <thead>
                                <tr class="bg-[#0f2441] text-white">
                                    @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                        <th class="px-3 py-2 font-semibold border-0 text-center"></th>
                                    @endif
                                    @foreach($table->columns->sortBy('sort_order') as $col)
                                        <th class="px-3 py-3 text-center font-bold">{{ $displayLabel($col->header, $col->header_ar) }}</th>
                                    @endforeach
                                    @if($table->has_dynamic_rows && !$isReadOnly)
                                        <th class="border-0" style="width: 40px;"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                    @foreach($table->fixedRows->sortBy('sort_order') as $ri => $row)
                                    <tr style="background: {{ $ri%2===0 ? '#fff' : '#f9fafb' }};">
                                        <td class="px-3 py-2 font-bold text-gray-900 text-center" style="border-color: #f1f5f9;">{{ $displayLabel($row->label, $row->label_ar) }}</td>
                                        @foreach($table->columns->sortBy('sort_order') as $col)
                                        <td class="px-2 py-1" style="border-color: #f1f5f9;">
                                            @if($col->input_type==='checkbox')
                                                <div class="text-center"><input type="checkbox" wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" style="accent-color: {{ $fColor }};" @if($isReadOnly) disabled @endif></div>
                                            @elseif($col->input_type==='radio')
                                                <div class="text-center"><input type="radio" wire:model="tableData.{{ $table->table_key }}.{{ $ri }}._radio" value="{{ $col->column_key }}" name="radio_{{ $table->table_key }}_{{ $ri }}" style="accent-color: {{ $fColor }};" @if($isReadOnly) disabled @endif></div>
                                            @elseif($col->input_type==='number')
                                                <input type="number" wire:model.live="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" class="w-full iuhm_input rounded-lg border-0" @if($isReadOnly) readonly @endif>
                                            @elseif($col->input_type==='select')
                                                <select wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" class="w-full iuhm_select rounded-lg border-0" @if($isReadOnly) disabled @endif>
                                                    <option value="">--</option>
                                                    @foreach($col->options??[] as $opt)<option value="{{ $opt }}">{{ $opt }}</option>@endforeach
                                                </select>
                                            @elseif($col->input_type==='readonly')
                                                <input type="text" readonly class="w-full iuhm_input rounded-lg border-0" style="background: #e9ecef;" value="{{ $tableData[$table->table_key][$ri][$col->column_key]??'' }}">
                                            @else
                                                <input type="text" wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" class="w-full iuhm_input rounded-lg border-0" @if($isReadOnly) readonly @endif>
                                            @endif
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                @else
                                    @php $rowCount = (int) ($tableRowCounts[$table->table_key] ?? $table->min_rows); @endphp
                                    @foreach($rowCount > 0 ? range(0, $rowCount - 1) : [] as $ri)
                                    <tr style="background: {{ $ri%2===0 ? '#fff' : '#f9fafb' }};">
                                        @foreach($table->columns->sortBy('sort_order') as $col)
                                        <td class="px-2 py-1" style="border-color: #f1f5f9;">
                                            @if($col->input_type==='checkbox')
                                                <div class="text-center"><input type="checkbox" wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" style="accent-color: {{ $fColor }};" @if($isReadOnly) disabled @endif></div>
                                            @elseif($col->input_type==='radio')
                                                <div class="text-center"><input type="radio" wire:model="tableData.{{ $table->table_key }}.{{ $ri }}._radio" value="{{ $col->column_key }}" name="radio_{{ $table->table_key }}_{{ $ri }}" style="accent-color: {{ $fColor }};" @if($isReadOnly) disabled @endif></div>
                                            @elseif($col->input_type==='number')
                                                <input type="number" wire:model.live="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" class="w-full iuhm_input rounded-lg border-0" @if($isReadOnly) readonly @endif>
                                            @elseif($col->input_type==='select')
                                                <select wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" class="w-full iuhm_select rounded-lg border-0" @if($isReadOnly) disabled @endif>
                                                    <option value="">--</option>
                                                    @foreach($col->options??[] as $opt)<option value="{{ $opt }}">{{ $opt }}</option>@endforeach
                                                </select>
                                            @else
                                                <input type="text" wire:model="tableData.{{ $table->table_key }}.{{ $ri }}.{{ $col->column_key }}" class="w-full iuhm_input rounded-lg border-0" @if($isReadOnly) readonly @endif>
                                            @endif
                                        </td>
                                        @endforeach
                                        @if($table->has_dynamic_rows && !$isReadOnly)
                                        <td class="px-1 py-1 text-center" style="border-color: #f1f5f9;">
                                            @if(($tableRowCounts[$table->table_key]??$table->min_rows) > $table->min_rows)
                                                <button wire:click="removeTableRow('{{ $table->table_key }}', {{ $ri }})" class="p-1 text-red-500 hover:bg-red-50 rounded-lg" style="background: transparent; border: none;">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                @endif
                                @if($table->has_total_row)
                                <tr style="background: #f0f9ff; font-weight: 700;">
                                    @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                        <td class="px-3 py-2" style="border-color: #e9ecef;">{{ $tr('Total','المجموع') }}</td>
                                    @endif
                                    @foreach($table->columns->sortBy('sort_order') as $col)
                                    <td class="px-3 py-2" style="border-color: #e9ecef;">
                                        @if($col->is_totaled){{ number_format($this->getTableTotal($table->table_key, $col->column_key), 2) }}@endif
                                    </td>
                                    @endforeach
                                    @if($table->has_dynamic_rows && !$isReadOnly)<td style="border-color: #e9ecef;"></td>@endif
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        </div>
                    </div>
                    @if($table->has_dynamic_rows && !$isReadOnly)
                    <button wire:click="addTableRow('{{ $table->table_key }}')"
                            class="btn-sm rounded-xl mt-2 inline-flex items-center gap-1 font-semibold"
                            style="border: 1px dashed {{ $fColor }}; background: transparent; color: {{ $fColor }}; font-size: 0.78rem; padding: 0.5rem 1rem;">
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
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 sm:px-8 py-4 border-t border-gray-100 bg-gray-50 flex-shrink-0">

            {{-- Left: back / save --}}
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">

                @if($showIntroduction && $formulaire->has_introduction)
                    {{-- intro page: only back to project --}}
                @elseif($currentStep > 1)
                    <button wire:click="previousStep"
                            class="btn-sm rounded-xl font-semibold inline-flex items-center gap-1 transition-colors"
                            style="border: 1px solid #e2e8f0; background: #fff; color: #374151; font-size: 0.82rem; padding: 0.5rem 1rem;">
                        <i class="ri-arrow-left-s-line"></i>{{ $tr('Précédent','السابق') }}
                    </button>
                @else
                    <a href="{{ route('user.project.detail', $projectId) }}"
                       class="btn-sm rounded-xl font-semibold inline-flex items-center gap-1 transition-colors"
                       style="border: 1px solid #e2e8f0; background: #fff; color: #374151; font-size: 0.82rem; padding: 0.5rem 1rem;">
                         <i class="ri-arrow-left-s-line"></i>{{ $tr('Retour','رجوع') }}
                    </a>
                @endif

                @if(!$isReadOnly && !($showIntroduction && $formulaire->has_introduction))
                <button wire:click="saveProgress" wire:loading.attr="disabled"
                        class="btn-sm rounded-xl font-semibold inline-flex items-center gap-1 transition-colors"
                        style="border: 1px solid #e2e8f0; background: #fff; color: #6b7280; font-size: 0.82rem; padding: 0.5rem 1rem;">
                    <span wire:loading.remove wire:target="saveProgress"><i class="ri-save-3-line"></i>{{ $tr('Sauvegarder','حفظ') }}</span>
                    <span wire:loading wire:target="saveProgress"><svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                </button>
                @endif
            </div>

            {{-- Right: step pagination + next/submit --}}
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">

                @if($showIntroduction && $formulaire->has_introduction)
                    {{-- Intro: start button --}}
                    <button wire:click="skipIntroduction"
                            class="btn-sm rounded-full font-semibold px-6 py-2"
                            style="background: {{ $fColor }}; color: #fff; border: 0; font-size: 0.85rem;">
                        <i class="ri-play-line mr-1"></i>{{ $tr('Commencer','بدء') }}
                    </button>
                @else
                    {{-- Step pagination --}}
                    @if($totalSteps > 1)
                    <div class="flex items-center gap-1">
                        {{-- prev arrow --}}
                        <button wire:click="previousStep" @if($currentStep <= 1) disabled @endif
                                class="btn-sm rounded-full p-0 flex items-center justify-center border-0"
                                style="width: 30px; height: 30px; background: {{ $currentStep>1 ? '#f1f5f9' : 'transparent' }}; color: #374151;">
                            <i class="ri-arrow-left-s-line"></i>
                        </button>
                        @foreach(range(1, $totalSteps) as $s)
                            @if($s === $currentStep)
                                <button type="button" disabled
                                        class="btn-sm rounded-full p-0 flex items-center justify-center fw-bold"
                                        style="width: 30px; height: 30px; font-size: 0.75rem; background: #0f2441; color: #fff; border: 0;">
                                    {{ $s }}
                                </button>
                            @elseif($s < $currentStep)
                                <button type="button" wire:click="previousStep"
                                        class="btn-sm rounded-full p-0 flex items-center justify-center fw-bold"
                                        style="width: 30px; height: 30px; font-size: 0.75rem; background: #f1f5f9; color: #374151; border: 0;">
                                    {{ $s }}
                                </button>
                            @else
                                <button type="button" wire:click="nextStep"
                                        class="btn-sm rounded-full p-0 flex items-center justify-center fw-bold"
                                        style="width: 30px; height: 30px; font-size: 0.75rem; background: #f1f5f9; color: #374151; border: 0;">
                                    {{ $s }}
                                </button>
                            @endif
                        @endforeach
                        {{-- next arrow --}}
                        @php
                            $isLastStep = $currentStep >= $totalSteps;
                        @endphp

                        <button
                            wire:click="nextStep"
                            {{ $isLastStep ? 'disabled' : '' }}
                            class="btn-sm rounded-full p-0 flex items-center justify-center border-0"
                            style="width: 30px; height: 30px; background: {{ !$isLastStep ? '#f1f5f9' : 'transparent' }}; color: #374151;">
                            <i class="ri-arrow-right-s-line"></i>
                        </button>
                    </div>
                    @endif

                    {{-- Next / Submit --}}
                    @if($currentStep < $totalSteps)
                    <button wire:click="nextStep" wire:loading.attr="disabled"
                            class="btn-sm rounded-xl font-semibold inline-flex items-center gap-1 transition-colors"
                            style="background: {{ $fColor }}; color: #fff; border: 0; font-size: 0.82rem; padding: 0.5rem 1rem;">
                        <span wire:loading.remove wire:target="nextStep">{{ $tr('Suivant','التالي') }}<i class="ri-arrow-right-s-line ms-1"></i></span>
                        <span wire:loading wire:target="nextStep"><svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                    </button>
                    @endif

                    @if($currentStep == $totalSteps && !$isReadOnly)
                    <button type="button" @click="openSubmitConfirm()"
                            class="btn-sm rounded-xl font-bold inline-flex items-center gap-1 transition-colors"
                            style="background: #0f2441; color: #fff; border: 0; font-size: 0.84rem; padding: 0.5rem 1.25rem;">
                        <i class="ri-send-plane-fill mr-1"></i>{{ $tr('Soumettre','إرسال') }}
                    </button>
                    @endif
                @endif

            </div>
        </div>
        {{-- END footer --}}

        {{-- ---- LOGOS ---- --}}
        <div class="flex items-center justify-center gap-7 px-4 py-3 border-t border-gray-100 flex-shrink-0">
            @if(isset($project) && $project->logo1)
                <img src="{{ asset('uploads/' . $project->logo1) }}" alt="" style="height: 90px; object-fit: contain;">
            @else
                <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="" style="height: 90px; object-fit: contain;">
            @endif
            @if(isset($project) && $project->logo2)
                <img src="{{ asset('uploads/' . $project->logo2) }}" alt="" style="height: 90px; object-fit: contain;">
            @else
                <img src="{{ asset('assets/site/images/indh_logo.png') }}" alt="" style="height: 90px; object-fit: contain;">
            @endif
            @if(isset($project) && $project->logo3)
                <img src="{{ asset('uploads/' . $project->logo3) }}" alt="" style="height: 90px; object-fit: contain;">
            @else
                <img src="{{ asset('assets/site/images/logo_zettat.png') }}" alt="" style="height: 90px; object-fit: contain;">
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
                     class="relative bg-white rounded-2xl shadow-xl w-full max-w-md"
                     @click.stop>
                    <div class="px-4 py-4 border-b flex items-center justify-between">
                        <h5 class="mb-0 font-bold text-gray-900 flex items-center gap-2">
                            <i class="ri-shield-check-line" style="color: {{ $form->color ?? '#2f5496' }};"></i>
                            {{ $tr('Confirmer l\'envoi', 'تأكيد الإرسال') }}
                        </h5>
                        <button type="button" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors" @click="closeSubmitConfirm()">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>

                    <div class="px-4 py-4 text-gray-600" style="line-height: 1.7;">
                        {{ $tr('Vous allez soumettre ce formulaire. Après envoi, vous ne pourrez plus modifier vos réponses.', 'أنت على وشك إرسال هذه الاستمارة. بعد الإرسال لن تتمكن من تعديل إجاباتك.') }}
                    </div>

                    <div class="px-4 py-3 border-t bg-gray-50 rounded-b-2xl flex flex-col sm:flex-row justify-end gap-2">
                        <button type="button" class="btn-sm rounded-xl font-semibold px-4 py-2 transition-colors border border-gray-300 bg-white text-gray-700 hover:bg-gray-50" @click="closeSubmitConfirm()">
                            {{ $tr('Annuler', 'إلغاء') }}
                        </button>
                        <button type="button"
                                class="btn-sm rounded-xl font-bold inline-flex items-center gap-1 text-white transition-colors px-4 py-2"
                                style="background-color: {{ $form->color ?? '#2f5496' }};"
                                @click="confirmSubmit()">
                            <i class="ri-send-plane-fill mr-1"></i> {{ $tr('Oui, soumettre', 'نعم، إرسال') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif


</div>