<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.formulaires.submissions', $submission->dynamic_form_id) }}"
                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <i class="ri-arrow-left-line text-xl"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $submission->form->title }}</h2>
                <p class="text-gray-500 text-sm mt-1">
                    Soumission de {{ $submission->candidat->first_name }} {{ $submission->candidat->last_name }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $submission->status_badge_color }}-100 text-{{ $submission->status_badge_color }}-800">
                {{ $submission->status_label }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Candidat Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Candidat</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-500">Nom:</span>
                        <p class="font-medium text-gray-800">{{ $submission->candidat->first_name }} {{ $submission->candidat->last_name }}</p>
                    </div>
                    @if($submission->candidat->cin)
                        <div>
                            <span class="text-gray-500">CIN:</span>
                            <p class="font-medium text-gray-800">{{ $submission->candidat->cin }}</p>
                        </div>
                    @endif
                    @if($submission->candidat->email)
                        <div>
                            <span class="text-gray-500">Email:</span>
                            <p class="font-medium text-gray-800">{{ $submission->candidat->email }}</p>
                        </div>
                    @endif
                    @if($submission->candidat->phone)
                        <div>
                            <span class="text-gray-500">Téléphone:</span>
                            <p class="font-medium text-gray-800">{{ $submission->candidat->phone }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Management -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Gestion du statut</h3>
                <div class="space-y-2">
                    @foreach(['submitted' => ['Soumis', 'blue'], 'in_review' => ['En révision', 'purple'], 'approved' => ['Approuvé', 'green'], 'rejected' => ['Rejeté', 'red']] as $key => [$label, $clr])
                        <button wire:click="openStatusModal('{{ $key }}')"
                            class="w-full px-4 py-2 text-sm rounded-lg transition text-left
                                {{ $submission->status === $key ? 'bg-'.$clr.'-100 text-'.$clr.'-800 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                @if($submission->reviewed_at)
                    <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500">
                        <p>Révisé le: {{ $submission->reviewed_at->format('d/m/Y H:i') }}</p>
                        @if($submission->reviewer)
                            <p>Par: {{ $submission->reviewer->name }}</p>
                        @endif
                        @if($submission->review_notes)
                            <p class="mt-1">Notes: {{ $submission->review_notes }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Submission Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Informations</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Créé le:</span>
                        <span class="text-gray-800">{{ $submission->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Étape actuelle:</span>
                        <span class="text-gray-800">{{ $submission->current_step }} / {{ $submission->form->steps->count() }}</span>
                    </div>
                    @if($submission->submitted_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Soumis le:</span>
                            <span class="text-gray-800">{{ $submission->submitted_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Data -->
        <div class="lg:col-span-3 space-y-6">
            @foreach($submission->form->steps as $step)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100" style="background-color: {{ $submission->form->color }}10;">
                        <h3 class="font-semibold text-gray-800">
                            Étape {{ $step->step_number }}: {{ $step->title }}
                        </h3>
                    </div>
                    <div class="p-6">
                        {{-- Field Answers --}}
                        @foreach($step->fields->sortBy('sort_order') as $field)
                            @if(in_array($field->type, ['heading', 'paragraph']))
                                @if($field->type === 'heading')
                                    <h4 class="font-semibold text-gray-700 mt-4 mb-2">{{ $field->label }}</h4>
                                @else
                                    <p class="text-gray-500 text-sm mb-2">{{ $field->label }}</p>
                                @endif
                            @else
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-500 mb-1">{{ $field->label }}</label>
                                    <div class="px-4 py-2.5 bg-gray-50 rounded-lg text-sm text-gray-800 border border-gray-100">
                                        {{ $submission->getAnswer($field->field_key) ?: '-' }}
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        {{-- Table Answers --}}
                        @foreach($step->tables->sortBy('sort_order') as $table)
                            <div class="mt-6">
                                <h4 class="font-medium text-gray-700 mb-3">{{ $table->title }}</h4>
                                @php $tableData = $submission->getTableData($table->table_key); @endphp

                                <div class="overflow-x-auto">
                                    <table class="w-full border-collapse border border-gray-200 text-sm">
                                        <thead>
                                            <tr>
                                                @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                                    <th class="border border-gray-200 px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500"></th>
                                                @endif
                                                @foreach($table->columns->sortBy('sort_order') as $col)
                                                    <th class="border border-gray-200 px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500">
                                                        {{ $col->header }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                                @foreach($table->fixedRows->sortBy('sort_order') as $ri => $row)
                                                    <tr>
                                                        <td class="border border-gray-200 px-4 py-2 bg-gray-50 font-medium text-gray-700">{{ $row->label }}</td>
                                                        @foreach($table->columns->sortBy('sort_order') as $col)
                                                            <td class="border border-gray-200 px-4 py-2 text-gray-800">
                                                                @php $val = $tableData[$ri][$col->column_key] ?? '-'; @endphp
                                                                @if($col->input_type === 'checkbox')
                                                                    @if($val && $val !== '-')
                                                                        <i class="ri-checkbox-circle-fill text-green-500"></i>
                                                                    @else
                                                                        <i class="ri-checkbox-blank-circle-line text-gray-300"></i>
                                                                    @endif
                                                                @else
                                                                    {{ $val }}
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            @else
                                                @forelse($tableData as $ri => $rowData)
                                                    <tr>
                                                        @foreach($table->columns->sortBy('sort_order') as $col)
                                                            <td class="border border-gray-200 px-4 py-2 text-gray-800">
                                                                @php $val = $rowData[$col->column_key] ?? '-'; @endphp
                                                                @if($col->input_type === 'checkbox')
                                                                    @if($val && $val !== '-')
                                                                        <i class="ri-checkbox-circle-fill text-green-500"></i>
                                                                    @else
                                                                        <i class="ri-checkbox-blank-circle-line text-gray-300"></i>
                                                                    @endif
                                                                @else
                                                                    {{ $val }}
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="{{ $table->columns->count() }}" class="border border-gray-200 px-4 py-4 text-center text-gray-400">
                                                            Aucune donnée
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            @endif

                                            @if($table->has_total_row)
                                                @php
                                                    $totals = [];
                                                    foreach($table->columns->sortBy('sort_order') as $col) {
                                                        if ($col->is_totaled) {
                                                            $sum = 0;
                                                            foreach ($tableData as $rowData) {
                                                                $sum += (float)($rowData[$col->column_key] ?? 0);
                                                            }
                                                            $totals[$col->column_key] = $sum;
                                                        }
                                                    }
                                                @endphp
                                                <tr class="bg-gray-50 font-bold">
                                                    @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                                        <td class="border border-gray-200 px-4 py-2 text-right">Total</td>
                                                    @endif
                                                    @foreach($table->columns->sortBy('sort_order') as $col)
                                                        <td class="border border-gray-200 px-4 py-2 text-gray-800">
                                                            @if($col->is_totaled)
                                                                {{ number_format($totals[$col->column_key] ?? 0, 2) }}
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Status Change Modal --}}
    @if($showStatusModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" wire:click.self="$set('showStatusModal', false)">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Changer le statut</h3>
                </div>
                <div class="p-6 space-y-4">
                    <p class="text-sm text-gray-600">
                        Changer le statut en: <strong>{{ $newStatus }}</strong>
                    </p>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optionnel)</label>
                        <textarea wire:model="reviewNotes" rows="3"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm"
                            placeholder="Ajouter des notes de révision..."></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                    <button wire:click="$set('showStatusModal', false)"
                        class="px-5 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition">Annuler</button>
                    <button wire:click="updateStatus"
                        class="px-5 py-2.5 text-sm text-white bg-green-600 hover:bg-green-700 rounded-lg transition">Confirmer</button>
                </div>
            </div>
        </div>
    @endif
</div>
