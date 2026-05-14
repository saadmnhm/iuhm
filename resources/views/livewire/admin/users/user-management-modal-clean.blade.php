    <!-- Create/Edit User Modal -->
    <div
        x-data="{
            open: false,
            mode: 'create',
            form: {
                id: null,
                userType: 'admin',
                nom: '',
                prenom: '',
                email: '',
                phone: '',
                role: 'admin',
                password: '',
                password_confirmation: '',
                is_active: true,
            },
            defaults() {
                return {
                    id: null,
                    userType: 'admin',
                    nom: '',
                    prenom: '',
                    email: '',
                    phone: '',
                    role: 'admin',
                    password: '',
                    password_confirmation: '',
                    is_active: true,
                };
            },
            openModal(payload) {
                this.mode = payload.mode || 'create';
                this.form = { ...this.defaults(), ...payload };

                if (this.mode === 'create') {
                    this.form.id = null;
                    this.form.role = 'admin';
                    this.form.is_active = true;
                    this.form.password = '';
                    this.form.password_confirmation = '';
                }

                this.open = true;
            },
            closeModal() {
                this.open = false;
                this.form = this.defaults();
                this.mode = 'create';
            },
        }"
        @user-modal-open.window="openModal($event.detail)"
        @user-modal-close.window="closeModal()"
        x-cloak
        wire:ignore.self
    >
        <template x-if="open">
            <div
                class="fixed inset-0 z-50 overflow-y-auto"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                <div class="absolute inset-0 bg-black/50" @click="closeModal()"></div>

                <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg sm:max-w-2xl z-10 overflow-y-auto max-h-[90vh]"
                    @click.stop
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                >
                    <input type="hidden" x-model="form.id" wire:model.defer="editId">
                    <input type="hidden" x-model="form.userType" wire:model.defer="editingUserType">
                    <input type="hidden" x-model="form.userType" wire:model.defer="userTypeCreate">

                    <div class="text-center px-4 sm:px-6 py-4">
                        <h3 class="text-xl sm:text-[30px] font-bold text-center text-[#04103A]" x-text="mode === 'edit' ? 'Modifier utilisateur' : 'Nouvel utilisateur'"></h3>
                        <p class="text-base text-gray-600 font-medium mt-4">Complétez les formulaires suivants</p>
                    </div>

                    <div class="px-4 sm:px-6 py-4 sm:py-5 space-y-4">
                        <div x-show="mode === 'create'" x-cloak>
                            <label class="block text-sm font-semibold text-[#04103A] mb-2">Type d'utilisateur *</label>
                            <select x-model="form.userType" wire:model.defer="userTypeCreate" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                                <option value="admin">Administrateur</option>
                                <option value="candidat">Bénéficiaire</option>
                            </select>
                            @error('userTypeCreate')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-show="form.userType === 'admin'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-[#04103A] mb-2">Nom</label>
                                <input type="text" x-model="form.nom" wire:model.defer="nom" placeholder="Entrez le nom" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                                @error('nom')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#04103A] mb-2">Prénom</label>
                                <input type="text" x-model="form.prenom" wire:model.defer="prenom" placeholder="Entrez le prénom" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                                @error('prenom')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div x-show="form.userType === 'candidat'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-[#04103A] mb-2">Nom</label>
                                <input type="text" x-model="form.nom" wire:model.defer="nom" placeholder="Entrez le nom" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                                @error('nom')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#04103A] mb-2">Prénom</label>
                                <input type="text" x-model="form.prenom" wire:model.defer="prenom" placeholder="Entrez le prénom" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                                @error('prenom')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#04103A] mb-2">E-mail *</label>
                            <input type="email" x-model="form.email" wire:model.defer="email" placeholder="example@urbanunity.com" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-show="form.userType === 'admin'" x-cloak>
                            <label class="block text-sm font-semibold text-[#04103A] mb-2">Rôle *</label>
                            <select x-model="form.role" wire:model.defer="role" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                                <option value="" disabled selected>Sélectionnez un rôle</option>
                                @foreach($allRoles as $roleItem)
                                    <option value="{{ $roleItem['name'] }}">{{ $roleItem['label'] }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#04103A] mb-2">Téléphone *</label>
                            <input type="tel" x-model="form.phone" wire:model.defer="phone" placeholder="Entrez le numéro de téléphone" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-[#04103A] mb-2">Mot de passe <span x-text="mode === 'create' ? '*' : '(optionnel)'"></span></label>
                                <input type="password" x-model="form.password" wire:model.defer="password" placeholder="•••••••" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                                @error('password')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#04103A] mb-2">Confirmer mot de passe <span x-text="mode === 'create' ? '*' : ''"></span></label>
                                <input type="password" x-model="form.password_confirmation" wire:model.defer="password_confirmation" placeholder="•••••••" class="w-full font-semibold h-13 text-[#76767F] bg-[#E4E1E6] rounded-lg text-sm py-2 px-4 focus:outline-none">
                                @error('password_confirmation')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="py-2">
                            <div class="flex items-center gap-3">
                                <button type="button"
                                        @click="form.is_active = !form.is_active"
                                        wire:click="$toggle('is_active')"
                                        class="relative w-14 h-7 rounded-full transition-all duration-300"
                                        :class="form.is_active ? 'bg-[#066E1B]' : 'bg-gray-300'">
                                    <span class="absolute top-1 left-1 w-5 h-5 bg-white rounded-full shadow-md transform transition-all duration-300"
                                        :class="form.is_active ? 'translate-x-7' : ''">
                                    </span>
                                </button>

                                <span class="text-sm font-medium text-gray-600" x-text="form.is_active ? 'Uncheck to create a disabled account' : 'Check to create a enabled account'"></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end items-center gap-3 px-4 sm:px-6 py-4">
                        <button type="button"
                            @click="closeModal()"
                            class="px-4 py-2 text-sm font-semibold text-gray-700 h-12.5 text-center rounded-full hover:bg-gray-200 transition-colors"
                        >
                            Annuler
                        </button>
                        <button type="button"
                            @click="$wire.submitUser(form)"
                            wire:loading.attr="disabled"
                            class="gap-2 px-4 py-2 h-12.5 text-center rounded-full w-37.5 text-sm font-semibold text-white bg-[#1B264F] hover:bg-[#0f1a3a] transition-colors disabled:opacity-60"
                        >
                            <svg wire:loading wire:target="submitUser" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                            Confirmer
                        </button>
                    </div>
                </div>
                </div>
            </div>
        </template>
    </div>
