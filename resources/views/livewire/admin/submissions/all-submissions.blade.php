<div class="max-w-7xl mx-auto">

    {{-- ═══ Statistics ═══ --}}
    <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-6">
        @foreach([
            ['Total', $stats['total'], 'ri-file-list-3-line', 'blue'],
            ['Brouillon', $stats['draft'], 'ri-draft-line', 'gray'],
            ['Soumis', $stats['submitted'], 'ri-send-plane-line', 'indigo'],
            ['En révision', $stats['in_review'], 'ri-time-line', 'amber'],
            ['Approuvés', $stats['approved'], 'ri-checkbox-circle-line', 'green'],
            ['Rejetés', $stats['rejected'], 'ri-close-circle-line', 'red'],
        ] as [$label, $value, $icon, $color])
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-{{ $color }}-100 flex items-center justify-center flex-shrink-0">
                    <i class="{{ $icon }} text-{{ $color }}-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">{{ $label }}</p>
                    <p class="text-xl font-bold text-gray-900">{{ $value }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ═══ Filters ═══ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-3 mb-3">
            <div class="flex-1 min-w-[200px] relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" wire:model.live="search" placeholder="Rechercher par nom, email, matricule, formulaire..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">
            </div>
            <select wire:model.live="statusFilter" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                <option value="all">Tous les statuts</option>
                <option value="draft">Brouillon</option>
                <option value="submitted">Soumis</option>
                <option value="in_review">En révision</option>
                <option value="approved">Approuvé</option>
                <option value="rejected">Rejeté</option>
            </select>
            <select wire:model.live="programeFilter" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                <option value="all">Tous les programmes</option>
                @foreach($programmes as $p)
                <option value="{{ $p->id }}">{{ $p->project_name }}</option>
                @endforeach
            </select>
            <button wire:click="resetFilters" class="px-3 py-2.5 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <i class="ri-filter-off-line"></i> Reset
            </button>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <select wire:model.live="formulaireFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3">
                <option value="all">Tous les formulaires</option>
                @foreach($formulaires as $f)
                <option value="{{ $f->id }}">{{ $f->title }}</option>
                @endforeach
            </select>
            <select wire:model.live="responsableFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3">
                <option value="all">Tous les responsables</option>
                <option value="none">Non assigné</option>
                @foreach($admins as $admin)
                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="genderFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3">
                <option value="all">Genre</option>
                <option value="homme">Homme</option>
                <option value="femme">Femme</option>
            </select>
            <select wire:model.live="addressFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3">
                <option value="all">Toutes les adresses</option>
                @foreach($addresses as $addr)
                <option value="{{ $addr }}">{{ $addr }}</option>
                @endforeach
            </select>
            <input type="date" wire:model.live="dateFrom" class="border border-gray-300 rounded-lg text-sm py-2 px-3" placeholder="Du">
            <input type="date" wire:model.live="dateTo" class="border border-gray-300 rounded-lg text-sm py-2 px-3" placeholder="Au">
        </div>
    </div>

    {{-- ═══ Submissions Table ═══ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Candidat</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Formulaire</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Programme</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Responsable</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Statut</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($submissions as $sub)
                    @php
                        $statusColors = [
                            'draft'     => 'bg-gray-100 text-gray-700',
                            'submitted' => 'bg-blue-100 text-blue-800',
                            'in_review' => 'bg-amber-100 text-amber-800',
                            'approved'  => 'bg-green-100 text-green-800',
                            'rejected'  => 'bg-red-100 text-red-800',
                        ];
                        $statusLabels = [
                            'draft' => 'Brouillon', 'submitted' => 'Soumis',
                            'in_review' => 'En révision', 'approved' => 'Approuvé', 'rejected' => 'Rejeté',
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            @if($sub->candidat)
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($sub->candidat->nom ?? '', 0, 1)) }}{{ strtoupper(substr($sub->candidat->prenom ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $sub->candidat->nom }} {{ $sub->candidat->prenom }}</p>
                                    <p class="text-xs text-gray-500">{{ $sub->candidat->email }}</p>
                                </div>
                            </div>
                            @else
                            <span class="text-sm text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $sub->form->title ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $sub->programe->project_name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            @if($sub->reviewer)
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $sub->reviewer->name }}</span>
                            @else
                            <span class="text-xs text-gray-400 italic">Non assigné</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $statusColors[$sub->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $statusLabels[$sub->status] ?? ucfirst($sub->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $sub->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($sub->candidat)
                            <a href="{{ route('admin.candidat.submissions', $sub->candidat_id) }}" class="text-indigo-600 hover:text-indigo-800">
                                <i class="ri-eye-line text-lg"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <i class="ri-file-list-3-line text-4xl text-gray-300 block mb-2"></i>
                            <p class="text-gray-500 font-medium">Aucune soumission trouvée</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $submissions->links() }}</div>
</div>
