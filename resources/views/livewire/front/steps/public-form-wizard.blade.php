

<div
    x-data="{
        open: !localStorage.getItem('termsAccepted'),
        agreed: false
    }"
    x-show="open"
    x-cloak
    class="overlay"
>
    <div class="modal-card">
        <h1><strong>tout les champs sont obligatoires</strong></h1>

        <label class="checkbox">
            <input type="checkbox" x-model="agreed">
            I agree to the terms
        </label>

        <br>
        <button
            :disabled="!agreed"
            @click="localStorage.setItem('termsAccepted','true'); open=false"
        >
            Accept
        </button>
    </div>
</div>

<div class="parent-steps container">

<div class="header-form">
    <h1>Business Plan</h1>
    <p>(خطة العمل)</p>
    <div class="language-switcher" style="display: flex; gap: 10px; margin-top: 10px; justify-content: space-evenly;">
        <a href="{{ route('lang.switch', ['locale' => 'en']) }}" 
           class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}"
           style="padding: 5px 15px; border: 1px solid #ccc; border-radius: 5px; text-decoration: none; {{ app()->getLocale() === 'en' ? 'background: #648454; color: white;' : 'background: white; color: #333;' }}">
            EN
        </a>
        <a href="{{ route('lang.switch', ['locale' => 'fr']) }}" 
           class="lang-btn {{ app()->getLocale() === 'fr' ? 'active' : '' }}"
           style="padding: 5px 15px; border: 1px solid #ccc; border-radius: 5px; text-decoration: none; {{ app()->getLocale() === 'fr' ? 'background: #648454; color: white;' : 'background: white; color: #333;' }}">
            FR
        </a>
        <a href="{{ route('lang.switch', ['locale' => 'ar']) }}" 
           class="lang-btn {{ app()->getLocale() === 'ar' ? 'active' : '' }}"
           style="padding: 5px 15px; border: 1px solid #ccc; border-radius: 5px; text-decoration: none; {{ app()->getLocale() === 'ar' ? 'background: #648454; color: white;' : 'background: white; color: #333;' }}">
            AR
        </a>
    </div>
</div>
    

<div class="step-progress-container">
    <div class="step-progress">
        @for($i = 0; $i <= 8; $i++)
            <div class="step-item {{ $step >= $i ? 'active' : '' }} {{ $step == $i ? 'current' : '' }}">
                <div class="step-circle">
                    @if($step > $i)
                        <i class="ri-check-line"></i>
                    @else
                        <span>{{ $i }}</span>
                    @endif
                </div>
                @if($i < 8)
                    <div class="step-line"></div>
                @endif
            </div>
        @endfor
    </div>
</div>

    @include('livewire.front.steps.step'.$step)



        <div class="bg-yellow-100 border border-yellow-400 p-4 mb-4 rounded d-none">
            <h4 class="font-bold mb-2">🧪 Development Testing Tools</h4>
            <div class="flex gap-2 flex-wrap">
                <button wire:click="fillTestData" class=" text-black px-3 py-1 rounded text-sm">
                    Fill All Test Data
                </button>
                <button wire:click="testDatabaseSave" class="bg-green-500 text-white px-3 py-1 rounded text-sm">
                    Test DB Save
                </button>
                @for($i = 1; $i <= 8; $i++)
                    <button wire:click="goToStep({{ $i }})" class=" text-black px-3 py-1 rounded text-sm">
                        Go to Step {{ $i }}
                    </button>
                @endfor
            </div>
        </div>


    <p class="steps-indicateur mt-4">( {{ $step }} / 8 )</p>

    <div class="navigation-buttons mt-4 flex justify-center gap-4">
        @if ($step > 1)
            <button wire:click="back" class="navigation-btn btn-back"><i class="ri-arrow-left-circle-fill me-1 ms-1"></i>{{ __('messages.precedent') }}</button>
        @endif

        @if ($step < 8)
            <button wire:click="next" class="navigation-btn btn-next">{{ __('messages.suivant') }} <i class="ri-arrow-right-circle-fill me-1 ms-1"></i></button>
        @endif
        @if ($step == 8)
            <button wire:click="submit" class="navigation-btn btn-submit">{{ __('messages.submitter') }} <i class="ri-send-plane-fill me-1 ms-1"></i></button>
        @endif
    </div>

    @if (session()->has('success'))
        <p>{{ session('success') }}</p>
    @endif


        <div class="wizard-layout">
        <!-- Header with Logo -->
        <header class="wizard-header">
            <div class="wizard-logo-container">
                <img src="{{ asset('assets/images/iuhm_logo.png') }}" alt="Logo" class="wizard-logo">
            </div>
        </header>

        <!-- Main Content -->
        <main class="wizard-content">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="wizard-footer">
            <div class="wizard-footer-content">
                <div class="wizard-logo-footer">
                    <img src="{{ asset('assets/images/iuhm_logo.png') }}" alt="iuhm-logo-footer">
                    <img src="{{ asset('assets/images/indh_logo.png') }}" alt="indh-logo-footer" >
                    <img src="{{ asset('assets/images/logo_zettat.png') }}" alt="zettat-logo-footer" >
                </div>
                <p class="text-center mt-5">&copy; {{ date('Y') }} Tous droits réservés par <a href="www.iuhm.org" style='color:#2f5496'>initiative urbaine hay mohammadi</a></p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('scroll-to-top', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>
</div>


