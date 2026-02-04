
<div>

    <div class="parent-steps container">
        <!-- Read-Only Mode Banner -->
        @if($isReadOnly && $existingProject)
            <div class="text-blue-700 px-4 py-3 rounded mb-4" role="alert">
                <div class="flex items-center">
                    <i class="ri-information-fill mr-2"></i>
                    <div>
                        <p class="font-bold">Project Submitted - View Only Mode</p>
                        <p class="text-sm">
                            Status: <x-status-badge :status="$existingProject->status" />
                            | Submitted: {{ $existingProject->submitted_at?->format('Y-m-d H:i') }}
                            @if($existingProject->reviewed_at)
                                | Reviewed: {{ $existingProject->reviewed_at->format('Y-m-d H:i') }}
                            @endif
                        </p>
                        @if($existingProject->review_notes)
                            <p class="text-sm mt-2"><strong>Admin Notes:</strong> {{ $existingProject->review_notes }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Draft Mode Banner -->
        @if($projectId && !$isReadOnly)
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <div class="flex items-center">
                    <i class="ri-save-line mr-2"></i>
                    <p class="text-sm">Draft mode - Your progress is automatically saved when you move between steps.</p>
                </div>
            </div>
        @endif
        <div class="header-form">
            <h1>BILAN DES COMPÉTENCES</h1>
            <p>(تقييم المهارات)</p>
            <p style="font-size: 1rem;">Grâce à ce bilan de compétences, tu apprendras à te définir, à valoriser tes compétences et à identifier tes
                axes d'amélioration. Cet accompagnement devrait t'aider à fixer un objectif professionnel clair, puis tu
                pourras élaborer un plan d'action avec ton ou ta conseiller(ère) pour l'atteindre. Bonne chance !
            </p>
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

        @include('livewire.front.bilan_competence.step'.$step)



        <p class="steps-indicateur mt-4">( {{ $step }} / 7 )</p>

        <div class="navigation-buttons mt-4 flex justify-center gap-4">
            @if ($step > 1)
                <button wire:click="back" class="navigation-btn btn-back" @if($isReadOnly)  @endif>
                    <i class="ri-arrow-left-circle-fill me-1 ms-1"></i>{{ __('messages.precedent') }}
                </button>
            @endif

            @if ($step < 7)
                <button wire:click="next" class="navigation-btn btn-next" @if($isReadOnly)  @endif>
                    {{ __('messages.suivant') }} <i class="ri-arrow-right-circle-fill me-1 ms-1"></i>
                </button>
            @endif
            
            
            @if ($step == 7 && !$isReadOnly)
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
                <img src="{{ asset('assets/site/images/indh_logo.png') }}" alt="indh-logo-footer" >
                <img src="{{ asset('assets/site/images/logo_zettat.png') }}" alt="zettat-logo-footer" >
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
