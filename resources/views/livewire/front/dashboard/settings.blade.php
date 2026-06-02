@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif class="p-6 font-sans">

        @if($activeTab === 'profile')
        <div>
            <h2 class="text-[26px] font-bold text-gray-900 mb-4">{{ $tr('Votre Espace Candidat', 'فضاء المترشح') }}</h2>

            <div class="flex items-center gap-3 bg-green-50 border border-green-100 rounded-xl px-4 py-3 mb-6">
                <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <i class="ri-shield-check-line text-[#066E1B]"></i>
                </div>
                <p class="text-sm text-gray-600 mb-0">{{ $tr("Gérez vos informations personnelles et académiques pour optimiser le traitement de votre dossier au sein de l'Association Initiative Urbaine.", 'أدر معلوماتك الشخصية والأكاديمية لتحسين معالجة ملفك داخل جمعية المبادرة الحضرية.') }}</p>
            </div>

            @if (session()->has('profile_success'))
                <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 mb-4 text-sm">
                    <i class="ri-check-line"></i> {{ session('profile_success') }}
                </div>
            @endif

            <form wire:submit.prevent="updateProfile">
                <div class="bg-white rounded-xl shadow-sm p-6">

                    <div class="flex flex-col items-center mb-6">
                        <div class="rounded-full border-4 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden mb-3" style="width:96px;height:96px;">
                            @if ($new_profile_image)
                                <img src="{{ $new_profile_image->temporaryUrl() }}" class="w-full h-full object-cover">
                            @elseif ($profile_image)
                                <img src="{{ route('uploads.show', ['path' => $profile_image]) }}" class="w-full h-full object-cover">
                            @else
                                <i class="ri-user-3-line text-3xl text-gray-300"></i>
                            @endif
                        </div>
                        <label for="profile-image" class="cursor-pointer text-sm text-[#066E1B] font-semibold hover:underline">
                            {{ $tr('Changer la photo', 'تغيير الصورة') }}
                        </label>
                        <input type="file" id="profile-image" wire:model="new_profile_image" accept=".jpg,.jpeg,.png,.webp" class="hidden">
                        @error('new_profile_image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-2 mb-4">
                        <i class="ri-user-3-line text-[#066E1B]"></i>
                        <h5 class="font-bold text-gray-800 mb-0">{{ $tr('Informations Personnelles', 'المعلومات الشخصية') }}</h5>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('Prénom', 'الاسم') }} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="nom" placeholder="{{ $tr('Prénom', 'الاسم') }}"
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200">
                            @error('nom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('Nom', 'النسب') }} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="prenom" placeholder="{{ $tr('Nom', 'النسب') }}"
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200">
                            @error('prenom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('CIN (ID Number)', 'رقم البطاقة الوطنية') }} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="cin" placeholder="{{ $tr('CIN', 'رقم البطاقة') }}"
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200">
                            @error('cin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('Date de naissance', 'تاريخ الازدياد') }} <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="date_naissance"
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200">
                            @error('date_naissance') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('Genre', 'الجنس') }} <span class="text-red-500">*</span></label>
                            <select wire:model="gender"
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200">
                                <option value="">{{ __('messages.selectionner') }}</option>
                                <option value="homme">{{ __('messages.homme') }}</option>
                                <option value="femme">{{ __('messages.femme') }}</option>
                            </select>
                            @error('gender') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mb-4">
                        <i class="ri-graduation-cap-line text-[#066E1B]"></i>
                        <h5 class="font-bold text-gray-800 mb-0">{{ $tr('Parcours Académique', 'المسار الأكاديمي') }}</h5>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr("Niveau d'étude", 'المستوى الدراسي') }} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="niveau_etude" placeholder="{{ $tr('Ex: Licence', 'مثال: ليسانس') }}"
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200">
                            @error('niveau_etude') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('Spécialité', 'التخصص') }} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="specialite" placeholder="{{ $tr('Ex: Économie Sociale', 'مثال: الاقتصاد الاجتماعي') }}"
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200">
                            @error('specialite') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mb-4">
                        <i class="ri-map-pin-2-line text-[#066E1B]"></i>
                        <h5 class="font-bold text-gray-800 mb-0">{{ $tr('Coordonnées', 'معلومات التواصل') }}</h5>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('Adresse email', 'البريد الإلكتروني') }} <span class="text-red-500">*</span></label>
                            <input type="email" wire:model="email" placeholder="email@example.com"
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('Téléphone', 'رقم الهاتف') }} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="phone" inputmode="numeric" oninput="this.value=this.value.replace(/\D/g,'')" placeholder="+212 6 00 00 00 00"
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200">
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('Région', 'الجهة') }} <span class="text-red-500">*</span></label>
                            <select wire:model.live="selected_region"
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200">
                                <option value="">{{ $tr('Sélectionner une région', 'اختر جهة') }}</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region }}">{{ $region }}</option>
                                @endforeach
                            </select>
                            @error('selected_region') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('Ville', 'المدينة') }} <span class="text-red-500">*</span></label>
                            <select wire:model.live="selected_city" {{ empty($selected_region) ? 'disabled' : '' }}
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200 disabled:opacity-50">
                                <option value="">{{ $tr('Sélectionner une ville', 'اختر مدينة') }}</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}">{{ $city }}</option>
                                @endforeach
                            </select>
                            @error('selected_city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('Préfecture', 'العمالة / الإقليم') }} <span class="text-red-500">*</span></label>
                            <select wire:model.live="selected_prefecture" {{ empty($selected_city) ? 'disabled' : '' }}
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200 disabled:opacity-50">
                                <option value="">{{ $tr('Sélectionner une préfecture', 'اختر عمالة / إقليم') }}</option>
                                @foreach($prefectures as $prefecture)
                                    <option value="{{ $prefecture }}">{{ $prefecture }}</option>
                                @endforeach
                            </select>
                            @error('selected_prefecture') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('Détails adresse', 'تفاصيل العنوان') }} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="address_detail" placeholder="{{ $tr('Ex : IMM 12, GH B, Appartement 4', 'مثال: IMM 12, GH B, شقة 4') }}"
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200">
                            @error('address_detail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="bg-[#066E1B] hover:bg-[#055717] text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
                            <i class="ri-save-line me-1"></i>{{ $tr('Enregistrer les modifications', 'حفظ التغييرات') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif

        @if($activeTab === 'password')
        <div>
            <h2 class="text-[26px] font-bold text-gray-900 mb-4">{{ $tr('Sécurité', 'الأمان') }}</h2>

            <div class="flex items-center gap-3 bg-green-50 border border-green-100 rounded-xl px-4 py-3 mb-6">
                <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <i class="ri-shield-check-line text-[#066E1B]"></i>
                </div>
                <p class="text-sm text-gray-600 mb-0">{{ $tr("Modifiez votre mot de passe pour sécuriser votre compte. Le mot de passe doit contenir au moins 6 caractères.", 'غيّر كلمة مرورك لتأمين حسابك. يجب أن تتكون كلمة المرور من 6 أحرف على الأقل.') }}</p>
            </div>

            @if (session()->has('password_success'))
                <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 mb-4 text-sm">
                    <i class="ri-check-line"></i> {{ session('password_success') }}
                </div>
            @endif

            <form wire:submit.prevent="updatePassword">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="ri-lock-password-line text-[#066E1B]"></i>
                        <h5 class="font-bold text-gray-800 mb-0">{{ $tr('Changer le mot de passe', 'تغيير كلمة المرور') }}</h5>
                    </div>
                    <div class="flex flex-col gap-4" style="max-width:480px;">
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('Mot de passe actuel', 'كلمة المرور الحالية') }} <span class="text-red-500">*</span></label>
                            <input type="password" wire:model="current_password" placeholder="{{ $tr('Mot de passe actuel', 'كلمة المرور الحالية') }}"
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200 @error('current_password') ring-2 ring-red-300 @enderror">
                            @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('Nouveau mot de passe', 'كلمة المرور الجديدة') }} <span class="text-red-500">*</span></label>
                            <input type="password" wire:model="new_password" placeholder="{{ $tr('Nouveau mot de passe', 'كلمة المرور الجديدة') }}"
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200 @error('new_password') ring-2 ring-red-300 @enderror">
                            @error('new_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">{{ $tr('Confirmer le nouveau mot de passe', 'تأكيد كلمة المرور الجديدة') }} <span class="text-red-500">*</span></label>
                            <input type="password" wire:model="new_password_confirmation" placeholder="{{ $tr('Confirmer le mot de passe', 'تأكيد كلمة المرور') }}"
                                class="w-full bg-gray-100 rounded-lg px-3 py-2.5 text-sm text-gray-800 border-0 focus:outline-none focus:ring-2 focus:ring-green-200">
                        </div>
                        <div class="mt-2">
                            <button type="submit"
                                class="bg-[#066E1B] hover:bg-[#055717] text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
                                <i class="ri-lock-line me-1"></i>{{ $tr('Mettre à jour le mot de passe', 'تحديث كلمة المرور') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endif

</div>