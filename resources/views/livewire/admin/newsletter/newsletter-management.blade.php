<div>
    <div class="px-6 pb-6 pt-8 sm:px-8 sm:pb-8 sm:pt-10">

        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestion Newsletter</h1>
                    <p class="text-gray-600 mt-2">Gérez les abonnés à la newsletter. Ajoutez, activez ou supprimez des abonnés.</p>
                </div>
                <button wire:click="openCreate"
                    class="inline-flex items-center gap-2 rounded-xl bg-[#04103A] px-5 py-3 text-sm font-semibold text-white shadow hover:bg-[#04103acc] transition">
                    <i class="ri-add-line text-base"></i>
                    Ajouter un abonné
                </button>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 mb-10 mt-10">
                @foreach ($stats_card as $item)
                    <div class="rounded-2xl bg-white p-5 shadow-sm">
                        <div class="flex justify-between mb-3">
                            <p class="text-[14px] font-bold uppercase tracking-[0.05em] text-[#45464E] mt-0.5">{{ $item['label'] }}</p>
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#9AF89330]">
                                <i class="{{ $item['icon'] }} text-xl text-[#04103A]"></i>
                            </div>
                        </div>
                        <p class="text-2xl font-black text-slate-900">{{ $item['data'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="p-4 rounded-lg mb-6 mt-6">
            <div class="flex gap-4 items-center">
                <div class="relative flex-1">
                    <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" wire:model.live.debounce="search" placeholder="Chercher un abonné par email..."
                           class="w-full rounded-xl iuhm_input" style="padding: 0 40px;">
                </div>
                <select wire:model.live="statusFilter" class="rounded-xl iuhm_select">
                    <option value="all">Tous les statuts</option>
                    <option value="active">Actifs</option>
                    <option value="inactive">Inactifs</option>
                </select>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="mt-8 overflow-hidden rounded-[22px]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-[#04103a] text-white">
                        <tr>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">#</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Email</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Date d'inscription</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Statut</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($newsletters as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $item->id }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->email }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $item->subscribed_at ? $item->subscribed_at->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($item->is_active)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            <i class="ri-checkbox-circle-line"></i> Actif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-500">
                                            <i class="ri-close-circle-line"></i> Inactif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-3 items-center">
                                        <button wire:click="toggleActive({{ $item->id }})"
                                            class="text-yellow-600 hover:text-yellow-800 text-sm font-medium"
                                            title="{{ $item->is_active ? 'Désactiver' : 'Activer' }}">
                                            <i class="{{ $item->is_active ? 'ri-toggle-fill' : 'ri-toggle-line' }} text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <i class="ri-mail-line text-4xl text-slate-200 mb-3 block"></i>
                                    <p class="text-sm font-semibold text-slate-500">Aucun abonné trouvé.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between bg-white border-t border-gray-100 rounded-b-[22px]">
                <p class="text-sm text-slate-500">
                    Affichage de {{ $newsletters->firstItem() ?? 0 }} à {{ $newsletters->lastItem() ?? 0 }}
                    sur {{ $newsletters->total() }} abonnés
                </p>
                <div>{{ $newsletters->links() }}</div>
            </div>
        </div>

    </div>

    {{-- Create / Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
             x-data x-on:keydown.escape.window="$wire.showModal = false">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl" @click.outside="$wire.showModal = false">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
                    <h2 class="text-lg font-bold text-gray-900">
                        {{ $editMode ? 'Modifier un abonné' : 'Ajouter un abonné' }}
                    </h2>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <div class="px-6 py-6 space-y-5">
                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Adresse email <span class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" placeholder="exemple@email.com"
                               class="w-full rounded-xl iuhm_input {{ $errors->has('email') ? 'border-red-400' : '' }}">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Statut --}}
                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model="isActive" id="isActive"
                               class="h-4 w-4 rounded border-gray-300 text-[#04103A] focus:ring-[#04103A]">
                        <label for="isActive" class="text-sm font-medium text-gray-700">Abonné actif</label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 px-6 py-4">
                    <button wire:click="$set('showModal', false)"
                        class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button wire:click="save"
                        class="rounded-xl bg-[#04103A] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04103acc]">
                        <span wire:loading.remove wire:target="save">
                            {{ $editMode ? 'Mettre à jour' : 'Ajouter' }}
                        </span>
                        <span wire:loading wire:target="save">Enregistrement...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
