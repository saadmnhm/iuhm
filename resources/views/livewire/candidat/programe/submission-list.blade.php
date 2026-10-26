@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div class="min-h-screen bg-gray-50" style="{{ $isArabic ? 'direction:rtl;' : '' }}">
<div class="p-4 sm:p-6 lg:p-8">

    {{-- PAGE TITLE --}}
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">
        {{ $tr('Gestion des Soumissions de Formulaires', 'إدارة تقديم الاستمارات') }}
    </h1>

    {{-- INFO BOX --}}
    <div class="rounded-xl p-4 mb-6 flex items-start gap-3 bg-green-50 border border-green-100">
        <span class="rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 w-9 h-9 bg-green-100">
            <i class="ri-shield-check-line text-green-600 text-xl"></i>
        </span>
        <p class="mb-0 text-sm text-gray-700 leading-relaxed">
            {{ $tr('Retrouvez ci-dessous la liste de toutes vos soumissions de formulaires et candidatures par projet ou type de formulaire.', 'تجدون أدناه قائمة بجميع الاستمارات المقدمة والترشيحات حسب المشروع أو نوع الاستمارة.') }}
        </p>
    </div>

    {{-- STATS ROW --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach ($state_card as $item )
        <div class="bg-white rounded-2xl shadow-sm h-28 p-6 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-base font-bold">{{ $item['label'] }}</p>
                    <p class="text-3xl font-bold text-[#04103A]">{{ $item['data'] }}</p>
                </div>
                <div class="w-12 h-12 {{ $item['bg_color'] ?? 'bg-green-100' }} rounded-lg flex items-center justify-center">
                    <i class="{{ $item['icon'] }} {{ $item['icon_color'] ?? 'text-green-700' }} text-[21px]"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- SEARCH & TABLE --}}
    <div class="rounded-2xl overflow-hidden mb-4 bg-white border border-gray-100 shadow-sm">

        {{-- TABS --}}
        <div class="flex items-center gap-4 mb-4 overflow-x-auto border-b border-gray-100 px-4 py-3" style="width: 100%;">
            <button wire:click="setTab('all')" 
                    class="whitespace-nowrap pb-3 text-sm font-semibold transition-colors duration-150 px-2 relative border-0 bg-transparent {{ $activeTab === 'all' ? 'text-green-600 font-bold' : 'text-gray-700' }}"
                    style="outline: none; font-size: 1.05rem; cursor: pointer; border-bottom: 3px solid {{ $activeTab === 'all' ? '#16a34a' : 'transparent' }} !important; margin-bottom: -1px;">
                <i class="ri-file-list-3-line mr-1"></i>
                {{ $tr('Tous les formulaires', 'جميع الاستمارات') }}
            </button>
            <button wire:click="setTab('project')" 
                    class="whitespace-nowrap pb-3 text-sm font-semibold transition-colors duration-150 px-2 relative border-0 bg-transparent {{ $activeTab === 'project' ? 'text-green-600 font-bold' : 'text-gray-700' }}"
                    style="outline: none; font-size: 1.05rem; cursor: pointer; border-bottom: 3px solid {{ $activeTab === 'project' ? '#16a34a' : 'transparent' }} !important; margin-bottom: -1px;">
                <i class="ri-folders-line mr-1"></i>
                {{ $tr('Formulaires groupés par projet', 'حسب المشاريع') }}
            </button>
        </div>
        <div class="px-4 py-4 flex flex-col sm:flex-row justify-between gap-3 items-start sm:items-center">

            <div class="relative w-full sm:max-w-md">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg" @if($isArabic) style="left: auto; right: 12px;" @endif></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                       class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-100 border-0 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:bg-white transition-all iuhm_search"
                       style="font-size: 0.875rem; height: 44px;"
                       placeholder="{{ $tr('Rechercher un projet ou formulaire...', 'البحث عن مشروع أو استمارة...') }}"
                       @if($isArabic) style="padding-left: 1rem; padding-right: 2.5rem;" @endif>
            </div>
            
            <div class="w-full sm:w-auto">
                <select wire:model.live="statusFilter" class="w-full sm:w-48 px-4 py-3 rounded-full bg-gray-100 border-0 text-gray-900 focus:ring-2 focus:ring-green-500 focus:bg-white transition-all appearance-none bg-no-repeat bg-right pr-10 iuhm_select"
                        style="font-size: 0.875rem; height: 44px; background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z'/%3E%3C/svg%3E\"); background-position: right 0.75rem center; background-size: 1.25rem;">
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
<div class="overflow-hidden">

    {{-- Loading overlay --}}
    <div wire:loading class="absolute inset-0 bg-white/70 backdrop-blur-sm flex items-center justify-center z-20">
        <div class="flex flex-col items-center gap-2">
            <div class="w-8 h-8 border-[3px] border-[#04103A] border-t-transparent rounded-full animate-spin"></div>
            <span class="text-sm text-gray-500 font-medium">{{ $tr('Chargement...', 'جاري التحميل...') }}</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px]">

            {{-- HEADER --}}
            <thead>
                <tr class="bg-[#04103A] text-white">
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">{{ $tr('Projet', 'مشروع') }}</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">{{ $tr('Description', 'وصف') }}</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">
                        {{ $tr('Soumission', 'إرسال') }}<br>
                        <span class="text-gray-300 font-normal normal-case">{{ $tr('Mise à jour', 'تحديث') }}</span>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">{{ $tr('Statut', 'حالة') }}</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase">{{ $tr('Actions', 'إجراءات') }}</th>
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
                                   class="w-9 h-9 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-colors"
                                   title="{{ $tr('Ouvrir', 'فتح') }}">
                                    <i class="ri-eye-line text-sm"></i>
                                </a>
                            @endif

                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-12 text-gray-400">
                        <i class="ri-inbox-archive-line block mb-2 text-4xl"></i>
                        {{ $tr('Aucune donnée trouvée', 'لم يتم العثور على بيانات') }}
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>
</div>
@else
<div class="overflow-hidden">

    {{-- Loading --}}
    <div wire:loading class="absolute inset-0 bg-white/70 backdrop-blur-sm flex items-center justify-center z-20">
        <div class="w-8 h-8 border-[3px] border-[#04103A] border-t-transparent rounded-full animate-spin"></div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px]">

            {{-- HEADER --}}
            <thead>
                <tr class="bg-[#04103A] text-white">
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">{{ $tr('Projet', 'مشروع') }}</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">{{ $tr('Formulaire', 'استمارة') }}</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">{{ $tr('Description', 'وصف') }}</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">{{ $tr('Soumission', 'إرسال') }}</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase">{{ $tr('Statut', 'حالة') }}</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase">{{ $tr('Actions', 'إجراءات') }}</th>
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
                                        {{ count($group['forms']) }} {{ $tr('formulaires', 'استمارة') }}
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
                               class="btn-sm rounded-xl inline-flex items-center gap-1.5 font-semibold transition-colors"
                               style="padding: 0 16px; height: 36px; background: #f0fdf4; color: #10b981; font-size: 0.8rem; border: 1px solid #bbf7d0;">
                                <span>{{ $tr('Fiche Projet', 'ملف المشروع') }}</span>
                                <i class="ri-arrow-right-line" style="font-size: 0.9rem;"></i>
                            </a>
                        </td>

                    </tr>

                @endforeach
            @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-gray-400">
                        <i class="ri-inbox-archive-line block mb-2 text-4xl"></i>
                        {{ $tr('Aucun projet trouvé', 'لم يتم العثور على مشروع') }}
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