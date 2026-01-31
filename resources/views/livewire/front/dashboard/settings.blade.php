<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0"><i class="ri-settings-3-line me-2"></i>Account Settings</h4>
                </div>
                <div class="card-body p-0">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs border-bottom px-4 pt-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button 
                                class="nav-link {{ $activeTab === 'profile' ? 'active' : '' }}" 
                                wire:click="setActiveTab('profile')"
                                type="button">
                                <i class="ri-user-line me-1"></i> Profile Information
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button 
                                class="nav-link {{ $activeTab === 'password' ? 'active' : '' }}" 
                                wire:click="setActiveTab('password')"
                                type="button">
                                <i class="ri-lock-password-line me-1"></i> Change Password
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content p-4">
                        <!-- Profile Information Tab -->
                        @if($activeTab === 'profile')
                        <div class="tab-pane fade show active">
                            @if (session()->has('profile_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="ri-check-line me-2"></i>{{ session('profile_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form wire:submit.prevent="updateProfile">
                                <div class="row g-3">
                                    <div class="field-image text-center mb-6">
                                        <label for="profile-image" class="block mb-2 font-semibold">
                                            {{ __('messages.Photo_profil') }}
                                        </label>

                                        <div class="mb-3">
                                            <div
                                                class="mx-auto rounded-full border-4 border-dashed border-gray-300 bg-gray-100 flex items-center justify-center overflow-hidden"
                                                style="width: 200px; height: 200px;"
                                            >
                                                @if ($new_profile_image)
                                                    <img
                                                        src="{{ $new_profile_image->temporaryUrl() }}"
                                                        alt="Photo de profil"
                                                        class="w-full h-full object-cover"
                                                    >
                                                @elseif ($profile_image)
                                                    <img
                                                        src="{{ asset('uploads/' . $profile_image) }}"
                                                        alt="Photo de profil"
                                                        class="w-full h-full object-cover"
                                                    >
                                                @else
                                                    <span class="text-gray-400">{{ __('messages.aucune_image') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <input
                                            type="file"
                                            id="profile-image"
                                            wire:model="new_profile_image"
                                            accept="image/*"
                                            class="form-control"
                                        >

                                        @error('new_profile_image')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror

                                        <div wire:loading wire:target="new_profile_image" class="text-blue-500 text-sm mt-2">
                                            {{ __('messages.telechargement_cours') }}
                                        </div>
                                    </div>
                                    <!-- Name Fields -->
                                    <div class="col-md-6">
                                        <label for="nom" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input 
                                            type="text" 
                                            class="form-control @error('nom') is-invalid @enderror" 
                                            id="nom" 
                                            wire:model="nom"
                                            placeholder="Enter your first name">
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="prenom" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input 
                                            type="text" 
                                            class="form-control @error('prenom') is-invalid @enderror" 
                                            id="prenom" 
                                            wire:model="prenom"
                                            placeholder="Enter your last name">
                                        @error('prenom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                            <label for="age" class="block disc mb-2 font-semibold">{{ __('messages.age') }}</label>
                                            <input type="number" class="form-control border border-gray-300 rounded p-2 w-full" id="age" name="age" wire:model="age" min="18" max="100" placeholder="Ex: 25">
                                            @error('age') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <!-- Login & Email -->

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input 
                                            type="email" 
                                            class="form-control @error('email') is-invalid @enderror" 
                                            id="email" 
                                            wire:model="email"
                                            placeholder="Enter your email">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input 
                                            type="text" 
                                            class="form-control @error('phone') is-invalid @enderror" 
                                            id="phone" 
                                            wire:model="phone"
                                            placeholder="Enter your phone number">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-control border border-gray-300 rounded p-2 w-full" id="gender" name="gender" wire:model="gender">
                                            <option value="">{{ __('messages.selectionner') }}</option>
                                            <option value="homme">{{ __('messages.homme') }}</option>
                                            <option value="femme">{{ __('messages.femme') }}</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="address" class="block disc mb-2 font-semibold">{{ __('messages.adresse') }}</label>
                                        <select class="form-control border border-gray-300 rounded p-2 w-full" id="address" name="address" wire:model="address">
                                            <option value="">{{ __('messages.selectionner_adresse') }}</option>
                                            <option value="Hay Mohamadi">{{ __('messages.hay_mohamadi') }}</option>
                                            <option value="Ain Sbaa">{{ __('messages.ain_sbaa') }}</option>
                                            <option value="Roches Noires">{{ __('messages.rochnoir') }}</option>
                                        </select>
                                        @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="ri-save-line me-2"></i>Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif

                        <!-- Change Password Tab -->
                        @if($activeTab === 'password')
                        <div class="tab-pane fade show active">
                            @if (session()->has('password_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="ri-check-line me-2"></i>{{ session('password_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form wire:submit.prevent="updatePassword">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="ri-information-line me-2"></i>
                                            Password must be at least 6 characters long.
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                                        <input 
                                            type="password" 
                                            class="form-control @error('current_password') is-invalid @enderror" 
                                            id="current_password" 
                                            wire:model="current_password"
                                            placeholder="Enter your current password">
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                                        <input 
                                            type="password" 
                                            class="form-control @error('new_password') is-invalid @enderror" 
                                            id="new_password" 
                                            wire:model="new_password"
                                            placeholder="Enter your new password">
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="new_password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                        <input 
                                            type="password" 
                                            class="form-control" 
                                            id="new_password_confirmation" 
                                            wire:model="new_password_confirmation"
                                            placeholder="Confirm your new password">
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="ri-lock-line me-2"></i>Update Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


