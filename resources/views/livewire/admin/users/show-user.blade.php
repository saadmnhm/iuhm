<div class="max-w-4xl mx-auto">
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

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">User Details</h3>
            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>

        <div class="p-6">
            <!-- User Avatar and Name -->
            <div class="flex items-center pb-6 border-b border-gray-200 mb-6">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center text-white text-3xl font-semibold mr-6">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h4 class="text-2xl font-semibold text-gray-900 mb-1">{{ $user->name }}</h4>
                    <p class="text-gray-500">{{ $user->email }}</p>
                </div>
                <div>
                    @if($user->is_active ?? true)
                        <span class="px-4 py-2 text-sm font-medium rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                    @else
                        <span class="px-4 py-2 text-sm font-medium rounded-full bg-red-100 text-red-800">
                            Disabled
                        </span>
                    @endif
                </div>
            </div>

            <!-- User Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">User ID</span>
                        <span class="text-base text-gray-900">#{{ $user->id }}</span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Role</span>
                        <span class="px-3 py-1 text-sm font-medium rounded-full inline-block
                            {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $user->role === 'admin' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $user->role === 'user' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Email</span>
                        <span class="text-base text-gray-900">{{ $user->email }}</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Joined</span>
                        <span class="text-base text-gray-900">{{ $user->created_at->format('F d, Y \a\t g:i A') }}</span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Last Updated</span>
                        <span class="text-base text-gray-900">{{ $user->updated_at->format('F d, Y \a\t g:i A') }}</span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Account Status</span>
                        @if($user->is_active ?? true)
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800 inline-block">
                                Active & Enabled
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800 inline-block">
                                Disabled
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if(Auth::user()->isSuperAdmin())
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.edit', $user->id) }}" 
                   class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-center font-medium rounded-lg transition-colors duration-200">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit User
                    </span>
                </a>

                @if($user->id !== Auth::id())
                <button wire:click="toggleStatus" 
                        class="flex-1 px-6 py-3 {{ ($user->is_active ?? true) ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-lg transition-colors duration-200">
                    <span class="flex items-center justify-center gap-2">
                        @if($user->is_active ?? true)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            Disable Account
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Enable Account
                        @endif
                    </span>
                </button>
                @endif

                <a href="{{ route('admin.users.index') }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                    Back to List
                </a>
            </div>
            @else
            <div class="pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" 
                   class="block w-full px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 text-center font-medium rounded-lg transition-colors duration-200">
                    Back to List
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
