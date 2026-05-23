@php
        $isArabic = str_starts_with(app()->getLocale(), 'ar');
        $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;

        $profileChecks = [
            !empty($candidat->nom),
            !empty($candidat->prenom),
            !empty($candidat->email),
            !empty($candidat->phone),
            !empty($candidat->address),
            !empty($candidat->date_naissance),
            !empty($candidat->gender),
        ];
        $profileCompleted = collect($profileChecks)->filter()->count();
        $profileTotal = count($profileChecks);
        $profileProgress = $profileTotal > 0 ? round(($profileCompleted / $profileTotal) * 100) : 0;
@endphp

<div style="min-height:100vh; background:#f4f5f7; {{ $isArabic ? 'direction:rtl;' : '' }}">
<div class="p-3 p-md-4">

    {{-- ── HERO BANNER ── --}}
    <div class="rounded-4 mb-4 p-4 p-md-5 d-flex align-items-center justify-content-between position-relative overflow-hidden"
         style="background: linear-gradient(135deg, #0f2441 0%, #15304a 60%, #1a3d5c 100%); min-height:220px;">
        <div class="position-relative" style="z-index:2;">
            <h1 class="fw-bold text-white mb-1" style="font-size:clamp(1.6rem,4vw,2.6rem);">
                {{ $tr('Bienvenue,', 'مرحباً،') }} {{ $candidat->prenom }} {{ $candidat->nom }}
            </h1>
            <p class="text-white mb-3" style="opacity:.75; font-size:.95rem;">{{ $tr('Association Initiative Urbaine - ERP', 'جمعية المبادرة الحضرية - ERP') }}</p>
            <a href="{{ route('user.projects.list') }}"
               class="btn btn-sm fw-bold px-3 py-2 rounded-3"
               style="background:#fff; color:#0f2441; font-size:.82rem;">
                <i class="ri-function-line me-1" style="color:#066E1B;"></i>
                {{ $tr("Vue d'ensemble du système", 'نظرة عامة على النظام') }}
            </a>
        </div>
        <div class="d-none d-md-block position-absolute rounded-4"
             style="right:-30px; top:-20px; width:220px; height:180px;
                    background:linear-gradient(135deg,#12345a,#0b2340); opacity:.6; transform:rotate(-10deg); z-index:1;"></div>
        <div class="d-none d-md-block position-absolute rounded-4"
             style="right:60px; bottom:-30px; width:140px; height:120px;
                    background:linear-gradient(135deg,#1a4a6e,#0f2441); opacity:.4; transform:rotate(5deg); z-index:1;"></div>
    </div>

    {{-- ── STATS ROW ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="rounded-4 p-3 p-md-4 text-center" style="background:#fff; border:1px solid #e9ecef; box-shadow:0 1px 4px rgba(0,0,0,.04);">
                <div class="mb-1" style="font-size:1.7rem; font-weight:700; color:#0f2441;">{{ $stats['total'] }}</div>
                <div class="text-uppercase fw-semibold" style="font-size:.65rem; letter-spacing:.08em; color:#6c757d;">{{ $tr('Total Candidatures', 'إجمالي الترشيحات') }}</div>
                <div class="mt-2 d-flex justify-content-center">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#f0f4ff;">
                        <i class="ri-file-list-3-line" style="color:#0f2441; font-size:1rem;"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="rounded-4 p-3 p-md-4 text-center" style="background:#fff; border:1px solid #e9ecef; box-shadow:0 1px 4px rgba(0,0,0,.04);">
                <div class="mb-1" style="font-size:1.7rem; font-weight:700; color:#166534;">{{ $stats['approved'] }}</div>
                <div class="text-uppercase fw-semibold" style="font-size:.65rem; letter-spacing:.08em; color:#6c757d;">{{ $tr('Candidatures Approuvé', 'المقبولة') }}</div>
                <div class="mt-2 d-flex justify-content-center">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#f0fdf4;">
                        <i class="ri-checkbox-circle-line" style="color:#166534; font-size:1rem;"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="rounded-4 p-3 p-md-4 text-center" style="background:#fff; border:1px solid #e9ecef; box-shadow:0 1px 4px rgba(0,0,0,.04);">
                <div class="mb-1" style="font-size:1.7rem; font-weight:700; color:#92400e;">{{ $stats['submitted'] }}</div>
                <div class="text-uppercase fw-semibold" style="font-size:.65rem; letter-spacing:.08em; color:#6c757d;">{{ $tr('Candidatures En Révision', 'قيد المراجعة') }}</div>
                <div class="mt-2 d-flex justify-content-center">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#fffbeb;">
                        <i class="ri-time-line" style="color:#92400e; font-size:1rem;"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="rounded-4 p-3 p-md-4 text-center" style="background:#fff; border:1px solid #e9ecef; box-shadow:0 1px 4px rgba(0,0,0,.04);">
                <div class="mb-1" style="font-size:1.7rem; font-weight:700; color:#991b1b;">{{ $projectEligibilityStats['not_eligible'] }}</div>
                <div class="text-uppercase fw-semibold" style="font-size:.65rem; letter-spacing:.08em; color:#6c757d;">{{ $tr('Candidatures Non Éligibles', 'غير المؤهلة') }}</div>
                <div class="mt-2 d-flex justify-content-center">
                    <span class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#fef2f2;">
                        <i class="ri-error-warning-line" style="color:#991b1b; font-size:1rem;"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── APPS POUR VOUS ── --}}
    <h5 class="fw-bold mb-3" style="color:#0f172a;">{{ $tr('Apps pour vous', 'التطبيقات لك') }}</h5>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="rounded-4 p-4 h-100 d-flex flex-column" style="background:#f0f2f5; min-height:230px;">
                <div class="rounded-3 d-flex align-items-center justify-content-center mb-3"
                     style="width:50px;height:50px; background:#fff; color:#0f2441;">
                    <i class="ri-user-3-line" style="font-size:1.3rem;"></i>
                </div>
                <h5 class="fw-bold mb-1" style="color:#04103A;">{{ $tr('Mon profile', 'ملفي الشخصي') }}</h5>
                <p class="small text-uppercase mb-3" style="color:#9ca3af; letter-spacing:.07em; font-size:.68rem; font-weight:600;">
                    {{ $tr('METTEZ À JOUR VOTRE PROFIL : NOM, LOCALISATION, ÂGE ET AUTRES INFORMATIONS PERSONNELLES.', 'قم بتحديث ملفك: الاسم، الموقع، العمر والمعلومات الشخصية.') }}
                </p>
                <div class="mt-auto">
                    <a href="{{ route('user.settings') }}" class="fw-bold text-decoration-none" style="color:#066E1B; font-size:.9rem;">
                        {{ $tr('Accéder', 'الوصول') }} <i class="ri-arrow-right-long-line"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <a href="{{ route('user.projects.list') }}" class="text-decoration-none d-block h-100">
                <div class="rounded-4 p-4 h-100 d-flex flex-column" style="background:#f0f2f5; min-height:230px;">
                    <div class="rounded-3 d-flex align-items-center justify-content-center mb-3"
                         style="width:50px;height:50px; background:#fff; color:#0f2441;">
                        <i class="ri-apps-2-line" style="font-size:1.3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="color:#04103A;">{{ $tr('Gestion des Projets et des Candidatures', 'إدارة المشاريع والترشيحات') }}</h5>
                    <p class="small text-uppercase mb-3" style="color:#9ca3af; letter-spacing:.07em; font-size:.68rem; font-weight:600;">
                        {{ $tr("EXPLOREZ LES PROJETS DISPONIBLES ET SUIVEZ L'AVANCEMENT DE VOS CANDIDATURES.", 'استكشف المشاريع المتاحة وتابع تقدم ترشيحاتك.') }}
                    </p>
                    <div class="mt-auto">
                        <span class="fw-bold" style="color:#066E1B; font-size:.9rem;">
                            {{ $tr('Accéder', 'الوصول') }} <i class="ri-arrow-right-long-line"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <a href="{{ route('user.support') }}" class="text-decoration-none d-block h-100">
                <div class="rounded-4 p-4 h-100 d-flex flex-column" style="background:#f0f2f5; min-height:230px;">
                    <div class="rounded-3 d-flex align-items-center justify-content-center mb-3"
                         style="width:50px;height:50px; background:#fff; color:#0f2441;">
                        <i class="ri-customer-service-2-line" style="font-size:1.3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="color:#04103A;">{{ $tr('Support & Assistance', 'الدعم والمساعدة') }}</h5>
                    <p class="small text-uppercase mb-3" style="color:#9ca3af; letter-spacing:.07em; font-size:.68rem; font-weight:600;">
                        {{ $tr("BESOIN D'AIDE ? CONTACTEZ L'ÉQUIPE DE SUPPORT TECHNIQUE DE L'ERP.", 'هل تحتاج إلى مساعدة؟ تواصل مع فريق الدعم التقني.') }}
                    </p>
                    <div class="mt-auto">
                        <span class="fw-bold" style="color:#066E1B; font-size:.9rem;">
                            {{ $tr('Accéder', 'الوصول') }} <i class="ri-arrow-right-long-line"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- ── BOTTOM ROW: Activity + Services ── --}}
    <div class="row g-3 mb-4">

        {{-- Recent Activity --}}
        <div class="col-12 col-lg-8">
            <div class="rounded-4 p-4" style="background:#f0f2f5;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-semibold mb-0" style="color:#0f172a; font-size:1rem;">{{ $tr('Activité Récente', 'النشاط الأخير') }}</h5>
                    <a href="{{ route('user.projects.list') }}" class="fw-semibold text-decoration-none" style="color:#066E1B; font-size:.85rem;">{{ $tr('Voir tout', 'عرض الكل') }}</a>
                </div>
                <div class="d-flex flex-column gap-2">
                    @forelse(array_slice($filteredSubmissions, 0, 5) as $sub)
                        <div class="rounded-3 p-3 d-flex align-items-center justify-content-between gap-2"
                             style="background:#fbf8fd;">
                            <div class="d-flex align-items-center gap-3">
                                <span class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                      style="width:38px;height:38px; background:{{ $sub['form_color'] ?? '#2f5496' }}22; color:{{ $sub['form_color'] ?? '#2f5496' }};">
                                    <i class="{{ $sub['form_icon'] ?? 'ri-file-list-3-line' }}" style="font-size:1rem;"></i>
                                </span>
                                <div>
                                    <div class="fw-semibold" style="font-size:.88rem; color:#0f172a;">{{ $sub['form_title'] }}</div>
                                    <div class="text-muted" style="font-size:.78rem;">{{ $sub['programe_name'] ?: $tr('Formulaire général', 'استمارة عامة') }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <span class="badge rounded-pill px-2 py-1" style="background:{{ $sub['status_badge_color'] ?? '#6b7280' }}22; color:{{ $sub['status_badge_color'] ?? '#6b7280' }}; font-size:.72rem; border:1px solid {{ $sub['status_badge_color'] ?? '#6b7280' }}44;">
                                    {{ $sub['status_label'] ?? $sub['status'] }}
                                </span>
                                <span class="text-muted" style="font-size:.75rem;">{{ $sub['updated_at'] ?? '-' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="ri-inbox-archive-line d-block mb-2" style="font-size:2rem;"></i>
                            <span style="font-size:.85rem;">{{ $tr('Aucune activité récente.', 'لا يوجد نشاط حديث.') }}</span>
                        </div>
                    @endforelse
                    @if(count($filteredSubmissions) === 0)
                        <div class="text-muted" style="font-size:.82rem;">{{ $tr("Plus d'éléments d'activité ici...", 'لا توجد عناصر أخرى...') }}</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div class="col-12 col-lg-4 d-flex flex-column gap-3">

            {{-- Service Status --}}
            <div class="rounded-4 p-4" style="background: linear-gradient(135deg, #16a34a, #15803d); color:#fff;">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="ri-shield-check-line" style="font-size:1.1rem;"></i>
                    <span class="fw-bold" style="font-size:.9rem;">{{ $tr('État des Services', 'حالة الخدمات') }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span style="font-size:.82rem; opacity:.9;">{{ $tr('Base de données', 'قاعدة البيانات') }}</span>
                    <span class="fw-bold" style="font-size:.82rem;">{{ $tr('Opérationnel', 'يعمل') }}</span>
                </div>
                <div class="rounded-pill mb-3" style="height:5px; background:rgba(255,255,255,.3);">
                    <div class="rounded-pill" style="height:5px; width:100%; background:#fff;"></div>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span style="font-size:.82rem; opacity:.9;">{{ $tr('Stockage Cloud', 'التخزين السحابي') }}</span>
                    <span class="fw-bold" style="font-size:.82rem;">{{ $profileProgress }}%</span>
                </div>
                <div class="rounded-pill" style="height:5px; background:rgba(255,255,255,.3);">
                    <div class="rounded-pill" style="height:5px; width:{{ $profileProgress }}%; background:#fff;"></div>
                </div>
            </div>

            {{-- Support --}}
            <div class="rounded-4 p-4" style="background:#eae7eb;">
                <h6 class="fw-bold mb-2" style="color:#04103A; font-size:.9rem;">{{ $tr('Support Technique', 'الدعم التقني') }}</h6>
                <p class="text-muted mb-3" style="font-size:.8rem;">{{ $tr("Besoin d'aide avec l'ERP? Contactez l'équipe IT.", 'هل تحتاج مساعدة في ERP؟ تواصل مع الفريق.') }}</p>
                <a href="{{ route('user.support') }}"
                   class="d-block text-center fw-bold rounded-pill px-4 py-2 text-decoration-none"
                   style="border:2px solid #c6c5d0; color:#04103A; font-size:.85rem; transition:.2s;"
                   onmouseover="this.style.background='#04103A';this.style.color='#fff';"
                   onmouseout="this.style.background='transparent';this.style.color='#04103A';">
                    {{ $tr('Ouvrir un Ticket', 'فتح تذكرة') }}
                </a>
            </div>

        </div>
    </div>

</div>
    @if($showCompleteProfileModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="ri-information-line text-warning me-2"></i>{{ $tr('Complétez votre profil', 'أكمل ملفك الشخصي') }}
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="ri-user-settings-line" style="font-size:3.5rem;color:#648454;"></i>
                        <h5 class="mt-3 mb-2">{{ $tr('Votre profil est incomplet', 'ملفك الشخصي غير مكتمل') }}</h5>
                        <p class="text-muted mb-0 small">{{ $tr('Veuillez compléter vos informations pour accéder à toutes les fonctionnalités.', 'يرجى إكمال معلوماتك للوصول إلى جميع الميزات.') }}</p>
                    </div>
                    <div class="alert alert-info d-flex align-items-start border-0 rounded-3">
                        <i class="ri-lightbulb-line me-2 mt-1"></i>
                        <div>
                            <strong>{{ $tr('Pourquoi compléter votre profil ?', 'لماذا يجب إكمال ملفك الشخصي؟') }}</strong>
                            <ul class="mb-0 mt-2 ps-3 small">
                                <li>{{ $tr("Améliorer la précision des critères d'éligibilité", 'تحسين دقة معايير الأهلية') }}</li>
                                <li>{{ $tr('Recevoir des notifications personnalisées', 'تلقي إشعارات مخصصة') }}</li>
                                <li>{{ $tr('Accélérer le traitement de vos demandes', 'تسريع معالجة طلباتك') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn fw-semibold" wire:click="goToSettings"
                            style="background:#648454;color:white;border-radius:.6rem;">
                        <i class="ri-settings-3-line me-1"></i>{{ $tr('Compléter le profil', 'إكمال الملف الشخصي') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
