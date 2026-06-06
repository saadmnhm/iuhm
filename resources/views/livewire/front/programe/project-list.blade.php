@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div style="min-height:100vh; background:#f4f5f7; {{ $isArabic ? 'direction:rtl;' : '' }}">
<div class="p-3 p-md-4">

    {{-- ── PAGE TITLE ── --}}
    <h1 class="fw-bold mb-3" style="color:#0f172a; font-size:clamp(1.4rem,3vw,2rem);">
        {{ $tr('Gestion des Projets et des Candidatures', 'إدارة المشاريع والترشيحات') }}
    </h1>

    {{-- ── INFO BOX ── --}}
    <div class="rounded-4 p-3 mb-4 d-flex align-items-start gap-3" style="background:#f0fdf4; border:1px solid #bbf7d0;">
        <span class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
              style="width:36px;height:36px;background:#dcfce7;">
            <i class="ri-shield-check-line" style="color:#16a34a;font-size:1rem;"></i>
        </span>
        <p class="mb-0 small" style="color:#374151; line-height:1.6;">
            {{ $tr('Chaque candidature est examinée et passe par plusieurs statuts :', 'كل ترشيح يمر بعدة حالات:') }}
            <strong>{{ $tr('Soumise', 'مقدمة') }}</strong>{{ $tr(', lorsqu\'elle est en attente d\'attribution à un évaluateur ;', '، في انتظار التعيين لمقيّم؛') }}
            <strong>{{ $tr('En révision', 'قيد المراجعة') }}</strong>{{ $tr(', lorsqu\'elle a été assignée et est en cours d\'évaluation ;', '، تم تعيينها وهي قيد التقييم؛') }}
            <strong>{{ $tr('Terminée', 'منتهية') }}</strong>{{ $tr(', lorsqu\'elle a validé avec succès toutes les étapes ;', '، اجتازت بنجاح جميع المراحل؛') }}
            {{ $tr('et', 'و') }} <strong>{{ $tr('Refusée', 'مرفوضة') }}</strong>{{ $tr(', lorsqu\'elle n\'a pas satisfait aux critères et a été rejetée.', '، لم تستوفِ المعايير وتم رفضها.') }}
        </p>
    </div>

    {{-- ── SEARCH & FILTERS ROW ── --}}
    <div class="d-flex justify-content-between align-items-center gap-3 mb-4 flex-wrap">
        <div class="position-relative flex-grow-1" style="max-width: 500px;">
            <i class="ri-search-line position-absolute start-0 top-50 translate-y-middle ms-3 text-slate-400" style="transform: translateY(-50%) !important;"></i>
            <input wire:model.live.debounce.300ms="search" type="text"
                   class="form-control iuhm_search rounded-xl ps-5"
                   style="font-size:.82rem; height: 42px;"
                   placeholder="{{ $tr('Rechercher un projet...', 'بحث عن مشروع...') }}">
        </div>
        
        <div>
            <select wire:model.live="statusFilter" class="form-select iuhm_select rounded-full" style="min-width:160px; font-size:.82rem; height: 42px;">
                <option value="">{{ $tr('Tous les statuts', 'جميع الحالات') }}</option>
                <option value="submitted">{{ $tr('En révision', 'قيد المراجعة') }}</option>
                <option value="approved">{{ $tr('Approuvé', 'مقبول') }}</option>
                <option value="not_eligible">{{ $tr('Non éligible', 'غير مؤهل') }}</option>
                <option value="eligible">{{ $tr('Disponible', 'متاح') }}</option>
            </select>
        </div>
    </div>

    {{-- ── PROJECTS CARDS GRID ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        @forelse($rows as $row)
            @php
                $color = $row['color'] ?? '#2f5496';
                if (!str_starts_with($color, '#')) {
                    $color = '#' . $color;
                }
            @endphp
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 d-flex flex-column transition-all hover:shadow-md h-100" style="min-height: 280px; border: 1px solid #f1f5f9 !important;">
                
                {{-- Top Row: Icon Container & Eligibility Badge --}}
                <div class="d-flex justify-content-between align-items-start mb-3 w-100">
                    {{-- Icon with backdrop --}}
                    <div class="rounded-[16px] d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width: 48px; height: 48px; background: {{ $color }}15; color: {{ $color }};">
                        <i class="{{ $row['icon'] }} text-[22px]"></i>
                    </div>

                    {{-- Eligibility Pill --}}
                    @if ($row['eligible'])
                        <span class="badge px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider text-emerald-600 border border-emerald-200"
                              style="background: #f0fdf4; font-size: 0.72rem;">
                            {{ $tr('Eligible', 'مؤهل') }}
                        </span>
                    @else
                        <span class="badge px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider text-rose-600 border border-rose-200"
                              style="background: #fff1f2; font-size: 0.72rem;">
                            {{ $tr('Non Eligible', 'غير مؤهل') }}
                        </span>
                    @endif
                </div>

                {{-- Second Row: Criteria Pills (Age and Location) --}}
                <div class="d-flex justify-between gap-2 mb-3">
                    {{-- Age range --}}
                    <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-3 text-xs font-semibold"
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

                        <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-3 text-xs font-semibold text-truncate"
                            style="background: #f1f5f9; color: #475569; font-size: 0.74rem; max-width: 100%;"
                            title="{{ is_array($locations) ? implode(', ', $locations) : $locations }}">

                            @foreach(array_slice($locations, 0, 2) as $location)
                                <span>{{ $location }}</span>
                            @endforeach

                            @if($count > 2)
                                <span class="ms-1 px-2 py-1 rounded-pill bg-secondary text-white">
                                    +{{ $count - 2 }}
                                </span>
                            @endif

                        </div>
                </div>

                {{-- Third Row: Title --}}
                <h3 class="font-bold text-[23px] text-[#04103A] mb-2">{{ $row['name'] }}</h3>

                {{-- Fourth Row: Description --}}
                <p class="text-gray-500 text-[17px] leading-relaxed flex-grow-1 mb-4" style="color: #64748b !important;">
                    {{ $row['description'] }}
                </p>

                {{-- Fifth Row: Bottom action and sub-status --}}
                <div class="d-flex align-items-center justify-content-between pt-3   w-100 mt-auto">
                    <div>
                      
                            @if ($row['eligible'])
                                <!-- <span class="badge rounded-pill px-2.5 py-1.5"
                                      style="background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; font-size: 0.72rem; font-weight: 600;">
                                    {{ $tr('Disponible', 'متاح') }}
                                </span> -->
                            @else
                                <!-- <span class="badge rounded-pill px-2.5 py-1.5"
                                      style="background: #fff1f2; color: #be123c; border: 1px solid #ffe4e6; font-size: 0.72rem; font-weight: 600;">
                                    {{ $tr('Non Éligible', 'غير مؤهل') }}
                                </span> -->
                            @endif
                    </div>

                    <a href="{{ route('user.project.detail', $row['id']) }}"
                       class="btn btn-sm rounded-3 d-inline-flex align-items-center gap-1.5 border-0 font-semibold"
                       style="padding: 0 16px; height: 36px; background: #e0e7ff; color: #4338ca; font-size: 0.8rem; transition: background 0.2s;">
                        <span>{{ $tr('Détails', 'تفاصيل') }}</span>
                        <i class="ri-arrow-right-line" style="font-size: 0.9rem;"></i>
                    </a>
                </div>

            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="ri-inbox-archive-line d-block mb-2" style="font-size: 2.5rem; color: #cbd5e1;"></i>
                {{ $tr('Aucun projet trouvé.', 'لم يتم العثور على مشاريع.') }}
            </div>
        @endforelse
    </div>
    

    {{-- ── PAGINATION ── --}}
    @if ($totalPages > 1)
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-3 py-3 border-top mt-4 bg-white rounded-[24px] shadow-sm border border-gray-100">
        <span class="text-muted" style="font-size:.8rem;">
            {{ $tr('Affichage de', 'عرض') }}
            {{ (($currentPage - 1) * $perPage) + 1 }}
            {{ $tr('à', 'إلى') }}
            {{ min($currentPage * $perPage, $total) }}
            {{ $tr('sur', 'من') }} <strong>{{ $total }}</strong>
            {{ $tr('projets', 'مشاريع') }}
        </span>
        <div class="d-flex gap-1 align-items-center">
            <button wire:click="previousPage" @if($currentPage <= 1) disabled @endif
                    class="btn btn-sm rounded-3 d-inline-flex align-items-center justify-content-center"
                    style="width:32px;height:32px;padding:0;background:#f8fafc;border:1px solid #e2e8f0;color:#374151;">
                <i class="ri-arrow-left-s-line"></i>
            </button>
            @for($p = max(1, $currentPage - 2); $p <= min($totalPages, $currentPage + 2); $p++)
                <button wire:click="gotoPage({{ $p }})"
                        class="btn btn-sm rounded-3 fw-semibold d-inline-flex align-items-center justify-content-center"
                        style="width:32px;height:32px;padding:0;font-size:.8rem;
                               {{ $p === $currentPage ? 'background:#0f2441;color:#fff;border:0;' : 'background:#f8fafc;border:1px solid #e2e8f0;color:#374151;' }}">
                    {{ $p }}
                </button>
            @endfor
            <button wire:click="nextPage" @if($currentPage >= $totalPages) disabled @endif
                    class="btn btn-sm rounded-3 d-inline-flex align-items-center justify-content-center"
                    style="width:32px;height:32px;padding:0;background:#f8fafc;border:1px solid #e2e8f0;color:#374151;">
                <i class="ri-arrow-right-s-line"></i>
            </button>
        </div>
    </div>
    @endif

</div>
</div>
