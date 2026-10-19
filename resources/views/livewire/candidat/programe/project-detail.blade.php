@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;

    $totalForms     = count($formulaires);
    $completedForms = collect($formulaires)->filter(fn($f) => in_array($f['submission_status'], ['approved','submitted','in_review']))->count();
    $progress       = $totalForms > 0 ? round(($completedForms / $totalForms) * 100) : 0;
    $allDone        = $completedForms === $totalForms && $totalForms > 0;

    $submittedAt = collect($formulaires)->filter(fn($f) => !is_null($f['submitted_at']))->sortBy('submitted_at')->first()['submitted_at'] ?? null;
@endphp

<div class="min-h-screen bg-gray-50">
<div class="p-4 sm:p-6 lg:p-8">

    {{-- PAGE TITLE --}}
    <h1 class="text-2xl font-bold text-gray-900 mb-6">
        {{ $tr('Gestion des Projets et des Candidatures', 'إدارة المشاريع والترشيحات') }}
    </h1>

    {{-- FLASH MESSAGES --}}
    @if(session('success') || session('message'))
    <div class="rounded-xl p-4 mb-4 flex items-center gap-3 bg-green-50 border border-green-100 text-green-800" role="alert">
        <i class="ri-checkbox-circle-line text-xl"></i> {{ session('success') ?? session('message') }}
    </div>
    @endif
    @if(session('error'))
    <div class="rounded-xl p-4 mb-4 flex items-center gap-3 bg-red-50 border border-red-100 text-red-800" role="alert">
        <i class="ri-error-warning-line text-xl"></i> {{ session('error') }}
    </div>
    @endif

    {{-- FORMATION REVIEW BANNER --}}
    @if($ProjectsSubmission && $ProjectsSubmission->require_formation_review && !$ProjectsSubmission->formation_review_rating)
    <div class="rounded-xl p-4 mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-amber-50 border border-amber-200">
        <div>
            <div class="font-semibold text-amber-900">{{ $tr('Avis de Formation Requis', 'مطلوب رأي التكوين') }}</div>
            <div class="text-sm mt-1 text-amber-800">{{ $tr("L'administration a demandé votre avis sur la formation.", 'طلبت الإدارة رأيك في التكوين.') }}</div>
        </div>
        <a href="{{ route('user.project.review', ['id' => $project->id]) }}"
           class="btn btn-sm font-semibold rounded-xl px-4 py-2 whitespace-nowrap"
           style="background:#d97706;color:#fff;border:0;">
            <i class="ri-feedback-line mr-1"></i>{{ $tr('Donner mon avis', 'إعطاء رأيي') }}
        </a>
    </div>
    @endif

    {{-- CANDIDAT INFO + STATUS CARD --}}
    <div class="rounded-2xl p-5 mb-6 bg-white border border-gray-200 shadow-sm">
        <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
            {{-- Left: Candidat info --}}
            <div class="w-full lg:w-2/3">
                <div class="flex items-center gap-2 mb-3">
                    <span class="badge px-3 py-1 rounded-full font-semibold text-xs uppercase tracking-wider bg-green-100 text-green-700 border border-green-200">
                        CANDIDAT ACTIF
                    </span>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    {{ $candidat->prenom }} {{ $candidat->nom }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex items-start gap-3">
                        <i class="ri-mail-line mt-1 flex-shrink-0 text-gray-400 text-lg"></i>
                        <div>
                            <div class="text-xs text-gray-400 uppercase tracking-wider font-semibold">EMAIL</div>
                            <div class="text-sm text-gray-900 font-medium">{{ $candidat->email }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ri-phone-line mt-1 flex-shrink-0 text-gray-400 text-lg"></i>
                        <div>
                            <div class="text-xs text-gray-400 uppercase tracking-wider font-semibold">TÉLÉPHONE</div>
                            <div class="text-sm text-gray-900 font-medium">{{ $candidat->phone ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ri-map-pin-2-line mt-1 flex-shrink-0 text-gray-400 text-lg"></i>
                        <div>
                            <div class="text-xs text-gray-400 uppercase tracking-wider font-semibold">QUARTIER</div>
                            <div class="text-sm text-gray-900 font-medium">
                                {{ collect([$candidat->selected_city, $candidat->selected_prefecture])->filter()->join(', ') ?: ($candidat->address ?? '-') }}
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ri-calendar-check-line mt-1 flex-shrink-0 text-gray-400 text-lg"></i>
                        <div>
                            <div class="text-xs text-gray-400 uppercase tracking-wider font-semibold">SOUMIS LE</div>
                            <div class="text-sm text-gray-900 font-medium">
                                {{ $submittedAt ? \Carbon\Carbon::parse($submittedAt)->format('d F Y') : '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Right: Status card --}}
            <div class="w-full lg:w-1/3">
                <div class="rounded-2xl p-5 text-white" style="background:#0f2441;">
                    <div class="font-semibold mb-3" style="font-size:.95rem;">{{ $tr('Statut de la Revue', 'حالة المراجعة') }}</div>
                    <p class="mb-4 text-sm" style="opacity:.8;line-height:1.5;">
                        @if($allDone)
                            {{ $tr('Tous vos formulaires ont été soumis avec succès.', 'تم إرسال جميع استماراتك بنجاح.') }}
                        @else
                            {{ $tr("L'examen de cette soumission est en cours. Veuillez valider les formulaires ci-dessous.", 'يجري فحص هذا الترشيح. يرجى التحقق من الاستمارات أدناه.') }}
                        @endif
                    </p>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs uppercase tracking-wider" style="opacity:.7;">PROGRESSION</span>
                        <span class="font-bold text-lg">{{ $progress }}%</span>
                    </div>
                    <div class="rounded-full h-1.5 bg-white/20 overflow-hidden">
                        <div class="rounded-full h-full bg-green-400 transition-all duration-500" style="width:{{ $progress }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ALL-DONE BANNER --}}
    @if($allDone)
    <div class="rounded-xl p-4 mb-6 flex items-center gap-3 bg-green-50 border border-green-100">
        <span class="rounded-full flex items-center justify-center flex-shrink-0 w-10 h-10 bg-green-100">
            <i class="ri-trophy-fill text-green-600 text-xl"></i>
        </span>
        <div>
            <div class="font-semibold text-green-800">{{ $tr('Félicitations ! Dossier complet', 'تهانينا! ملفك مكتمل') }}</div>
            <div class="text-sm text-green-700">{{ $tr('Tous vos formulaires ont été soumis. Nous reviendrons vers vous prochainement.', 'تم إرسال جميع استماراتك. سنعاود التواصل معك قريبًا.') }}</div>
        </div>
    </div>
    @endif

    {{-- FORMULAIRES DU PROJET --}}
    <div class="rounded-2xl overflow-hidden bg-white border border-gray-200 shadow-sm">

        <div class="px-5 py-4 border-b border-gray-100">
            <h5 class="font-bold text-gray-900 mb-2">{{ $tr('Formulaires du Projet', 'استمارات المشروع') }}</h5>
            <div class="w-9 h-0.5 rounded bg-green-600"></div>
        </div>

        @if(count($formulaires) > 0)
        <div class="p-4 space-y-3">
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

                <div class="rounded-xl border border-gray-100 {{ !$canStart ? 'opacity-55' : '' }}">
                    {{-- Main row --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 p-4">
                        {{-- Icon --}}
                        <span class="rounded-xl flex items-center justify-center flex-shrink-0"
                              style="width:44px;height:44px;background:{{ $color }}18;color:{{ $color }};">
                            @if(!$canStart)
                                <i class="ri-lock-2-line text-xl text-gray-400"></i>
                            @else
                                <i class="{{ $formulaire['icon'] }} text-xl"></i>
                            @endif
                        </span>

                        {{-- Title + modified --}}
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-truncate text-sm text-gray-900">{{ $formulaire['title'] }}</div>
                            <div class="text-xs text-gray-400">
                                {{ $tr('Dernière modification', 'آخر تعديل') }} :
                                {{ $formulaire['last_modified_human'] ?? $tr('jamais', 'لم يعدّل') }}
                            </div>
                        </div>

                        {{-- Status badge --}}
                        <span class="badge px-3 py-1 rounded-full font-semibold border flex-shrink-0 text-xs"
                              style="background:{{ $bBg }};color:{{ $bColor }};border-color:{{ $bBorder }} !important;">
                            {{ $sText }}
                        </span>

                        {{-- Ouvrir button (if has submission) --}}
                        @if($hasSubmission)
                            <button wire:click="startFormulaire({{ $index }})"
                                    class="btn btn-sm rounded-xl inline-flex items-center gap-1 flex-shrink-0 transition-colors"
                                    style="border:1px solid #e2e8f0;background:#fff;color:#374151;font-size:.78rem;padding:.375rem .75rem;">
                                <i class="ri-eye-line"></i> {{ $tr('Ouvrir', 'فتح') }}
                            </button>
                        @endif

                        {{-- Action button --}}
                        @if(!$canStart)
                            <span class="btn btn-sm rounded-xl inline-flex items-center gap-1 flex-shrink-0"
                                  style="border:1px solid #e5e7eb;background:#f3f4f6;color:#9ca3af;font-size:.78rem;padding:.375rem .75rem;cursor:not-allowed;">
                                <i class="ri-lock-2-line"></i> {{ $tr('Verrouillé', 'مغلق') }}
                            </span>
                        @elseif($isSubmitted || $isInReview || $isApproved)
                            <span class="btn btn-sm rounded-xl inline-flex items-center gap-1 flex-shrink-0"
                                  style="border:1px solid #e2e8f0;background:#fff;color:#6b7280;font-size:.78rem;padding:.375rem .75rem;">
                                <i class="ri-checkbox-circle-line text-green-500"></i> {{ $tr('Soumis', 'مُرسل') }}
                            </span>
                        @else
                            <button wire:click="startFormulaire({{ $index }})"
                                    wire:loading.attr="disabled"
                                    class="btn btn-sm rounded-xl font-semibold inline-flex items-center gap-1 flex-shrink-0 transition-colors"
                                    style="background:#0f2441;color:#fff;border:0;font-size:.78rem;padding:.375rem .75rem;">
                                <span wire:loading.remove wire:target="startFormulaire({{ $index }})">
                                    <i class="ri-send-plane-line"></i> {{ $tr('Soumettre', 'إرسال') }}
                                </span>
                                <span wire:loading wire:target="startFormulaire({{ $index }})" class="flex items-center gap-1">
                                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </span>
                            </button>
                        @endif
                    </div>

                    {{-- Comment for refused --}}
                    @if($isRefused && !empty($formulaire['review_notes']))
                    <div class="px-4 pb-4">
                        <div class="rounded-lg p-3 bg-red-50 border border-red-100">
                            <div class="text-xs font-bold mb-2 uppercase tracking-wider text-red-600">
                                {{ $tr('COMMENTAIRE', 'تعليق') }}
                            </div>
                            <p class="mb-0 text-sm text-gray-800 leading-relaxed">
                                {{ $formulaire['review_notes'] }}
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- Lock reason --}}
                    @if(!$canStart && !empty($formulaire['lock_reason']))
                    <div class="px-4 pb-4">
                        <div class="rounded-lg p-2 bg-gray-50 border border-gray-100">
                            <span class="text-xs text-gray-500 flex items-center gap-1">
                                <i class="ri-information-line"></i>{{ $formulaire['lock_reason'] }}
                            </span>
                        </div>
                    </div>
                    @endif

                </div>
            @endforeach
        </div>

        {{-- Footer --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4 border-t border-gray-100">
            <span class="text-sm text-gray-500">
                {{ $tr('Affichage de 1 à', 'عرض من 1 إلى') }} {{ $totalForms }}
                {{ $tr('sur', 'من') }} <strong>{{ $totalForms }}</strong>
                {{ $tr('formulaires', 'استمارة') }}
            </span>
            <a href="{{ route('user.projects.list') }}"
               class="btn btn-sm rounded-xl inline-flex items-center gap-1 transition-colors"
               style="border:1px solid #e2e8f0;background:#fff;color:#374151;font-size:.78rem;padding:.375rem .75rem;">
                <i class="ri-arrow-left-s-line"></i> {{ $tr('Retour', 'رجوع') }}
            </a>
        </div>

        @else
        <div class="text-center py-12 px-4 text-gray-400">
            <i class="ri-file-list-3-line block mb-2 text-4xl"></i>
            <span class="text-sm">{{ $tr("Ce programme n'a pas encore de formulaires.", 'لا توجد استمارات مرتبطة بهذا البرنامج.') }}</span>
        </div>
        @endif

    </div>

</div>
</div>