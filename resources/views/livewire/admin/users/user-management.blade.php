<div>
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Admin</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['total_users'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Super Admins</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['super_admins'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Admins</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['admins'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Regular Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['regular_users'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Cards -->
    <div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center gap-3">
                <h3 class="text-lg font-semibold text-gray-900 mr-auto">All Admin</h3>

                {{-- Search --}}
                <div class="relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" wire:model.live="search" placeholder="Rechercher..."
                           class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none w-52">
                </div>

                {{-- Role filter --}}
                <select wire:model.live="roleFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3">
                    <option value="all">Tous les rôles</option>
                    @foreach($allRoles as $r)
                    <option value="{{ $r->name }}">{{ $r->label }}</option>
                    @endforeach
                </select>

                @if(Auth::user()->isSuperAdmin())
                <a href="{{ route('admin.roles.index') }}"
                   class=" px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 text-center">
                    <i class="ri-shield-user-line"></i> Gérer les Rôles
                </a>
                @endif

                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2 px-4 py-2 bg-green-logo text-white text-sm font-semibold rounded-lg transition">
                    <i class="ri-user-add-line"></i> Créer une Admin
                </a>
                @endif
            </div>

        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($users as $user)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <!-- User Avatar and Info -->
                    <div class="flex items-center mb-4 pb-4 border-b border-gray-100">
                        <div class="w-14 h-14 rounded-full bg-green-logo flex items-center justify-center text-white text-xl font-semibold mr-4">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-base font-semibold text-gray-900 truncate">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ $user->email }}</div>
                        </div>
                    </div>

                    <!-- Role Badge -->
                    <div class="mb-4">
                        @php
                            $roleModel = $allRoles->firstWhere('name', $user->role);
                            $cls = \App\Models\Role::colorClasses($roleModel?->color ?? 'gray');
                        @endphp
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $cls['badge'] }}">
                            {{ $roleModel?->label ?? ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                        @if(!($user->is_active ?? true))
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 ml-2">
                            Disabled
                        </span>
                        @endif
                    </div>

                    <!-- Join Date -->
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Joined {{ $user->created_at->format('M d, Y') }}
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <a href="{{ route('admin.users.show', $user->id) }}" 
                           class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 text-center">
                            View
                        </a>
                        @if(Auth::user()->isSuperAdmin())
                        <button wire:click="openDeleteModal({{ $user->id }})"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 text-center">
                            Delete
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <p class="text-gray-500">No users found</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ open: @entangle('showDeleteModal').live }" x-cloak>
        <div
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center"
        >
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

            {{-- Panel --}}
            <div
                x-show="open"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 z-10 overflow-hidden"
                @click.stop
            >
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Delete Admin</h3>
                    </div>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5">
                    <p class="text-gray-600 text-sm">
                        Are you sure you want to delete
                        <span class="font-semibold text-gray-900">{{ $selectedUser?->name ?? 'this user' }}</span>?
                        This action is permanent and cannot be undone.
                    </p>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <button
                        @click="open = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="deleteUser"
                        wire:loading.attr="disabled"
                        class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors disabled:opacity-60"
                    >
                        <svg wire:loading wire:target="deleteUser" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        Delete Admin
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
