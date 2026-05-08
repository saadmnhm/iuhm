@if(!request()->routeIs('admin.dashboard'))

    <aside class="aside-admin w-64 ">
        <div class="logo p-6 border-b border-white/10">
            <img src="{{asset('assets/admin/image/iuhm_logo.png')}}" alt="">
        </div>

        <nav class="p-4 space-y-2 ">
            {{-- Dashboard: always visible to all admins --}}
            <a wire:navigate href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'bg-[#DCFCE7]' : '' }}">
                <i class="ri-home-line mr-1"></i>
                <span>Dashboard</span>
            </a>
            @if(request()->is('admin/console/*'))
                    @canmodule('users')
                    <a wire:navigate href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.users*') ? 'bg-[#DCFCE7] font-medium' : '' }}"><i class="ri-admin-line mr-1"></i>Gestion Admin</a>
                    @endcanmodule
                    @canmodule('formulaires')
                    <a wire:navigate href="{{ route('admin.formulaires.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.formulaires*') ? 'bg-[#DCFCE7] font-medium' : '' }}">
                        <i class="ri-file-list-3-line mr-1"></i> Formulaires
                    </a>
                    @endcanmodule
                    @canmodule('addresses')
                    <a wire:navigate href="{{ route('admin.addresses.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.addresses*') ? 'bg-[#DCFCE7] font-medium' : '' }}"><i class="ri-map-pin-line mr-1"></i>Addresses</a>
                    @endcanmodule
                    @canmodule('gestion_roles')
                    <a wire:navigate href="{{ route('admin.roles.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.roles*') ? 'bg-[#DCFCE7] font-medium' : '' }}">
                        <i class="ri-shield-user-line mr-1"></i> Gestion des Rôles
                    </a>
                    @endcanmodule
            @endif
                @if(request()->is('admin/media/*') || request()->is('admin/media') )

                    @canmodule('media')
                    <a wire:navigate href="{{ route('admin.media.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.media*') ? 'bg-[#DCFCE7]' : '' }}">
                        <i class="ri-image-2-line mr-1"></i> News & Media   
                    </a>                    
                    <a wire:navigate href="{{ route('admin.blog.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.blog*') ? 'bg-[#DCFCE7]' : '' }}">
                        <i class="ri-article-line mr-1"></i> Blog
                    </a>
                    <a wire:navigate href="{{ route('admin.news.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.news*') ? 'bg-[#DCFCE7]' : '' }}">
                        <i class="ri-newspaper-line mr-1"></i> Actualités
                    </a>
                    <a wire:navigate href="{{ route('admin.deliverables.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.deliverables*') ? 'bg-[#DCFCE7]' : '' }}">
                        <i class="ri-file-download-line mr-1"></i> Livrables
                    </a>
                    <a wire:navigate href="{{ route('admin.newsletters.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.newsletters*') ? 'bg-[#DCFCE7]' : '' }}">
                        <i class="ri-mail-line mr-1"></i> Newsletters
                    </a>

                    @endcanmodule
                @endif
<!-- 
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex w-full items-center justify-between gap-3 px-4 py-3 rounded-lg transition hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                        <i class="ri-folder-open-line text-lg"></i>
                        <span>Projets</span>
                    </div>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div x-show="open" x-collapse class="ml-3 mt-1 space-y-1">

                    {{-- Programme submissions links --}}
                    @canmodule('programmes')
                    @foreach($programe_list ?? [] as $list)
                        <a href="{{ route('admin.project.submissions', $list->id) }}" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.project.submissions') && request()->route('id') == $list->id ? 'bg-white/20' : 'hover:bg-white/10' }}">
                            <i class="{{ $list->icon }} text-lg"></i>
                            <span>{{ $list->project_name }}</span>
                        </a>
                    @endforeach
                    @endcanmodule

                </div>
            </div>

            @canmodule('support')
            <a wire:navigate href="{{ route('admin.support.tickets') }}" 
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.support*') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Support</span>
            </a>
            @endcanmodule



            @canmodule('my_submissions')
            <a wire:navigate href="{{ route('admin.my.submissions') }}" 
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.my.submissions') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                <i class="ri-task-line text-lg"></i>
                <span>Mes Assignations</span>
            </a>
            @endcanmodule

            @canmodule('all_submissions')
            <a wire:navigate href="{{ route('admin.all.submissions') }}" 
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.all.submissions') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                <i class="ri-file-list-3-line text-lg"></i>
                <span>Toutes Soumissions</span>
            </a>
            @endcanmodule

            @canmodule('history_audit')
            <a wire:navigate href="{{ route('admin.history.audit') }}" 
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.history.audit') ? 'bg-white/20' : 'hover:bg-white/10' }}">
                <i class="ri-history-line text-lg"></i>
                <span>Historique & Audit</span>
            </a>
            @endcanmodule
            @canmodule('chat')
            <div x-data="{ open: {{ request()->routeIs('admin.chat*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="flex w-full items-center justify-between gap-3 px-4 py-3 rounded-lg transition hover:bg-white/10">
                    <div class="flex items-center gap-3">
                        <i class="ri-chat-3-line text-lg"></i>
                        <span>Chat & Broadcast</span>
                        @php $totalUnread = \App\Models\ChatMessage::totalUnreadForAdmin(); @endphp
                        @if($totalUnread > 0)
                            <span class="bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $totalUnread > 99 ? '99+' : $totalUnread }}</span>
                        @endif
                    </div>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="ml-3 mt-1 space-y-1">
                    <a wire:navigate href="{{ route('admin.chat.list') }}" class="block px-4 py-2 rounded hover:bg-white/10 {{ request()->routeIs('admin.chat.list') || (request()->routeIs('admin.chat.conversation')) ? 'bg-white/20 font-medium' : '' }}">
                        <i class="ri-chat-3-line mr-1"></i> Conversations
                    </a>
                    <a wire:navigate href="{{ route('admin.chat.broadcast') }}" class="block px-4 py-2 rounded hover:bg-white/10 {{ request()->routeIs('admin.chat.broadcast') ? 'bg-white/20 font-medium' : '' }}">
                        <i class="ri-broadcast-line mr-1"></i> Broadcast
                    </a>
                </div>
            </div>
            @endcanmodule


            @canmodule('association')
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex w-full items-center justify-between gap-3 px-4 py-3 rounded-lg transition hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                        <i class="ri-building-2-line text-lg"></i>
                        <span>Association</span>
                    </div>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div x-show="open" x-collapse class="ml-3 mt-1 space-y-1">

                    @canmodule('rh')
                    <a wire:navigate href="{{ route('admin.rh.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.rh*') ? 'bg-gray-100 font-medium' : '' }}">
                        <i class="ri-team-line mr-1"></i> Gestion RH
                    </a>
                    @endcanmodule

                    @canmodule('finance')
                    <a wire:navigate href="{{ route('admin.finance.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.finance*') ? 'bg-gray-100 font-medium' : '' }}">
                        <i class="ri-money-dollar-circle-line mr-1"></i> Finance
                    </a>
                    @endcanmodule

                    @canmodule('material')
                    <a wire:navigate href="{{ route('admin.material.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.material*') ? 'bg-gray-100 font-medium' : '' }}">
                        <i class="ri-archive-line mr-1"></i> Matériel
                    </a>
                    @endcanmodule

                    @canmodule('association_parameters')
                    <a wire:navigate href="{{ route('admin.association.parameters') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.association*') ? 'bg-gray-100 font-medium' : '' }}">
                        <i class="ri-settings-3-line mr-1"></i> Paramètres Association
                    </a>
                    @endcanmodule


                </div>
            </div>
            @endcanmodule
            
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex w-full items-center justify-between gap-3 px-4 py-3 rounded-lg transition hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3" />
                        </svg>
                        <span>Referential</span>
                    </div>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="ml-3 mt-1 space-y-1">

                    @canmodule('programe')
                    <a wire:navigate href="{{ route('admin.programe') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.programe*') ? 'bg-gray-100 font-medium' : '' }}"><i class="ri-trello-fill mr-1"></i>Projet</a>
                    @endcanmodule

                    @canmodule('users')
                    <a wire:navigate href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.users*') ? 'bg-gray-100 font-medium' : '' }}"><i class="ri-admin-line mr-1"></i>Gestion Admin</a>
                    @endcanmodule

                    
                    @canmodule('addresses')
                    <a wire:navigate href="{{ route('admin.addresses.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.addresses*') ? 'bg-gray-100 font-medium' : '' }}"><i class="ri-map-pin-line mr-1"></i>Addresses</a>
                    @endcanmodule

                    @canmodule('formulaires')
                    <a wire:navigate href="{{ route('admin.formulaires.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.formulaires*') ? 'bg-gray-100 font-medium' : '' }}">
                        <i class="ri-file-list-3-line mr-1"></i> Formulaires
                    </a>
                    @endcanmodule

                    @canmodule('activity_logs')
                    <a wire:navigate href="{{ route('admin.activity.logs') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.activity*') ? 'bg-gray-100 font-medium' : '' }}">
                        <i class="ri-history-line mr-1"></i> Activity Logs
                    </a>
                    @endcanmodule


                    @canmodule('gestion_roles')
                    <a wire:navigate href="{{ route('admin.roles.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.roles*') ? 'bg-gray-100 font-medium' : '' }}">
                        <i class="ri-shield-user-line mr-1"></i> Gestion des Rôles
                    </a>
                    @endcanmodule



                    @canmodule('dev_tools')
                    <a wire:navigate href="{{ route('admin.dev.tools') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.dev*') ? 'bg-gray-100 font-medium' : '' }}">
                        <i class="ri-code-s-slash-line mr-1"></i> Dev Tools
                    </a>
                    @endcanmodule

                </div>
            </div> -->

        </nav>
    </aside>
@endif

<!-- Main Content -->
<div class="flex-1 flex flex-col">
    <!-- Header -->
    <header class="bg-[#F1F5F9]  px-8 py-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-[#04103A]">{{ $header ?? 'Dashboard' }}</h1>


            <div  class="flex justify-between items-center">
                <!-- <div class="aside-menu ">
                    <i class="ri-menu-line"></i>
                </div>

                <div class="notif">
                    <i class="ri-notification-4-line"></i>
                </div> -->
                
                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                        <div class="w-10 h-10 rounded-full bg-green-logo  flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
                            style="display: none;">
                        
                        <a wire:navigate href="{{ route('admin.users.show', Auth::id()) }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            My Profile
                        </a>

                        

                        <div class="border-t border-gray-200 my-2"></div>

                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition w-full text-left">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="flex-1 p-8">
        {{ $slot }}
    </main>
</div>