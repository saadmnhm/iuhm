@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<nav class="top-navbar bg-white border-b border-gray-200 sticky top-0 z-40" @if($isArabic) dir="rtl" @endif>
    <div class="navbar-content max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Left Side - Page Title -->
            <div class="navbar-left flex items-center gap-3">
                <button 
                    class="sidebar-toggle lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors"
                    id="sidebarToggle"
                    wire:click="$dispatch('toggle-sidebar')"
                    aria-label="{{ $tr('Ouvrir le menu', 'فتح القائمة') }}">
                    <i class="ri-menu-line text-xl"></i>
                </button>
                <h5 class="page-title mb-0 text-lg font-semibold text-gray-900 truncate">{{ $pageTitle ?? $tr('Tableau de bord', 'لوحة التحكم') }}</h5>
            </div>

            <!-- Right Side -->
            <div class="navbar-right flex items-center gap-3">
                <!-- Language Switcher -->
                <div class="lang-switcher flex items-center gap-1">
                    <a href="{{ route('lang.switch', ['locale' => 'fr']) }}"
                       class="lang-chip px-3 py-1.5 rounded-full text-xs font-medium transition-all {{ app()->getLocale()==='fr' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        FR
                    </a>
                    <a href="{{ route('lang.switch', ['locale' => 'ar']) }}"
                       class="lang-chip px-3 py-1.5 rounded-full text-xs font-medium transition-all {{ app()->getLocale()==='ar' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        AR
                    </a>
                </div>

                <!-- Notifications Dropdown -->
                <div class="dropdown notification-dropdown relative" x-data="{ open: false }">
                    <button 
                        class="nav-icon-btn p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors relative"
                        type="button" 
                        @click="open = !open"
                        @keydown.escape="open = false"
                        aria-expanded="false"
                        aria-label="{{ $tr('Notifications', 'الإشعارات') }}">
                        <i class="ri-notification-3-line text-xl"></i>
                        @if($unreadCount > 0)
                        <span class="notification-badge absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">{{ $unreadCount }}</span>
                        @endif
                    </button>

                    <div 
                        x-show="open"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 transform -translate-y-1"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        @click.outside="open = false"
                        class="dropdown-menu absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden z-50"
                        style="min-width: 340px; max-width: 380px;"
                        aria-label="{{ $tr('Notifications', 'الإشعارات') }}">
                        
                        {{-- Header --}}
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-sm text-gray-900">{{ $tr('Notifications', 'الإشعارات') }}</span>
                                @if($unreadCount > 0)
                                <span class="badge px-2 py-0.5 rounded-full text-xs font-medium bg-green-600 text-white">{{ $unreadCount }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Notification items --}}
                        <div class="max-h-80 overflow-y-auto">
                            @forelse($notifications as $notif)
                            <a class="dropdown-item flex items-start gap-3 px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors" href="#">
                                <div class="rounded-full flex items-center justify-content-center flex-shrink-0 {{ $notif['color'] }}" style="width: 36px; height: 36px; color: white;">
                                    <i class="{{ $notif['icon'] }} text-lg"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-sm text-gray-900 truncate">{{ $notif['title'] }}</div>
                                    <div class="text-sm text-gray-500 truncate">{{ $notif['text'] }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $notif['time'] }}</div>
                                </div>
                            </a>
                            @empty
                            <div class="text-center py-8 px-3">
                                <i class="ri-notification-off-line text-3xl text-gray-300"></i>
                                <p class="mt-2 mb-0 text-sm text-gray-400">{{ $tr('Aucune notification', 'لا توجد إشعارات') }}</p>
                            </div>
                            @endforelse
                        </div>

                        {{-- Footer --}}
                        @if(count($notifications) > 0)
                        <div class="px-4 py-2 text-center border-t border-gray-100">
                            <a href="{{ route('user.dashboard') }}" class="text-sm font-medium text-green-600 hover:text-green-700 transition-colors">
                                <i class="ri-external-link-line mr-1"></i>{{ $tr('Voir toutes les soumissions', 'عرض كل الطلبات') }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="dropdown profile-dropdown relative" x-data="{ open: false }">
                    <button 
                        class="profile-btn flex items-center gap-2 p-1 pr-3 rounded-lg hover:bg-gray-100 transition-colors"
                        type="button" 
                        @click="open = !open"
                        @keydown.escape="open = false"
                        aria-expanded="false"
                        aria-label="{{ $tr('Profil', 'الملف الشخصي') }}">
                        <div class="profile-avatar w-8 h-8 rounded-full bg-green-100 flex items-center justify-center overflow-hidden">
                            @if($profile_image)
                                <img src="{{ route('uploads.show', ['path' => $profile_image]) }}" alt="Profile" class="w-full h-full object-cover">
                            @else
                                <i class="ri-user-line text-green-600 text-xl"></i>
                            @endif
                        </div>
                        <div class="profile-info hidden md:block text-left">
                            <div class="profile-name text-sm font-medium text-gray-900">{{ Auth::guard('candidat')->user()->nom }} {{ Auth::guard('candidat')->user()->prenom }}</div>
                            <div class="profile-role text-xs text-gray-500">{{ $tr('Candidat', 'مترشح') }}</div>
                        </div>
                        <i class="ri-arrow-down-s-line hidden md:block text-gray-400"></i>
                    </button>
                    <ul 
                        x-show="open"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 transform -translate-y-1"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        @click.outside="open = false"
                        class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden z-50 py-1"
                        aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" href="{{ route('user.settings') }}">
                                <i class="ri-settings-3-line text-green-600"></i> {{ $tr('Paramètres', 'الإعدادات') }}
                            </a>
                        </li>
                        <li><hr class="my-1 border-gray-100"></li>
                        <li>
                            <form action="{{ route('user.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-50 border-0 bg-transparent text-left">
                                    <i class="ri-logout-box-line"></i> {{ $tr('Déconnexion', 'تسجيل الخروج') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>