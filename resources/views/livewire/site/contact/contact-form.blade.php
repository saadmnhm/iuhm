<div>
    @if($success)
        <div class="bg-[#F4F4F6] rounded-[2rem] p-8 sm:p-10 lg:p-12 flex flex-col items-center justify-center text-center min-h-[300px]">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-[#dcfce7] mb-5">
                <svg class="h-8 w-8 text-[#10752d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-[#0f172a] mb-2">Demande envoyée !</h3>
            <p class="text-sm font-medium text-slate-500 max-w-xs">Nous avons bien re\u00e7u votre message et vous contacterons sous 48h ouvr\u00e9es.</p>
            <button wire:click="$set('success', false)"
                    class="mt-6 rounded-xl bg-[#1e293b] hover:bg-[#0f172a] transition px-6 py-3 text-xs font-bold text-white">
                Envoyer une autre demande
            </button>
        </div>
    @else
        <div class="bg-[#F4F4F6] rounded-[2rem] p-8 sm:p-10 lg:p-12">
            <form wire:submit.prevent="send" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wide text-[#0f172a]">Nom complet *</label>
                        <input type="text" wire:model="name" placeholder="Votre nom complet"
                               class="w-full rounded-xl bg-[#e9e9ee] px-5 py-4 text-sm text-[#0f172a] placeholder-[#8e8e9c] border-none focus:ring-2 focus:ring-[#10752d] @error('name') ring-2 ring-rose-400 @enderror">
                        @error('name') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wide text-[#0f172a]">Email *</label>
                        <input type="email" wire:model="email" placeholder="votre@email.com"
                               class="w-full rounded-xl bg-[#e9e9ee] px-5 py-4 text-sm text-[#0f172a] placeholder-[#8e8e9c] border-none focus:ring-2 focus:ring-[#10752d] @error('email') ring-2 ring-rose-400 @enderror">
                        @error('email') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wide text-[#0f172a]">T\u00e9l\u00e9phone</label>
                        <input type="tel" wire:model="phone" placeholder="06 00 00 00 00"
                               class="w-full rounded-xl bg-[#e9e9ee] px-5 py-4 text-sm text-[#0f172a] placeholder-[#8e8e9c] border-none focus:ring-2 focus:ring-[#10752d]">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wide text-[#0f172a]">Service</label>
                        <div class="relative">
                            <select wire:model="subject"
                                    class="w-full appearance-none rounded-xl bg-[#e9e9ee] px-5 py-4 text-sm text-[#0f172a] border-none focus:ring-2 focus:ring-[#10752d]">
                                <option value="">Choisir un service</option>
                                <option value="\u00c9ducation">\u00c9ducation</option>
                                <option value="Sant\u00e9">Sant\u00e9</option>
                                <option value="S\u00e9curit\u00e9 Alimentaire">S\u00e9curit\u00e9 Alimentaire</option>
                                <option value="Int\u00e9gration Sociale">Int\u00e9gration Sociale</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#8e8e9c]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wide text-[#0f172a]">Message *</label>
                    <textarea wire:model="message" placeholder="D\u00e9crivez votre demande..." rows="4"
                              class="w-full rounded-xl bg-[#e9e9ee] px-5 py-4 text-sm text-[#0f172a] placeholder-[#8e8e9c] border-none focus:ring-2 focus:ring-[#10752d] resize-none @error('message') ring-2 ring-rose-400 @enderror"></textarea>
                    @error('message') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full rounded-xl bg-[#1e293b] hover:bg-[#0f172a] transition px-5 py-4 text-sm font-bold text-white disabled:opacity-60 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="send">Envoyer la demande</span>
                        <span wire:loading wire:target="send" class="flex items-center gap-2">
                            <svg class="size-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Envoi en cours\u2026
                        </span>
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>