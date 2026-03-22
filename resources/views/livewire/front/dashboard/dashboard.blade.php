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

<div x-data="{ searchOpen: false }" style="min-height:100vh; background:#f8fafc; {{ $isArabic ? 'direction:rtl;' : '' }}">

    <div class="container-fluid px-2 px-md-3  py-3 ">
    

        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <div class="small text-muted">{{ $tr('Total dossiers', 'إجمالي الملفات') }}</div>
                        <div class="h4 fw-bold mb-0">{{ $stats['total'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <div class="small text-muted">{{ $tr('Brouillons', 'المسودات') }}</div>
                        <div class="h4 fw-bold mb-0 text-warning">{{ $stats['drafts'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <div class="small text-muted">{{ $tr('Soumis / En revue', 'مرسلة / قيد المراجعة') }}</div>
                        <div class="h4 fw-bold mb-0 text-primary">{{ $stats['submitted'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <div class="small text-muted">{{ $tr('Approuvés', 'تمت الموافقة') }}</div>
                        <div class="h4 fw-bold mb-0 text-success">{{ $stats['approved'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <div class="small text-muted">{{ $tr('Non éligibles', 'غير مؤهلين') }}</div>
                        <div class="h4 fw-bold mb-0 text-danger">{{ $projectEligibilityStats['not_eligible'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h5 class="fw-bold mb-0">{{ $tr('Projets et éligibilité', 'المشاريع والأهلية') }}</h5>
                            <span class="badge rounded-pill text-bg-light border">{{ $projectEligibilityStats['eligible'] }} {{ $tr('éligible(s) sur', 'مؤهل من أصل') }} {{ $projectEligibilityStats['total'] }}</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @forelse($projectInsights as $project)
                                <div class="col-12">
                                    <div class="border rounded-4 p-3 p-md-4 h-100" style="background:{{ $project['bg_color'] ?: '#f8fafc' }}22; border-color:#e5e7eb !important;">
                                        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                            <div class="d-flex gap-3 align-items-start">
                                                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:{{ $project['color'] }}1f; color:{{ $project['color'] }};">
                                                    <i class="{{ $project['icon'] }} fs-5"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1">{{ $project['name'] }}</h6>
                                                    <p class="text-muted small mb-2">{{ $project['description'] ?: $tr('Aucune description disponible.', 'لا يوجد وصف متاح.') }}</p>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <span class="badge rounded-pill text-bg-light border">{{ $tr('Âge', 'العمر') }}: {{ $project['min_age'] ?? '-' }} - {{ $project['max_age'] ?? '-' }} {{ $tr('ans', 'سنة') }}</span>
                                                        @if($project['already_started'])
                                                            <span class="badge rounded-pill bg-info-subtle text-info-emphasis border border-info-subtle">{{ $tr('Déjà commencé', 'تم البدء مسبقًا') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                @if($project['eligible'])
                                                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle mb-2">{{ $tr('Éligible', 'مؤهل') }}</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle mb-2">{{ $tr('Non éligible', 'غير مؤهل') }}</span>
                                                @endif
                                                <div>
                                                    <a href="{{ route('user.project.detail', $project['id']) }}" class="btn btn-sm fw-semibold {{ $project['eligible'] ? 'btn-primary' : 'btn-outline-secondary' }} rounded-3">
                                                        <i class="ri-eye-line me-1"></i>{{ $project['eligible'] ? $tr('Postuler / Continuer', 'التقديم / المتابعة') : $tr('Voir conditions', 'عرض الشروط') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        @if(!$project['eligible'] && !empty($project['reasons']))
                                            <div class="alert alert-warning mt-3 mb-0 py-2 px-3 small rounded-3 border-0">
                                                <strong class="d-block mb-1">{{ $tr('Pourquoi non éligible ?', 'لماذا غير مؤهل؟') }}</strong>
                                                <ul class="mb-0 ps-3">
                                                    @foreach($project['reasons'] as $reason)
                                                        <li>{{ $reason }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center text-muted py-5">
                                        <i class="ri-inbox-archive-line fs-1 d-block mb-2"></i>
                                        {{ $tr('Aucun projet disponible actuellement.', 'لا توجد مشاريع متاحة حاليًا.') }}
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">{{ $tr('Infos utiles', 'معلومات مفيدة') }}</h6>
                        <ul class="list-unstyled mb-0 d-grid gap-3 small">
                            <li class="d-flex align-items-start gap-2">
                                <i class="ri-user-heart-line mt-1 text-primary"></i>
                                <div>{{ $tr('Âge candidat', 'عمر المترشح') }}: <strong>{{ $candidateAge ?? $tr('Non renseigné', 'غير مذكور') }}</strong></div>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="ri-map-pin-user-line mt-1 text-success"></i>
                                <div>{{ $tr('Adresse', 'العنوان') }}: <strong>{{ collect([ $candidat->selected_region, $candidat->selected_city, $candidat->selected_prefecture, $candidat->address_detail ])->filter()->join(', ') ?: ($isArabic ? 'غير متوفر' : 'Non renseigné') }} </strong></div>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="ri-folder-chart-line mt-1 text-info"></i>
                                <div>{{ $tr('Projets déjà démarrés', 'المشاريع التي بدأت بالفعل') }}: <strong>{{ $projectEligibilityStats['started'] }}</strong></div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">{{ $tr('Actions rapides', 'إجراءات سريعة') }}</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('user.projects.list') }}" class="btn btn-outline-primary rounded-3 text-start">
                                <i class="ri-briefcase-line me-1"></i>{{ $tr('Explorer les projets', 'استكشاف المشاريع') }}
                            </a>
                            <a href="{{ route('user.settings') }}" class="btn btn-outline-secondary rounded-3 text-start">
                                <i class="ri-settings-3-line me-1"></i>{{ $tr('Mettre à jour mon profil', 'تحديث ملفي الشخصي') }}
                            </a>
                            <a href="{{ route('user.support') }}" class="btn btn-outline-success rounded-3 text-start">
                                <i class="ri-customer-service-2-line me-1"></i>{{ $tr('Contacter le support', 'الاتصال بالدعم') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-5">
        
            <div class="card-body p-4 pt-3">
                @forelse($filteredSubmissions as $submission)
                    <div class="border rounded-4 p-3 mb-3">
                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $submission['form_title'] }}</h6>
                                <p class="text-muted small mb-2">
                                    {{ $tr('Projet', 'المشروع') }}: <strong>{{ $submission['programe_name'] ?: $tr('Formulaire général', 'استمارة عامة') }}</strong>
                                </p>
                                <div class="d-flex flex-wrap gap-2 small text-muted">
                                    <span><i class="ri-calendar-event-line me-1"></i>{{ $tr('Maj', 'آخر تحديث') }}: {{ $submission['updated_at'] ?: '-' }}</span>
                                    <span><i class="ri-stairs-line me-1"></i>{{ $tr('Étape', 'المرحلة') }} {{ $submission['current_step'] }} / {{ $submission['total_steps'] }}</span>
                                </div>
                            </div>
                            <div class="text-lg-end">
                                <span class="badge mb-2" style="background:{{ $submission['status_badge_color'] ?? '#6b7280' }}; color:#fff;">{{ $submission['status_label'] ?? ucfirst($submission['status']) }}</span>
                                <div>
                                    @if(in_array($submission['status'], ['draft', 'returned']))
                                        <button wire:click="resumeForm({{ $submission['id'] }})" class="btn btn-sm btn-primary rounded-3 fw-semibold">
                                            <i class="ri-play-circle-line me-1"></i>{{ $tr('Continuer', 'متابعة') }}
                                        </button>
                                    @else
                                        <button wire:click="resumeForm({{ $submission['id'] }})" class="btn btn-sm btn-outline-secondary rounded-3 fw-semibold">
                                            <i class="ri-eye-line me-1"></i>{{ $tr('Voir', 'عرض') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="ri-file-list-3-line fs-1 d-block mb-2"></i>
                        {{ $tr('Aucune soumission trouvée avec les filtres actuels.', 'لم يتم العثور على أي طلب وفق عوامل التصفية الحالية.') }}
                    </div>
                @endforelse
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
