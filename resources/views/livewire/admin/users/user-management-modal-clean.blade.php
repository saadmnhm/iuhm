    <!-- Create/Edit User Modal -->
    <div x-data="{ open: @entangle('showCreateModal').live }" x-cloak>
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
            <div class="absolute inset-0 bg-black/50" @click="$wire.closeModals()"></div>

            {{-- Panel --}}
            <div
                x-show="open"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-2xl shadow-xl w-170 mx-4 z-10 overflow-hidden"
                @click.stop
            >
                {{-- Header --}}
                <div class="text-center px-6 py-4">
                    <h3 class="text-[30px] font-bold text-center text-[#04103A]">{{ $isEditingUser ? 'Modifier utilisateur' : 'Nouvel utilisateur' }}</h3>
                    <p class="text-base text-gray-600 font-medium mt-4">Complétez les formulaires suivants</p>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5 space-y-4">

                    <!-- User Type Selector (only show when creating) -->
                    @if(!$isEditingUser)
                    <div>
                        <label class="block text-sm font-semibold text-[#04103A] mb-2">Type d'utilisateur *</label>
                        <select wire:model.live="userTypeCreate" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                            <option value="admin">Administrateur</option>
                            <option value="candidat">Bénéficiaire</option>
                        </select>
                    </div>


                    @endif

                    <!-- Fields for Admin -->
                    @if((!$isEditingUser && $userTypeCreate === 'admin') || ($isEditingUser && $editingUserType === 'admin'))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#04103A] mb-2">Nom</label>
                            <input type="text" wire:model="nom" placeholder="Entrez le nom" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#04103A] mb-2">Prénom</label>
                            <input type="text" wire:model="prenom" placeholder="Entrez le prénom" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                        </div>
                    </div>
                    @endif

                    <!-- Fields for Candidat -->
                    @if((!$isEditingUser && $userTypeCreate === 'candidat') || ($isEditingUser && $editingUserType === 'candidat'))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#04103A] mb-2">Nom</label>
                            <input type="text" wire:model="nom" placeholder="Entrez le nom" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#04103A] mb-2">Prénom</label>
                            <input type="text" wire:model="prenom" placeholder="Entrez le prénom" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                        </div>
                    </div>
                    @endif

                    <!-- Email Field -->
                    <div>
                        <label class="block text-sm font-semibold text-[#04103A] mb-2">E-mail *</label>
                        <input type="email" wire:model="email" placeholder="example@urbanunity.com" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                    </div>

                    <!-- Role (Admin only) -->
                    @if((!$isEditingUser && $userTypeCreate === 'admin') || ($isEditingUser && $editingUserType === 'admin'))
                    <div>
                        <label class="block text-sm font-semibold text-[#04103A] mb-2">Rôle *</label>
                        <select wire:model="role" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                            <option value="user">Utilisateur</option>
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    @endif

                    <!-- Phone (for both types) -->
                    <div>
                        <label class="block text-sm font-semibold text-[#04103A] mb-2">Téléphone *</label>
                        <input type="tel" wire:model="phone" placeholder="Entrez le numéro de téléphone" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                    </div>

                    <!-- Password Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#04103A] mb-2">Mot de passe {{ !$isEditingUser ? '*' : '(optionnel)' }}</label>
                            <input type="password" wire:model="password" placeholder="•••••••" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#04103A] mb-2">Confirmer mot de passe {{ !$isEditingUser ? '*' : '' }}</label>
                            <input type="password" wire:model="password_confirmation" placeholder="•••••••" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                        </div>
                    </div>
                    <!-- Account Status -->
                    <div class=" py-2">

                        <div class="flex items-center gap-3">
                                {{-- Switch --}}
                            <button type="button"
                                    wire:click="$toggle('is_active')"
                                    class="relative w-14 h-7 rounded-full transition-all duration-300
                                    {{ $is_active ? 'bg-[#066E1B]' : 'bg-gray-300' }}">

                                {{-- Circle --}}
                                <span class="absolute top-1 left-1 w-5 h-5 bg-white rounded-full shadow-md transform transition-all duration-300
                                    {{ $is_active ? 'translate-x-7' : '' }}">
                                </span>

                            </button>
                            
                            {{-- Label --}}
                            <span class="text-sm font-medium text-gray-600">
                                {{ $is_active ? 'Uncheck to create a disabled account' : 'Check to create a enabled account' }}
                            </span>


                        </div>
                    </div>

                {{-- Footer --}} 
                <div class="flex justify-end items-center gap-3 px-6 py-4 ">
                    <button
                        wire:click="closeModals"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 h-12.5 text-center rounded-full hover:bg-gray-200 transition-colors"
                    >
                        Annuler
                    </button>
                    <button
                        wire:click="createUser"
                        wire:loading.attr="disabled"
                        class="gap-2 px-4 py-2 h-12.5 text-center rounded-full w-37.5 text-sm font-semibold text-white bg-[#1B264F] hover:bg-[#0f1a3a] transition-colors disabled:opacity-60"
                    >
                        <svg wire:loading wire:target="createUser" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        Confirmer
                    </button>
                </div>
            </div>
        </div>
    </div>
