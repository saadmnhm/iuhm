<div class="max-w-7xl mx-auto">

    {{-- ═══ Statistics ═══ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['Candidats assignés', $stats['candidats_assigned'], 'ri-user-follow-line', 'indigo'],
            ['En révision', $stats['candidats_in_review'], 'ri-time-line', 'amber'],
            ['Approuvés', $stats['candidats_approved'], 'ri-checkbox-circle-line', 'green'],
            ['Rejetés', $stats['candidats_rejected'], 'ri-close-circle-line', 'red'],
        ] as [$label, $value, $icon, $color])
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">{{ $label }}</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $value }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-{{ $color }}-100 flex items-center justify-center">
                    <i class="{{ $icon }} text-{{ $color }}-600 text-lg"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ═══ Tabs ═══ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex border-b border-gray-100">
            <button wire:click="$set('tab', 'candidats')"
                    class="flex-1 py-3 text-center text-sm font-semibold transition {{ $tab === 'candidats' ? 'text-indigo-700 border-b-2 border-indigo-600 bg-indigo-50/50' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="ri-user-line mr-1"></i> Candidats assignés
            </button>
            <button wire:click="$set('tab', 'submissions')"
                    class="flex-1 py-3 text-center text-sm font-semibold transition {{ $tab === 'submissions' ? 'text-indigo-700 border-b-2 border-indigo-600 bg-indigo-50/50' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="ri-file-list-3-line mr-1"></i> Soumissions assignées ({{ $stats['submissions_total'] }})
            </button>
        </div>

        {{-- ═══ Toolbar ═══ --}}
        <div class="p-4 flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px] relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" wire:model.live="search" placeholder="Rechercher par nom, email, matricule..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">
            </div>
            <select wire:model.live="statusFilter" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                <option value="all">Tous les statuts</option>
                @if($tab === 'candidats')
                <option value="in_review">En révision</option>
                <option value="approved">Approuvé</option>
                <option value="rejected">Rejeté</option>
                @else
                <option value="draft">Brouillon</option>
                <option value="submitted">Soumis</option>
                <option value="in_review">En révision</option>
                <option value="approved">Approuvé</option>
                <option value="rejected">Rejeté</option>
                @endif
            </select>
        </div>
    </div>

    {{-- ═══ CANDIDATS TAB ═══ --}}
    @if($tab === 'candidats')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($items as $candidat)
        @php
            $statusColors = [
                'in_review' => 'bg-amber-100 text-amber-800',
                'approved'  => 'bg-green-100 text-green-800',
                'rejected'  => 'bg-red-100 text-red-800',
            ];
            $statusLabels = [
                'in_review' => 'En révision',
                'approved'  => 'Approuvé',
                'rejected'  => 'Rejeté',
            ];
        @endphp
        <a href="{{ route('admin.candidat.submissions', $candidat->id) }}"
           class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-indigo-200 transition group block">
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-sm font-bold shadow">
                            {{ strtoupper(substr($candidat->nom ?? '', 0, 1)) }}{{ strtoupper(substr($candidat->prenom ?? '', 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 group-hover:text-indigo-700 transition">{{ $candidat->nom }} {{ $candidat->prenom }}</h4>
                            @if($candidat->matricule)
                            <span class="text-xs text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded font-mono">{{ $candidat->matricule }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $statusColors[$candidat->review_status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $statusLabels[$candidat->review_status] ?? ($candidat->review_status ?? 'N/A') }}
                    </span>
                </div>

                <div class="space-y-1.5 text-sm text-gray-600">
                    @if($candidat->email)
                    <div class="flex items-center gap-2"><i class="ri-mail-line text-gray-400 w-4"></i> {{ $candidat->email }}</div>
                    @endif
                    @if($candidat->phone)
                    <div class="flex items-center gap-2"><i class="ri-phone-line text-gray-400 w-4"></i> {{ $candidat->phone }}</div>
                    @endif
                    @if($candidat->reviewed_at)
                    <div class="flex items-center gap-2"><i class="ri-calendar-check-line text-gray-400 w-4"></i> Assigné le {{ $candidat->reviewed_at->format('d/m/Y H:i') }}</div>
                    @endif
                </div>

                @if($candidat->review_notes)
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-500 italic line-clamp-2"><i class="ri-sticky-note-line mr-1"></i> {{ $candidat->review_notes }}</p>
                </div>
                @endif
            </div>
        </a>
        @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
            <i class="ri-user-follow-line text-5xl text-gray-300 mb-3 block"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucun candidat assigné</h3>
            <p class="text-sm text-gray-500">Vous n'avez pas encore de candidats assignés pour révision.</p>
        </div>
        @endforelse
    </div>

    {{-- ═══ SUBMISSIONS TAB ═══ --}}
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($items as $sub)
        @php
            $statusColors = [
                'draft'     => 'bg-gray-100 text-gray-600',
                'submitted' => 'bg-blue-100 text-blue-800',
                'in_review' => 'bg-amber-100 text-amber-800',
                'approved'  => 'bg-green-100 text-green-800',
                'rejected'  => 'bg-red-100 text-red-800',
            ];
            $statusLabels = [
                'draft'     => 'Brouillon',
                'submitted' => 'Soumis',
                'in_review' => 'En révision',
                'approved'  => 'Approuvé',
                'rejected'  => 'Rejeté',
            ];
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $sub->form->title ?? 'Formulaire' }}</h4>
                        @if($sub->programe)
                        <p class="text-xs text-gray-500">{{ $sub->programe->project_name }}</p>
                        @endif
                    </div>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $statusColors[$sub->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $statusLabels[$sub->status] ?? ucfirst($sub->status) }}
                    </span>
                </div>

                @if($sub->candidat)
                <div class="flex items-center gap-2 mb-3 p-2 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-xs font-bold">
                        {{ strtoupper(substr($sub->candidat->nom ?? '', 0, 1)) }}{{ strtoupper(substr($sub->candidat->prenom ?? '', 0, 1)) }}
                    </div>
                    <div class="text-sm">
                        <p class="font-medium text-gray-800">{{ $sub->candidat->nom }} {{ $sub->candidat->prenom }}</p>
                        <p class="text-xs text-gray-500">{{ $sub->candidat->email }}</p>
                    </div>
                </div>
                @endif

                <div class="space-y-1.5 text-sm text-gray-600">
                    @if($sub->submitted_at)
                    <div class="flex items-center gap-2"><i class="ri-send-plane-line text-gray-400 w-4"></i> Soumis le {{ $sub->submitted_at->format('d/m/Y H:i') }}</div>
                    @endif
                    @if($sub->reviewed_at)
                    <div class="flex items-center gap-2"><i class="ri-checkbox-circle-line text-gray-400 w-4"></i> Révisé le {{ $sub->reviewed_at->format('d/m/Y H:i') }}</div>
                    @endif
                </div>

                @if($sub->review_notes)
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-500 italic line-clamp-2"><i class="ri-sticky-note-line mr-1"></i> {{ $sub->review_notes }}</p>
                </div>
                @endif

                @if($sub->candidat)
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.candidat.submissions', $sub->candidat_id) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
                        <i class="ri-arrow-right-line mr-1"></i> Voir les soumissions du candidat
                    </a>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
            <i class="ri-file-list-3-line text-5xl text-gray-300 mb-3 block"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucune soumission assignée</h3>
            <p class="text-sm text-gray-500">Vous n'avez pas encore de soumissions assignées pour révision.</p>
        </div>
        @endforelse
    </div>
    @endif

    <div class="mt-6">{{ $items->links() }}</div>
</div>
