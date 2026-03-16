@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div class="container py-4" @if($isArabic) dir="rtl" @endif>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0"><i class="ri-settings-3-line me-2"></i>{{ $tr('Paramètres du compte', 'إعدادات الحساب') }}</h4>
                </div>
                <div class="card-body p-0">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs border-bottom px-4 pt-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button 
                                class="nav-link {{ $activeTab === 'profile' ? 'active' : '' }}" 
                                wire:click="setActiveTab('profile')"
                                type="button">
                                <i class="ri-user-line me-1"></i>{{ $tr('Informations du profil', 'معلومات الملف الشخصي') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button 
                                class="nav-link {{ $activeTab === 'password' ? 'active' : '' }}" 
                                wire:click="setActiveTab('password')"
                                type="button">
                                <i class="ri-lock-password-line me-1"></i>{{ $tr('Changer le mot de passe', 'تغيير كلمة المرور') }}
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
                                                        src="{{ route('uploads.show', ['path' => $profile_image]) }}"
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
                                            accept=".jpg,.jpeg,.png,.webp"
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
                                        <label for="nom" class="form-label">{{ $tr('Prénom', 'الاسم') }} <span class="text-danger">*</span></label>
                                        <input 
                                            type="text" 
                                            class="form-control @error('nom') is-invalid @enderror" 
                                            id="nom" 
                                            wire:model="nom"
                                            placeholder="{{ $tr('Entrez votre prénom', 'أدخل اسمك') }}">
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="prenom" class="form-label">{{ $tr('Nom', 'النسب') }} <span class="text-danger">*</span></label>
                                        <input 
                                            type="text" 
                                            class="form-control @error('prenom') is-invalid @enderror" 
                                            id="prenom" 
                                            wire:model="prenom"
                                            placeholder="{{ $tr('Entrez votre nom', 'أدخل نسبك') }}">
                                        @error('prenom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="cin" class="form-label">{{ $tr('CIN', 'رقم البطاقة الوطنية') }} <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            class="form-control @error('cin') is-invalid @enderror"
                                            id="cin"
                                            wire:model="cin"
                                            inputmode="numeric"
                                            placeholder="{{ $tr('Entrez votre CIN', 'أدخل رقم البطاقة الوطنية') }}">
                                        @error('cin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="date_naissance" class="form-label">{{ $tr('Date de naissance', 'تاريخ الازدياد') }} <span class="text-danger">*</span></label>
                                        <input
                                            type="date"
                                            class="form-control @error('date_naissance') is-invalid @enderror"
                                            id="date_naissance"
                                            wire:model="date_naissance">
                                        @error('date_naissance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="niveau_etude" class="form-label">{{ $tr('Niveau d\'étude', 'المستوى الدراسي') }} <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            class="form-control @error('niveau_etude') is-invalid @enderror"
                                            id="niveau_etude"
                                            wire:model="niveau_etude"
                                            placeholder="{{ $tr('Entrez votre niveau d\'étude', 'أدخل مستواك الدراسي') }}">
                                        @error('niveau_etude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="specialite" class="form-label">{{ $tr('Spécialité', 'التخصص') }} <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            class="form-control @error('specialite') is-invalid @enderror"
                                            id="specialite"
                                            wire:model="specialite"
                                            placeholder="{{ $tr('Entrez votre spécialité', 'أدخل تخصصك') }}">
                                        @error('specialite')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- Login & Email -->

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">{{ $tr('Adresse email', 'البريد الإلكتروني') }} <span class="text-danger">*</span></label>
                                        <input 
                                            type="email" 
                                            class="form-control @error('email') is-invalid @enderror" 
                                            id="email" 
                                            wire:model="email"
                                            placeholder="{{ $tr('Entrez votre email', 'أدخل بريدك الإلكتروني') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">{{ $tr('Téléphone', 'رقم الهاتف') }} <span class="text-danger">*</span></label>
                                        <input 
                                            type="number" 
                                            class="form-control @error('phone') is-invalid @enderror" 
                                            id="phone" 
                                            wire:model="phone"
                                            min="0"
                                            step="1"
                                            inputmode="numeric"
                                            oninput="this.value=this.value.replace(/\D/g,'')"
                                            placeholder="{{ $tr('Entrez votre numéro de téléphone', 'أدخل رقم هاتفك') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">{{ $tr('Genre', 'الجنس') }} <span class="text-danger">*</span></label>
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
                                        <label for="selected_region" class="form-label">{{ $tr('Région', 'الجهة') }} <span class="text-danger">*</span></label>
                                        <select class="form-control @error('selected_region') is-invalid @enderror" id="selected_region" wire:model.live="selected_region">
                                            <option value="">{{ $tr('Sélectionner une région', 'اختر جهة') }}</option>
                                            @foreach($regions as $region)
                                                <option value="{{ $region }}">{{ $region }}</option>
                                            @endforeach
                                        </select>
                                        @error('selected_region')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="selected_city" class="form-label">{{ $tr('Ville', 'المدينة') }} <span class="text-danger">*</span></label>
                                        <select class="form-control @error('selected_city') is-invalid @enderror" id="selected_city" wire:model.live="selected_city" {{ empty($selected_region) ? 'disabled' : '' }}>
                                            <option value="">{{ $tr('Sélectionner une ville', 'اختر مدينة') }}</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city }}">{{ $city }}</option>
                                            @endforeach
                                        </select>
                                        @error('selected_city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="selected_prefecture" class="form-label">{{ $tr('Préfecture', 'العمالة / الإقليم') }} <span class="text-danger">*</span></label>
                                        <select class="form-control @error('selected_prefecture') is-invalid @enderror" id="selected_prefecture" wire:model.live="selected_prefecture" {{ empty($selected_city) ? 'disabled' : '' }}>
                                            <option value="">{{ $tr('Sélectionner une préfecture', 'اختر عمالة / إقليم') }}</option>
                                            @foreach($prefectures as $prefecture)
                                                <option value="{{ $prefecture }}">{{ $prefecture }}</option>
                                            @endforeach
                                        </select>
                                        @error('selected_prefecture')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="address_detail" class="form-label">{{ $tr('Détails adresse (IMM, GH...)', 'تفاصيل العنوان (IMM, GH...)') }} <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            class="form-control @error('address_detail') is-invalid @enderror"
                                            id="address_detail"
                                            wire:model="address_detail"
                                            placeholder="{{ $tr('Ex : IMM 12, GH B, Appartement 4', 'مثال: IMM 12, GH B, شقة 4') }}"
                                        >
                                        @error('address_detail')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="ri-save-line me-2"></i>{{ $tr('Enregistrer les modifications', 'حفظ التغييرات') }}
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
                                            {{ $tr('Le mot de passe doit contenir au moins 6 caractères.', 'يجب أن تتكون كلمة المرور من 6 أحرف على الأقل.') }}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="current_password" class="form-label">{{ $tr('Mot de passe actuel', 'كلمة المرور الحالية') }} <span class="text-danger">*</span></label>
                                        <input 
                                            type="password" 
                                            class="form-control @error('current_password') is-invalid @enderror" 
                                            id="current_password" 
                                            wire:model="current_password"
                                            placeholder="{{ $tr('Entrez votre mot de passe actuel', 'أدخل كلمة المرور الحالية') }}">
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="new_password" class="form-label">{{ $tr('Nouveau mot de passe', 'كلمة المرور الجديدة') }} <span class="text-danger">*</span></label>
                                        <input 
                                            type="password" 
                                            class="form-control @error('new_password') is-invalid @enderror" 
                                            id="new_password" 
                                            wire:model="new_password"
                                            placeholder="{{ $tr('Entrez votre nouveau mot de passe', 'أدخل كلمة المرور الجديدة') }}">
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="new_password_confirmation" class="form-label">{{ $tr('Confirmer le nouveau mot de passe', 'تأكيد كلمة المرور الجديدة') }} <span class="text-danger">*</span></label>
                                        <input 
                                            type="password" 
                                            class="form-control" 
                                            id="new_password_confirmation" 
                                            wire:model="new_password_confirmation"
                                            placeholder="{{ $tr('Confirmez votre nouveau mot de passe', 'أكد كلمة المرور الجديدة') }}">
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="ri-lock-line me-2"></i>{{ $tr('Mettre à jour le mot de passe', 'تحديث كلمة المرور') }}
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


