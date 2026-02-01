<div class="max-w-2xl mx-auto">
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Edit User</h3>
        </div>

        <form wire:submit.prevent="updateUser" class="p-6">
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="name" type="text" id="name" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="email" type="email" id="email" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-gray-500 text-xs">(leave empty to keep current)</span>
                    </label>
                    <input wire:model="password" type="password" id="password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="role" id="role" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                    @error('role') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" wire:model="is_active" 
                               class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="text-sm font-medium text-gray-700">Account is Active</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-2 ml-8">Uncheck to disable this user's account</p>
                    @error('is_active') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                    Update User
                </button>
                <a href="{{ route('admin.users.show', $userId) }}" 
                   class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 text-center font-medium rounded-lg transition-colors duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
