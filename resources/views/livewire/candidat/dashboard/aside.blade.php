@if(!request()->routeIs('user.dashboard') && !request()->routeIs('user.dynamic_form'))
    <aside class="aside-admin flex h-full w-full flex-col justify-between overflow-y-auto border-e border-gray-200">
        
        <div>
            {{-- Logo Section --}}
            <div class="logo p-6 border-b border-gray-100 flex items-center justify-center">
                <a href="{{ route('user.dashboard') }}" class="block focus:outline-none">
                    <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="IUHM Logo" class="h-11 w-auto object-contain">
                </a>
            </div>

            {{-- Main Navigation --}}
            <nav class="p-4 space-y-1.5 font-medium">
                
                {{-- Dashboard --}}
                <a wire:navigate 
                   href="{{ route('user.dashboard') }}" 
                   class="flex items-center gap-x-3 px-3.5 py-2.5 rounded-lg text-base transition-colors {{ request()->routeIs('user.dashboard') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="ri-dashboard-3-line text-xl"></i>
                    <span>{{ $tr('Tableau de bord', 'لوحة التحكم') }}</span>
                </a>

                {{-- Projects Accordion --}}
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open" 
                            class="flex w-full items-center justify-between gap-x-3 px-3.5 py-2.5 rounded-lg text-base text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                        <div class="flex items-center gap-x-3">
                            <i class="ri-folder-open-line text-xl"></i>
                            <span>{{ $tr('Mes Projets', 'مشاريعي') }}</span>
                        </div>
                        <i class="ri-arrow-down-s-line text-lg transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="open" x-collapse class="ps-8 pe-1 space-y-1">
                        <a wire:navigate href="{{ route('user.projects.list') }}" 
                           class="block px-3 py-2 rounded-md text-sm transition-colors {{ request()->routeIs('user.projects.list') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <i class="ri-list-check-2 me-1.5 text-base"></i> {{ $tr('Tous les projets', 'كل المشاريع') }}
                        </a>
                        
                        <a wire:navigate href="{{ route('user.submissions.list') }}" 
                           class="block px-3 py-2 rounded-md text-sm transition-colors {{ request()->routeIs('user.submissions.list') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <i class="ri-file-list-3-line me-1.5 text-base"></i> {{ $tr('Mes soumissions', 'ترشيحاتي') }}
                        </a>

                        @forelse($programe_list ?? [] as $list)
                            <a wire:navigate href="{{ route('user.project.detail', $list->id) }}" 
                               class="block px-3 py-2 rounded-md text-sm transition-colors truncate {{ request()->routeIs('user.project.detail') && request()->route('id') == $list->id ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}"
                               title="{{ $list->project_name }}">
                                <i class="ri-checkbox-blank-circle-fill text-[7px] me-2 align-middle"></i> {{ $list->project_name }}
                            </a>
                        @empty
                            <p class="px-3 py-2 text-sm text-gray-400 font-normal italic">
                                {{ $tr('Aucun projet disponible.', 'لا توجد مشاريع متاحة.') }}
                            </p>
                        @endforelse
                    </div>
                </div>

                {{-- Admin Chat Accordion --}}
                @php
                    $isChatActive = request()->routeIs('user.chat*') || request()->routeIs('user.broadcasts.*');
                    $unreadChat = $unreadChat ?? \App\Models\ChatMessage::unreadForCandidat(Auth::guard('candidat')->id());
                @endphp
                <div x-data="{ open: {{ $isChatActive ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" 
                            class="flex w-full items-center justify-between gap-x-3 px-3.5 py-2.5 rounded-lg text-base text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                        <div class="flex items-center gap-x-3">
                            <i class="ri-chat-3-line text-xl"></i>
                            <span>{{ $tr('Chat Admin', 'دردشة الإدارة') }}</span>
                        </div>
                        <div class="flex items-center gap-x-2">
                            @if($unreadChat > 0)
                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                    {{ $unreadChat > 99 ? '99+' : $unreadChat }}
                                </span>
                            @endif
                            <i class="ri-arrow-down-s-line text-lg transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </div>
                    </button>
                    
                    <div x-show="open" x-collapse class="ps-8 pe-1 space-y-1">
                        <a wire:navigate href="{{ route('user.chat') }}" 
                           class="block px-3 py-2 rounded-md text-sm transition-colors {{ request()->routeIs('user.chat') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <i class="ri-chat-1-line me-1.5 text-base"></i> {{ $tr('Conversations', 'محادثات') }}
                        </a>
                        <a wire:navigate href="{{ route('user.broadcasts.history') }}" 
                           class="block px-3 py-2 rounded-md text-sm transition-colors {{ request()->routeIs('user.broadcasts.history') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <i class="ri-broadcast-line me-1.5 text-base"></i> {{ $tr('Historique messages', 'سجل الرسائل') }}
                        </a>
                    </div>
                </div>

                {{-- Settings Accordion --}}
                @php
                    $isSettingsActive = request()->routeIs('user.settings');
                    $profileComplete = Auth::guard('candidat')->user()?->isProfileComplete() ?? true;
                    $passwordTabActive = $isSettingsActive && request('tab') === 'password';
                @endphp
                <div x-data="{ open: {{ $isSettingsActive ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" 
                            class="flex w-full items-center justify-between gap-x-3 px-3.5 py-2.5 rounded-lg text-base text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                        <div class="flex items-center gap-x-3">
                            <i class="ri-settings-3-line text-xl"></i>
                            <span>{{ $tr('Paramètres', 'الإعدادات') }}</span>
                            @if(!$profileComplete)
                                <span class="inline-block w-2 h-2 rounded-full bg-red-500" title="{{ $tr('Profil incomplet', 'الملف غير مكتمل') }}"></span>
                            @endif
                        </div>
                        <i class="ri-arrow-down-s-line text-lg transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="open" x-collapse class="ps-8 pe-1 space-y-1">
                        <a wire:navigate href="{{ route('user.settings') }}"
                           class="block px-3 py-2 rounded-md text-sm transition-colors {{ $isSettingsActive && !$passwordTabActive ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <i class="ri-user-3-line me-1.5 text-base"></i> {{ $tr('Informations personnelles', 'المعلومات الشخصية') }}
                            @if(!$profileComplete)
                                <span class="inline-block w-2 h-2 rounded-full bg-red-500 align-middle ms-1"></span>
                            @endif
                        </a>
                        <a wire:navigate href="{{ route('user.settings', ['tab' => 'password']) }}"
                           class="block px-3 py-2 rounded-md text-sm transition-colors {{ $passwordTabActive ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <i class="ri-shield-check-line me-1.5 text-base"></i> {{ $tr('Sécurité', 'الأمان') }}
                        </a>
                    </div>
                </div>

                {{-- Support --}}
                <a wire:navigate 
                   href="{{ route('user.support') }}" 
                   class="flex items-center gap-x-3 px-3.5 py-2.5 rounded-lg text-base transition-colors {{ request()->routeIs('user.support') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="ri-customer-service-2-line text-xl"></i>
                    <span>{{ $tr('Support', 'الدعم') }}</span>
                </a>

            </nav>
        </div>

        {{-- Footer Profile Area --}}
        <div class="p-4 border-t border-gray-100">
            <a wire:navigate href="{{ route('user.settings') }}" class="flex items-center gap-x-3 px-2 py-1 rounded-lg hover:bg-gray-50 transition-colors" title="{{ $tr('Mon profil', 'ملفي الشخصي') }}">
                <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm overflow-hidden flex-shrink-0">
                    @if($candidat?->profile_image)
                        <img src="{{ route('uploads.show', ['path' => $candidat->profile_image]) }}" alt="Profile" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($candidat?->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr($candidat?->nom ?? '', 0, 1)) }}
                    @endif
                </div>
                <div class="truncate text-sm">
                    <p class="font-medium text-gray-800 truncate">{{ trim(($candidat?->prenom ?? '') . ' ' . ($candidat?->nom ?? '')) ?: 'Utilisateur' }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $candidat?->email ?? '' }}</p>
                </div>
                @if(!($candidat?->isProfileComplete() ?? true))
                    <span class="ms-auto inline-block w-2 h-2 rounded-full bg-red-500 flex-shrink-0" title="{{ $tr('Profil incomplet', 'الملف غير مكتمل') }}"></span>
                @endif
            </a>
        </div>

    </aside>
@endif