<div class="min-h-screen bg-slate-100">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                <div>
                    <h2 class="text-xl font-bold text-[#0f1d57]">{{ $formId ? 'Modifier le Formulaire' : 'Nouveau Formulaire' }}</h2>
                    <p class="text-sm text-slate-600 mt-1">D�finissez les caract�ristiques et les �tapes du formulaire</p>
                </div>
                <a href="{{ route('admin.formulaires.index') }}" class="text-slate-400 hover:text-slate-600 text-2xl">
                    <i class="ri-close-line"></i>
                </a>
            </div>

            <!-- Step Indicators -->
            <div class="px-6 pt-6 pb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4 flex-1">
                        <!-- Step 1 -->
                        <div class="flex items-center gap-3 flex-1">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#0f1d57] text-white font-bold text-sm">
                                1
                            </div>
                            <span class="text-sm font-semibold text-[#0f1d57]">Informations G�n�rales</span>
                            <div class="flex-1 h-0.5 bg-slate-200"></div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex items-center gap-3 flex-1">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $formId ? 'bg-[#0f1d57]' : 'bg-slate-200' }} {{ $formId ? 'text-white' : 'text-slate-400' }} font-bold text-sm">
                                2
                            </div>
                            <span class="text-sm font-semibold {{ $formId ? 'text-[#0f1d57]' : 'text-slate-400' }}">Etapes</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Version Tabs (FR / AR) -->
            <div class="flex gap-0 border-b border-slate-200 px-6">
                <button wire:click="$set('versionTab', 'fr')" class="px-4 py-3 text-sm font-semibold transition {{ isset($versionTab) && $versionTab === 'fr' ? 'border-b-2 border-[#0f1d57] text-[#0f1d57]' : 'border-transparent text-slate-600 hover:text-slate-800' }}">
                    <span class="inline-flex items-center gap-2">
                        <span class="inline-block w-5 h-5 rounded-full bg-slate-900 text-white text-xs flex items-center justify-center font-bold">FR</span>
                        Version Fran�aise
                    </span>
                </button>
                <button wire:click="$set('versionTab', 'ar')" class="px-4 py-3 text-sm font-semibold transition {{ isset($versionTab) && $versionTab === 'ar' ? 'border-b-2 border-[#0f1d57] text-[#0f1d57]' : 'border-transparent text-slate-600 hover:text-slate-800' }}" dir="rtl">
                    <span class="inline-flex items-center gap-2">
                        <span class="inline-block w-5 h-5 rounded-full bg-emerald-600 text-white text-xs flex items-center justify-center font-bold">AR</span>
                        ?????? ???????
                    </span>
                </button>
            </div>

            <!-- Form Content -->
            <div class="px-6 py-6 space-y-6">
                <!-- French Version -->
                @if(!isset($versionTab) || $versionTab === 'fr')
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Titre du Formulaire</label>
                    <input type="text" wire:model.blur="title" placeholder="Ex: Inscription au programme de tutorat" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm placeholder-slate-400 focus:bg-white focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10 outline-none transition">
                    @error('title') <span class="text-rose-600 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Description</label>
                    <textarea wire:model.blur="introduction" rows="4" placeholder="Pr�sentez l'objectif de ce formulaire aux r�sidents..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm placeholder-slate-400 focus:bg-white focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10 outline-none transition resize-none"></textarea>
                </div>
                @endif

                <!-- Arabic Version -->
                @if(isset($versionTab) && $versionTab === 'ar')
                <div dir="rtl">
                    <label class="block text-sm font-semibold text-slate-900 mb-2">????? ???????</label>
                    <input type="text" wire:model.blur="title_ar" placeholder="????: ??????? ?? ?????? ?????? ????????" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm placeholder-slate-400 focus:bg-white focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10 outline-none transition">
                    @error('title_ar') <span class="text-rose-600 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div dir="rtl">
                    <label class="block text-sm font-semibold text-slate-900 mb-2">?????</label>
                    <textarea wire:model.blur="introduction_ar" rows="4" placeholder="??? ????? ?? ??? ??????? ????????..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm placeholder-slate-400 focus:bg-white focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10 outline-none transition resize-none"></textarea>
                </div>
                @endif

                <!-- Form Status Section -->
                <div class="bg-gradient-to-r from-emerald-50 to-emerald-100/50 rounded-xl p-4 flex items-center justify-between border border-emerald-200">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-white">
                            <i class="ri-lightning-charge-line"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">�tat du Formulaire</p>
                            <p class="text-xs text-slate-600">D�terminez si ce formulaire est imm�diatement accessible.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                        <span class="text-sm font-semibold text-emerald-700">{{ $is_active ? 'Actif' : 'Inactif' }}</span>
                    </div>
                </div>
            </div>

            <!-- Modal Footer Actions -->
            <div class="flex gap-3 border-t border-slate-200 bg-slate-50 p-6">
                <button type="button" wire:click="cancelCreate" class="flex-1 px-6 py-3 rounded-xl border border-slate-300 bg-white text-slate-900 font-semibold transition hover:bg-slate-50 flex items-center justify-center gap-2">
                    <i class="ri-close-line"></i>
                    Annuler
                </button>
                <button type="button" wire:click="saveSettings" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="flex-1 px-6 py-3 rounded-xl bg-[#0f1d57] text-white font-semibold transition hover:bg-[#14256f] flex items-center justify-center gap-2">
                    <i class="ri-arrow-right-line" wire:loading.remove wire:target="saveSettings"></i>
                    <svg wire:loading wire:target="saveSettings" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="saveSettings">{{ $formId ? '�tapes' : 'Cr�er le Formulaire' }}</span>
                    <span wire:loading wire:target="saveSettings">Enregistrement...</span>
                </button>
            </div>
        </div>
    </div>
</div>
