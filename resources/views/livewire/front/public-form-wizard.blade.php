
<div class="parent-steps container">

<div class="header-form">
    <!-- <h1>{{ __('messages.welcome') }}</h1>
    <p>{{ __('messages.submit') }}</p> -->
    <h1>Business Plan</h1>
    <p>(خطة العمل)</p>
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



    @if(app()->environment('local'))
        <div class="bg-yellow-100 border border-yellow-400 p-4 mb-4 rounded">
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
    @endif


    <p class="steps-indicateur mt-4">( {{ $step }} / 8 )</p>

    <div class="navigation-buttons mt-4 flex justify-center gap-4">
        @if ($step > 1)
            <button wire:click="back" class="navigation-btn btn-back"><i class="ri-arrow-left-circle-fill me-1 ms-1"></i>Précedent</button>
        @endif

        @if ($step < 8)
            <button wire:click="next" class="navigation-btn btn-next">Suivant <i class="ri-arrow-right-circle-fill me-1 ms-1"></i></button>
        @endif
        @if ($step == 8)
            <button wire:click="submit" class="navigation-btn btn-submit">Soumettre <i class="ri-send-plane-fill me-1 ms-1"></i></button>
        @endif
    </div>

    @if (session()->has('success'))
        <p>{{ session('success') }}</p>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('scroll-to-top', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>
</div>
