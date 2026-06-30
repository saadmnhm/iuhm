<div>
    @if(session()->has('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        {{ session('success') }}
    </div>
    @endif

    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $submission->form->title }}</h2>
                <div class="flex gap-4 mt-2 text-sm text-gray-500">
                    <span>ID: #{{ $submission->id }}</span>
                    <span>{{ $submission->created_at->format('d M Y') }}</span>
                    @if($submission->submitted_at)
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                        Soumis le {{ $submission->submitted_at->format('d M Y') }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('admin.candidats.edit', $submission->candidat->id) }}" class="flex items-center justify-center gap-1 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200 transitioninline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-50 border border-gray-200 rounded-full hover:bg-gray-100 transition-colors">
                    <i class="ri-edit-2-line"></i> Edit info
                </a>
                <a href="{{ route('admin.candidat.submission.export', ['candidatId' => $submission->candidat_id ?? $submission->candidat->id, 'id' => $submission->id]) }}" target="_blank"
                                class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-lg border border-red-200 transition">
                    <i class="ri-file-pdf-line"></i> Export PDF
                </a>
                <a href="{{ route('admin.candidat.submissions', $submission->candidat->id) }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    Retour
                </a>
            </div>
        </div>

        <!-- Candidat Info -->
        @if($submission->candidat)
        <div class="bg-gray-50 rounded-lg p-4 flex items-center gap-4">
            @if($submission->candidat->profile_image)
                <img src="{{ asset('uploads/'.$submission->candidat->profile_image) }}" alt="{{ $submission->candidat->nom }}" class="w-16 h-16 rounded-full object-cover">
            @else
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-xl font-bold"
                     style="background-color: {{ $submission->form->color ?? '#6366f1' }};">
                    {{ strtoupper(substr($submission->candidat->nom, 0, 1)) }}
                </div>
            @endif
            <div>
                <h3 class="font-semibold text-gray-900">{{ $submission->candidat->nom }} {{ $submission->candidat->prenom }}</h3>
                <p class="text-gray-600">{{ $submission->candidat->email }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($submission->form->steps as $step)
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 {{ $loop->last && $loop->count % 2 !== 0 ? 'lg:col-span-2' : '' }}">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="{{ $submission->form->icon ?? 'ri-file-list-3-line' }} text-xl" style="color: {{ $submission->form->color ?? '#6366f1' }};"></i>
                {{ $step->title }}
            </h3>
            <div class="space-y-3">
                @foreach($step->fields->sortBy('sort_order') as $field)
                    @if($field->type === 'heading')
                        <h4 class="font-semibold text-gray-700 mt-2">{{ $field->label }}</h4>
                    @elseif($field->type === 'paragraph')
                        <p class="text-gray-500 text-sm">{{ $field->label }}</p>
                    @else
                        <div>
                            <label class="text-sm font-medium text-gray-600">{{ $field->label }}</label>
                            @php
                                $rawAnswer = $submission->getAnswer($field->field_key);
                            @endphp

                            @if($field->type === 'file' && $rawAnswer)
                                @php
                                    $decoded = json_decode($rawAnswer, true);
                                    $filePaths = is_array($decoded)
                                        ? collect($decoded)->filter(fn ($p) => is_string($p) && trim($p) !== '')->values()->all()
                                        : [trim((string) $rawAnswer)];
                                @endphp
                                <div class="space-y-1 mt-1">
                                    @foreach($filePaths as $path)
                                        @php
                                            $cleanPath = ltrim(str_starts_with($path, 'uploads/') ? substr($path, 8) : $path, '/');
                                        @endphp
                                        <div class="flex items-center gap-3 flex-wrap">
                                            <a href="{{ route('uploads.show', ['path' => $cleanPath]) }}"
                                               target="_blank"
                                               class="inline-flex items-center gap-2 text-sm text-indigo-700 hover:text-indigo-900 hover:underline">
                                                <i class="ri-eye-line"></i>
                                                Voir
                                            </a>
                                            <a href="{{ route('uploads.download', ['path' => $cleanPath]) }}"
                                               class="inline-flex items-center gap-2 text-sm text-emerald-700 hover:text-emerald-900 hover:underline">
                                                <i class="ri-download-2-line"></i>
                                                Télécharger
                                            </a>
                                            <span class="text-xs text-gray-500">{{ basename($cleanPath) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="mt-1 flex items-start iuhm_input_div gap-2">
                                    <p class="text-gray-900 flex-1">{{ $rawAnswer ?: 'N/A' }}</p>
                                    <button type="button"
                                            wire:click="openEditAnswerModal('field', '{{ $field->field_key }}', null, null, null, {{ json_encode((string) ($rawAnswer ?? '')) }})"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full text-gray-500 hover:bg-gray-100 hover:text-indigo-600 transition"
                                            title="Modifier la réponse">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach

                @foreach($step->tables->sortBy('sort_order') as $table)
                    @php $tableData = $submission->getTableData($table->table_key); @endphp
                    <div class="mt-4">
                        <label class="text-sm font-medium text-gray-600 block mb-2">{{ $table->title }}</label>
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse border border-gray-200 text-sm">
                                <thead>
                                    <tr>
                                        @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                            <th class="border border-gray-200 px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500"></th>
                                        @endif
                                        @foreach($table->columns->sortBy('sort_order') as $col)
                                            <th class="border border-gray-200 px-3 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500">{{ $col->header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty())
                                        @foreach($table->fixedRows->sortBy('sort_order') as $ri => $row)
                                            <tr>
                                                <td class="border border-gray-200 px-3 py-2 bg-gray-50 font-medium text-gray-700 text-xs">{{ $row->label }}</td>
                                                @foreach($table->columns->sortBy('sort_order') as $col)
                                                    <td class="border border-gray-200 px-3 py-2 text-gray-900">
                                                        @php $val = $tableData[$ri][$col->column_key] ?? 'N/A'; @endphp
                                                        <div class="flex items-center justify-between gap-2">
                                                            <div class="flex-1">
                                                                @if($col->input_type === 'checkbox')
                                                                    @if($val && $val !== 'N/A') <i class="ri-checkbox-circle-fill text-green-500"></i>
                                                                    @else <i class="ri-checkbox-blank-circle-line text-gray-300"></i>
                                                                    @endif
                                                                @else
                                                                    {{ $val }}
                                                                @endif
                                                            </div>
                                                            <button type="button"
                                                                    wire:click="openEditAnswerModal('table', null, '{{ $table->table_key }}', {{ $ri }}, '{{ $col->column_key }}', {{ json_encode((string) $val) }})"
                                                                    class="inline-flex items-center justify-center w-7 h-7 rounded-full text-gray-400 hover:bg-gray-100 hover:text-indigo-600 transition"
                                                                    title="Modifier cette valeur">
                                                                <i class="ri-pencil-line text-sm"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @else
                                        @forelse($tableData as $ri => $rowData)
                                            <tr>
                                                @foreach($table->columns->sortBy('sort_order') as $col)
                                                    <td class="border border-gray-200 px-3 py-2 text-gray-900">
                                                        @php $val = $rowData[$col->column_key] ?? 'N/A'; @endphp
                                                        <div class="flex items-center justify-between gap-2">
                                                            <div class="flex-1">
                                                                @if($col->input_type === 'checkbox')
                                                                    @if($val && $val !== 'N/A') <i class="ri-checkbox-circle-fill text-green-500"></i>
                                                                    @else <i class="ri-checkbox-blank-circle-line text-gray-300"></i>
                                                                    @endif
                                                                @else
                                                                    {{ $val }}
                                                                @endif
                                                            </div>
                                                            <button type="button"
                                                                    wire:click="openEditAnswerModal('table', null, '{{ $table->table_key }}', {{ $ri }}, '{{ $col->column_key }}', {{ json_encode((string) $val) }})"
                                                                    class="inline-flex items-center justify-center w-7 h-7 rounded-full text-gray-400 hover:bg-gray-100 hover:text-indigo-600 transition"
                                                                    title="Modifier cette valeur">
                                                                <i class="ri-pencil-line text-sm"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @empty
                                            <tr><td colspan="{{ $table->columns->count() }}" class="border border-gray-200 px-3 py-3 text-center text-gray-400 text-xs">Aucune donnée</td></tr>
                                        @endforelse
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



    @if($showEditAnswerModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
         wire:click.self="closeEditAnswerModal">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Modifier la réponse</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valeur</label>
                    <textarea wire:model="editAnswerValue" rows="4"
                        class="w-full iuhm_textarea rounded-lg "
                        placeholder="Saisissez la nouvelle réponse..."></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <button wire:click="closeEditAnswerModal"
                    class="px-5 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition">Annuler</button>
                <button wire:click="saveEditedAnswer"
                    class="px-5 py-2.5 text-sm text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">Enregistrer</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Status Change Modal --}}
    @if($showStatusModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
         wire:click.self="$set('showStatusModal', false)">
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
