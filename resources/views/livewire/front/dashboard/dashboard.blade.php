<div x-data="{ searchOpen: false }" style="min-height:100vh; background:#f8fafc;">
    @php
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

    <div class="container-fluid px-2 px-md-3  py-3 ">
    

        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <div class="small text-muted">Total dossiers</div>
                        <div class="h4 fw-bold mb-0">{{ $stats['total'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <div class="small text-muted">Brouillons</div>
                        <div class="h4 fw-bold mb-0 text-warning">{{ $stats['drafts'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <div class="small text-muted">Soumis / En revue</div>
                        <div class="h4 fw-bold mb-0 text-primary">{{ $stats['submitted'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <div class="small text-muted">Approuvés</div>
                        <div class="h4 fw-bold mb-0 text-success">{{ $stats['approved'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <div class="small text-muted">Non éligibles</div>
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
                            <h5 class="fw-bold mb-0">Projets et éligibilité</h5>
                            <span class="badge rounded-pill text-bg-light border">{{ $projectEligibilityStats['eligible'] }} éligible(s) sur {{ $projectEligibilityStats['total'] }}</span>
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
                                                    <p class="text-muted small mb-2">{{ $project['description'] ?: 'Aucune description disponible.' }}</p>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <span class="badge rounded-pill text-bg-light border">Âge: {{ $project['min_age'] ?? '-' }} - {{ $project['max_age'] ?? '-' }} ans</span>
                                                        @if($project['already_started'])
                                                            <span class="badge rounded-pill bg-info-subtle text-info-emphasis border border-info-subtle">Déjà commencé</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                @if($project['eligible'])
                                                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle mb-2">Éligible</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle mb-2">Non éligible</span>
                                                @endif
                                                <div>
                                                    <a href="{{ route('user.project.detail', $project['id']) }}" class="btn btn-sm fw-semibold {{ $project['eligible'] ? 'btn-primary' : 'btn-outline-secondary' }} rounded-3">
                                                        <i class="ri-eye-line me-1"></i>{{ $project['eligible'] ? 'Postuler / Continuer' : 'Voir conditions' }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        @if(!$project['eligible'] && !empty($project['reasons']))
                                            <div class="alert alert-warning mt-3 mb-0 py-2 px-3 small rounded-3 border-0">
                                                <strong class="d-block mb-1">Pourquoi non éligible ?</strong>
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
                                        Aucun projet disponible actuellement.
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
                        <h6 class="fw-bold mb-3">Infos utiles</h6>
                        <ul class="list-unstyled mb-0 d-grid gap-3 small">
                            <li class="d-flex align-items-start gap-2">
                                <i class="ri-user-heart-line mt-1 text-primary"></i>
                                <div>Âge candidat: <strong>{{ $candidateAge ?? 'Non renseigné' }}</strong></div>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="ri-map-pin-user-line mt-1 text-success"></i>
                                <div>Adresse: <strong>{{ $candidat->address ?: 'Non renseignée' }}</strong></div>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="ri-folder-chart-line mt-1 text-info"></i>
                                <div>Projets déjà démarrés: <strong>{{ $projectEligibilityStats['started'] }}</strong></div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Actions rapides</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('user.projects.list') }}" class="btn btn-outline-primary rounded-3 text-start">
                                <i class="ri-briefcase-line me-1"></i>Explorer les projets
                            </a>
                            <a href="{{ route('user.settings') }}" class="btn btn-outline-secondary rounded-3 text-start">
                                <i class="ri-settings-3-line me-1"></i>Mettre à jour mon profil
                            </a>
                            <a href="{{ route('user.support') }}" class="btn btn-outline-success rounded-3 text-start">
                                <i class="ri-customer-service-2-line me-1"></i>Contacter le support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-5">
            <div class="card-header bg-white border-0 p-4 pb-2">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                    <h5 class="fw-bold mb-0">Mes soumissions récentes</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <button wire:click="setFilter('draft')" class="btn btn-sm {{ $activeFilter === 'draft' ? 'btn-warning' : 'btn-outline-warning' }} rounded-pill">Brouillons</button>
                        <button wire:click="setFilter('submitted')" class="btn btn-sm {{ $activeFilter === 'submitted' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill">Soumis</button>
                        <button wire:click="setFilter('approved')" class="btn btn-sm {{ $activeFilter === 'approved' ? 'btn-success' : 'btn-outline-success' }} rounded-pill">Approuvés</button>
                        <button wire:click="setFilter('rejected')" class="btn btn-sm {{ $activeFilter === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill">Rejetés</button>
                        <button wire:click="clearFilters" class="btn btn-sm btn-outline-secondary rounded-pill">Réinitialiser</button>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="ri-search-line"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="searchQuery" class="form-control border-start-0" placeholder="Rechercher par formulaire ou projet...">
                    </div>
                </div>
            </div>
            <div class="card-body p-4 pt-3">
                @forelse($filteredSubmissions as $submission)
                    <div class="border rounded-4 p-3 mb-3">
                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $submission['form_title'] }}</h6>
                                <p class="text-muted small mb-2">
                                    Projet: <strong>{{ $submission['programe_name'] ?: 'Formulaire général' }}</strong>
                                </p>
                                <div class="d-flex flex-wrap gap-2 small text-muted">
                                    <span><i class="ri-calendar-event-line me-1"></i>Maj: {{ $submission['updated_at'] ?: '-' }}</span>
                                    <span><i class="ri-stairs-line me-1"></i>Étape {{ $submission['current_step'] }} / {{ $submission['total_steps'] }}</span>
                                </div>
                            </div>
                            <div class="text-lg-end">
                                <span class="badge mb-2" style="background:{{ $submission['status_badge_color'] ?? '#6b7280' }}; color:#fff;">{{ $submission['status_label'] ?? ucfirst($submission['status']) }}</span>
                                <div>
                                    @if(in_array($submission['status'], ['draft', 'returned']))
                                        <button wire:click="resumeForm({{ $submission['id'] }})" class="btn btn-sm btn-primary rounded-3 fw-semibold">
                                            <i class="ri-play-circle-line me-1"></i>Continuer
                                        </button>
                                    @else
                                        <button wire:click="resumeForm({{ $submission['id'] }})" class="btn btn-sm btn-outline-secondary rounded-3 fw-semibold">
                                            <i class="ri-eye-line me-1"></i>Voir
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="ri-file-list-3-line fs-1 d-block mb-2"></i>
                        Aucune soumission trouvée avec les filtres actuels.
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
                        <i class="ri-information-line text-warning me-2"></i>Complétez votre profil
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="ri-user-settings-line" style="font-size:3.5rem;color:#648454;"></i>
                        <h5 class="mt-3 mb-2">Votre profil est incomplet</h5>
                        <p class="text-muted mb-0 small">Veuillez compléter vos informations pour accéder à toutes les fonctionnalités.</p>
                    </div>
                    <div class="alert alert-info d-flex align-items-start border-0 rounded-3">
                        <i class="ri-lightbulb-line me-2 mt-1"></i>
                        <div>
                            <strong>Pourquoi compléter votre profil ?</strong>
                            <ul class="mb-0 mt-2 ps-3 small">
                                <li>Améliorer la précision des critères d'éligibilité</li>
                                <li>Recevoir des notifications personnalisées</li>
                                <li>Accélérer le traitement de vos demandes</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn fw-semibold" wire:click="goToSettings"
                            style="background:#648454;color:white;border-radius:.6rem;">
                        <i class="ri-settings-3-line me-1"></i>Compléter le profil
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div x-data="{ fabOpen: false }" style="position:fixed;right:1.25rem;bottom:1.5rem;z-index:1000;" class="d-lg-none">
        <div x-show="fabOpen" x-transition class="d-flex flex-column gap-2 mb-2 align-items-end">
            <a href="{{ route('user.projects.list') }}" class="btn btn-sm fw-semibold d-flex align-items-center gap-2 shadow-sm" style="background:white;border:1px solid #e2e8f0;color:#374151;border-radius:2rem;padding:.45rem 1rem;font-size:.8rem;">
                <i class="ri-briefcase-4-line" style="color:#2f5496;"></i>Projets
            </a>
            <a href="{{ route('user.settings') }}" class="btn btn-sm fw-semibold d-flex align-items-center gap-2 shadow-sm" style="background:white;border:1px solid #e2e8f0;color:#374151;border-radius:2rem;padding:.45rem 1rem;font-size:.8rem;">
                <i class="ri-settings-3-line" style="color:#6366f1;"></i>Paramètres
            </a>
            <a href="{{ route('user.support') }}" class="btn btn-sm fw-semibold d-flex align-items-center gap-2 shadow-sm" style="background:white;border:1px solid #e2e8f0;color:#374151;border-radius:2rem;padding:.45rem 1rem;font-size:.8rem;">
                <i class="ri-customer-service-2-line" style="color:#22c55e;"></i>Support
            </a>
        </div>
        <button @click="fabOpen = !fabOpen" class="btn btn-lg rounded-circle shadow d-flex align-items-center justify-content-center" style="width:52px;height:52px;background:#6366f1;color:white;border:none;font-size:1.4rem;">
            <i :class="fabOpen ? 'ri-close-line' : 'ri-add-line'"></i>
        </button>
    </div>
</div>
