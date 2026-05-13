<div>
    <div class="px-6 pb-6 pt-8 sm:px-8 sm:pb-8 sm:pt-10">

        @if(session()->has('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
            <i class="ri-check-line text-lg text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        {{-- Page Header --}}
        <div class="mb-6 flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-emerald-700">CONTACT STUDIO</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Messages Contact</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">
                    Consultez et gérez les messages envoyés via le formulaire de contact du site.
                </p>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-3 mb-10 mt-6">
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

        {{-- Search & Filter --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center mb-6">
            <div class="relative flex-1">
                <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" wire:model.live.debounce="search" placeholder="Rechercher par nom, email, sujet..."
                       class="w-full rounded-xl iuhm_input" style="padding: 0 40px;">
            </div>
            <select wire:model.live="statusFilter" class="rounded-xl iuhm_select">
                <option value="all">Tous les messages</option>
                <option value="unread">Non lus</option>
                <option value="read">Lus</option>
            </select>
        </div>

        {{-- Data Table --}}
        <div class="mt-4 overflow-hidden rounded-[22px]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-[#04103a] text-white">
                        <tr>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Nom</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Email</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Téléphone</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Sujet</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Reçu le</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Statut</th>
                            <th class="px-6 py-5 text-center text-xs font-semibold text-white uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($contacts as $contact)
                        <tr class="hover:bg-gray-50 transition-colors {{ !$contact->is_read ? 'bg-blue-50/40' : 'bg-white' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if(!$contact->is_read)
                                    <span class="h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                                    @endif
                                    <span class="font-semibold text-slate-900 text-sm">{{ $contact->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <a href="mailto:{{ $contact->email }}" class="hover:text-blue-600 transition">{{ $contact->email }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ $contact->phone ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700 max-w-[200px] truncate">
                                {{ $contact->subject ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ $contact->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($contact->is_read)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-emerald-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Lu
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700 ring-1 ring-blue-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span> Non lu
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" wire:click="openDetail({{ $contact->id }})"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#0f1d57]/5 text-[#0f1d57] transition hover:bg-[#0f1d57] hover:text-white" title="Voir le message">
                                        <i class="ri-eye-line text-base"></i>
                                    </button>
                                    @if(!$contact->is_read)
                                    <button type="button" wire:click="markRead({{ $contact->id }})"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 transition hover:bg-emerald-600 hover:text-white" title="Marquer comme lu">
                                        <i class="ri-check-line text-base"></i>
                                    </button>
                                    @endif
                                    <button type="button" wire:click="delete({{ $contact->id }})"
                                            wire:confirm="Supprimer ce message définitivement ?"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-600 hover:text-white" title="Supprimer">
                                        <i class="ri-delete-bin-2-fill text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <i class="ri-mail-line text-4xl text-slate-200 mb-3 block"></i>
                                <p class="text-sm font-semibold text-slate-500">Aucun message trouvé.</p>
                                <p class="text-xs text-slate-400 mt-1">Les messages du formulaire de contact apparaîtront ici.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between bg-white rounded-b-[22px]">
                <p class="text-sm text-slate-500">
                    Affichage de {{ $contacts->firstItem() ?? 0 }} à {{ $contacts->lastItem() ?? 0 }} sur {{ $contacts->total() }} messages
                </p>
                <div>{{ $contacts->links() }}</div>
            </div>
        </div>

    </div>

    {{-- Detail Modal --}}
    @if($showModal && $selected)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="w-full max-w-xl rounded-2xl bg-white shadow-xl flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h3 class="text-lg font-bold text-slate-800">Message de {{ $selected->name }}</h3>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">Nom</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $selected->name }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">Email</p>
                        <a href="mailto:{{ $selected->email }}" class="text-sm font-semibold text-blue-600 hover:underline">{{ $selected->email }}</a>
                    </div>
                    @if($selected->phone)
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">Téléphone</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $selected->phone }}</p>
                    </div>
                    @endif
                    @if($selected->subject)
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">Sujet</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $selected->subject }}</p>
                    </div>
                    @endif
                    <div class="col-span-2">
                        <p class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">Reçu le</p>
                        <p class="text-sm text-slate-600">{{ $selected->created_at->format('d M Y à H:i') }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Message</p>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $selected->message }}</div>
                </div>
            </div>

            <div class="border-t border-slate-100 px-6 py-4 flex justify-between items-center gap-3 rounded-b-2xl bg-slate-50">
                <button type="button" wire:click="delete({{ $selected->id }})"
                        wire:confirm="Supprimer ce message définitivement ?"
                        class="inline-flex items-center gap-2 rounded-full bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-600 hover:text-white transition">
                    <i class="ri-delete-bin-line"></i> Supprimer
                </button>
                <div class="flex gap-2">
                    <a href="mailto:{{ $selected->email }}" class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-600 hover:text-white transition">
                        <i class="ri-reply-line"></i> Répondre
                    </a>
                    <button wire:click="$set('showModal', false)" type="button" class="rounded-full px-5 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
