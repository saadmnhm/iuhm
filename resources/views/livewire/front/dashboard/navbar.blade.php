
<nav class="top-navbar">
    <div class="navbar-content">
        <!-- Left Side - Page Title/Breadcrumb -->
        <div class="navbar-left">
            <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
                <i class="ri-menu-line"></i>
            </button>
            <h5 class="page-title mb-0">{{ $pageTitle ?? 'Dashboard' }}</h5>
        </div>

        <!-- Right Side - Notifications & Profile -->
        <div class="navbar-right">
            <!-- Notifications Dropdown -->
            <div class="dropdown notification-dropdown">
                <button class="nav-icon-btn" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-notification-3-line"></i>
                    <span class="notification-badge">3</span>
                </button>
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
            </div>

            <!-- Profile Dropdown -->
            <div class="dropdown profile-dropdown">
                <button class="profile-btn" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-avatar">
                        @if($profile_image)
                            <img src="{{ asset('uploads/' . $profile_image) }}" alt="Profile">
                        @else
                            <i class="ri-user-line"></i>
                        @endif
                    </div>
                    <div class="profile-info d-none d-md-block">
                        <div class="profile-name">{{ Auth::guard('candidat')->user()->nom }} {{ Auth::guard('candidat')->user()->prenom }}</div>
                        <div class="profile-role">User</div>
                    </div>
                    <i class="ri-arrow-down-s-line d-none d-md-block"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end profile-menu" aria-labelledby="profileDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('form.settings') }}">
                            <i class="ri-settings-3-line me-2"></i> Settings
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('form.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="ri-logout-box-line me-2"></i> Sign Out
                            </button>
                        </form>
                    </li>
                    <div class="language-switcher" style="display: flex; gap: 10px; margin-top: 10px; justify-content: space-evenly;">
                        <a href="{{ route('lang.switch', ['locale' => 'en']) }}" 
                        class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                        style="padding: 5px 15px; border: 1px solid #ccc; border-radius: 5px; text-decoration: none; {{ app()->getLocale() === 'en' ? 'background: #648454; color: white;' : 'background: white; color: #333;' }}">
                            EN
                        </a>
                        <a href="{{ route('lang.switch', ['locale' => 'fr']) }}" 
                        class="lang-btn {{ app()->getLocale() === 'fr' ? 'active' : '' }}"
                        style="padding: 5px 15px; border: 1px solid #ccc; border-radius: 5px; text-decoration: none; {{ app()->getLocale() === 'fr' ? 'background: #648454; color: white;' : 'background: white; color: #333;' }}">
                            FR
                        </a>
                        <a href="{{ route('lang.switch', ['locale' => 'ar']) }}" 
                        class="lang-btn {{ app()->getLocale() === 'ar' ? 'active' : '' }}"
                        style="padding: 5px 15px; border: 1px solid #ccc; border-radius: 5px; text-decoration: none; {{ app()->getLocale() === 'ar' ? 'background: #648454; color: white;' : 'background: white; color: #333;' }}">
                            AR
                        </a>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</nav>
