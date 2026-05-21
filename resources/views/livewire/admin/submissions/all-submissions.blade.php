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

    @php
        $statusLabels = [
            'submitted' => 'Soumis',
            'in_review' => 'En révision',
            'approved'  => 'Approuvé',
            'rejected'  => 'Rejeté',
        ];
        $hasAdvancedFilter = $formulaireFilter !== 'all' || $responsableFilter !== 'all'
                          || $genderFilter !== 'all' || $addressFilter !== 'all'
                          || $dateFrom || $dateTo;
        $hasAnyFilter = $search || $statusFilter !== 'all' || $programeFilter !== 'all' || $hasAdvancedFilter;
        $advancedCount = (int)($formulaireFilter !== 'all') + (int)($responsableFilter !== 'all')
                       + (int)($genderFilter !== 'all') + (int)($addressFilter !== 'all')
                       + (int)($dateFrom !== '') + (int)($dateTo !== '');
    @endphp

    {{-- ══ Page Header ══ --}}
    <div class="mb-6">
        <h1 class="iuhm_title_1">Gestion des Submissions</h1>
        <p class="text-sm text-gray-500 mt-1">Suivi et gestion de toutes les candidatures soumises.</p>
    </div>

    {{-- ══ Info banner ══ --}}
    <!-- <div class="flex items-start gap-4 bg-green-50 border border-green-200 rounded-2xl px-5 py-4 mb-6">
        <div class="w-9 h-9 rounded-xl bg-green-100 border border-green-300 flex items-center justify-center shrink-0 mt-0.5">
            <i class="ri-shield-check-line text-green-600 text-lg"></i>
        </div>
        <p class="text-sm text-gray-700 leading-relaxed">
            Chaque soumission est examinée et passe par plusieurs statuts :
            <span class="font-semibold text-gray-900">Soumise</span>, lorsqu'elle est en attente d'attribution à un évaluateur ;
            <span class="font-semibold text-amber-700">En révision</span>, lorsqu'elle a été assignée et est en cours d'évaluation ;
            <span class="font-semibold text-green-700">Terminée</span>, lorsqu'elle a validé avec succès toutes les étapes ;
            et <span class="font-semibold text-red-700">Refusée</span>, lorsqu'elle n'a pas satisfait aux critères et a été rejetée.
        </p>
    </div> -->

    {{-- ══ Stat Cards ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">

        @foreach($statCards as $card)
        @php $isActive = ($statusFilter === $card['key']); @endphp
        <button wire:click="filterByStatus('{{ $card['key'] }}')"
                class="bg-white rounded-[30px] shadow-sm h-32 p-6 border border-gray-100 text-left">
            <div class="flex items-start justify-between">
                <p class="text-gray-500 text-[15px] font-bold ">{{ $card['label'] }}</p>
                <div class="w-12 h-12 bg-[#9af89330] rounded-lg flex items-center justify-center">
                    <i class="{{ $card['icon'] }} text-[#066E1B] text-[21px]"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 leading-none">{{ number_format($card['value']) }}</p>
        </button>
        @endforeach
    </div>

    {{-- ══ Main content card ══ --}}
    <div class=" overflow-hidden"
         x-data="{ advanced: {{ $hasAdvancedFilter ? 'true' : 'false' }} }">

        {{-- Tab bar + inline filters --}}
        <div class="flex items-center justify-between border-b border-gray-100 px-6 pt-0 flex-wrap gap-y-2">
            <div class="flex items-center gap-0">
                <button wire:click="$set('tab', 'formulaire')" class="py-4 px-1 mr-6 text-sm font-semibold border-b-2 transition-colors duration-150 {{ $tab === 'formulaire' ? 'px-6 py-4 text-[18px] font-bold text-[#172554] border-b-2 border-green-600' : 'px-6 py-4 text-[18px] font-bold text-[#172554] border-b-2 border-transparent hover:text-gray-700' }}">
                    <i class="ri-file-list-3-line mr-1.5"></i>Soumissions par formulaire
                </button>
                <button wire:click="$set('tab', 'project')" class="py-4 px-1 mr-6 text-sm font-semibold border-b-2 transition-colors duration-150 {{ $tab === 'project' ? 'px-6 py-4 text-[18px] font-bold text-[#172554] border-b-2 border-green-600' : 'px-6 py-4 text-[18px] font-bold text-[#172554] border-b-2 border-transparent hover:text-gray-700' }}">
                    <i class="ri-folder-3-line mr-1.5"></i>Soumissions par projet
                </button>
            </div>

        </div>
            <div class="flex items-center gap-2 p-8 flex-wrap">
                {{-- Search --}}
                <div class="relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text"
                           wire:model.live.debounce.350ms="search"
                           placeholder="Nom, email, matricule..."
                           class="iuhm_search  rounded-lg">
                    @if($search)
                    <button wire:click="clearFilter('search')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-sm"></i>
                    </button>
                    @endif
                </div>

                {{-- Status filter --}}
                <select wire:model.live="statusFilter"
                        class="iuhm_select ">
                    <option value="all">Tous les statuts</option>
                    <option value="submitted">Soumis</option>
                    <option value="in_review">En révision</option>
                    <option value="approved">Approuvé</option>
                    <option value="rejected">Rejeté</option>
                </select>

                {{-- Project filter --}}
                <select wire:model.live="programeFilter"
                        class="iuhm_select ">
                    <option value="all">Tous les projets</option>
                    @foreach($programmes as $p)
                    <option value="{{ $p->id }}">{{ $p->project_name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="formulaireFilter"
                        class="iuhm_select {{ $formulaireFilter !== 'all' ? 'border-indigo-400 text-indigo-700' : 'border-gray-200' }}">
                    <option value="all">Tous les formulaires</option>
                    @foreach($formulaires as $f)
                    <option value="{{ $f->id }}">{{ $f->title }}</option>
                    @endforeach
                </select>

                <select wire:model.live="responsableFilter"
                        class="iuhm_select {{ $responsableFilter !== 'all' ? 'border-indigo-400 text-indigo-700' : 'border-gray-200' }}">
                    <option value="all">Tous les responsables</option>
                    <option value="none">Non assigné</option>
                    @foreach($admins as $admin)
                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                    @endforeach
                </select>

                {{-- Advanced toggle --}}
                <button @click="advanced = !advanced"
                        class="iuhm_select {{ $hasAdvancedFilter ? 'bg-gray-900 text-white border-gray-900' : 'text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                    <i class="ri-equalizer-line text-sm"></i>
                    Filtres
                    @if($advancedCount > 0)
                    <span class="w-5 h-5 {{ $hasAdvancedFilter ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-bold flex items-center justify-center">{{ $advancedCount }}</span>
                    @endif
                </button>

                @if($hasAnyFilter)
                <button wire:click="resetFilters"
                        class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition"
                        title="Réinitialiser tous les filtres">
                    <i class="ri-filter-off-line text-sm"></i>
                </button>
                @endif
            </div>

        {{-- Advanced filters (collapsible) --}}
        <div x-show="advanced"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="px-6 py-3 ">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide whitespace-nowrap">Filtres avancés :</span>



                <select wire:model.live="genderFilter"
                        class="iuhm_select {{ $genderFilter !== 'all' ? 'border-indigo-400 text-indigo-700' : 'border-gray-200' }}">
                    <option value="all">Genre</option>
                    <option value="homme">Homme</option>
                    <option value="femme">Femme</option>
                </select>

                <select wire:model.live="addressFilter"
                        class="iuhm_select {{ $addressFilter !== 'all' ? 'border-indigo-400 text-indigo-700' : 'border-gray-200' }}">
                    <option value="all">Toutes les adresses</option>
                    @foreach($addresses as $addr)
                    <option value="{{ $addr }}">{{ $addr }}</option>
                    @endforeach
                </select>

                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 whitespace-nowrap"><i class="ri-calendar-line text-sm"></i> Du :</span>
                    <input type="date" wire:model.live="dateFrom"
                           class="iuhm_input rounded-lg {{ $dateFrom ? 'border-indigo-400' : 'border-gray-200' }}">
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 whitespace-nowrap">Au :</span>
                    <input type="date" wire:model.live="dateTo"
                           class="iuhm_input rounded-lg {{ $dateTo ? 'border-indigo-400' : 'border-gray-200' }}">
                </div>

                @if($hasAnyFilter)
                <button wire:click="resetFilters"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition">
                    <i class="ri-filter-off-line"></i> Réinitialiser
                </button>
                @endif
            </div>
        </div>

        {{-- Active filter chips --}}
        @php
            $chips = [];
            if ($search)                          $chips[] = ['Recherche : "'.$search.'"', 'search'];
            if ($statusFilter !== 'all')          $chips[] = ['Statut : '.($statusLabels[$statusFilter] ?? $statusFilter), 'statusFilter'];
            if ($programeFilter !== 'all')        $chips[] = ['Projet : '.($programmes->firstWhere('id', $programeFilter)?->project_name ?? $programeFilter), 'programeFilter'];
            if ($formulaireFilter !== 'all')      $chips[] = ['Formulaire : '.($formulaires->firstWhere('id', $formulaireFilter)?->title ?? $formulaireFilter), 'formulaireFilter'];
            if ($responsableFilter === 'none')    $chips[] = ['Responsable : Non assigné', 'responsableFilter'];
            elseif ($responsableFilter !== 'all') $chips[] = ['Responsable : '.($admins->firstWhere('id', $responsableFilter)?->name ?? $responsableFilter), 'responsableFilter'];
            if ($genderFilter !== 'all')          $chips[] = ['Genre : '.ucfirst($genderFilter), 'genderFilter'];
            if ($addressFilter !== 'all')         $chips[] = ['Adresse : '.$addressFilter, 'addressFilter'];
            if ($dateFrom)                        $chips[] = ['Du : '.date('d/m/Y', strtotime($dateFrom)), 'dateFrom'];
            if ($dateTo)                          $chips[] = ['Au : '.date('d/m/Y', strtotime($dateTo)), 'dateTo'];
        @endphp
        @if(count($chips))
        <div class="flex flex-wrap items-center gap-2 px-6 py-2.5 bg-gray-50 border-b border-gray-100">
            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide whitespace-nowrap">Filtres actifs :</span>
            @foreach($chips as [$label, $field])
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-900 text-white text-xs font-medium rounded-full">
                {{ $label }}
                <button wire:click="clearFilter('{{ $field }}')" class="text-white/60 hover:text-white leading-none ml-0.5">
                    <i class="ri-close-line text-xs"></i>
                </button>
            </span>
            @endforeach
        </div>
        @endif

        {{-- ══ FORMULAIRE TAB TABLE ══ --}}
        @if($tab === 'formulaire')
        <div class="relative">
            <div wire:loading
                 class="absolute inset-0 bg-white/70 backdrop-blur-[2px] flex items-center justify-center z-20">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-8 h-8 border-[3px] border-gray-800 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-sm font-medium text-gray-500">Chargement...</span>
                </div>
            </div>

            <table class="w-full">
                <thead>
                    <tr class="bg-[#04103A] border-b border-gray-100">
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase rounded-tl-[10px]">CA</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Projet</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">
                            Soumission
                            <br>
                            <span class="normal-case font-normal text-gray-500 text-xs">Mise à jour</span>
                        </th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Réviseur</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Status</th>
                        <th class="px-6 py-5 text-center text-xs font-semibold text-white uppercase rounded-tr-[10px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($submissions as $sub)
                    @php
                        $rowUrl = $sub->candidat ? route('admin.candidat.submissions', ['id' => $sub->candidat_id, 'projectId' => $sub->programe_id]) : null;
                        $nom = $sub->candidat->nom ?? '';
                        $prenom = $sub->candidat->prenom ?? '';
                        $initials = strtoupper(substr($nom, 0, 1)).strtoupper(substr($prenom, 0, 1));
                        $avatarColors = ['bg-slate-500','bg-indigo-500','bg-violet-500','bg-emerald-500','bg-amber-500','bg-rose-500','bg-cyan-500','bg-pink-500'];
                        $avatarColor = $avatarColors[abs(crc32($nom.$prenom)) % count($avatarColors)];
                        $statusConfig = [
                            'submitted' => ['dot' => 'bg-blue-500',  'bg' => 'bg-blue-50',  'text' => 'text-blue-700',  'label' => 'Soumis'],
                            'in_review' => ['dot' => 'bg-amber-500', 'bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'label' => 'En révision'],
                            'approved'  => ['dot' => 'bg-green-500', 'bg' => 'bg-green-50', 'text' => 'text-green-700', 'label' => 'Terminé'],
                            'rejected'  => ['dot' => 'bg-red-500',   'bg' => 'bg-red-100',  'text' => 'text-red-700',   'label' => 'Refusé'],
                        ];
                        $sc = $statusConfig[$sub->status] ?? ['dot' => 'bg-gray-400','bg' => 'bg-gray-100','text' => 'text-gray-600','label' => ucfirst($sub->status ?? '')];
                        $formulaireCount = $sub->programe?->formulaires()->count() ?? 0;
                        $reviewerName = $sub->ProjectsSubmission?->reviewer?->name ?? ($sub->reviewer?->name ?? null);
                        $reviewerParts = $reviewerName ? explode(' ', $reviewerName, 2) : null;
                    @endphp
                   <tr class="hover:bg-gray-200 bg-gray-100 transition-colors font-bold" style="border-bottom: 10px solid #fbf8fd;">

                        {{-- Avatar --}}
                        <td class="px-5 py-3.5">
                            <div class="w-9 h-9 rounded-full {{ $avatarColor }} text-white flex items-center justify-center text-xs font-bold flex-shrink-0 overflow-hidden">
                                @if($sub->candidat?->profile_image)
                                    <img src="{{ asset('uploads/' . $sub->candidat->profile_image) }}" alt="{{ $nom }} {{ $prenom }}" class="w-full h-full object-cover">
                                @else
                                    {{ $initials ?: '?' }}
                                @endif
                            </div>
                        </td>

                        {{-- Projet + candidat info --}}
                        <td class="px-5 py-3.5">
                            <p class="text-sm font-semibold text-gray-900 leading-tight">
                                {{ Str::limit($sub->programe->project_name ?? 'N/A', 24) }}
                            </p>
                            @if($sub->candidat)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $nom }} {{ $prenom }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $sub->candidat->email }}</p>
                            @endif
                            @if($formulaireCount)
                            <p class="text-xs text-gray-300 mt-0.5">{{ $formulaireCount }} formulaire{{ $formulaireCount > 1 ? 's' : '' }}</p>
                            @endif
                        </td>

                        {{-- Dates --}}
                        <td class="px-5 py-3.5">
                            <p class="text-sm text-gray-700">{{ $sub->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $sub->updated_at->format('d/m/Y') }}</p>
                        </td>

                        {{-- Réviseur --}}
                        <td class="px-5 py-3.5">
                            @if($reviewerParts)
                            <p class="text-sm text-gray-700 font-medium leading-tight">{{ $reviewerParts[0] }}</p>
                            @if(isset($reviewerParts[1]))
                            <p class="text-xs text-gray-400 mt-0.5">{{ $reviewerParts[1] }}</p>
                            @endif
                            @else
                            <span class="text-sm text-gray-300">-</span>
                            @endif
                        </td>

                        {{-- Status badge --}}
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $sc['bg'] }} {{ $sc['text'] }} text-xs font-semibold rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }} inline-block"></span>
                                {{ $sc['label'] }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td onclick="event.stopPropagation()" class="px-5 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($sub->candidat)
                                <a href="{{ route('admin.candidat.submissions', ['id' => $sub->candidat_id, 'projectId' => $sub->programe_id]) }}"
                                   class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition"
                                   title="Voir le dossier">
                                    <i class="ri-eye-line text-sm"></i>
                                </a>
                                <a href="{{ route('admin.candidats.show', $sub->candidat_id) }}"
                                   class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition"
                                   title="Profil candidat">
                                    <i class="ri-user-line text-sm"></i>
                                </a>
                                @else
                                <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <i class="ri-file-search-line text-5xl text-gray-200 block mb-3"></i>
                            <p class="text-gray-700 font-semibold mb-1">Aucun résultat pour ces filtres</p>
                            <p class="text-sm text-gray-400 mb-5">Essayez de modifier vos critères de recherche.</p>
                            <button wire:click="resetFilters"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white hover:bg-gray-800 rounded-xl text-sm font-medium transition">
                                <i class="ri-filter-off-line"></i> Réinitialiser les filtres
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Formulaire Pagination --}}
        <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-3">
            <p class="text-sm text-gray-400">
                Affichage de {{ $submissions->firstItem() ?? 0 }} à {{ $submissions->lastItem() ?? 0 }}
                sur {{ number_format($submissions->total()) }} submissions
            </p>
            <div>{{ $submissions->links() }}</div>
        </div>

        @else
        {{-- ══ PROJECT TAB TABLE ══ --}}
        <div class="relative">
            <div wire:loading
                 class="absolute inset-0 bg-white/70 backdrop-blur-[2px] flex items-center justify-center z-20">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-8 h-8 border-[3px] border-gray-800 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-sm font-medium text-gray-500">Chargement...</span>
                </div>
            </div>

            <table class="w-full">
                <thead>
                    <tr class="bg-[#04103A] border-b border-gray-100">
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase rounded-tl-[10px]">CA</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Projet</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">
                            Soumission<br><span class="normal-case font-normal text-gray-500 text-xs">Mise à jour</span>
                        </th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Réviseur</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Status</th>
                        <th class="px-6 py-5 text-center text-xs font-semibold text-white uppercase rounded-tr-[10px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($ProjectsSubmissions as $projectSub)
                    @php
                        $projectRowUrl = $projectSub->candidat ? route('admin.candidat.submissions', ['id' => $projectSub->candidat_id, 'projectId' => $projectSub->programe_id]) : null;
                        $pNom = $projectSub->candidat->nom ?? '';
                        $pPrenom = $projectSub->candidat->prenom ?? '';
                        $pInitials = strtoupper(substr($pNom,0,1)).strtoupper(substr($pPrenom,0,1));
                        $pAvatarColors = ['bg-slate-500','bg-indigo-500','bg-violet-500','bg-emerald-500','bg-amber-500','bg-rose-500','bg-cyan-500','bg-pink-500'];
                        $pAvatarColor = $pAvatarColors[abs(crc32($pNom.$pPrenom)) % count($pAvatarColors)];
                        $pStatusConfig = [
                            'pending'   => ['dot' => 'bg-blue-500',  'bg' => 'bg-blue-50',  'text' => 'text-blue-700',  'label' => 'Soumis'],
                            'in_review' => ['dot' => 'bg-amber-500', 'bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'label' => 'En révision'],
                            'approved'  => ['dot' => 'bg-green-500', 'bg' => 'bg-green-50', 'text' => 'text-green-700', 'label' => 'Terminé'],
                            'rejected'  => ['dot' => 'bg-red-500',   'bg' => 'bg-red-100',  'text' => 'text-red-700',   'label' => 'Refusé'],
                        ];
                        $pSc = $pStatusConfig[$projectSub->review_status] ?? ['dot' => 'bg-gray-400','bg' => 'bg-gray-100','text' => 'text-gray-600','label' => ucfirst($projectSub->review_status ?? '')];
                        $pReviewerName = $projectSub->reviewer?->name;
                        $pReviewerParts = $pReviewerName ? explode(' ', $pReviewerName, 2) : null;
                        $pActivity = $projectSub->last_activity ?? $projectSub->updated_at;
                    @endphp
                    <tr class="hover:bg-gray-200 bg-gray-100 transition-colors font-bold" style="border-bottom: 10px solid #fbf8fd;">

                        {{-- Avatar --}}
                        <td class="px-5 py-3.5">
                            <div class="w-9 h-9 rounded-full {{ $pAvatarColor }} text-white flex items-center justify-center text-xs font-bold flex-shrink-0 overflow-hidden">
                                @if($projectSub->candidat?->profile_image)
                                    <img src="{{ asset('uploads/' . $projectSub->candidat->profile_image) }}" alt="{{ $pNom }} {{ $pPrenom }}" class="w-full h-full object-cover">
                                @else
                                    {{ $pInitials ?: '?' }}
                                @endif
                            </div>
                        </td>

                        {{-- Projet + candidat --}}
                        <td class="px-5 py-3.5">
                            <p class="text-sm font-semibold text-gray-900 leading-tight">
                                {{ Str::limit($projectSub->project->project_name ?? 'N/A', 24) }}
                            </p>
                            @if($projectSub->candidat)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $pNom }} {{ $pPrenom }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $projectSub->candidat->email }}</p>
                            @endif
                        </td>

                        {{-- Dates --}}
                        <td class="px-5 py-3.5">
                            <p class="text-sm text-gray-700">{{ $projectSub->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $pActivity?->format('d/m/Y') ?? '-' }}</p>
                        </td>

                        {{-- Réviseur --}}
                        <td class="px-5 py-3.5">
                            @if($pReviewerParts)
                            <p class="text-sm text-gray-700 font-medium leading-tight">{{ $pReviewerParts[0] }}</p>
                            @if(isset($pReviewerParts[1]))
                            <p class="text-xs text-gray-400 mt-0.5">{{ $pReviewerParts[1] }}</p>
                            @endif
                            @else
                            <span class="text-sm text-gray-300">-</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $pSc['bg'] }} {{ $pSc['text'] }} text-xs font-semibold rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full {{ $pSc['dot'] }} inline-block"></span>
                                {{ $pSc['label'] }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td onclick="event.stopPropagation()" class="px-5 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($projectSub->candidat)
                                <a href="{{ route('admin.candidat.submissions', ['id' => $projectSub->candidat_id, 'projectId' => $projectSub->programe_id]) }}"
                                   class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition"
                                   title="Voir le dossier">
                                    <i class="ri-eye-line text-sm"></i>
                                </a>
                                <a href="{{ route('admin.candidats.show', $projectSub->candidat_id) }}"
                                   class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition"
                                   title="Profil candidat">
                                    <i class="ri-user-line text-sm"></i>
                                </a>
                                @else
                                <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <i class="ri-folder-open-line text-5xl text-gray-200 block mb-3"></i>
                            <p class="text-gray-700 font-semibold mb-1">Aucune soumission projet trouvée</p>
                            <p class="text-sm text-gray-400 mb-5">Ajustez les filtres pour afficher des résultats.</p>
                            <button wire:click="resetFilters"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white hover:bg-gray-800 rounded-xl text-sm font-medium transition">
                                <i class="ri-filter-off-line"></i> Réinitialiser
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Project Pagination --}}
        <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-3">
            <p class="text-sm text-gray-400">
                Affichage de {{ $ProjectsSubmissions->firstItem() ?? 0 }} à {{ $ProjectsSubmissions->lastItem() ?? 0 }}
                sur {{ number_format($ProjectsSubmissions->total()) }} submissions
            </p>
            <div>{{ $ProjectsSubmissions->links('vendor.pagination.circle') }}</div>
        </div>
        @endif

    </div>{{-- end main card --}}

</div>