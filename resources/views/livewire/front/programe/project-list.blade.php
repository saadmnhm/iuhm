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

    {{-- ── STATS ROW ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="rounded-4 p-3 p-md-4 text-center" style="background:#fff;border:1px solid #e9ecef;box-shadow:0 1px 4px rgba(0,0,0,.04);">
                <div class="fw-bold mb-1" style="font-size:2rem;color:#0f2441;">{{ $total }}</div>
                <div class="text-uppercase fw-semibold" style="font-size:.62rem;letter-spacing:.08em;color:#6c757d;">{{ $tr('Total Candidatures', 'إجمالي الترشيحات') }}</div>
                <div class="mt-2 d-flex justify-content-center">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:#f0f4ff;">
                        <i class="ri-file-list-3-line" style="color:#0f2441;font-size:.95rem;"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="rounded-4 p-3 p-md-4 text-center" style="background:#fff;border:1px solid #e9ecef;box-shadow:0 1px 4px rgba(0,0,0,.04);">
                <div class="fw-bold mb-1" style="font-size:2rem;color:#166534;">{{ $approved }}</div>
                <div class="text-uppercase fw-semibold" style="font-size:.62rem;letter-spacing:.08em;color:#6c757d;">{{ $tr('Candidatures Approuvé', 'المقبولة') }}</div>
                <div class="mt-2 d-flex justify-content-center">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:#f0fdf4;">
                        <i class="ri-checkbox-circle-line" style="color:#166534;font-size:.95rem;"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="rounded-4 p-3 p-md-4 text-center" style="background:#fff;border:1px solid #e9ecef;box-shadow:0 1px 4px rgba(0,0,0,.04);">
                <div class="fw-bold mb-1" style="font-size:2rem;color:#92400e;">{{ $inReview }}</div>
                <div class="text-uppercase fw-semibold" style="font-size:.62rem;letter-spacing:.08em;color:#6c757d;">{{ $tr('Candidatures En Révision', 'قيد المراجعة') }}</div>
                <div class="mt-2 d-flex justify-content-center">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:#fffbeb;">
                        <i class="ri-time-line" style="color:#92400e;font-size:.95rem;"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="rounded-4 p-3 p-md-4 text-center" style="background:#fff;border:1px solid #e9ecef;box-shadow:0 1px 4px rgba(0,0,0,.04);">
                <div class="fw-bold mb-1" style="font-size:2rem;color:#991b1b;">{{ $notEligible }}</div>
                <div class="text-uppercase fw-semibold" style="font-size:.62rem;letter-spacing:.08em;color:#6c757d;">{{ $tr('Candidatures Non Éligibles', 'غير المؤهلة') }}</div>
                <div class="mt-2 d-flex justify-content-center">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:#fef2f2;">
                        <i class="ri-error-warning-line" style="color:#991b1b;font-size:.95rem;"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── SEARCH & TABLE ── --}}
    <div class="rounded-4 overflow-hidden" style="background:#fff;border:1px solid #e9ecef;box-shadow:0 1px 4px rgba(0,0,0,.04);">

        {{-- Table header / search --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 p-3 border-bottom" style="background:#fff;">
            <div>
                <h6 class="fw-bold mb-0" style="color:#0f172a;">{{ $tr('Liste des candidatures', 'قائمة الترشيحات') }}</h6>
                <div class="mt-1" style="width:40px;height:3px;background:#16a34a;border-radius:2px;"></div>
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <input wire:model.live.debounce.300ms="search" type="text"
                       class="form-control form-control-sm rounded-3"
                       style="min-width:200px;font-size:.82rem;"
                       placeholder="{{ $tr('Rechercher un projet...', 'بحث عن مشروع...') }}">
                <select wire:model.live="statusFilter" class="form-select form-select-sm rounded-3" style="min-width:140px;font-size:.82rem;">
                    <option value="">{{ $tr('Tous les statuts', 'جميع الحالات') }}</option>
                    <option value="submitted">{{ $tr('En révision', 'قيد المراجعة') }}</option>
                    <option value="approved">{{ $tr('Approuvé', 'مقبول') }}</option>
                    <option value="not_eligible">{{ $tr('Non éligible', 'غير مؤهل') }}</option>
                    <option value="eligible">{{ $tr('Disponible', 'متاح') }}</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table mb-0" style="font-size:.83rem;">
                <thead>
                    <tr style="background:#0f2441;">
                        <th class="text-white fw-semibold py-3 px-3" style="font-size:.72rem;letter-spacing:.06em;text-transform:uppercase;border:0;min-width:140px;">{{ $tr('Projets', 'المشاريع') }}</th>
                        <th class="text-white fw-semibold py-3 px-3" style="font-size:.72rem;letter-spacing:.06em;text-transform:uppercase;border:0;min-width:200px;">{{ $tr('Descriptions', 'الأوصاف') }}</th>
                        <th class="text-white fw-semibold py-3 px-3" style="font-size:.72rem;letter-spacing:.06em;text-transform:uppercase;border:0;white-space:nowrap;">
                            {{ $tr('Sublission', 'تقديم') }}<br>
                            <span style="opacity:.75;">{{ $tr('Mise à jour', 'تحديث') }}</span>
                        </th>
                        <th class="text-white fw-semibold py-3 px-3" style="font-size:.72rem;letter-spacing:.06em;text-transform:uppercase;border:0;">{{ $tr('Réviseur', 'المراجع') }}</th>
                        <th class="text-white fw-semibold py-3 px-3" style="font-size:.72rem;letter-spacing:.06em;text-transform:uppercase;border:0;">{{ $tr('Status', 'الحالة') }}</th>
                        <th class="text-white fw-semibold py-3 px-3" style="font-size:.72rem;letter-spacing:.06em;text-transform:uppercase;border:0;">{{ $tr('Actions', 'إجراءات') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background=''">
                            <td class="py-3 px-3 fw-bold align-middle" style="color:#0f172a;">
                                {{ $row['name'] }}
                            </td>
                            <td class="py-3 px-3 align-middle" style="color:#64748b;">
                                {{ $row['description'] }}
                            </td>
                            <td class="py-3 px-3 align-middle" style="white-space:nowrap;">
                                <div style="color:#374151;font-size:.8rem;">{{ $row['submitted_at'] ?? '-' }}</div>
                                <div style="color:#94a3b8;font-size:.78rem;">{{ $row['updated_at'] ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-3 align-middle" style="color:#94a3b8;">-</td>
                            <td class="py-3 px-3 align-middle">
                                <span class="badge rounded-pill px-2 py-1"
                                      style="background:{{ $row['status_color'] }}22;color:{{ $row['status_color'] }};border:1px solid {{ $row['status_color'] }}44;font-size:.73rem;">
                                    • {{ $row['status_label'] }}
                                </span>
                            </td>
                            <td class="py-3 px-3 align-middle">
                                <a href="{{ route('user.project.detail', $row['id']) }}"
                                   class="btn btn-sm rounded-3 d-inline-flex align-items-center justify-content-center"
                                   style="width:32px;height:32px;padding:0;background:#f0f4ff;color:#0f2441;border:0;">
                                    <i class="ri-eye-line" style="font-size:.9rem;"></i>
                                </a>
                                @if($row['eligible'] && $row['submission'])
                                    <span class="mx-1 text-muted">—</span>
                                    <span class="badge rounded-pill" style="background:#e0f2fe;color:#0369a1;font-size:.7rem;">
                                        {{ $tr('Continuer', 'متابعة') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="ri-inbox-archive-line d-block mb-2" style="font-size:2rem;"></i>
                                {{ $tr('Aucun projet trouvé.', 'لم يتم العثور على مشاريع.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-3 py-3 border-top" style="background:#fff;">
            <span class="text-muted" style="font-size:.8rem;">
                {{ $tr('Affichage de', 'عرض') }}
                {{ (($currentPage - 1) * $perPage) + 1 }}
                {{ $tr('à', 'إلى') }}
                {{ min($currentPage * $perPage, $total) }}
                {{ $tr('sur', 'من') }} <strong>{{ $total }}</strong>
                {{ $tr('candidatures', 'ترشيح') }}
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
    </div>

</div>
</div>
