




    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="display-4 fw-bold text-primary mb-2">
                        <i class="ri-dashboard-line me-2"></i>{{ $candidat->nom}} {{ $candidat->prenom}} 
                    </h1>
                    <p class="text-muted">Welcome to your project management dashboard</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-4 mb-4">
                <!-- Total Projects -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-2 small fw-semibold text-uppercase">Total Projects</p>
                                    <h2 class="fw-bold mb-0 text-primary"></h2>
                                </div>
                                <div class="icon-box bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="ri-folder-line fs-3 text-primary"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-top">
                                <small class="text-success">
                                    <i class="ri-arrow-up-line"></i> All submissions
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Projects -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-2 small fw-semibold text-uppercase">Pending Review</p>
                                    <h2 class="fw-bold mb-0 text-warning"></h2>
                                </div>
                                <div class="icon-box bg-warning bg-opacity-10 rounded-3 p-3">
                                    <i class="ri-time-line fs-3 text-warning"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-top">
                                <small class="text-muted">
                                    <i class="ri-information-line"></i> Awaiting registration
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed Projects -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-2 small fw-semibold text-uppercase">Completed</p>
                                    <h2 class="fw-bold mb-0 text-success"></h2>
                                </div>
                                <div class="icon-box bg-success bg-opacity-10 rounded-3 p-3">
                                    <i class="ri-checkbox-circle-line fs-3 text-success"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-top">
                                <small class="text-success">
                                    <i class="ri-check-line"></i> Registered projects
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Success Rate -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-2 small fw-semibold text-uppercase">Success Rate</p>
                                    <h2 class="fw-bold mb-0 text-info">
                                        
                                    </h2>
                                </div>
                                <div class="icon-box bg-info bg-opacity-10 rounded-3 p-3">
                                    <i class="ri-line-chart-line fs-3 text-info"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-top">
                                <small class="text-info">
                                    <i class="ri-percent-line"></i> Completion rate
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Recent Projects Table -->
                <div class="col-12 col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="ri-file-list-3-line me-2 text-primary"></i>Recent Projects
                                </h5>
                                <a href="/" class="btn btn-sm btn-outline-primary">
                                    View All <i class="ri-arrow-right-line ms-1"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Quick Actions & Info -->
                <div class="col-12 col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="ri-flashlight-line me-2 text-primary"></i>Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="/" class="btn btn-primary btn-lg">
                                    <i class="ri-add-circle-line me-2"></i>New Project
                                </a>
                                <button class="btn btn-outline-secondary" wire:click="$refresh">
                                    <i class="ri-refresh-line me-2"></i>Refresh Data
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Project Distribution -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="ri-pie-chart-line me-2 text-primary"></i>Project Status
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small fw-semibold">Completed</span>
                                    <span class="small text-success fw-semibold"></span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" >
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small fw-semibold">Pending</span>
                                    <span class="small text-warning fw-semibold"></span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" >
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted">Total Projects</span>
                                    <span class="fw-bold"></span>
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
                                Complete Your Profile
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <div class="mb-3">
                                    <i class="ri-user-settings-line" style="font-size: 4rem; color: #648454;"></i>
                                </div>
                                <h5 class="mb-3">Your profile is incomplete</h5>
                                <p class="text-muted mb-0">
                                    Please complete your profile information to access all features. 
                                    Add your phone number, address, city, and country to get started.
                                </p>
                            </div>
                            
                            <div class="alert alert-info d-flex align-items-start">
                                <i class="ri-lightbulb-line me-2 mt-1"></i>
                                <div>
                                    <strong>Why complete your profile?</strong>
                                    <ul class="mb-0 mt-2 ps-3">
                                        <li>Submit and manage projects</li>
                                        <li>Receive important notifications</li>
                                        <li>Better support and communication</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                        
                            <button type="button" class="btn btn-primary" wire:click="goToSettings">
                                <i class="ri-settings-3-line me-1"></i>Complete Profile Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>