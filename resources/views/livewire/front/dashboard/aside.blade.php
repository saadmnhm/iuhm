<aside class="sidebar d-flex flex-column">
    <div class="logo">
       <a href="{{ route('user.dashboard') }}"> <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="Logo"></a>
    </div>

    <nav class="py-3 flex-grow-1">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="ri-home-4-line fs-5"></i>
                    <span>Home</span>
                </a>
            </li>
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex w-full items-center justify-between gap-3 px-4 py-3 rounded-lg transition hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3" />
                            </svg>
                            <span>Projets</span>
                            <!-- Arrow -->
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    @foreach($programe_list ?? [] as $list)
                        <div x-show="open" x-collapse class="ml-3 mt-1 space-y-1">
                            <li class="nav-item">
                                <a href="{{ route('user.project.detail', $list->id) }}" class="nav-link {{ request()->routeIs('user.project.detail') && request()->route('id') == $list->id ? 'active' : '' }}">
                                    <i class="ri-folder-open-line fs-5"></i>
                                    <span>{{ $list->project_name }}</span>
                                </a>
                            </li>
                        </div>
                    @endforeach 
            </div>
            
            @if(false)  

                <div x-data="{ open: false }">
                    <!-- Parent -->
                    <button @click="open = !open" class="flex w-full items-center justify-between gap-3 px-4 py-3 rounded-lg transition hover:bg-gray-100">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3" />
                            </svg>
                            <span>Formulaires</span>
                            <!-- Arrow -->
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <!-- Children -->
                    <div x-show="open" x-collapse class="ml-3 mt-1 space-y-1">
                        <li class="nav-item">
                            <a href="{{ route('user.etude_marche') }}" class="nav-link {{ request()->routeIs('user.etude_marche') ? 'active' : '' }}">
                                <i class="ri-search-eye-line fs-5"></i>
                                <span>Etude De Marche</span>
                            </a>
                        </li>                
                        <li class="nav-item">
                            <a href="{{ route('user.evaluation_idee') }}" class="nav-link {{ request()->routeIs('user.evaluation_idee') ? 'active' : '' }}">
                                <i class="ri-lightbulb-line fs-5"></i>
                                <span>Evaluation Idee</span>
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a href="{{ route('user.bilan_competences') }}" class="nav-link {{ request()->routeIs('user.bilan_competences') ? 'active' : '' }}">
                                <i class="ri-user-star-line fs-5"></i>
                                <span>Bilan Competences</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.bmc') }}" class="nav-link {{ request()->routeIs('user.bmc') ? 'active' : '' }}">
                                <i class="ri-layout-grid-line fs-5"></i>
                                <span>Business model canevas</span>
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a href="{{ route('user.business_plan') }}" class="nav-link {{ request()->routeIs('user.business_plan') ? 'active' : '' }}">
                                <i class="ri-bar-chart-box-line fs-5"></i>
                                <span>Business Plan</span>
                            </a>
                        </li>
                    </div>
                </div>
            @endif


            <li class="nav-item mt-3">
                <small class="nav-link text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Assistance</small>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.support') }}" class="nav-link {{ request()->routeIs('user.support') ? 'active' : '' }}">
                    <i class="ri-customer-service-2-line fs-5"></i>
                    <span>Support</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.blog') }}" class="nav-link {{ request()->routeIs('user.blog*') ? 'active' : '' }}">
                    <i class="ri-article-line fs-5"></i>
                    <span>Blog & Actualités</span>
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