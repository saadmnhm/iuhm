@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<nav class="top-navbar" @if($isArabic) dir="rtl" @endif>
        <div class="navbar-content">
            <!-- Left Side - Page Title -->
            <div class="navbar-left">
                <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
                    <i class="ri-menu-line"></i>
                </button>
                <h5 class="page-title mb-0">{{ $pageTitle ?? $tr('Tableau de bord', 'لوحة التحكم') }}</h5>
            </div>

            <!-- Right Side -->
            <div class="navbar-right">

                <div class="lang-switcher d-flex align-items-center gap-2">
                    <a href="{{ route('lang.switch', ['locale' => 'fr']) }}"
                       class="lang-chip {{ app()->getLocale()==='fr' ? 'active' : '' }}">FR</a>
                    <a href="{{ route('lang.switch', ['locale' => 'ar']) }}"
                       class="lang-chip {{ app()->getLocale()==='ar' ? 'active' : '' }}">AR</a>
                </div>

                <!-- Notifications Dropdown -->
                <div class="dropdown notification-dropdown">
                    <button class="nav-icon-btn" type="button" id="notificationDropdown"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <i class="ri-notification-3-line"></i>
                        @if($unreadCount > 0)
                        <span class="notification-badge"></span>
                        @endif
                    </button>

                    <div class="dropdown-menu dropdown-menu-end notification-menu shadow-lg border-0 rounded-3 p-0"
                        style="min-width:340px;max-width:380px;" aria-labelledby="notificationDropdown">

                        {{-- Header --}}
                        <div class="d-flex align-items-center justify-content-between px-4 py-3"
                            style="border-bottom:1px solid #f1f5f9;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold" style="font-size:.9rem;color:#1e293b;">{{ $tr('Notifications', 'الإشعارات') }}</span>
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
                                <div class="rounded-circle d-flex align-items-center justify-content-center shrink-0 {{ $notif['color'] }}"
                                    style="width:36px;height:36px;color:white;">
                                    <i class="{{ $notif['icon'] }}" style="font-size:.95rem;"></i>
                                </div>
                                <div class="grow min-width-0">
                                    <div class="fw-semibold text-truncate" style="font-size:.82rem;color:#1e293b;">{{ $notif['title'] }}</div>
                                    <div class="text-truncate" style="font-size:.75rem;color:#64748b;">{{ $notif['text'] }}</div>
                                    <div style="font-size:.68rem;color:#94a3b8;margin-top:.15rem;">{{ $notif['time'] }}</div>
                                </div>
                            </a>
                            @empty
                            <div class="text-center py-5 px-3">
                                <i class="ri-notification-off-line" style="font-size:2rem;color:#cbd5e1;"></i>
                                <p class="mt-2 mb-0 text-muted" style="font-size:.82rem;">{{ $tr('Aucune notification', 'لا توجد إشعارات') }}</p>
                            </div>
                            @endforelse
                        </div>

                        {{-- Footer --}}
                        @if(count($notifications) > 0)
                        <div class="px-4 py-2 text-center" style="border-top:1px solid #f1f5f9;">
                            <a href="{{ route('user.dashboard') }}"
                            class="text-decoration-none fw-semibold"
                            style="font-size:.78rem;color:#6366f1;">
                                <i class="ri-external-link-line me-1"></i>{{ $tr('Voir toutes les soumissions', 'عرض كل الطلبات') }}
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
                            <div class="profile-role">{{ $tr('Candidat', 'مترشح') }}</div>
                        </div>
                        <i class="ri-arrow-down-s-line d-none d-md-block"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end profile-menu shadow border-0 rounded-3"
                        aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('user.settings') }}">
                                <i class="ri-settings-3-line" style="color:#6366f1;"></i> {{ $tr('Paramètres', 'الإعدادات') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form action="{{ route('user.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                    <i class="ri-logout-box-line"></i> {{ $tr('Déconnexion', 'تسجيل الخروج') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <ul class="dropdown-menu dropdown-menu-end notification-menu" aria-labelledby="notificationDropdown">
            <li class="dropdown-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Notifications</span>
                    <a href="#" class="text-primary small">{{ $tr('Tout marquer comme lu', 'تحديد الكل كمقروء') }}</a>
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
                        <div class="notification-title">{{ $tr('Nouveau projet soumis', 'تم إرسال مشروع جديد') }}</div>
                        <div class="notification-text">{{ $tr('Votre projet a été soumis avec succès', 'تم إرسال مشروعك بنجاح') }}</div>
                        <div class="notification-time">{{ $tr('Il y a 5 minutes', 'منذ 5 دقائق') }}</div>
                    </div>
                </a>
            </li>
            <li>
                <a class="dropdown-item notification-item unread" href="#">
                    <div class="notification-icon bg-success">
                        <i class="ri-check-line"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">{{ $tr('Projet approuvé', 'تمت الموافقة على المشروع') }}</div>
                        <div class="notification-text">{{ $tr('Votre projet "Innovation 2024" a été approuvé', 'تمت الموافقة على مشروعك "Innovation 2024"') }}</div>
                        <div class="notification-time">{{ $tr('Il y a 2 heures', 'منذ ساعتين') }}</div>
                    </div>
                </a>
            </li>
            <li>
                <a class="dropdown-item notification-item" href="#">
                    <div class="notification-icon bg-warning">
                        <i class="ri-information-line"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">{{ $tr('Mise à jour requise', 'مطلوب تحديث') }}</div>
                        <div class="notification-text">{{ $tr('Veuillez mettre à jour les informations de votre profil', 'يرجى تحديث معلومات ملفك الشخصي') }}</div>
                        <div class="notification-time">{{ $tr('Il y a 1 jour', 'منذ يوم واحد') }}</div>
                    </div>
                </a>
            </li>
            
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-center text-primary" href="#">
                    {{ $tr('Voir toutes les notifications', 'عرض كل الإشعارات') }}
                </a>
            </li>
        </ul>

        
</nav>
