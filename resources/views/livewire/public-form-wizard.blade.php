<div class="container">

<h1>{{ __('messages.welcome') }}</h1>
<button>{{ __('messages.submit') }}</button>
    <h2>Step {{ $step }} / 8</h2>

    <progress value="{{ $step }}" max="8"></progress>

    @include('livewire.steps.step'.$step)
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
            @for($i = 0; $i <= 8; $i++)
                <button wire:click="goToStep({{ $i }})" class=" text-black px-3 py-1 rounded text-sm">
                    Go to Step {{ $i }}
                </button>
            @endfor
        </div>
    </div>
@endif
    <div>
        @if ($step > 1)
            <button wire:click="back">Back</button>
        @endif

        @if ($step < 8)
            <button wire:click="next">Next</button>
        @endif
        @if ($step == 8)
            <button wire:click="submit">Submit</button>
        @endif
    </div>

    @if (session()->has('success'))
        <p>{{ session('success') }}</p>
    @endif
</div>
