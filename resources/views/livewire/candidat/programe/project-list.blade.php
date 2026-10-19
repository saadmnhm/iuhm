@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div class="min-h-screen bg-gray-50" style="{{ $isArabic ? 'direction:rtl;' : '' }}">
<div class="p-4 sm:p-6 lg:p-8">

    {{-- PAGE TITLE --}}
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">
        {{ $tr('Gestion des Projets et des Candidatures', 'إدارة المشاريع والترشيحات') }}
    </h1>

    {{-- INFO BOX --}}
    <div class="rounded-xl p-4 mb-6 flex items-start gap-3 bg-green-50 border border-green-100">
        <span class="rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 w-10 h-10 bg-green-100">
            <i class="ri-shield-check-line text-green-600 text-xl"></i>
        </span>
        <p class="mb-0 text-sm text-gray-700 leading-relaxed">
            {{ $tr('Chaque candidature est examinée et passe par plusieurs statuts :', 'كل ترشيح يمر بعدة حالات:') }}
            <strong>{{ $tr('Soumise', 'مقدمة') }}</strong>{{ $tr(', lorsqu\'elle est en attente d\'attribution à un évaluateur ;', '، في انتظار التعيين لمقيّم؛') }}
            <strong>{{ $tr('En révision', 'قيد المراجعة') }}</strong>{{ $tr(', lorsqu\'elle a été assignée et est en cours d\'évaluation ;', '، تم تعيينها وهي قيد التقييم؛') }}
            <strong>{{ $tr('Terminée', 'منتهية') }}</strong>{{ $tr(', lorsqu\'elle a validé avec succès toutes les étapes ;', '، اجتازت بنجاح جميع المراحل؛') }}
            {{ $tr('et', 'و') }} <strong>{{ $tr('Refusée', 'مرفوضة') }}</strong>{{ $tr(', lorsqu\'elle n\'a pas satisfait aux critères et a été rejetée.', '، لم تستوفِ المعايير وتم رفضها.') }}
        </p>
    </div>

    {{-- SEARCH & FILTERS ROW --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div class="relative w-full sm:max-w-md">
            <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg" @if($isArabic) style="left: auto; right: 12px;" @endif></i>
            <input wire:model.live.debounce.300ms="search" type="text"
                   class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-100 border-0 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:bg-white transition-all iuhm_search"
                   style="font-size: 0.875rem; height: 44px;"
                   placeholder="{{ $tr('Rechercher un projet...', 'بحث عن مشروع...') }}"
                   @if($isArabic) style="padding-left: 1rem; padding-right: 2.5rem;" @endif>
        </div>
        
        <div class="w-full sm:w-auto">
            <select wire:model.live="statusFilter" class="w-full sm:w-48 px-4 py-3 rounded-full bg-gray-100 border-0 text-gray-900 focus:ring-2 focus:ring-green-500 focus:bg-white transition-all appearance-none bg-no-repeat bg-right pr-10 iuhm_select"
                    style="font-size: 0.875rem; height: 44px; background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z'/%3E%3C/svg%3E\"); background-position: right 0.75rem center; background-size: 1.25rem;">
                <option value="">{{ $tr('Tous les statuts', 'جميع الحالات') }}</option>
                <option value="submitted">{{ $tr('En révision', 'قيد المراجعة') }}</option>
                <option value="approved">{{ $tr('Approuvé', 'مقبول') }}</option>
                <option value="not_eligible">{{ $tr('Non éligible', 'غير مؤهل') }}</option>
                <option value="eligible">{{ $tr('Disponible', 'متاح') }}</option>
            </select>
        </div>
    </div>

    {{-- PROJECTS CARDS GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-8">
        @forelse($rows as $row)
        @php
            $color = $row['color'] ?? '#2f5496';
            if (!str_starts_with($color, '#')) {
                $color = '#' . $color;
            }
        @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col transition-all hover:shadow-md h-full" style="min-height: 280px; border: 1px solid #f1f5f9 !important;">
            
            {{-- Top Row: Icon Container & Eligibility Badge --}}
            <div class="flex justify-between items-start mb-3 w-full">
                {{-- Icon with backdrop --}}
                <div class="rounded-xl flex items-center justify-center flex-shrink-0"
                     style="width: 48px; height: 48px; background: {{ $color }}15; color: {{ $color }};">
                    <i class="{{ $row['icon'] }} text-[22px]"></i>
                </div>

                {{-- Eligibility Pill --}}
                @if ($row['eligible'])
                    <span class="badge px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider text-green-700 border border-green-200"
                          style="background: #f0fdf4; font-size: 0.72rem;">
                        {{ $tr('Eligible', 'مؤهل') }}
                    </span>
                @else
                    <span class="badge px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider text-red-700 border border-red-200"
                          style="background: #fff1f2; font-size: 0.72rem;">
                        {{ $tr('Non Eligible', 'غير مؤهل') }}
                    </span>
                @endif
            </div>

            {{-- Show reasons directly below the eligibility badge when not eligible --}}
            @if (! $row['eligible'] && ! empty($row['reasons']))
            <div class="mb-3 w-full">
                <ul class="mb-0 text-sm text-red-600" style="line-height:1.4; margin-left:1rem;">
                    @foreach($row['reasons'] as $reason)
                        <li>{{ $reason }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Second Row: Criteria Pills (Age and Location) --}}
            <div class="flex flex-wrap gap-2 mb-3">
                {{-- Age range --}}
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold"
                     style="background: #f1f5f9; color: #475569; font-size: 0.74rem;">
                    {{ $row['min_age'] ?? '0' }} - {{ $row['max_age'] ?? '99' }} {{ $tr('ANS', 'سنة') }}
                </div>

                {{-- Location list --}}
                @php
                    $locations = $row['locations'];

                    // convert string → array if needed
                    if (is_string($locations)) {
                        $locations = array_filter(array_map('trim', explode(',', $locations)));
                    }

                    $count = is_countable($locations) ? count($locations) : 0;
                @endphp

                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold truncate"
                     style="background: #f1f5f9; color: #475569; font-size: 0.74rem; max-width: 100%;"
                     title="{{ is_array($locations) ? implode(', ', $locations) : $locations }}">

                    @foreach(array_slice($locations, 0, 2) as $location)
                        <span>{{ $location }}</span>
                    @endforeach

                    @if($count > 2)
                        <span class="ms-1 px-2 py-1 rounded-full bg-gray-500 text-white text-xs">
                            +{{ $count - 2 }}
                        </span>
                    @endif

                </div>
            </div>

            {{-- Third Row: Title --}}
            <h3 class="font-bold text-lg text-[#04103A] mb-2 line-clamp-2">{{ $row['name'] }}</h3>

            {{-- Fourth Row: Description --}}
            <p class="text-gray-500 text-base leading-relaxed flex-grow mb-4 line-clamp-3" style="color: #64748b !important;">
                {{ $row['description'] }}
            </p>

            {{-- Fifth Row: Bottom action --}}
            <div class="flex items-center justify-between pt-3 w-full mt-auto">
                <div>
                    {{-- Status indicators shown in top row now --}}
                </div>

                <a href="{{ route('user.project.detail', $row['id']) }}"
                   class="btn btn-sm rounded-xl inline-flex items-center gap-1.5 border-0 font-semibold transition-colors"
                   style="padding: 0 16px; height: 36px; background: #e0e7ff; color: #4338ca; font-size: 0.8rem;">
                    <span>{{ $tr('Détails', 'تفاصيل') }}</span>
                    <i class="ri-arrow-right-line" style="font-size: 0.9rem;"></i>
                </a>
            </div>

        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-400">
            <i class="ri-inbox-archive-line block mb-3 text-5xl"></i>
            <p class="text-gray-500">{{ $tr('Aucun projet trouvé.', 'لم يتم العثور على مشاريع.') }}</p>
        </div>
        @endforelse
    </div>
    
    {{-- PAGINATION --}}
    @if ($totalPages > 1)
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-4 py-4 bg-white rounded-2xl shadow-sm border border-gray-100">
        <span class="text-sm text-gray-500">
            {{ $tr('Affichage de', 'عرض') }}
            {{ (($currentPage - 1) * $perPage) + 1 }}
            {{ $tr('à', 'إلى') }}
            {{ min($currentPage * $perPage, $total) }}
            {{ $tr('sur', 'من') }} <strong>{{ $total }}</strong>
            {{ $tr('projets', 'مشاريع') }}
        </span>
        <div class="flex gap-1 items-center">
            <button wire:click="previousPage" @if($currentPage <= 1) disabled @endif
                    class="btn btn-sm rounded-xl inline-flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    style="width:36px;height:36px;padding:0;background:#f8fafc;border:1px solid #e2e8f0;color:#374151;"
                    aria-label="{{ $tr('Page précédente', 'الصفحة السابقة') }}">
                <i class="ri-arrow-left-s-line"></i>
            </button>
            @for($p = max(1, $currentPage - 2); $p <= min($totalPages, $currentPage + 2); $p++)
                <button wire:click="gotoPage({{ $p }})"
                        class="btn btn-sm rounded-xl fw-semibold inline-flex items-center justify-center transition-colors"
                        style="width:36px;height:36px;padding:0;font-size:.8rem;
                               {{ $p === $currentPage ? 'background:#0f2441;color:#fff;border:0;' : 'background:#f8fafc;border:1px solid #e2e8f0;color:#374151;' }}">
                    {{ $p }}
                </button>
            @endfor
            <button wire:click="nextPage" @if($currentPage >= $totalPages) disabled @endif
                    class="btn btn-sm rounded-xl inline-flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    style="width:36px;height:36px;padding:0;background:#f8fafc;border:1px solid #e2e8f0;color:#374151;"
                    aria-label="{{ $tr('Page suivante', 'الصفحة التالية') }}">
                <i class="ri-arrow-right-s-line"></i>
            </button>
        </div>
    </div>
    @endif

</div>
</div>