<div>

<h1>{{ __('messages.welcome') }}</h1>
<button>{{ __('messages.submit') }}</button>
    <h2>Step {{ $step }} / 8</h2>

    <progress value="{{ $step }}" max="8"></progress>

    @if ($step === 1)
        <input wire:model="name" placeholder="Name">
        @error('name') <span>{{ $message }}</span> @enderror
    @endif

    @if ($step === 2)
        <input wire:model="email" placeholder="Email">
        @error('email') <span>{{ $message }}</span> @enderror
    @endif

    @if ($step === 3)
        <input wire:model="phone" placeholder="Phone">
    @endif

    @if ($step === 4)
        <input wire:model="address" placeholder="Address">
    @endif

    @if ($step === 5)
        <input wire:model="city" placeholder="City">
    @endif

    @if ($step === 6)
        <input wire:model="country" placeholder="Country">
    @endif

    @if ($step === 7)
        <textarea wire:model="notes"></textarea>
    @endif

    @if ($step === 8)
        <button wire:click="submit">Submit</button>
    @endif

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
