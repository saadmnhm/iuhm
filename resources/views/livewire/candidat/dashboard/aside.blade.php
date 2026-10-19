
@if(!request()->routeIs('user.dashboard') && !request()->routeIs('user.dynamic_form'))

    <aside class="aside-admin w-64 "> 
        <div class="logo p-6 border-b border-white/10">
            <a href="{{ route('user.dashboard') }}" class="block">
                <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="IUHM Logo">
            </a>
        </div>

        <nav class="p-4 space-y-2">
            {{-- Dashboard: always visible --}}
            <a wire:navigate href="{{ route('user.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('user.dashboard') ? 'bg-[#DCFCE7] font-medium text-green-700' : 'text-gray-700' }}">
                <i class="ri-home-line mr-2"></i>
                <span>{{ $tr('Tableau de bord', 'لوحة التحكم') }}</span>
            </a>

            {{-- Mes Projets (collapsible) --}}
            @if(request()->routeIs('user.projects*') || request()->routeIs('user.projects') || request()->routeIs('user.submissions*'))
                <div x-data="{ open: true }">
                    <button @click="open = !open" class="flex w-full items-center justify-between gap-3 px-4 py-2 rounded hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-2">
                            <i class="ri-folder-open-line text-lg"></i>
                            <span>{{ $tr('Mes Projets', 'مشاريعي') }}</span>
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-3 mt-1 space-y-1">
                        <a href="{{ route('user.projects.list') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('user.projects.list') ? 'bg-[#DCFCE7] font-medium text-green-700' : '' }}">
                            <i class="ri-list-check-2 mr-2"></i> {{ $tr('Tous les projets', 'كل المشاريع') }}
                        </a>
                        <a href="{{ route('user.submissions.list') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('user.submissions.list') ? 'bg-[#DCFCE7] font-medium text-green-700' : '' }}">
                            <i class="ri-file-list-3-line mr-2"></i> {{ $tr('Mes soumissions', 'ترشيحاتي') }}
                        </a>
                        @forelse($programe_list ?? [] as $list)
                            <a href="{{ route('user.project.detail', $list->id) }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('user.project.detail') && request()->route('id') == $list->id ? 'bg-[#DCFCE7] font-medium text-green-700' : '' }}">
                                <i class="ri-arrow-right-s-line mr-2"></i> {{ $list->project_name }}
                            </a>
                        @empty
                            <p class="px-4 py-2 text-xs text-gray-400">{{ $tr('Aucun projet disponible.', 'لا توجد مشاريع متاحة.') }}</p>
                        @endforelse
                    </div>
                </div>
            @endif

            {{-- Blog & Actualités --}}
            <!-- <a wire:navigate href="{{ route('user.blog') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('user.blog*') ? 'bg-[#DCFCE7] font-medium text-green-700' : 'text-gray-700' }}">
                <i class="ri-article-line mr-2"></i>
                <span>{{ $tr('Blog & Actualités', 'المدونة والأخبار') }}</span>
            </a> -->

            {{-- Assistance Section --}}
            <a wire:navigate href="{{ route('user.support') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('user.support') ? 'bg-[#DCFCE7] font-medium text-green-700' : 'text-gray-700' }}">
                <i class="ri-customer-service-2-line mr-2"></i>
                <span>{{ $tr('Support', 'الدعم') }}</span>
            </a>

            {{-- Chat Admin --}}
            <div x-data="{ open: {{ request()->routeIs('user.chat*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="flex w-full items-center justify-between gap-3 px-4 py-2 rounded hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-2">
                        <i class="ri-chat-3-line text-lg"></i>
                        <span>{{ $tr('Chat Admin', 'دردشة الإدارة') }}</span>
                    </div>
                    @php
                        $unreadChat = \App\Models\ChatMessage::unreadForCandidat(Auth::guard('candidat')->id());
                    @endphp
                    @if($unreadChat > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $unreadChat > 99 ? '99+' : $unreadChat }}</span>
                    @endif
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="ml-3 mt-1 space-y-1">
                    <a wire:navigate href="{{ route('user.chat') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('user.chat') ? 'bg-[#DCFCE7] font-medium text-green-700' : '' }}">
                        <i class="ri-chat-3-line mr-2"></i> {{ $tr('Conversations', 'محادثات') }}
                    </a>
                    <a wire:navigate href="{{ route('user.broadcasts.history') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('user.broadcasts.history') ? 'bg-[#DCFCE7] font-medium text-green-700' : '' }}">
                        <i class="ri-broadcast-line mr-2"></i> {{ $tr('Historique messages', 'سجل الرسائل') }}
                    </a>
                </div>
            </div>

            {{-- Paramètres --}}
            @if(request()->routeIs('user.settings'))
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="flex w-full items-center justify-between gap-3 px-4 py-2 rounded hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-2">
                            <i class="ri-settings-3-line text-lg"></i>
                            <span>{{ $tr('Paramètres', 'الإعدادات') }}</span>
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-3 mt-1 space-y-1">
                        <button wire:click="$dispatch('set-active-tag', { tab: 'profile' })" type="button" class="block px-4 py-2 rounded hover:bg-gray-100 w-full text-left {{ $activeTab === 'profile' ? 'bg-[#DCFCE7] font-medium text-green-700' : '' }}">
                            <i class="ri-user-3-line mr-2"></i> {{ $tr('Votre Espace Candidat', 'فضاء المترشح') }}
                        </button>
                        <button wire:click="$dispatch('set-active-tag', { tab: 'password' })" type="button" class="block px-4 py-2 rounded hover:bg-gray-100 w-full text-left {{ $activeTab === 'password' ? 'bg-[#DCFCE7] font-medium text-green-700' : '' }}">
                            <i class="ri-shield-check-line mr-2"></i> {{ $tr('Sécurité', 'الأمان') }}
                        </button>
                    </div>
                </div>
            @endif
        </nav>
    </aside>
@endif