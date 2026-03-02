<div>

    {{-- ══ Toast notification ══ --}}
    @if(session('toast'))
    <div x-data="{ visible: true }"
         x-show="visible"
         x-init="setTimeout(() => visible = false, 3500)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-50 bg-green-600 text-white px-5 py-3.5 rounded-xl shadow-2xl flex items-center gap-3 min-w-[260px]">
        <i class="ri-checkbox-circle-fill text-2xl flex-shrink-0"></i>
        <p class="font-medium text-sm flex-1">{{ session('toast') }}</p>
        <button @click="visible = false" class="text-white/70 hover:text-white"><i class="ri-close-line"></i></button>
    </div>
    @endif

    {{-- Browser event toasts from Livewire --}}
    <div x-data="{ visible: false, message: '' }"
         x-on:notify.window="message = $event.detail.message; visible = true; setTimeout(() => visible = false, 3500)"
         x-show="visible"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-50 bg-green-600 text-white px-5 py-3.5 rounded-xl shadow-2xl flex items-center gap-3 min-w-[260px] hidden">
        <i class="ri-checkbox-circle-fill text-2xl flex-shrink-0"></i>
        <p class="font-medium text-sm flex-1" x-text="message"></p>
        <button @click="visible = false" class="text-white/70 hover:text-white"><i class="ri-close-line"></i></button>
    </div>

    {{-- ══ Statistics Cards (clickable, with trend badges) ══ --}}
    @php
        $statCards = [
            ['key' => 'all',       'label' => 'Total',        'value' => $stats['total'],     'icon' => 'ri-file-list-3-line',     'color' => 'blue',   'week' => null],
            ['key' => 'draft',     'label' => 'Brouillon',    'value' => $stats['draft'],     'icon' => 'ri-draft-line',           'color' => 'gray',   'week' => null],
            ['key' => 'submitted', 'label' => 'Soumis',       'value' => $stats['submitted'], 'icon' => 'ri-send-plane-line',      'color' => 'indigo', 'week' => $stats['submitted_week']],
            ['key' => 'in_review', 'label' => 'En révision',  'value' => $stats['in_review'], 'icon' => 'ri-time-line',            'color' => 'amber',  'week' => $stats['in_review_week']],
            ['key' => 'approved',  'label' => 'Approuvés',    'value' => $stats['approved'],  'icon' => 'ri-checkbox-circle-line', 'color' => 'green',  'week' => $stats['approved_week']],
            ['key' => 'rejected',  'label' => 'Rejetés',      'value' => $stats['rejected'],  'icon' => 'ri-close-circle-line',    'color' => 'red',    'week' => $stats['rejected_week']],
        ];
        $statusColors = [
            'draft'     => 'bg-gray-100 text-gray-700',
            'submitted' => 'bg-indigo-100 text-indigo-800',
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
        $statusDropdown = [
            'draft'     => ['gray',   'Brouillon'],
            'submitted' => ['indigo', 'Soumis'],
            'in_review' => ['amber',  'En révision'],
            'approved'  => ['green',  'Approuvé'],
            'rejected'  => ['red',    'Rejeté'],
        ];
    @endphp

    {{-- Stat cards grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        @foreach($statCards as $card)
        @php $isActive = ($statusFilter === $card['key']); @endphp
        <button wire:click="filterByStatus('{{ $card['key'] }}')"
                class="bg-white rounded-xl shadow-sm border p-4 text-left hover:shadow-md transition-all duration-200
                       {{ $isActive
                            ? 'border-'.$card['color'].'-400  ring-'.$card['color'].'-200'
                            : 'border-gray-100 hover:border-'.$card['color'].'-200' }}">
            <div class="flex items-start justify-between mb-2">
                <div class="w-9 h-9 rounded-lg bg-{{ $card['color'] }}-100 flex items-center justify-center flex-shrink-0">
                    <i class="{{ $card['icon'] }} text-{{ $card['color'] }}-600"></i>
                </div>
                @if($isActive)
                <span class="text-xs font-medium px-1.5 py-0.5 rounded-full bg-{{ $card['color'] }}-100 text-{{ $card['color'] }}-700">actif</span>
                @endif
            </div>
            <p class="text-xs text-gray-500 leading-tight">{{ $card['label'] }}</p>
            <p class="text-2xl font-bold text-gray-900 mt-0.5 leading-none">{{ $card['value'] }}</p>
            @if(!empty($card['week']))
            <p class="text-xs text-green-600 font-medium mt-1.5 flex items-center gap-0.5">
                <i class="ri-arrow-up-line text-xs"></i> +{{ $card['week'] }} cette semaine
            </p>
            @endif
        </button>
        @endforeach
    </div>

    {{-- ══ Filter Bar ══ --}}
    @php
        $hasAdvancedFilter = $formulaireFilter !== 'all' || $responsableFilter !== 'all'
                          || $genderFilter !== 'all' || $addressFilter !== 'all'
                          || $dateFrom || $dateTo;
        $hasAnyFilter = $search || $statusFilter !== 'all' || $programeFilter !== 'all' || $hasAdvancedFilter;
        $advancedCount = (int)($formulaireFilter !== 'all') + (int)($responsableFilter !== 'all')
                       + (int)($genderFilter !== 'all') + (int)($addressFilter !== 'all')
                       + (int)($dateFrom !== '') + (int)($dateTo !== '');
    @endphp

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4"
         x-data="{ advanced: {{ $hasAdvancedFilter ? 'true' : 'false' }} }">

        {{-- Row 1: Main filters --}}
        <div class="flex flex-wrap items-center gap-3">

            {{-- Search input --}}
            <div class="flex-1 min-w-[220px] relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text"
                       wire:model.live.debounce.350ms="search"
                       placeholder="Nom, email, matricule, formulaire..."
                       class="w-full pl-9 pr-9 py-2.5 border border-gray-300 rounded-lg text-sm focus: focus:ring-indigo-400 outline-none transition">
                @if($search)
                <button wire:click="clearFilter('search')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line text-sm"></i>
                </button>
                @endif
            </div>

            {{-- Status filter --}}
            <select wire:model.live="statusFilter"
                    class="border rounded-lg text-sm py-2.5 px-3 focus: focus:ring-indigo-400 outline-none bg-white transition
                           {{ $statusFilter !== 'all' ? 'border-indigo-400 text-indigo-700 font-medium' : 'border-gray-300' }}">
                <option value="all">Tous les statuts</option>
                <option value="draft">Brouillon</option>
                <option value="submitted">Soumis</option>
                <option value="in_review">En révision</option>
                <option value="approved">Approuvé</option>
                <option value="rejected">Rejeté</option>
            </select>

            {{-- Programme filter --}}
            <select wire:model.live="programeFilter"
                    class="border rounded-lg text-sm py-2.5 px-3 focus: focus:ring-indigo-400 outline-none bg-white transition
                           {{ $programeFilter !== 'all' ? 'border-indigo-400 text-indigo-700 font-medium' : 'border-gray-300' }}">
                <option value="all">Tous les programmes</option>
                @foreach($programmes as $p)
                <option value="{{ $p->id }}">{{ $p->project_name }}</option>
                @endforeach
            </select>

            {{-- Advanced filters toggle --}}
            <button @click="advanced = !advanced"
                    class="flex items-center gap-1.5 px-3.5 py-2.5 text-sm font-medium border rounded-lg transition
                           {{ $hasAdvancedFilter ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700' : 'text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                <i class="ri-equalizer-line"></i>
                Filtres avancés
                @if($advancedCount > 0)
                <span class="w-5 h-5 {{ $hasAdvancedFilter ? 'bg-white text-indigo-600' : 'bg-indigo-100 text-indigo-700' }} rounded-full text-xs font-bold flex items-center justify-center">{{ $advancedCount }}</span>
                @endif
                <i x-show="!advanced" class="ri-arrow-down-s-line text-sm"></i>
                <i x-show="advanced" class="ri-arrow-up-s-line text-sm"></i>
            </button>

            {{-- Reset button (only when a filter is active) --}}
            @if($hasAnyFilter)
            <button wire:click="resetFilters"
                    class="flex items-center gap-1.5 px-3.5 py-2.5 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition">
                <i class="ri-filter-off-line"></i> Réinitialiser
            </button>
            @endif
        </div>

        {{-- Advanced / secondary filters (collapsible) --}}
        <div x-show="advanced"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="mt-3 pt-3 border-t border-gray-100">
            <div class="flex flex-wrap items-center gap-3">

                <select wire:model.live="formulaireFilter"
                        class="border rounded-lg text-sm py-2 px-3 outline-none bg-white transition
                               {{ $formulaireFilter !== 'all' ? 'border-indigo-400 text-indigo-700' : 'border-gray-300' }}">
                    <option value="all">Tous les formulaires</option>
                    @foreach($formulaires as $f)
                    <option value="{{ $f->id }}">{{ $f->title }}</option>
                    @endforeach
                </select>

                <select wire:model.live="responsableFilter"
                        class="border rounded-lg text-sm py-2 px-3 outline-none bg-white transition
                               {{ $responsableFilter !== 'all' ? 'border-indigo-400 text-indigo-700' : 'border-gray-300' }}">
                    <option value="all">Tous les responsables</option>
                    <option value="none">Non assigné</option>
                    @foreach($admins as $admin)
                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="genderFilter"
                        class="border rounded-lg text-sm py-2 px-3 outline-none bg-white transition
                               {{ $genderFilter !== 'all' ? 'border-indigo-400 text-indigo-700' : 'border-gray-300' }}">
                    <option value="all">Genre</option>
                    <option value="homme">Homme</option>
                    <option value="femme">Femme</option>
                </select>

                <select wire:model.live="addressFilter"
                        class="border rounded-lg text-sm py-2 px-3 outline-none bg-white transition
                               {{ $addressFilter !== 'all' ? 'border-indigo-400 text-indigo-700' : 'border-gray-300' }}">
                    <option value="all">Toutes les adresses</option>
                    @foreach($addresses as $addr)
                    <option value="{{ $addr }}">{{ $addr }}</option>
                    @endforeach
                </select>

                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 flex items-center gap-1 whitespace-nowrap">
                        <i class="ri-calendar-line text-sm"></i> Du :
                    </span>
                    <input type="date" wire:model.live="dateFrom"
                           class="border rounded-lg text-sm py-2 px-3 outline-none transition
                                  {{ $dateFrom ? 'border-indigo-400' : 'border-gray-300' }}">
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 whitespace-nowrap">Au :</span>
                    <input type="date" wire:model.live="dateTo"
                           class="border rounded-lg text-sm py-2 px-3 outline-none transition
                                  {{ $dateTo ? 'border-indigo-400' : 'border-gray-300' }}">
                </div>
            </div>
        </div>
    </div>

    {{-- ══ Active filter chips ══ --}}
    @php
        $chips = [];
        if ($search)                          $chips[] = ['Recherche : "'.$search.'"',                                                    'search'];
        if ($statusFilter !== 'all')          $chips[] = ['Statut : '.($statusLabels[$statusFilter] ?? $statusFilter),                    'statusFilter'];
        if ($programeFilter !== 'all')        $chips[] = ['Programme : '.($programmes->firstWhere('id', $programeFilter)?->project_name ?? $programeFilter), 'programeFilter'];
        if ($formulaireFilter !== 'all')      $chips[] = ['Formulaire : '.($formulaires->firstWhere('id', $formulaireFilter)?->title ?? $formulaireFilter), 'formulaireFilter'];
        if ($responsableFilter === 'none')    $chips[] = ['Responsable : Non assigné',                                                    'responsableFilter'];
        elseif ($responsableFilter !== 'all') $chips[] = ['Responsable : '.($admins->firstWhere('id', $responsableFilter)?->name ?? $responsableFilter), 'responsableFilter'];
        if ($genderFilter !== 'all')          $chips[] = ['Genre : '.ucfirst($genderFilter),                                              'genderFilter'];
        if ($addressFilter !== 'all')         $chips[] = ['Adresse : '.$addressFilter,                                                    'addressFilter'];
        if ($dateFrom)                        $chips[] = ['Du : '.date('d/m/Y', strtotime($dateFrom)),                                    'dateFrom'];
        if ($dateTo)                          $chips[] = ['Au : '.date('d/m/Y', strtotime($dateTo)),                                      'dateTo'];
    @endphp

    @if(count($chips))
    <div class="flex flex-wrap items-center gap-2 mb-4">
        <span class="text-xs text-gray-500 font-medium whitespace-nowrap">Filtres actifs :</span>
        @foreach($chips as [$label, $field])
        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-full border border-indigo-100">
            {{ $label }}
            <button wire:click="clearFilter('{{ $field }}')" class="text-indigo-400 hover:text-indigo-700 leading-none">
                <i class="ri-close-line"></i>
            </button>
        </span>
        @endforeach
        <button wire:click="resetFilters" class="text-xs text-red-500 hover:text-red-700 font-medium underline ml-1">
            Tout effacer
        </button>
    </div>
    @endif

    {{-- ══ Submissions Table ══ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 relative">

        {{-- Loading overlay --}}
        <div wire:loading
             class="absolute inset-0 bg-white/70 backdrop-blur-[2px] flex items-center justify-center z-20 rounded-xl">
            <div class="flex flex-col items-center gap-2">
                <div class="w-8 h-8 border-[3px] border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                <span class="text-sm font-medium text-gray-500">Chargement...</span>
            </div>
        </div>

            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Candidat</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Formulaire</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Programme</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Responsable</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Statut</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Date</th>
                        <th class="px-4 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="">
                    @forelse($submissions as $sub)
                    @php
                        $rowUrl = $sub->candidat ? route('admin.candidat.submissions', $sub->candidat_id) : null;
                    @endphp
                    <tr class="{{ $rowUrl ? 'cursor-pointer hover:bg-indigo-50/40' : 'hover:bg-gray-50' }} transition-colors duration-100"
                        @if($rowUrl) onclick="window.location='{{ $rowUrl }}'" @endif>

                        {{-- Candidat cell --}}
                        <td class="px-4 py-3.5">
                            @if($sub->candidat)
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">
                                   @if($sub->candidat->profile_image)
                                        <img src="{{ asset('uploads/' . $sub->candidat->profile_image) }}" alt="{{ $sub->candidat->nom }} {{ $sub->candidat->prenom }}" class="w-full h-full object-cover rounded-full">
                                    @else
                                    {{ strtoupper(substr($sub->candidat->nom ?? '', 0, 1)) }}{{ strtoupper(substr($sub->candidat->prenom ?? '', 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 leading-tight">{{ $sub->candidat->nom }} {{ $sub->candidat->prenom }}</p>
                                    <p class="text-xs text-gray-400 leading-tight mt-0.5">{{ $sub->candidat->email }}</p>
                                    @if($sub->candidat->matricule)
                                    <span class="inline-block mt-1 px-1.5 py-px bg-indigo-100 text-indigo-600 text-xs rounded font-medium">{{ $sub->candidat->matricule }}</span>
                                    @endif
                                </div>
                            </div>
                            @else
                            <span class="text-sm text-gray-400 italic">Candidat supprimé</span>
                            @endif
                        </td>

                        {{-- Formulaire cell --}}
                        <td class="px-4 py-3.5">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-50 text-purple-700 text-xs font-medium rounded-full">
                                <i class="ri-file-text-line text-purple-400 text-xs"></i>
                                {{ Str::limit($sub->form->title ?? 'N/A', 26) }}
                            </span>
                        </td>

                        {{-- Programme cell --}}
                        <td class="px-4 py-3.5">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full">
                                <i class="ri-folder-3-line text-blue-400 text-xs"></i>
                                {{ Str::limit($sub->programe->project_name ?? 'N/A', 20) }}
                            </span>
                        </td>

                        {{-- Responsable — interactive assign dropdown --}}
                        <td onclick="event.stopPropagation()" class="px-4 py-3.5">
                            <div x-data="{ ropen: false }" class="relative inline-block">
                                @if($sub->reviewer)
                                <button @click="ropen = !ropen"
                                        class="flex items-center gap-1.5 text-xs font-medium px-2.5 py-1.5 bg-blue-50 text-blue-700 rounded-full border border-blue-100 hover:bg-blue-100 transition">
                                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        {{ strtoupper(substr($sub->reviewer->name, 0, 1)) }}
                                    </span>
                                    {{ Str::before($sub->reviewer->name, ' ') ?: $sub->reviewer->name }}
                                    <i class="ri-arrow-down-s-line text-xs text-blue-400"></i>
                                </button>
                                @else
                                <button @click="ropen = !ropen"
                                        class="flex items-center gap-1 text-xs font-medium px-2.5 py-1.5 border border-dashed border-gray-300 text-gray-400 rounded-full hover:border-indigo-400 hover:text-indigo-600 hover:bg-indigo-50 transition">
                                    <i class="ri-user-add-line"></i> Assigner
                                </button>
                                @endif

                                <div x-show="ropen" @click.outside="ropen = false"
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     class="absolute z-50 mt-1 left-0 bg-white rounded-xl shadow-xl border border-gray-100 py-1 w-48 max-h-60 overflow-y-auto">
                                    @if($sub->reviewer)
                                    <button @click="ropen = false; $wire.assignResponsable({{ $sub->id }}, null)"
                                            class="w-full text-left px-3 py-2 text-xs hover:bg-red-50 text-red-500 transition flex items-center gap-2">
                                        <i class="ri-user-unfollow-line"></i> Retirer le responsable
                                    </button>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    @endif
                                    @foreach($admins as $admin)
                                    <button @click="ropen = false; $wire.assignResponsable({{ $sub->id }}, {{ $admin->id }})"
                                            class="w-full text-left px-3 py-2 text-xs hover:bg-indigo-50 text-gray-700 transition flex items-center gap-2 {{ $sub->reviewed_by === $admin->id ? 'bg-indigo-50 font-semibold' : '' }}">
                                        <span class="w-5 h-5 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </span>
                                        {{ $admin->name }}
                                        @if($sub->reviewed_by === $admin->id)
                                        <i class="ri-check-line ml-auto text-indigo-600"></i>
                                        @endif
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </td>

                        {{-- Statut — interactive change popover --}}
                        <td onclick="event.stopPropagation()" class="px-4 py-3.5">
                            <div x-data="{ sopen: false }" class="relative inline-block">
                                <button @click="sopen = !sopen"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full hover:opacity-90 transition {{ $statusColors[$sub->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusLabels[$sub->status] ?? ucfirst($sub->status) }}
                                    <i class="ri-arrow-down-s-line text-xs opacity-60"></i>
                                </button>
                                <div x-show="sopen" @click.outside="sopen = false"
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     class="absolute z-50 mt-1 left-0 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 w-40">
                                    @foreach($statusDropdown as $skey => [$scolor, $slabel])
                                    <button @click="sopen = false; $wire.updateStatus({{ $sub->id }}, '{{ $skey }}')"
                                            class="w-full text-left px-3 py-1.5 text-xs hover:bg-gray-50 transition flex items-center gap-2 {{ $sub->status === $skey ? 'font-bold bg-gray-50' : '' }}">
                                        <span class="w-2 h-2 rounded-full bg-{{ $scolor }}-500 flex-shrink-0"></span>
                                        {{ $slabel }}
                                        @if($sub->status === $skey)
                                        <i class="ri-check-line ml-auto text-gray-500"></i>
                                        @endif
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </td>

                        {{-- Date cell --}}
                        <td class="px-4 py-3.5">
                            <div class="text-sm text-gray-700">{{ $sub->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $sub->created_at->diffForHumans() }}</div>
                        </td>

                        {{-- Actions dot-menu --}}
                        <td onclick="event.stopPropagation()" class="px-4 py-3.5 text-center">
                            <div x-data="{ aopen: false }" class="relative inline-block">
                                <button @click="aopen = !aopen"
                                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition"
                                        title="Actions">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <div x-show="aopen" @click.outside="aopen = false"
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     class="absolute z-50 right-0 mt-1 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 w-48">
                                    @if($sub->candidat)
                                    <a href="{{ route('admin.candidat.submissions', $sub->candidat_id) }}"
                                       class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                        <i class="ri-eye-line text-indigo-500 w-4 text-center"></i>
                                        Voir le dossier
                                    </a>
                                    <a href="{{ route('admin.candidats.show', $sub->candidat_id) }}"
                                       class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
                                        <i class="ri-user-line text-blue-500 w-4 text-center"></i>
                                        Profil candidat
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <p class="px-4 py-1 text-xs text-gray-400 font-semibold uppercase tracking-wide">Changer statut</p>
                                    @foreach($statusDropdown as $skey => [$scolor, $slabel])
                                    @if($sub->status !== $skey)
                                    <button @click="aopen = false; $wire.updateStatus({{ $sub->id }}, '{{ $skey }}')"
                                            class="w-full flex items-center gap-2.5 px-4 py-1.5 text-sm text-gray-600 hover:bg-gray-50 transition">
                                        <span class="w-2 h-2 rounded-full bg-{{ $scolor }}-500"></span>
                                        {{ $slabel }}
                                    </button>
                                    @endif
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center">
                            <i class="ri-file-search-line text-5xl text-gray-300 block mb-3"></i>
                            <p class="text-gray-700 font-semibold mb-1">Aucun résultat pour ces filtres</p>
                            <p class="text-sm text-gray-400 mb-5">Essayez de modifier vos critères de recherche</p>
                            <button wire:click="resetFilters"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 rounded-lg text-sm font-medium transition">
                                <i class="ri-filter-off-line"></i> Réinitialiser les filtres
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $submissions->links() }}
    </div>

</div>