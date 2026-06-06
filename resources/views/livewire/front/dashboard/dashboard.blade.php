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
<div class="p-6 font-sans">

    {{-- ── HERO BANNER ── --}}
    <div class="bg-gradient-to-r from-[#0f2441] to-[#15304a] text-white p-9 rounded-xl h-[248px] shadow-lg flex items-center justify-between">
        <div>
            <h1 class="text-3xl text-[48px] font-bold">
                {{ $tr('Bienvenue,', 'مرحباً،') }} {{ $candidat->prenom }} {{ $candidat->nom }}
            </h1>
            <p class="text-[18px] opacity-90 text-gray-200 mt-1">{{ $tr('Association Initiative Urbaine - ERP', 'جمعية المبادرة الحضرية - ERP') }}</p>
        </div>
        <div class="w-64 h-20 rounded-md bg-gradient-to-br from-[#12345a] to-[#0b2340] opacity-95"></div>
    </div>

    {{-- ── APPS POUR VOUS ── --}}
    <h3 class="text-slate-900 text-lg font-semibold mt-8 mb-3">{{ $tr('Apps pour vous', 'التطبيقات لك') }}</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    @foreach ($menu as $item )
    
    <div class="bg-[#F5F3F7] rounded-lg p-6 min-h-[268px] shadow-sm content-center">
        <div class="text-2xl mb-3 h-[50px] w-[50px] flex items-center justify-center text-[#0f2441] rounded-[10px] bg-white">
            <i class="{{ $item['icon'] }}"></i>
        </div>
        <h3 class="text-[20px] font-bold text-[#04103A]">{{ $item['label'] }}</h3>
        <small class="block mt-3 mb-3 text-[14px] font-[400] tracking-[2px] text-gray-400 uppercase">
            {{ $item['description'] }}
        </small>
        <a href="{{ $item['route'] }}" class="inline-block text-[#066E1B] font-bold hover:scale-105 no-underline">
            {{ $tr('Accéder', 'الوصول') }}<i class="ri-arrow-right-long-line relative top-[1px] left-[2px]"></i>
        </a>
    </div>
    @endforeach


    </div>

    {{-- ── BOTTOM ROW: Activity + Support ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-6">

        {{-- Recent Activity --}}
        <div class="lg:col-span-2 bg-[#F5F3F7] p-5 rounded-lg shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold">{{ $tr('Activité Récente', 'النشاط الأخير') }}</h4>
                <a href="{{ route('user.projects.list') }}" class="text-green-600">{{ $tr('Voir tout', 'عرض الكل') }}</a>
            </div>
            <div class="space-y-3">
                @forelse(array_slice($filteredSubmissions, 0, 5) as $sub)
                    <div class="flex items-center justify-between bg-[#FBF8FD] p-4 rounded-lg">
                        <div>
                            <div class="font-semibold">{{ $sub['form_title'] }}</div>
                            <div class="text-sm text-gray-500">{{ $sub['programe_name'] ?: $tr('Formulaire général', 'استمارة عامة') }}</div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full" style="background:{{ $sub['status_badge_color'] ?? '#6b7280' }}22; color:{{ $sub['status_badge_color'] ?? '#6b7280' }}; border:1px solid {{ $sub['status_badge_color'] ?? '#6b7280' }}44;">
                                {{ $sub['status_label'] ?? $sub['status'] }}
                            </span>
                            <div class="text-sm text-gray-400">{{ $sub['updated_at'] ?? '-' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-between bg-[#FBF8FD] p-4 rounded-lg">
                        <div class="text-sm text-gray-400">{{ $tr('Aucune activité récente.', 'لا يوجد نشاط حديث.') }}</div>
                    </div>
                @endforelse
                <div class="text-sm text-gray-400">{{ $tr("Plus d'éléments d'activité ici...", 'لا توجد عناصر أخرى...') }}</div>
            </div>
        </div>

        {{-- Support --}}
        <div class="bg-[#EAE7EB] content-center h-[200px] p-5 rounded-lg">
            <h4 class="text-[16px] text-[#04103A] font-bold">{{ $tr('Support Technique', 'الدعم التقني') }}</h4>
            <p class="text-sm text-gray-500 mt-2 font-semibold">{{ $tr("Besoin d'aide avec l'ERP? Contactez l'IT.", 'هل تحتاج مساعدة في ERP؟ تواصل مع الفريق.') }}</p>
            <a href="{{ route('user.support') }}"
               class="mt-4 inline-block w-full border-2 border-[#C6C5D0] text-[#04103A] font-bold text-center hover:bg-[#04103A] transition hover:text-white px-4 py-2 rounded-[50px]">
                {{ $tr('Ouvrir un Ticket', 'فتح تذكرة') }}
            </a>
        </div>

    </div>

</div>

    @if($showCompleteProfileModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="ri-information-line text-warning me-2"></i>{{ $tr('Complétez votre profil', 'أكمل ملفك الشخصي') }}
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="ri-user-settings-line" style="font-size:3.5rem;color:#648454;"></i>
                        <h5 class="mt-3 mb-2">{{ $tr('Votre profil est incomplet', 'ملفك الشخصي غير مكتمل') }}</h5>
                        <p class="text-muted mb-0 small">{{ $tr('Veuillez compléter vos informations pour accéder à toutes les fonctionnalités.', 'يرجى إكمال معلوماتك للوصول إلى جميع الميزات.') }}</p>
                    </div>
                    <div class="alert alert-info d-flex align-items-start border-0 rounded-3">
                        <i class="ri-lightbulb-line me-2 mt-1"></i>
                        <div>
                            <strong>{{ $tr('Pourquoi compléter votre profil ?', 'لماذا يجب إكمال ملفك الشخصي؟') }}</strong>
                            <ul class="mb-0 mt-2 ps-3 small">
                                <li>{{ $tr("Améliorer la précision des critères d'éligibilité", 'تحسين دقة معايير الأهلية') }}</li>
                                <li>{{ $tr('Recevoir des notifications personnalisées', 'تلقي إشعارات مخصصة') }}</li>
                                <li>{{ $tr('Accélérer le traitement de vos demandes', 'تسريع معالجة طلباتك') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn fw-semibold" wire:click="goToSettings"
                            style="background:#648454;color:white;border-radius:.6rem;">
                        <i class="ri-settings-3-line me-1"></i>{{ $tr('Compléter le profil', 'إكمال الملف الشخصي') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
