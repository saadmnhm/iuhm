@php
        $isArabic = str_starts_with(app()->getLocale(), 'ar');
        $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;

        $profileChecks = [
            !empty($candidat->nom),
            !empty($candidat->prenom),
            !empty($candidat->email),
            !empty($candidat->phone),
            !empty($candidat->address),
            !empty($candidat->date_naissance),
            !empty($candidat->gender),
        ];
        $profileCompleted = collect($profileChecks)->filter()->count();
        $profileTotal = count($profileChecks);
        $profileProgress = $profileTotal > 0 ? round(($profileCompleted / $profileTotal) * 100) : 0;
@endphp

<div style="{{ $isArabic ? 'direction:rtl;' : '' }}">
<div class="p-4 sm:p-6 lg:p-8 font-sans">

    {{-- HERO BANNER --}}
    <div class="bg-gradient-to-r from-[#0f2441] to-[#15304a] text-white p-6 rounded-2xl shadow-lg flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold">
                {{ $tr('Bienvenue,', 'مرحباً،') }} {{ $candidat->prenom }} {{ $candidat->nom }}
            </h1>
            <p class="text-lg opacity-90 text-gray-200 mt-1">{{ $tr('Association Initiative Urbaine - ERP', 'جمعية المبادرة الحضرية - ERP') }}</p>
        </div>
        <div class="w-full sm:w-40 h-20 rounded-lg bg-gradient-to-br from-[#12345a] to-[#0b2340] opacity-95"></div>
    </div>

    {{-- APPS POUR VOUS --}}
    <h3 class="text-lg font-semibold text-gray-900 mt-10 mb-4">{{ $tr('Apps pour vous', 'التطبيقات لك') }}</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    @foreach ($menu as $item )
    
    <div class="bg-[#F5F3F7] rounded-xl p-6 min-h-[268px] shadow-sm flex flex-col">
        <div class="text-2xl mb-3 h-12 w-12 flex items-center justify-center text-[#0f2441] rounded-lg bg-white">
            <i class="{{ $item['icon'] }}"></i>
        </div>
        <h3 class="text-xl font-bold text-[#04103A]">{{ $item['label'] }}</h3>
        <small class="block mt-3 mb-3 text-sm font-normal tracking-widest text-gray-400 uppercase">
            {{ $item['description'] }}
        </small>
        <a href="{{ $item['route'] }}" class="mt-auto inline-block text-green-700 font-bold hover:scale-105 transition-transform no-underline">
            {{ $tr('Accéder', 'الوصول') }}<i class="ri-arrow-right-long-line relative top-[1px] ml-[2px]"></i>
        </a>
    </div>
    @endforeach

    </div>

    {{-- BOTTOM ROW: Activity + Support --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-8">

        {{-- Recent Activity --}}
        <div class="lg:col-span-2 bg-[#F5F3F7] p-4 rounded-xl shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900">{{ $tr('Activité Récente', 'النشاط الأخير') }}</h4>
                <a href="{{ route('user.projects.list') }}" class="text-green-600 hover:text-green-700 font-medium text-sm">{{ $tr('Voir tout', 'عرض الكل') }}</a>
            </div>
            <div class="space-y-3">
                @forelse(array_slice($filteredSubmissions, 0, 5) as $sub)
                    <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-gray-100">
                        <div>
                            <div class="font-semibold text-gray-900">{{ $sub['form_title'] }}</div>
                            <div class="text-sm text-gray-500">{{ $sub['programe_name'] ?: $tr('Formulaire général', 'استمارة عامة') }}</div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full border" style="background:{{ $sub['status_badge_color'] ?? '#6b7280' }}22; color:{{ $sub['status_badge_color'] ?? '#6b7280' }}; border-color:{{ $sub['status_badge_color'] ?? '#6b7280' }}44;">
                                {{ $sub['status_label'] ?? $sub['status'] }}
                            </span>
                            <div class="text-sm text-gray-400">{{ $sub['updated_at'] ?? '-' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-gray-100">
                        <div class="text-sm text-gray-400">{{ $tr('Aucune activité récente.', 'لا يوجد نشاط حديث.') }}</div>
                    </div>
                @endforelse
                <div class="text-sm text-gray-400">{{ $tr("Plus d'éléments d'activité ici...", 'لا توجد عناصر أخرى...') }}</div>
            </div>
        </div>

        {{-- Support --}}
        <div class="bg-[#EAE7EB] flex flex-col justify-center h-48 p-5 rounded-xl">
            <h4 class="text-base font-bold text-[#04103A]">{{ $tr('Support Technique', 'الدعم التقني') }}</h4>
            <p class="text-sm text-gray-500 mt-2 font-medium">{{ $tr("Besoin d'aide avec l'ERP? Contactez l'IT.", 'هل تحتاج مساعدة في ERP؟ تواصل مع الفريق.') }}</p>
            <a href="{{ route('user.support') }}"
               class="mt-4 inline-block w-full border-2 border-[#C6C5D0] text-[#04103A] font-bold text-center hover:bg-[#04103A] hover:text-white transition-colors px-4 py-2 rounded-full">
                {{ $tr('Ouvrir un Ticket', 'فتح تذكرة') }}
            </a>
        </div>

    </div>

</div>

    {{-- Profile complete modal --}}
    @if($showCompleteProfileModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" role="dialog" aria-modal="true" aria-labelledby="profile-modal-title-dashboard">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h5 id="profile-modal-title-dashboard" class="text-lg font-bold flex items-center gap-2">
                    <i class="ri-information-line text-amber-500"></i>{{ $tr('Complétez votre profil', 'أكمل ملفك الشخصي') }}
                </h5>
            </div>
            <div class="p-6">
                <div class="text-center mb-6">
                    <i class="ri-user-settings-line text-6xl text-green-600"></i>
                    <h5 class="mt-3 mb-2 text-lg font-semibold">{{ $tr('Votre profil est incomplet', 'ملفك الشخصي غير مكتمل') }}</h5>
                    <p class="text-sm text-gray-500 mb-0">
                        {{ $tr('Veuillez compléter vos informations pour accéder à toutes les fonctionnalités.', 'يرجى إكمال معلوماتك للوصول إلى جميع الميزات.') }}
                    </p>
                </div>
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
                    <i class="ri-lightbulb-line text-blue-500 mt-1"></i>
                    <div>
                        <strong>{{ $tr('Pourquoi compléter votre profil ?', 'لماذا يجب إكمال ملفك الشخصي؟') }}</strong>
                        <ul class="mb-0 mt-2 pl-4 space-y-1 text-sm">
                            <li>{{ $tr("Améliorer la précision des critères d'éligibilité", 'تحسين دقة معايير الأهلية') }}</li>
                            <li>{{ $tr('Recevoir des notifications personnalisées', 'تلقي إشعارات مخصصة') }}</li>
                            <li>{{ $tr('Accélérer le traitement de vos demandes', 'تسريع معالجة طلباتك') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                <button type="button" wire:click="goToSettings" class="btn w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-semibold text-white transition-colors" style="background:#648454;">
                    <i class="ri-settings-3-line"></i>{{ $tr('Compléter le profil', 'إكمال الملف الشخصي') }}
                </button>
            </div>
        </div>
    </div>
    @endif

</div>