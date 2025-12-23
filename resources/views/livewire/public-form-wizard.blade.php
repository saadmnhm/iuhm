<div class="container">

<h1>{{ __('messages.welcome') }}</h1>
<button>{{ __('messages.submit') }}</button>
    <h2>Step {{ $step }} / 8</h2>

    <progress value="{{ $step }}" max="8"></progress>

    @include('livewire.steps.step'.$step)

    <div>
        @if ($step > 1)
            <button wire:click="back">Back</button>
        @endif

        @if ($step < 8)
            <button wire:click="next">Next</button>
        @endif
    </div>

    @if (session()->has('success'))
        <p>{{ session('success') }}</p>
    @endif
</div>
