

<div class="dashboard-wrapper dashboard-container">
    <!-- Sidebar -->
    <livewire:components.aside />

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="display-4 fw-bold text-primary mb-2">
                        <i class="ri-dashboard-line me-2"></i>Dashboard
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
                                    <h2 class="fw-bold mb-0 text-primary">{{ $totalProjects }}</h2>
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
                                    <h2 class="fw-bold mb-0 text-warning">{{ $pendingProjects }}</h2>
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
                                    <h2 class="fw-bold mb-0 text-success">{{ $completedProjects }}</h2>
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
                                        {{ $totalProjects > 0 ? number_format(($completedProjects / $totalProjects) * 100, 1) : 0 }}%
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
                        <div class="card-body p-0">
                            @if($recentProjects && count($recentProjects) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0 fw-semibold">Project</th>
                                                <th class="border-0 fw-semibold">Owner</th>
                                                <th class="border-0 fw-semibold">Address</th>
                                                <th class="border-0 fw-semibold">Status</th>
                                                <th class="border-0 fw-semibold">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentProjects as $project)
                                            <tr>
                                                <td class="align-middle">
                                                    <div class="fw-semibold text-dark">{{ $project->project_name ?? 'N/A' }}</div>
                                                    <small class="text-muted">#{{ $project->id }}</small>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="d-flex align-items-center">
                                                        @if($project->profile_image)
                                                            <img src="{{ asset('uploads/'.$project->profile_image) }}" 
                                                                    alt="{{ $project->ceo_name }}" 
                                                                    class="rounded-circle me-2" 
                                                                    style="width: 32px; height: 32px; object-fit: cover;">
                                                        @else
                                                            <div class="avatar-circle me-2">
                                                                {{ strtoupper(substr($project->ceo_name ?? 'U', 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <span class="small">{{ $project->ceo_name ?? 'N/A' }}</span>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="ri-map-pin-line me-1"></i>{{ $project->address ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="align-middle">
                                                    @if($project->registration)
                                                        <span class="badge bg-success">
                                                            <i class="ri-check-line me-1"></i>Completed
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="ri-time-line me-1"></i>Pending
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <small class="text-muted">
                                                        <i class="ri-calendar-line me-1"></i>
                                                        {{ $project->created_at->format('M d, Y') }}
                                                    </small>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="ri-inbox-line display-1 text-muted"></i>
                                    <p class="text-muted mt-3">No projects found</p>
                                </div>
                            @endif
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
                                    <span class="small text-success fw-semibold">{{ $completedProjects }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" 
                                            style="width: {{ $totalProjects > 0 ? ($completedProjects / $totalProjects) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small fw-semibold">Pending</span>
                                    <span class="small text-warning fw-semibold">{{ $pendingProjects }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" 
                                            style="width: {{ $totalProjects > 0 ? ($pendingProjects / $totalProjects) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted">Total Projects</span>
                                    <span class="fw-bold">{{ $totalProjects }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>