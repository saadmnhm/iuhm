@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;

    $totalForms     = count($formulaires);
    $completedForms = collect($formulaires)->filter(fn($f) => in_array($f['submission_status'], ['approved','submitted','in_review']))->count();
    $progress       = $totalForms > 0 ? round(($completedForms / $totalForms) * 100) : 0;
    $allDone        = $completedForms === $totalForms && $totalForms > 0;

    $submittedAt = collect($formulaires)->filter(fn($f) => !is_null($f['submitted_at']))->sortBy('submitted_at')->first()['submitted_at'] ?? null;
@endphp

<div style="min-height:100vh; background:#f4f5f7;">
<div class="p-3 p-md-4">

    {{-- PAGE TITLE --}}
    <h1 class="fw-bold mb-3" style="color:#0f172a; font-size:clamp(1.2rem,2.5vw,1.6rem);">
        {{ $tr('Gestion des Projets et des Candidatures', 'إدارة المشاريع والترشيحات') }}
    </h1>

    {{-- FLASH MESSAGES --}}
    @if(session('success') || session('message'))
        <div class="alert rounded-4 border-0 mb-3 d-flex align-items-center gap-2" style="background:#f0fdf4;color:#166534;">
            <i class="ri-checkbox-circle-line fs-5"></i> {{ session('success') ?? session('message') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert rounded-4 border-0 mb-3 d-flex align-items-center gap-2" style="background:#fef2f2;color:#991b1b;">
            <i class="ri-error-warning-line fs-5"></i> {{ session('error') }}
        </div>
    @endif

    {{-- FORMATION REVIEW BANNER --}}
    @if($ProjectsSubmission && $ProjectsSubmission->require_formation_review && !$ProjectsSubmission->formation_review_rating)
    <div class="rounded-4 p-3 mb-3 d-flex align-items-center justify-content-between gap-3 flex-wrap"
         style="background:#fffbeb;border:1px solid #fcd34d;">
        <div>
            <div class="fw-bold" style="color:#92400e;">{{ $tr('Avis de Formation Requis', 'مطلوب رأي التكوين') }}</div>
            <div class="small mt-1" style="color:#b45309;">{{ $tr("L'administration a demandé votre avis sur la formation.", 'طلبت الإدارة رأيك في التكوين.') }}</div>
        </div>
        <a href="{{ route('user.project.review', ['id' => $project->id]) }}"
           class="btn btn-sm fw-semibold rounded-3 px-3"
           style="background:#d97706;color:#fff;border:0;">
            <i class="ri-feedback-line me-1"></i>{{ $tr('Donner mon avis', 'إعطاء رأيي') }}
        </a>
    </div>
    @endif

    {{-- CANDIDAT INFO + STATUS CARD --}}
    <div class="rounded-4 p-4 mb-4" style="background:#fff;border:1px solid #e9ecef;box-shadow:0 1px 6px rgba(0,0,0,.05);">
        <div class="row align-items-center g-3">
            {{-- Left: Candidat info --}}
            <div class="col-12 col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background:#dcfce7;color:#166534;font-size:.72rem;letter-spacing:.05em;border:1px solid #bbf7d0;">
                        CANDIDAT ACTIF
                    </span>
                </div>
                <h2 class="fw-bold mb-3" style="color:#0f172a;font-size:clamp(1.1rem,2.5vw,1.5rem);">
                    {{ $candidat->prenom }} {{ $candidat->nom }}
                </h2>
                <div class="row g-2">
                    <div class="col-12 col-sm-6">
                        <div class="d-flex align-items-start gap-2">
                            <i class="ri-mail-line mt-1 flex-shrink-0" style="color:#6b7280;font-size:.9rem;"></i>
                            <div>
                                <div style="font-size:.68rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;font-weight:600;">EMAIL</div>
                                <div style="font-size:.85rem;color:#374151;font-weight:500;">{{ $candidat->email }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="d-flex align-items-start gap-2">
                            <i class="ri-phone-line mt-1 flex-shrink-0" style="color:#6b7280;font-size:.9rem;"></i>
                            <div>
                                <div style="font-size:.68rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;font-weight:600;">TÉLÉPHONE</div>
                                <div style="font-size:.85rem;color:#374151;font-weight:500;">{{ $candidat->phone ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="d-flex align-items-start gap-2">
                            <i class="ri-map-pin-2-line mt-1 flex-shrink-0" style="color:#6b7280;font-size:.9rem;"></i>
                            <div>
                                <div style="font-size:.68rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;font-weight:600;">QUARTIER</div>
                                <div style="font-size:.85rem;color:#374151;font-weight:500;">
                                    {{ collect([$candidat->selected_city, $candidat->selected_prefecture])->filter()->join(', ') ?: ($candidat->address ?? '-') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="d-flex align-items-start gap-2">
                            <i class="ri-calendar-check-line mt-1 flex-shrink-0" style="color:#6b7280;font-size:.9rem;"></i>
                            <div>
                                <div style="font-size:.68rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;font-weight:600;">SOUMIS LE</div>
                                <div style="font-size:.85rem;color:#374151;font-weight:500;">
                                    {{ $submittedAt ? \Carbon\Carbon::parse($submittedAt)->format('d F Y') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Right: Status card --}}
            <div class="col-12 col-lg-5">
                <div class="rounded-4 p-4" style="background:#0f2441;color:#fff;">
                    <div class="fw-bold mb-2" style="font-size:.95rem;">{{ $tr('Statut de la Revue', 'حالة المراجعة') }}</div>
                    <p class="mb-3" style="font-size:.8rem;opacity:.8;line-height:1.5;">
                        @if($allDone)
                            {{ $tr('Tous vos formulaires ont été soumis avec succès.', 'تم إرسال جميع استماراتك بنجاح.') }}
                        @else
                            {{ $tr("L'examen de cette soumission est en cours. Veuillez valider les formulaires ci-dessous.", 'يجري فحص هذا الترشيح. يرجى التحقق من الاستمارات أدناه.') }}
                        @endif
                    </p>
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;opacity:.7;">PROGRESSION</span>
                        <span class="fw-bold" style="font-size:1.1rem;">{{ $progress }}%</span>
                    </div>
                    <div class="rounded-pill" style="height:6px;background:rgba(255,255,255,.2);">
                        <div class="rounded-pill" style="height:6px;width:{{ $progress }}%;background:#4ade80;transition:.4s;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ALL-DONE BANNER --}}
    @if($allDone)
    <div class="rounded-4 p-3 mb-3 d-flex align-items-center gap-3" style="background:#f0fdf4;border:1px solid #bbf7d0;">
        <span class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;background:#dcfce7;">
            <i class="ri-trophy-fill" style="color:#16a34a;font-size:1.2rem;"></i>
        </span>
        <div>
            <div class="fw-bold" style="color:#166534;">{{ $tr('Félicitations ! Dossier complet', 'تهانينا! ملفك مكتمل') }}</div>
            <div class="small" style="color:#15803d;">{{ $tr('Tous vos formulaires ont été soumis. Nous reviendrons vers vous prochainement.', 'تم إرسال جميع استماراتك. سنعاود التواصل معك قريبًا.') }}</div>
        </div>
    </div>
    @endif

    {{-- FORMULAIRES DU PROJET --}}
    <div class="rounded-4 overflow-hidden" style="background:#fff;border:1px solid #e9ecef;box-shadow:0 1px 4px rgba(0,0,0,.04);">

        <div class="px-4 pt-4 pb-2">
            <h5 class="fw-bold mb-0" style="color:#0f172a;">{{ $tr('Formulaires du Projet', 'استمارات المشروع') }}</h5>
            <div class="mt-1" style="width:36px;height:3px;background:#16a34a;border-radius:2px;"></div>
        </div>

        @if(count($formulaires) > 0)
        <div class="p-3 d-flex flex-column gap-2">
            @foreach($formulaires as $index => $formulaire)
                @php
                    $canStart   = $formulaire['can_start'] ?? true;
                    $subStatus  = $formulaire['submission_status'] ?? null;
                    $color      = $formulaire['color'] ?? '#2f5496';
                    $isRefused  = in_array($subStatus, ['refused', 'returned']);
                    $isApproved = $subStatus === 'approved';
                    $isInReview = $subStatus === 'in_review';
                    $isDraft    = $subStatus === 'draft';
                    $isSubmitted = $subStatus === 'submitted';
                    $hasSubmission = !is_null($subStatus);

                    if ($isApproved)      { $bBg='#dcfce7'; $bColor='#166534'; $bBorder='#bbf7d0'; $sText=$tr('Approuvé','مقبول'); }
                    elseif ($isRefused)   { $bBg='#fef2f2'; $bColor='#dc2626'; $bBorder='#fecaca'; $sText=$tr('Refusé','مرفوض'); }
                    elseif ($isInReview)  { $bBg='#fffbeb'; $bColor='#d97706'; $bBorder='#fde68a'; $sText=$tr('En révision','قيد المراجعة'); }
                    elseif ($isDraft)     { $bBg='#f9fafb'; $bColor='#6b7280'; $bBorder='#e5e7eb'; $sText=$tr('Brouillon','مسودة'); }
                    elseif ($isSubmitted) { $bBg='#eff6ff'; $bColor='#2563eb'; $bBorder='#bfdbfe'; $sText=$tr('Soumis','مُرسل'); }
                    else                  { $bBg='#f0f4ff'; $bColor='#0f2441'; $bBorder='#c7d2fe'; $sText=$tr('Disponible','متاح'); }
                @endphp

                <div class="rounded-4" style="border:1px solid #f1f5f9; {{ !$canStart ? 'opacity:.55;' : '' }}">
                    {{-- Main row --}}
                    <div class="d-flex align-items-center gap-3 p-3 flex-wrap">
                        {{-- Icon --}}
                        <span class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                              style="width:44px;height:44px;background:{{ $color }}18;color:{{ $color }};">
                            @if(!$canStart)
                                <i class="ri-lock-2-line" style="font-size:1.1rem;color:#9ca3af;"></i>
                            @else
                                <i class="{{ $formulaire['icon'] }}" style="font-size:1.1rem;"></i>
                            @endif
                        </span>

                        {{-- Title + modified --}}
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="fw-semibold text-truncate" style="font-size:.88rem;color:#0f172a;">{{ $formulaire['title'] }}</div>
                            <div style="font-size:.75rem;color:#9ca3af;">
                                {{ $tr('Dernière modification', 'آخر تعديل') }} :
                                {{ $formulaire['last_modified_human'] ?? $tr('jamais', 'لم يعدّل') }}
                            </div>
                        </div>

                        {{-- Status badge --}}
                        <span class="badge rounded-pill px-3 py-1 fw-semibold border flex-shrink-0"
                              style="background:{{ $bBg }};color:{{ $bColor }};border-color:{{ $bBorder }} !important;font-size:.72rem;">
                            {{ $sText }}
                        </span>

                        {{-- Ouvrir button (if has submission) --}}
                        @if($hasSubmission)
                            <button wire:click="startFormulaire({{ $index }})"
                                    class="btn btn-sm rounded-3 d-inline-flex align-items-center gap-1 flex-shrink-0"
                                    style="border:1px solid #e2e8f0;background:#fff;color:#374151;font-size:.78rem;padding:.3rem .75rem;">
                                <i class="ri-eye-line"></i> {{ $tr('Ouvrir', 'فتح') }}
                            </button>
                        @endif

                        {{-- Action button --}}
                        @if(!$canStart)
                            <span class="btn btn-sm rounded-3 d-inline-flex align-items-center gap-1 flex-shrink-0"
                                  style="border:1px solid #e5e7eb;background:#f3f4f6;color:#9ca3af;font-size:.78rem;padding:.3rem .75rem;cursor:not-allowed;">
                                <i class="ri-lock-2-line"></i> {{ $tr('Verrouillé', 'مغلق') }}
                            </span>
                        @elseif($isSubmitted || $isInReview || $isApproved)
                            <span class="btn btn-sm rounded-3 d-inline-flex align-items-center gap-1 flex-shrink-0"
                                  style="border:1px solid #e2e8f0;background:#fff;color:#6b7280;font-size:.78rem;padding:.3rem .75rem;">
                                <i class="ri-checkbox-circle-line" style="color:#16a34a;"></i> {{ $tr('Soumis', 'مُرسل') }}
                            </span>
                        @else
                            <button wire:click="startFormulaire({{ $index }})"
                                    wire:loading.attr="disabled"
                                    class="btn btn-sm rounded-3 fw-semibold d-inline-flex align-items-center gap-1 flex-shrink-0"
                                    style="background:#0f2441;color:#fff;border:0;font-size:.78rem;padding:.3rem .75rem;">
                                <span wire:loading.remove wire:target="startFormulaire({{ $index }})">
                                    <i class="ri-send-plane-line"></i> {{ $tr('Soumettre', 'إرسال') }}
                                </span>
                                <span wire:loading wire:target="startFormulaire({{ $index }})">
                                    <span class="spinner-border spinner-border-sm" style="width:.75rem;height:.75rem;"></span>
                                </span>
                            </button>
                        @endif
                    </div>

                    {{-- Comment for refused --}}
                    @if($isRefused && !empty($formulaire['review_notes']))
                    <div class="px-3 pb-3">
                        <div class="rounded-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;">
                            <div class="text-uppercase fw-bold mb-2" style="font-size:.65rem;letter-spacing:.1em;color:#dc2626;">
                                {{ $tr('COMMENTAIRE', 'تعليق') }}
                            </div>
                            <p class="mb-0" style="font-size:.82rem;color:#374151;line-height:1.6;">
                                {{ $formulaire['review_notes'] }}
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- Lock reason --}}
                    @if(!$canStart && !empty($formulaire['lock_reason']))
                    <div class="px-3 pb-3">
                        <div class="rounded-3 p-2" style="background:#f9fafb;border:1px solid #f1f5f9;">
                            <span class="small" style="color:#9ca3af;font-size:.78rem;">
                                <i class="ri-information-line me-1"></i>{{ $formulaire['lock_reason'] }}
                            </span>
                        </div>
                    </div>
                    @endif

                </div>
            @endforeach
        </div>

        {{-- Footer --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-4 py-3"
             style="border-top:1px solid #f1f5f9;">
            <span class="text-muted" style="font-size:.8rem;">
                {{ $tr('Affichage de 1 à', 'عرض من 1 إلى') }} {{ $totalForms }}
                {{ $tr('sur', 'من') }} <strong>{{ $totalForms }}</strong>
                {{ $tr('formulaires', 'استمارة') }}
            </span>
            <a href="{{ route('user.projects.list') }}"
               class="btn btn-sm rounded-3 d-inline-flex align-items-center gap-1"
               style="border:1px solid #e2e8f0;background:#fff;color:#374151;font-size:.78rem;">
                <i class="ri-arrow-left-s-line"></i> {{ $tr('Retour', 'رجوع') }}
            </a>
        </div>

        @else
        <div class="text-center py-5 px-4 text-muted">
            <i class="ri-file-list-3-line d-block mb-2" style="font-size:2rem;"></i>
            <span style="font-size:.85rem;">{{ $tr("Ce programme n'a pas encore de formulaires.", 'لا توجد استمارات مرتبطة بهذا البرنامج.') }}</span>
        </div>
        @endif

    </div>

</div>
</div>