<div>
    <div class="parent-steps container">
        @if($isReadOnly)
            <div class="text-blue-700 px-4 py-3 rounded mb-4" role="alert">
                <div class="flex items-center">
                    <i class="ri-information-fill mr-2"></i>
                    <div>
                        <p class="font-bold">Formulaire soumis - Mode lecture seule</p>
                    </div>
                </div>
            </div>
        @endif

        @if($recordId && !$isReadOnly)
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <div class="flex items-center">
                    <i class="ri-save-line mr-2"></i>
                    <p class="text-sm">Mode brouillon - Votre progression est sauvegardée.</p>
                </div>
            </div>
        @endif

        <div class="header-form">
            <h1>Business Model Canevas</h1>
            <p>( نموذج مخطط العمل )</p>
        </div>

        @include('livewire.front.bmc.step1')

        @if(!$isReadOnly)
            <div class="bg-yellow-100 border border-yellow-400 p-4 mb-4 rounded">
                <h4 class="font-bold mb-2">🧪 Development Testing Tools</h4>
                <div class="flex gap-2 flex-wrap">
                    <button wire:click="fillTestData" class="text-black px-3 py-1 rounded text-sm">
                        Fill All Test Data
                    </button>
                </div>
            </div>
        @endif

        <div class="navigation-buttons mt-4 flex justify-center gap-4">
            @if(!$isReadOnly)
                <button wire:click="saveAsDraft" class="navigation-btn" style="background-color: #28a745;">
                    <i class="ri-save-line me-1 ms-1"></i>Save Draft
                </button>
                <button wire:click="submit" class="navigation-btn btn-submit">
                    {{ __('messages.submitter') }} <i class="ri-send-plane-fill me-1 ms-1"></i>
                </button>
            @endif
        </div>

        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="wizard-footer-content mt-5">
            <div class="wizard-logo-footer">
                <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="iuhm-logo-footer">
                <img src="{{ asset('assets/site/images/indh_logo.png') }}" alt="indh-logo-footer">
                <img src="{{ asset('assets/site/images/logo_zettat.png') }}" alt="zettat-logo-footer">
            </div>
            <p class="text-center mt-5">&copy; {{ date('Y') }} Tous droits réservés par <a href="www.iuhm.org" target="_blank" style='color:#2f5496'>initiative urbaine hay mohammadi</a></p>
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
