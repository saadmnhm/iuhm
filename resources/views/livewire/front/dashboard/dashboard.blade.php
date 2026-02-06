




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
                @foreach($this->formTypes as $type => $info)
                @php $project = $this->getProjectForType($type); @endphp
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-{{ $info['color'] }} bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="{{ $info['icon'] }} fs-3 text-{{ $info['color'] }}"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $info['label'] }}</h6>
                                    @if($project)
                                        @if($project->status === 'draft')
                                            <span class="badge bg-warning text-dark mt-1">Brouillon</span>
                                        @elseif($project->status === 'submitted')
                                            <span class="badge bg-info mt-1">Soumis</span>
                                        @elseif($project->status === 'in_review')
                                            <span class="badge bg-primary mt-1">En révision</span>
                                        @elseif($project->status === 'approved')
                                            <span class="badge bg-success mt-1">Approuvé</span>
                                        @elseif($project->status === 'rejected')
                                            <span class="badge bg-danger mt-1">Rejeté</span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-muted mt-1">Non commencé</span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($project)
                                <p class="text-muted small mb-3">
                                    <i class="ri-time-line me-1"></i>
                                    Dernière modification: {{ $project->updated_at->format('d/m/Y H:i') }}
                                </p>
                                @if($project->project_name)
                                    <p class="small mb-3 text-truncate"><strong>Projet:</strong> {{ $project->project_name }}</p>
                                @endif
                            @else
                                <p class="text-muted small mb-3">Vous n'avez pas encore commencé ce formulaire.</p>
                            @endif

                            <div class="d-grid">
                                @if($project && $project->status === 'submitted')
                                    <a href="{{ route($info['route']) }}" class="btn btn-outline-{{ $info['color'] }}">
                                        <i class="ri-eye-line me-1"></i>Voir
                                    </a>
                                @elseif($project && $project->status === 'draft')
                                    <a href="{{ route($info['route']) }}" class="btn btn-{{ $info['color'] }}">
                                        <i class="ri-edit-line me-1"></i>Continuer
                                    </a>
                                @else
                                    <a href="{{ route($info['route']) }}" class="btn btn-outline-{{ $info['color'] }}">
                                        <i class="ri-add-circle-line me-1"></i>Commencer
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

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
                                <a href="{{ route('form.business_plan') }}" class="btn btn-primary">
                                    <i class="ri-bar-chart-box-line me-2"></i>Nouveau Business Plan
                                </a>
                                <a href="{{ route('form.support') }}" class="btn btn-outline-secondary">
                                    <i class="ri-customer-service-2-line me-2"></i>Support
                                </a>
                                <a href="{{ route('form.settings') }}" class="btn btn-outline-secondary">
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