




    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="display-4 fw-bold text-primary mb-2">
                        <i class="ri-dashboard-line me-2"></i>{{ $candidat->nom}} {{ $candidat->prenom}} 
                    </h1>
                    <p class="text-muted">Bienvenue sur votre tableau de bord</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-2 small fw-semibold text-uppercase">Total Formulaires</p>
                                    <h2 class="fw-bold mb-0 text-primary">{{ $stats['total'] }}</h2>
                                </div>
                                <div class="icon-box bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="ri-folder-line fs-3 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-2 small fw-semibold text-uppercase">Brouillons</p>
                                    <h2 class="fw-bold mb-0 text-warning">{{ $stats['drafts'] }}</h2>
                                </div>
                                <div class="icon-box bg-warning bg-opacity-10 rounded-3 p-3">
                                    <i class="ri-draft-line fs-3 text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-2 small fw-semibold text-uppercase">Soumis</p>
                                    <h2 class="fw-bold mb-0 text-info">{{ $stats['submitted'] }}</h2>
                                </div>
                                <div class="icon-box bg-info bg-opacity-10 rounded-3 p-3">
                                    <i class="ri-send-plane-line fs-3 text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-2 small fw-semibold text-uppercase">Approuvés</p>
                                    <h2 class="fw-bold mb-0 text-success">{{ $stats['approved'] }}</h2>
                                </div>
                                <div class="icon-box bg-success bg-opacity-10 rounded-3 p-3">
                                    <i class="ri-checkbox-circle-line fs-3 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Types Grid -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="fw-bold mb-3"><i class="ri-file-list-3-line me-2 text-primary"></i>Mes Formulaires</h5>
                </div>
            </div>
            <div class="row g-4 mb-4">
                @foreach($programe_list as $prog_list)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            @if ($candidat->age >= $prog_list->min_age && $candidat->age <= $prog_list->max_age || in_array($candidat->adresse, explode(',', $prog_list->allowed_address_id)))
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box bg-{{ $prog_list->color }} bg-opacity-10 rounded-3 p-3 me-3">
                                        <i class="{{ $prog_list->icon }} fs-3 text-{{ $prog_list->color }}"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $prog_list->project_name }}</h6>
                                    
                                    </div>
                                </div>
                                
                                @if($prog_list)
                                    <p class="text-muted small mb-3">
                                        <i class="ri-time-line me-1"></i>
                                        Dernière modification: {{ $prog_list->updated_at->format('d/m/Y H:i') }}
                                    </p>
                                    @if($prog_list->project_name)
                                        <p class="small mb-3 text-truncate"><strong>Projet:</strong> {{ $prog_list->project_name }}</p>
                                    @endif
                                @else
                                    <p class="text-muted small mb-3">Vous n'avez pas encore commencé ce formulaire.</p>
                                @endif

                                <div class="d-grid">
                                    @if($prog_list && $prog_list->status === 'submitted')
                                        <a href="" class="btn btn-outline">
                                            <i class="ri-eye-line me-1"></i>Voir
                                        </a>
                                    @elseif($prog_list && $prog_list->status === 'draft')
                                        <a href="" class="btn btn">
                                            <i class="ri-edit-line me-1"></i>Continuer
                                        </a>
                                    @else
                                        <a href="{{ route('user.project.detail', $prog_list->id) }}" class="btn btn-outline">
                                            <i class="ri-add-circle-line me-1"></i>Commencer
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @else
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-box bg-secondary bg-opacity-10 rounded-3 p-3 me-3">
                                            <i class="{{ $prog_list->icon }} fs-3 text-secondary"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0">{{ $prog_list->project_name }}</h6>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary mt-1">Inéligible</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Dynamic Form Submissions (Drafts & Submitted) -->
            @if(count($dynamicSubmissions) > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="fw-bold mb-3"><i class="ri-draft-line me-2 text-warning"></i>Mes Soumissions de Formulaires</h5>
                </div>
            </div>
            <div class="row g-4 mb-4">
                @foreach($dynamicSubmissions as $index => $sub)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card border-0 shadow-sm h-100 hover-lift" style="border-left: 4px solid {{ $sub['form_color'] }} !important;">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 48px; height: 48px; background-color: {{ $sub['form_color'] }}15;">
                                        <i class="{{ $sub['form_icon'] }} fs-4" style="color: {{ $sub['form_color'] }};"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-0">{{ $sub['form_title'] }}</h6>
                                        @if($sub['form_title_ar'])
                                            <small class="text-muted" dir="rtl">{{ $sub['form_title_ar'] }}</small>
                                        @endif
                                    </div>
                                    <span class="badge rounded-pill 
                                        @if($sub['status'] === 'draft') bg-warning text-dark
                                        @elseif($sub['status'] === 'submitted') bg-info text-white
                                        @elseif($sub['status'] === 'in_review') bg-primary text-white
                                        @elseif($sub['status'] === 'approved') bg-success text-white
                                        @elseif($sub['status'] === 'rejected') bg-danger text-white
                                        @else bg-secondary text-white
                                        @endif">
                                        {{ $sub['status_label'] }}
                                    </span>
                                </div>

                                @if($sub['programe_name'])
                                    <p class="small mb-2">
                                        <i class="ri-folder-line me-1 text-primary"></i>
                                        <strong>Projet:</strong> {{ $sub['programe_name'] }}
                                    </p>
                                @endif

                                @if($sub['status'] === 'draft' && $sub['total_steps'] > 0)
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between small text-muted mb-1">
                                            <span>Progression</span>
                                            <span>Étape {{ $sub['current_step'] }} / {{ $sub['total_steps'] }}</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-warning" role="progressbar" 
                                                 style="width: {{ ($sub['current_step'] / $sub['total_steps']) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @endif

                                <p class="text-muted small mb-3">
                                    <i class="ri-time-line me-1"></i>
                                    Dernière modification: {{ $sub['updated_at'] }}
                                </p>

                                @if($sub['submitted_at'])
                                    <p class="text-muted small mb-3">
                                        <i class="ri-send-plane-line me-1"></i>
                                        Soumis le: {{ $sub['submitted_at'] }}
                                    </p>
                                @endif

                                <div class="d-grid">
                                    @if($sub['status'] === 'draft')
                                        <button wire:click="resumeForm({{ $index }})" class="btn btn-warning btn-sm">
                                            <i class="ri-edit-line me-1"></i>Continuer le brouillon
                                        </button>
                                    @else
                                        <button wire:click="resumeForm({{ $index }})" class="btn btn-outline-primary btn-sm">
                                            <i class="ri-eye-line me-1"></i>Voir
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="row g-4">
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="ri-flashlight-line me-2 text-primary"></i>Actions Rapides
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('user.support') }}" class="btn btn-outline-secondary">
                                    <i class="ri-customer-service-2-line me-2"></i>Support
                                </a>
                                <a href="{{ route('user.settings') }}" class="btn btn-outline-secondary">
                                    <i class="ri-settings-3-line me-2"></i>Paramètres du profil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="ri-information-line me-2 text-primary"></i>Informations
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Email</span>
                                    <span class="fw-semibold">{{ $candidat->email }}</span>
                                </div>
                            </div>
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Inscrit le</span>
                                    <span class="fw-semibold">{{ $candidat->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Formulaires complétés</span>
                                    <span class="fw-semibold">{{ $stats['submitted'] + $stats['approved'] }} / 5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($showCompleteProfileModal)
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title fw-bold">
                                <i class="ri-information-line text-warning me-2"></i>
                                Complétez votre profil
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <div class="mb-3">
                                    <i class="ri-user-settings-line" style="font-size: 4rem; color: #648454;"></i>
                                </div>
                                <h5 class="mb-3">Votre profil est incomplet</h5>
                                <p class="text-muted mb-0">
                                    Veuillez compléter vos informations de profil pour accéder à toutes les fonctionnalités.
                                </p>
                            </div>
                            
                            <div class="alert alert-info d-flex align-items-start">
                                <i class="ri-lightbulb-line me-2 mt-1"></i>
                                <div>
                                    <strong>Pourquoi compléter votre profil?</strong>
                                    <ul class="mb-0 mt-2 ps-3">
                                        <li>Soumettre et gérer vos projets</li>
                                        <li>Recevoir des notifications importantes</li>
                                        <li>Meilleur support et communication</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-primary" wire:click="goToSettings">
                                <i class="ri-settings-3-line me-1"></i>Compléter le profil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>