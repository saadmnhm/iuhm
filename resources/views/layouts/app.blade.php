<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/admin/image/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* Custom scrollbar for sidebar */
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .sidebar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Livewire loading states */
        [wire\\:loading] { opacity: 0.6; pointer-events: none; }
        
        /* Focus visible for accessibility */
        :focus-visible {
            outline: 2px solid #648454;
            outline-offset: 2px;
        }
    </style>
</head>
<body class="min-h-screen bg-[#FBF8FD] ">
@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
    $programe_list = $programe_list ?? [];
    $isSettingsPage = $isSettingsPage ?? false;
    $activeTab = $activeTab ?? 'profile';
    $candidat = Auth::guard('candidat')->user();
    $profile_image = $candidat?->profile_image;
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp


<div class="flex min-h-screen">
    {{-- Sidebar --}}

   @include('livewire.candidat.dashboard.aside')

    

    {{-- Main Content Area --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Header --}}
        <header class="bg-[#F1F5F9]  px-8 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold text-gray-900">{{ $pageTitle ?? 'Dashboard' }}</h1>
                
                <div class="flex items-center gap-4">
                    {{-- Language Switcher --}}
                    <div class="flex items-center gap-2">
                        <a href="{{ route('lang.switch', ['locale' => 'fr']) }}"
                           class="px-3 py-1.5 rounded-full text-xs font-medium transition-all {{ app()->getLocale()==='fr' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            FR
                        </a>
                        <a href="{{ route('lang.switch', ['locale' => 'ar']) }}"
                           class="px-3 py-1.5 rounded-full text-xs font-medium transition-all {{ app()->getLocale()==='ar' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            AR
                        </a>
                    </div>

                    {{-- Profile Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if($profile_image)
                                    <img src="{{ route('uploads.show', ['path' => $profile_image]) }}" alt="Profile" class="w-full h-full object-cover">
                                @else
                                    <i class="ri-user-line text-green-600 text-xl"></i>
                                @endif
                            </div>
                            <div class="hidden sm:block text-left">
                                <div class="text-sm font-medium text-gray-900">{{ $candidat?->nom }} {{ $candidat?->prenom }}</div>
                                <div class="text-xs text-gray-500">Candidat</div>
                            </div>
                        </button>

                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                            <a href="{{ route('user.settings') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="ri-settings-3-line text-green-600"></i> {{ $tr('Paramètres', 'الإعدادات') }}
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form action="{{ route('user.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 text-left">
                                    <i class="ri-logout-box-line"></i> {{ $tr('Déconnexion', 'تسجيل الخروج') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-6 sm:p-8">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts

<script>
    // Handle Livewire token mismatch and keep active sessions alive
    document.addEventListener('livewire:init', () => {
        let handlingSessionExpired = false;
        const keepAliveUrl = `{{ route('user.keep-alive') }}`;

        const pingSession = () => {
            fetch(keepAliveUrl, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                cache: 'no-store',
            }).catch(() => {});
        };

        pingSession();
        setInterval(pingSession, 240000);

        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                pingSession();
            }
        });

        Livewire.hook('request', ({ fail }) => {
            fail(({ status, preventDefault }) => {
                if (status === 419) {
                    preventDefault();
                    if (handlingSessionExpired) {
                        return;
                    }
                    handlingSessionExpired = true;

                    const toast = document.createElement('div');
                    toast.className = 'fixed top-4 left-1/2 -translate-x-1/2 bg-amber-600 text-white px-6 py-3 rounded-lg shadow-lg font-medium text-sm z-50 transition-opacity duration-300';
                    toast.textContent = 'Session expirée. Redirection vers la connexion…';
                    document.body.appendChild(toast);

                    const redirectTo = `{{ route('user.login') }}?expired=1&_t=${Date.now()}`;
                    setTimeout(() => window.location.replace(redirectTo), 1200);
                }
            });
        });
    });
</script>
</body>
</html>