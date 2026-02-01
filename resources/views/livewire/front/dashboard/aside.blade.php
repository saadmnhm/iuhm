<aside class="sidebar d-flex flex-column">
    <div class="logo">
       <a href="{{ route('form.dashboard') }}"> <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="Logo"></a>
    </div>

    <nav class="py-3 flex-grow-1">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('form.dashboard') }}" class="nav-link {{ request()->routeIs('form.dashboard') ? 'active' : '' }}">
                    <i class="ri-home-4-line fs-5"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('form.business_plan') }}" class="nav-link {{ request()->routeIs('form.business_plan') ? 'active' : '' }}">
                    <i class="ri-bar-chart-box-line fs-5"></i>
                    <span>Business Plan</span>
                </a>
            </li>
            
        </ul>
    </nav>
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
</aside>