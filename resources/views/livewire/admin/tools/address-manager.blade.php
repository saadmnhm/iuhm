<div>
    <div class="">


        <div class="mb-8">
            <div class="text-m font-bold text-[#066E1B] uppercase tracking-wide mb-2">SYSTEM CONFIGURATION</div>
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-[36px] font-bold text-[#04103A]">Gestion des localisations</h1>
                    <p class="text-gray-600 text-[18px] mt-2">Gérer les hiérarchies organisationnelles en créant des autorisations granulaires pour les membres de l'équipe au sein de l'écosystème Initiative Urbaine</p>
                </div>
                <button wire:click="openModal" class="w-95 h-12.5 text-center p-2 content-center bg-[#1B264F] text-white text-[16px] font-normal rounded-full hover:bg-gray-800 transition">
                    <i class="ri-shield-user-line text-[19px] relative right-1"></i> Créer une localisation
            </div>
        </div>

        <div class="mt-8 overflow-hidden  ">
            <div class="border-b border-slate-200 rounded-[22px] bg-[#F5F3F7] ] p-5 ">
                <div class="grid gap-3 md:grid-cols-4 items-center">
                    <div>
                        <label for="regionFilter" class="iuhm_label">RÉGION</label>
                        <select wire:model="regionFilter" id="regionFilter" class="rounded-xl iuhm_input py-3 text-sm outline-none  w-full">
                            <option value="all">All Regions</option>
                            @foreach($regions as $r)
                                <option value="{{ $r }}">{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="cityFilter" class="iuhm_label">VILLE</label>
                        <select wire:model="cityFilter" id="cityFilter" class="rounded-xl iuhm_input py-3 text-sm outline-none  w-full">
                            <option value="all">All Villes</option>
                            @foreach($cities as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="prefectureFilter" class="iuhm_label">PREFECTURE</label>
                        <select wire:model="prefectureFilter" id="prefectureFilter" class="rounded-xl iuhm_input py-3 text-sm outline-none  w-full">
                            <option value="all">All Prefectures</option>
                            @foreach($prefectures as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-3 justify-end">
                        <button wire:click="applyFilters" class="rounded-full btn_standar"><i class="ri-filter-3-line mr-2"></i> Apply Filters</button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto mt-7 ">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-[#04103A] text-white">
                        <tr>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase rounded-tl-[10px]">REGION</th>
                            <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.16em]">VILLE</th>
                            <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.16em]">PREFECTURE</th>
                            <th class="px-6 py-5 text-center text-xs font-semibold text-white uppercase rounded-tr-[10px]">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-slate-50/50">
                        @forelse($addresses as $address)
                        <tr class="hover:bg-gray-200 bg-gray-100 transition-colors font-bold" style="border-bottom: 10px solid #fbf8fd;">
                            <td class="px-6 py-4 text-sm text-[#04103A]">{{ $address->region }}</td>
                            <td class="px-6 py-4 text-sm text-[#04103A]">{{ $address->city }}</td>
                            <td class="px-6 py-4 text-sm text-[#04103A]">{{ $address->prefecture }}</td>
                            <td class="px-6 py-6 text-center">
                                <div class="flex items-center justify-center gap-2 text-[#0f1d57]">
                                    <button type="button" wire:click="edit({{ $address->id }})" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#0f1d57]/5 transition hover:bg-[#0f1d57] hover:text-white" title="Modifier">
                                        <i class="ri-edit-2-line text-base"></i>
                                    </button>
                                    <button type="button" wire:click="delete({{ $address->id }})" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-600 hover:text-white" title="Supprimer">
                                        <i class="ri-delete-bin-2-fill text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-sm text-slate-500">Aucune localisation trouvée.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-4 border-t border-slate-200 bg-white px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">Affichage de {{ $addresses->firstItem() ?? 0 }} à {{ $addresses->lastItem() ?? 0 }} sur {{ $addresses->total() }} localisations</p>
                <div>{{ $addresses->links('vendor.pagination.circle') }}</div>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    @if($modalOpen)
    <div x-show="$wire.modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 p-4 md:p-6 backdrop-blur-sm" wire:click.self="closeModal" style="display: none;">
        <div class="overflow-hidden rounded-2xl bg-white my-4 md:my-0">
            <div class="flex items-start justify-between gap-2 px-6 py-4 ">
                <div class="flex-1 min-w-0 text-center">
                    <h3 class="text-[30px] font-bold tracking-tight text-[#04103A]">{{ $editMode ? 'Modifier la localisation' : 'Nouvelle localisation' }}</h3>
                    <p class="mt-0.5 text-[16px] text-[#45464E]">Complétez les formulaires suivants pour créer une nouvelle localisation</p>
                </div>
                <button type="button" wire:click="closeModal" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            <form wire:submit.prevent="save" class="p-6 space-y-4">
                <div>
                    <label class="iuhm_label_2 mb-1">Région *</label>
                    <input type="text" wire:model="region" placeholder="Entrez le nom complet"
                           class="w-full iuhm_input px-4 py-2.5 rounded-xl bg-[#F6F7FB] text-sm outline-none">
                    @error('region') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="iuhm_label_2 mb-1">Ville *</label>
                        <input placeholder="ville"  type="text" wire:model="city" class="w-full iuhm_input px-4 py-2.5 rounded-xl bg-[#F6F7FB] text-sm outline-none">
                        @error('city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="iuhm_label_2 mb-1">Préfecture *</label>
                        <input placeholder="préfecture" type="text" wire:model="prefecture" class="w-full iuhm_input px-4 py-2.5 rounded-xl bg-[#F6F7FB] text-sm outline-none">
                        @error('prefecture') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-semibold text-gray-700 h-12.5 text-center rounded-full hover:bg-gray-200 transition-colors">Cancel</button>
                    <button type="submit" class="gap-2 px-4 py-2 h-12.5 text-center rounded-full w-37.5 text-sm font-semibold text-white bg-[#1B264F] hover:bg-[#0f1a3a] transition-colors disabled:opacity-60">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

