<div>
    <div class="parent-steps container">
        @if($isReadOnly)
            <div class="text-blue-700 px-4 py-3 rounded mb-4" role="alert">
                <div class="flex items-center">
                    <i class="ri-information-fill mr-2"></i>
                    <div><p class="font-bold">Formulaire soumis - Mode lecture seule</p></div>
                </div>
            </div>
        @endif

        @if($etudeId && !$isReadOnly)
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <div class="flex items-center">
                    <i class="ri-save-line mr-2"></i>
                    <p class="text-sm">Mode brouillon - Votre progression est sauvegardée automatiquement.</p>
                </div>
            </div>
        @endif

        <div class="header-form">
            <h1>Etude de marché</h1>
            <p>(دراسة السوق)</p>
        </div>

        <div class="step-progress-container">
            <div class="step-progress">
                @for($i = 1; $i <= 4; $i++)
                    <div class="step-item {{ $step >= $i ? 'active' : '' }} {{ $step == $i ? 'current' : '' }}">
                        <div class="step-circle">
                            @if($step > $i)
                                <i class="ri-check-line"></i>
                            @else
                                <span>{{ $i }}</span>
                            @endif
                        </div>
                        @if($i < 4)
                            <div class="step-line"></div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>

        @include('livewire.front.etude_marche.step'.$step)

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

        <p class="steps-indicateur mt-4">( {{ $step }} / 4 )</p>

        <div class="navigation-buttons mt-4 flex justify-center gap-4">
            @if ($step > 1)
                <button wire:click="back" class="navigation-btn btn-back">
                    <i class="ri-arrow-left-circle-fill me-1 ms-1"></i>{{ __('messages.precedent') }}
                </button>
            @endif

            @if(!$isReadOnly)
                <button wire:click="saveAsDraft" class="navigation-btn" style="background-color: #28a745;">
                    <i class="ri-save-line me-1 ms-1"></i>Save Draft
                </button>
            @endif

            @if ($step < 4)
                <button wire:click="next" class="navigation-btn btn-next">
                    {{ __('messages.suivant') }} <i class="ri-arrow-right-circle-fill me-1 ms-1"></i>
                </button>
            @endif

            @if ($step == 4 && !$isReadOnly)
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
