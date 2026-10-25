<div>
@if($success)
    <div class="rounded-2xl bg-[#82E682]/20 border border-[#82E682] px-6 py-6 text-center max-w-xl">
        <svg class="mx-auto size-10 text-[#82E682] mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-base font-bold text-white">Merci ! Vous \u00eates maintenant abonn\u00e9(e).</p>
        <p class="text-sm text-slate-400 mt-1">Vous recevrez nos prochaines \u00e9ditions dans votre b\u00e2te mail.</p>
        <button wire:click="$set('success', false)"
                class="mt-4 rounded-lg bg-[#82E682]/20 px-4 py-2 text-xs font-bold text-[#82E682] hover:bg-[#82E682]/30 transition">
            S\u2019abonner avec un autre email
        </button>
    </div>
@else
    <form wire:submit.prevent="subscribe" class="flex flex-col gap-3 w-full max-w-xl">
        <div class="flex gap-3 {{ $stack ? 'flex-col' : 'flex-col sm:flex-row' }}">
            <input type="email" wire:model="email" placeholder="votre@email.com" required
                   class="flex-1 rounded-2xl bg-slate-800/60 border px-6 py-4 text-white placeholder:text-slate-400 focus:outline-none focus:border-[#82E682] focus:ring-1 focus:ring-[#82E682] transition-colors shadow-inner
                          @error('email') border-rose-400 @else border-slate-700/50 @enderror">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="rounded-2xl bg-[#82E682] px-8 py-4 text-base font-bold text-[#083a15] hover:bg-[#6edc6e] transition-colors whitespace-nowrap shadow-lg shadow-[#82E682]/10 disabled:opacity-70 flex items-center justify-center gap-2 {{ $stack ? 'w-full' : '' }}">
                <span wire:loading.remove wire:target="subscribe">S\u2019abonner</span>
                <span wire:loading wire:target="subscribe" class="flex items-center gap-2">
                    <svg class="size-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    En cours\u2026
                </span>
            </button>
        </div>
        @error('email')
            <p class="text-sm text-rose-400 font-medium">{{ $message }}</p>
        @enderror
    </form>
@endif
</div>