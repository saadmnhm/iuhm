@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div style="min-height:100vh; background:#f4f5f7; {{ $isArabic ? 'direction:rtl;' : '' }}">
<div class="p-3 p-md-4">

    {{-- ── PAGE TITLE ── --}}
    <h1 class="fw-bold mb-3" style="color:#0f172a; font-size:clamp(1.4rem,3vw,2rem);">
        {{ $tr('Gestion des Soumissions de Formulaires', 'إدارة تقديم الاستمارات') }}
    </h1>

    {{-- ── INFO BOX ── --}}
    <div class="rounded-4 p-3 mb-4 d-flex align-items-start gap-3" style="background:#f0fdf4; border:1px solid #bbf7d0;">
        <span class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
              style="width:36px;height:36px;background:#dcfce7;">
            <i class="ri-shield-check-line" style="color:#16a34a;font-size:1rem;"></i>
        </span>
        <p class="mb-0 small" style="color:#374151; line-height:1.6;">
            {{ $tr('Retrouvez ci-dessous la liste de toutes vos soumissions de formulaires et candidatures par projet ou type de formulaire.', 'تجدون أدناه قائمة بجميع الاستمارات المقدمة والترشيحات حسب المشروع أو نوع الاستمارة.') }}
        </p>
    </div>

    {{-- ── STATS ROW ── --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @foreach ($state_card as $item )
        <div class="bg-white rounded-[30px] shadow-sm h-32 p-6 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-[16px] font-bold">{{ $item['label'] }}</p>
                    <p class="text-3xl font-bold text-[#04103A]">{{ $item['data'] }}</p>
                </div>
                <div class="w-12 h-12 {{ $item['bg_color'] ?? 'bg-[#9af89330]' }} rounded-lg flex items-center justify-center">
                    <i class="{{ $item['icon'] }} {{ $item['icon_color'] ?? 'text-[#066E1B]' }} text-[21px]"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── SEARCH & TABLE ── --}}
    <div class="rounded-4 overflow-hidden mb-4">

        {{-- ── TABS ── --}}
        <div class="d-flex align-items-center gap-4 mb-4" style="border-bottom: 2px solid #e2e8f0; width: 100%;">
            <button wire:click="setTab('all')" 
                    class="pb-3 text-sm font-semibold transition-colors duration-150 px-2 position-relative border-0 bg-transparent {{ $activeTab === 'all' ? 'text-green-600 font-bold' : 'text-[#172554]' }}"
                    style="outline: none; font-size: 1.05rem; cursor: pointer; border-bottom: 3px solid {{ $activeTab === 'all' ? '#16a34a' : 'transparent' }} !important; margin-bottom: -2px;">
                <i class="ri-file-list-3-line me-1"></i>
                {{ $tr('Tous les formulaires', 'جميع الاستمارات') }}
            </button>
            <button wire:click="setTab('project')" 
                    class="pb-3 text-sm font-semibold transition-colors duration-150 px-2 position-relative border-0 bg-transparent {{ $activeTab === 'project' ? 'text-green-600 font-bold' : 'text-[#172554]' }}"
                    style="outline: none; font-size: 1.05rem; cursor: pointer; border-bottom: 3px solid {{ $activeTab === 'project' ? '#16a34a' : 'transparent' }} !important; margin-bottom: -2px;">
                <i class="ri-folders-line me-1"></i>
                {{ $tr('Formulaires groupés par projet', 'حسب المشاريع') }}
            </button>
        </div>

        
        <div class="py-4 d-flex justify-content-between gap-3 flex-wrap align-items-center">
            <div class="position-relative flex-grow-1" style="max-width: 500px;">
                <i class="ri-search-line position-absolute start-0 top-50 translate-y-middle ms-3 text-slate-400" style="transform: translateY(-50%) !important;"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                       class="w-full iuhm_search rounded-xl ps-5"
                       style="font-size:.82rem; height: 42px;"
                       placeholder="{{ $tr('Rechercher un projet ou formulaire...', 'البحث عن مشروع أو استمارة...') }}">
            </div>
            
            <div>
                <select wire:model.live="statusFilter" class="form-select iuhm_select rounded-full" style="min-width:160px; font-size:.82rem; height: 42px;">
                    <option value="">{{ $tr('Tous les statuts', 'جميع الحالات') }}</option>
                    <option value="draft">{{ $tr('Brouillon', 'مسودة') }}</option>
                    <option value="submitted">{{ $tr('Soumis', 'مقدمة') }}</option>
                    <option value="in_review">{{ $tr('En révision', 'قيد المراجعة') }}</option>
                    <option value="approved">{{ $tr('Approuvé', 'مقبول') }}</option>
                    <option value="rejected">{{ $tr('Rejeté', 'مرفوض') }}</option>
                </select>
            </div>
        </div>

@if ($activeTab === 'all')
<div class="relative bg-white rounded-[24px] border border-gray-100 overflow-hidden">

    {{-- Loading overlay --}}
    <div wire:loading class="absolute inset-0 bg-white/70 backdrop-blur-sm flex items-center justify-center z-20">
        <div class="flex flex-col items-center gap-2">
            <div class="w-8 h-8 border-[3px] border-gray-800 border-t-transparent rounded-full animate-spin"></div>
            <span class="text-sm text-gray-500 font-medium">Chargement...</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">

            {{-- HEADER --}}
            <thead>
                <tr class="bg-[#04103A] text-white">
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Projet</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Description</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">
                        Soumission<br>
                        <span class="text-gray-300 font-normal normal-case">Mise à jour</span>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Statut</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase">Actions</th>
                </tr>
            </thead>

            {{-- BODY --}}
            <tbody class="divide-y divide-gray-100">

            @forelse($rows as $row)
                @php
                    $color = $row['status_color'] ?? '#3b82f6';
                    if (!str_starts_with($color, '#')) $color = '#'.$color;
                @endphp

                <tr class="hover:bg-gray-50 transition">

                    {{-- PROJECT --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold"
                                 style="background: {{ $row['form_color'] ?? '#3b82f6' }}">
                                <i class="{{ $row['form_icon'] }}"></i>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $row['form_name'] }}
                                </p>
                                @if($row['project_name'])
                                    <p class="text-xs text-gray-500">
                                        {{ $row['project_name'] }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- DESCRIPTION --}}
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-600 line-clamp-2">
                            {{ $row['description'] }}
                        </p>
                    </td>

                    {{-- DATES --}}
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-700">
                            {{ $row['submitted_at'] ?? '-' }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ $row['updated_at'] ?? '-' }}
                        </p>
                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                              style="background: {{ $color }}15; color: {{ $color }};">
                            <span class="w-1.5 h-1.5 rounded-full"
                                  style="background: {{ $color }}"></span>
                            {{ $row['status_label'] }}
                        </span>
                    </td>

                    {{-- ACTIONS --}}
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">

                            @if($row['form_slug'])
                                <a href="{{ route('user.project.formulaire', [
                                    'projectId' => $row['project_id'],
                                    'formulaireSlug' => $row['form_slug'],
                                    'order' => $row['order']
                                ]) }}"
                                   class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center"
                                   title="Ouvrir">
                                    <i class="ri-eye-line text-sm"></i>
                                </a>
                            @endif

                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-gray-400">
                        Aucune donnée trouvée
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>
</div>
@else
<div class="relative bg-white rounded-[24px] border border-gray-100 overflow-hidden">

    {{-- Loading --}}
    <div wire:loading class="absolute inset-0 bg-white/70 backdrop-blur-sm flex items-center justify-center z-20">
        <div class="w-8 h-8 border-[3px] border-gray-800 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">

            {{-- HEADER --}}
            <thead>
                <tr class="bg-[#04103A] text-white">
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Projet</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Formulaire</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Description</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Soumission</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Statut</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase">Actions</th>
                </tr>
            </thead>

            {{-- BODY --}}
            <tbody class="divide-y divide-gray-100">

            @forelse($grouped as $group)
                @php
                    $projectColor = $group['color'] ?? '#2f5496';
                    if (!str_starts_with($projectColor, '#')) $projectColor = '#'.$projectColor;
                @endphp

                @foreach($group['forms'] as $sub)

                    @php
                        $formColor = $sub['form_color'] ?? '#3b82f6';
                        if (!str_starts_with($formColor, '#')) $formColor = '#'.$formColor;
                    @endphp

                    <tr class="hover:bg-gray-50 transition">

                        {{-- PROJECT --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white"
                                     style="background: {{ $projectColor }}">
                                    <i class="{{ $group['icon'] }}"></i>
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $group['name'] }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ count($group['forms']) }} formulaires
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- FORM --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white"
                                     style="background: {{ $formColor }}">
                                    <i class="{{ $sub['form_icon'] }} text-xs"></i>
                                </div>

                                <span class="text-sm font-medium text-gray-900">
                                    {{ $sub['form_name'] }}
                                </span>
                            </div>
                        </td>

                        {{-- DESCRIPTION --}}
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600 line-clamp-2">
                                {{ $sub['description'] }}
                            </p>
                        </td>

                        {{-- DATE --}}
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-700">
                                {{ $sub['submitted_at'] ?? '-' }}
                            </p>
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                                  style="background: {{ $sub['status_color'] }}15; color: {{ $sub['status_color'] }};">
                                <span class="w-1.5 h-1.5 rounded-full"
                                      style="background: {{ $sub['status_color'] }}"></span>
                                {{ $sub['status_label'] }}
                            </span>
                        </td>

                        {{-- ACTIONS --}}
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('user.project.detail', $group['id']) }}"
                               class="btn btn-sm rounded-3 d-inline-flex align-items-center gap-1.5 border-0 font-semibold"
                               style="padding: 0 16px; height: 36px; background: #e0f5ec; color: #10b981; font-size: 0.8rem;">
                                <span>{{ $tr('Fiche Projet', 'ملف المشروع') }}</span>
                                <i class="ri-arrow-right-line" style="font-size: 0.9rem;"></i>
                            </a>
                        </td>

                    </tr>

                @endforeach
            @empty
                <tr>
                    <td colspan="6" class="text-center py-10 text-gray-400">
                        Aucun projet trouvé
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>
</div>
@endif
    </div>
    </div>

</div>
</div>