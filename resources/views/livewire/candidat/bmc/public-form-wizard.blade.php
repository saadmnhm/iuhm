@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        @if($isReadOnly)
        <div class="flex items-center gap-2 p-4 mb-6 rounded-xl bg-blue-50 border border-blue-100 text-blue-800" role="alert">
            <i class="ri-information-fill text-xl"></i>
            <div>
                <p class="font-bold">{{ $tr('Formulaire soumis - Mode lecture seule', 'تم إرسال الاستمارة - وضع القراءة فقط') }}</p>
            </div>
        </div>
        @endif

        @if($recordId && !$isReadOnly)
        <div class="flex items-center gap-2 p-4 mb-6 rounded-xl bg-green-50 border border-green-100 text-green-800" role="alert">
            <i class="ri-save-line text-xl"></i>
            <p class="text-sm">{{ $tr('Mode brouillon - Votre progression est sauvegardée.', 'وضع المسودة - تم حفظ تقدمك.') }}</p>
        </div>
        @endif

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ $tr('Business Model Canevas', 'نموذج العمل التجاري') }}</h1>
            <p class="text-gray-500 mt-1">( {{ $tr('نموذج مخطط العمل', 'نموذج مخطط العمل') }} )</p>
        </div>

        @include('livewire.candidat.bmc.step1')

        @if(!$isReadOnly)
        <div class="flex items-center gap-2 p-4 mb-6 rounded-xl bg-amber-50 border border-amber-100">
            <h4 class="font-bold mb-2 flex items-center gap-2"><i class="ri-flask-line"></i> {{ $tr('Outils de test développement', 'أدوات اختبار التطوير') }}</h4>
            <div class="flex flex-wrap gap-2">
                <button wire:click="fillTestData" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-900 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                    {{ $tr('Remplir toutes les données de test', 'ملء كل بيانات الاختبار') }}
                </button>
            </div>
        </div>
        @endif

        <div class="flex justify-center gap-4 mt-8">
            @if(!$isReadOnly)
            <button wire:click="saveAsDraft" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white transition-colors text-sm" style="background: #16a34a;">
                <i class="ri-save-line"></i>{{ $tr('Enregistrer le brouillon', 'حفظ المسودة') }}
            </button>
            <button wire:click="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white transition-colors text-sm" style="background: #648454;">
                {{ $tr('Soumettre', 'إرسال') }} <i class="ri-send-plane-fill"></i>
            </button>
            @endif
        </div>

        @if (session()->has('success'))
        <div class="flex items-center gap-2 p-4 mt-6 rounded-xl bg-green-50 border border-green-100 text-green-800" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="flex items-center gap-2 p-4 mt-6 rounded-xl bg-red-50 border border-red-100 text-red-800" role="alert">
            <p>{{ session('error') }}</p>
        </div>
        @endif

        <div class="mt-12 pt-8 border-t border-gray-100">
            <div class="flex justify-center gap-6 mb-6">
                <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="iuhm-logo-footer" class="h-12 object-contain">
                <img src="{{ asset('assets/site/images/indh_logo.png') }}" alt="indh-logo-footer" class="h-12 object-contain">
                <img src="{{ asset('assets/site/images/logo_zettat.png') }}" alt="zettat-logo-footer" class="h-12 object-contain">
            </div>
            <p class="text-center text-gray-500">&copy; {{ date('Y') }} {{ $tr('Tous droits réservés par', 'جميع الحقوق محفوظة لدى') }} <a href="https://www.iuhm.org" target="_blank" class="text-green-700 hover:text-green-800 underline">initiative urbaine hay mohammadi</a></p>
        </div>

        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('scroll-to-top', () => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });
        </script>
    </div>
</div>