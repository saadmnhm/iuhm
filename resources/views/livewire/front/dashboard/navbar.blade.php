
<nav class="top-navbar">
        <div class="navbar-content">
            <!-- Left Side - Page Title -->
            <div class="navbar-left">
                <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
                    <i class="ri-menu-line"></i>
                </button>
                <h5 class="page-title mb-0">{{ $pageTitle ?? 'Dashboard' }}</h5>
            </div>

            <!-- Right Side -->
            <div class="navbar-right">

                <!-- Notifications Dropdown -->
                <div class="dropdown notification-dropdown">
                    <button class="nav-icon-btn" type="button" id="notificationDropdown"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <i class="ri-notification-3-line"></i>
                        @if($unreadCount > 0)
                        <span class="notification-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </button>

                    <div class="dropdown-menu dropdown-menu-end notification-menu shadow-lg border-0 rounded-3 p-0"
                        style="min-width:340px;max-width:380px;" aria-labelledby="notificationDropdown">

                        {{-- Header --}}
                        <div class="d-flex align-items-center justify-content-between px-4 py-3"
                            style="border-bottom:1px solid #f1f5f9;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold" style="font-size:.9rem;color:#1e293b;">Notifications</span>
                                @if($unreadCount > 0)
                                <span class="badge rounded-pill"
                                    style="background:#6366f1;color:white;font-size:.68rem;">{{ $unreadCount }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Notification items --}}
                        <div style="max-height:340px;overflow-y:auto;">
                            @forelse($notifications as $notif)
                            <a class="dropdown-item d-flex align-items-start gap-3 px-4 py-3"
                            href="#"
                            style="border-bottom:1px solid #f8fafc;transition:background .15s;"
                            onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 {{ $notif['color'] }}"
                                    style="width:36px;height:36px;color:white;">
                                    <i class="{{ $notif['icon'] }}" style="font-size:.95rem;"></i>
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <div class="fw-semibold text-truncate" style="font-size:.82rem;color:#1e293b;">{{ $notif['title'] }}</div>
                                    <div class="text-truncate" style="font-size:.75rem;color:#64748b;">{{ $notif['text'] }}</div>
                                    <div style="font-size:.68rem;color:#94a3b8;margin-top:.15rem;">{{ $notif['time'] }}</div>
                                </div>
                            </a>
                            @empty
                            <div class="text-center py-5 px-3">
                                <i class="ri-notification-off-line" style="font-size:2rem;color:#cbd5e1;"></i>
                                <p class="mt-2 mb-0 text-muted" style="font-size:.82rem;">Aucune notification</p>
                            </div>
                            @endforelse
                        </div>

                        {{-- Footer --}}
                        @if(count($notifications) > 0)
                        <div class="px-4 py-2 text-center" style="border-top:1px solid #f1f5f9;">
                            <a href="{{ route('user.dashboard') }}"
                            class="text-decoration-none fw-semibold"
                            style="font-size:.78rem;color:#6366f1;">
                                <i class="ri-external-link-line me-1"></i>Voir toutes les soumissions
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="dropdown profile-dropdown">
                    <button class="profile-btn" type="button" id="profileDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar">
                            @if($profile_image)
                                <img src="{{ route('uploads.show', ['path' => $profile_image]) }}" alt="Profile">
                            @else
                                <i class="ri-user-line"></i>
                            @endif
                        </div>
                        <div class="profile-info d-none d-md-block">
                            <div class="profile-name">{{ Auth::guard('candidat')->user()->nom }} {{ Auth::guard('candidat')->user()->prenom }}</div>
                            <div class="profile-role">Candidat</div>
                        </div>
                        <i class="ri-arrow-down-s-line d-none d-md-block"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end profile-menu shadow border-0 rounded-3"
                        aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('user.settings') }}">
                                <i class="ri-settings-3-line" style="color:#6366f1;"></i> Paramètres
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form action="{{ route('user.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                    <i class="ri-logout-box-line"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <div class="d-flex gap-2 px-3 pb-2 justify-content-center">
                                <a href="{{ route('lang.switch', ['locale' => 'fr']) }}"
                                class="btn btn-xs fw-semibold"
                                style="padding:4px 12px;border-radius:.4rem;font-size:.75rem;
                                        {{ app()->getLocale()==='fr' ? 'background:#648454;color:white;border:1px solid #648454;' : 'background:white;color:#374151;border:1px solid #e2e8f0;' }}">FR</a>
                                <a href="{{ route('lang.switch', ['locale' => 'en']) }}"
                                class="btn btn-xs fw-semibold"
                                style="padding:4px 12px;border-radius:.4rem;font-size:.75rem;
                                        {{ app()->getLocale()==='en' ? 'background:#648454;color:white;border:1px solid #648454;' : 'background:white;color:#374151;border:1px solid #e2e8f0;' }}">EN</a>
                                <a href="{{ route('lang.switch', ['locale' => 'ar']) }}"
                                class="btn btn-xs fw-semibold"
                                style="padding:4px 12px;border-radius:.4rem;font-size:.75rem;
                                        {{ app()->getLocale()==='ar' ? 'background:#648454;color:white;border:1px solid #648454;' : 'background:white;color:#374151;border:1px solid #e2e8f0;' }}">AR</a>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <ul class="dropdown-menu dropdown-menu-end notification-menu" aria-labelledby="notificationDropdown">
            <li class="dropdown-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Notifications</span>
                    <a href="#" class="text-primary small">Mark all as read</a>
                </div>
            </li>
            <li><hr class="dropdown-divider"></li>
            
            <!-- Notification Items -->
            <li>
                <a class="dropdown-item notification-item unread" href="#">
                    <div class="notification-icon bg-primary">
                        <i class="ri-file-text-line"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">New Project Submitted</div>
                        <div class="notification-text">Your project has been successfully submitted</div>
                        <div class="notification-time">5 minutes ago</div>
                    </div>
                </a>
            </li>
            <li>
                <a class="dropdown-item notification-item unread" href="#">
                    <div class="notification-icon bg-success">
                        <i class="ri-check-line"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">Project Approved</div>
                        <div class="notification-text">Your project "Innovation 2024" has been approved</div>
                        <div class="notification-time">2 hours ago</div>
                    </div>
                </a>
            </li>
            <li>
                <a class="dropdown-item notification-item" href="#">
                    <div class="notification-icon bg-warning">
                        <i class="ri-information-line"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">Update Required</div>
                        <div class="notification-text">Please update your profile information</div>
                        <div class="notification-time">1 day ago</div>
                    </div>
                </a>
            </li>
            
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-center text-primary" href="#">
                    View All Notifications
                </a>
            </li>
        </ul>

        
</nav>
