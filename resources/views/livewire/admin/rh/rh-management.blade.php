<div class="max-w-7xl mx-auto">

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3">
        <i class="ri-checkbox-circle-line text-green-500 text-xl"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    {{-- ═══ Statistics ═══ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['Total', $statistics['total'], 'ri-team-line', 'blue'],
            ['Actifs', $statistics['active'], 'ri-checkbox-circle-line', 'green'],
            ['Inactifs', $statistics['inactive'], 'ri-close-circle-line', 'red'],
            ['En congé', $statistics['en_conge'], 'ri-calendar-event-line', 'amber'],
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

    {{-- ═══ Toolbar ═══ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px] relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" wire:model.live="search" placeholder="Rechercher par nom, email, matricule, CIN..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">
            </div>
            <select wire:model.live="statusFilter" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                <option value="all">Tous les statuts</option>
                <option value="active">Actif</option>
                <option value="inactive">Inactif</option>
                <option value="en_conge">En congé</option>
                <option value="quitte">Quitté</option>
            </select>
            <select wire:model.live="departementFilter" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                <option value="all">Tous les départements</option>
                @foreach($departements as $dep)
                <option value="{{ $dep }}">{{ $dep }}</option>
                @endforeach
            </select>
            <a href="{{ route('admin.rh.print.list') }}" target="_blank" class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <i class="ri-printer-line mr-1"></i> Imprimer liste
            </a>
            <a href="{{ route('admin.rh.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <i class="ri-add-line mr-1"></i> Nouvel employé
            </a>
        </div>
    </div>

    {{-- ═══ Employee Cards ═══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($employees as $emp)
        @php
            $statusColors = [
                'active'   => 'bg-green-100 text-green-800',
                'inactive' => 'bg-red-100 text-red-800',
                'en_conge' => 'bg-amber-100 text-amber-800',
                'quitte'   => 'bg-gray-100 text-gray-600',
            ];
            $statusLabels = [
                'active'   => 'Actif',
                'inactive' => 'Inactif',
                'en_conge' => 'En congé',
                'quitte'   => 'Quitté',
            ];
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition group">
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        @if($emp->photo_path)
                        <img src="{{ asset('storage/' . $emp->photo_path) }}" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow">
                        @else
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-lg font-bold shadow">
                            {{ strtoupper(substr($emp->nom, 0, 1)) }}{{ strtoupper(substr($emp->prenom, 0, 1)) }}
                        </div>
                        @endif
                        <div>
                            <a href="{{ route('admin.rh.show', $emp->id) }}" class="font-semibold text-gray-900 hover:text-indigo-600 transition">{{ $emp->nom }} {{ $emp->prenom }}</a>
                            @if($emp->poste)
                            <p class="text-xs text-gray-500">{{ $emp->poste }}</p>
                            @endif
                        </div>
                    </div>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $statusColors[$emp->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $statusLabels[$emp->status] ?? $emp->status }}
                    </span>
                </div>

                <div class="space-y-1.5 text-sm mb-4">
                    @if($emp->matricule)
                    <div class="flex items-center gap-2 text-gray-600"><i class="ri-hashtag text-gray-400 w-4"></i> {{ $emp->matricule }}</div>
                    @endif
                    @if($emp->departement)
                    <div class="flex items-center gap-2 text-gray-600"><i class="ri-building-line text-gray-400 w-4"></i> {{ $emp->departement }}</div>
                    @endif
                    @if($emp->email)
                    <div class="flex items-center gap-2 text-gray-600"><i class="ri-mail-line text-gray-400 w-4"></i> {{ $emp->email }}</div>
                    @endif
                    @if($emp->phone)
                    <div class="flex items-center gap-2 text-gray-600"><i class="ri-phone-line text-gray-400 w-4"></i> {{ $emp->phone }}</div>
                    @endif
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="ri-file-text-line text-gray-400 w-4"></i>
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded font-medium">{{ $emp->contrat_type }}</span>
                        @if($emp->date_embauche)
                        <span class="text-xs text-gray-400">depuis {{ $emp->date_embauche->format('d/m/Y') }}</span>
                        @endif
                    </div>
                </div>

                <div class="flex gap-2 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.rh.show', $emp->id) }}"
                       class="flex-1 px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg transition text-center">
                        <i class="ri-eye-line mr-1"></i> Voir
                    </a>
                    <a href="{{ route('admin.rh.edit', $emp->id) }}"
                       class="flex-1 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition text-center">
                        <i class="ri-pencil-line mr-1"></i> Modifier
                    </a>
                    <a href="{{ route('admin.rh.print.attestation', $emp->id) }}" target="_blank"
                       class="px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition" title="Attestation">
                        <i class="ri-file-text-line"></i>
                    </a>
                    <button wire:click="delete({{ $emp->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer cet employé ?"
                            class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-lg transition">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
            <i class="ri-team-line text-5xl text-gray-300 mb-3 block"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucun employé trouvé</h3>
            <p class="text-sm text-gray-500">Ajoutez des employés pour commencer la gestion RH.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $employees->links() }}</div>
</div>
