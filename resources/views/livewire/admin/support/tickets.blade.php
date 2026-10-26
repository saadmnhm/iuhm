<div>
    {{-- Page header --}}
    <div class="mb-8">
        <div class="green_title_1">SUPPORT &amp; ASSISTANCE</div>
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-3xl">
                <h1 class="iuhm_title_1">Gestion des tickets</h1>
                <p class="text-[18px] text-gray-600">
                    Gérez les demandes envoyées par les candidats ainsi que les tickets internes créés par l'administration.
                </p>
            </div>
        </div>
    </div>

    {{-- Flash message --}}
    @if (session()->has('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
            <i class="ri-check-line text-lg text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Statistics (scoped to the active tab) --}}
    @php
        $statCards = [
            ['label' => 'Total tickets', 'value' => $statistics['total'], 'icon' => 'ri-ticket-2-line'],
            ['label' => 'Ouverts', 'value' => $statistics['open'], 'icon' => 'ri-mail-open-line'],
            ['label' => 'En cours', 'value' => $statistics['in_progress'], 'icon' => 'ri-time-line'],
            ['label' => 'Résolus', 'value' => $statistics['resolved'], 'icon' => 'ri-checkbox-circle-line'],
        ];
    @endphp

    <div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
        @foreach ($statCards as $card)
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="mb-3 flex justify-between">
                    <p class="mt-0.5 text-[14px] font-bold uppercase tracking-[0.05em] text-[#45464E]">{{ $card['label'] }}</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#9AF89330]">
                        <i class="{{ $card['icon'] }} text-xl text-[#04103A]"></i>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Tabs --}}
    <div class="mb-6 border-b border-gray-100">
        <div class="flex gap-1 overflow-x-auto">
            <button type="button" wire:click="setTab('candidats')"
                class="whitespace-nowrap border-b-2 px-6 py-4 text-[18px] font-bold text-[#172554] transition
                    {{ $tab === 'candidats' ? 'border-green-600' : 'border-transparent hover:text-gray-700' }}">
                Tickets candidats
                <span class="ml-2 inline-flex items-center rounded-full bg-[#9AF89330] px-2.5 py-0.5 text-xs font-bold text-[#066E1B]">
                    {{ $candidateCount }}
                </span>
            </button>
            <button type="button" wire:click="setTab('admin')"
                class="whitespace-nowrap border-b-2 px-6 py-4 text-[18px] font-bold text-[#172554] transition
                    {{ $tab === 'admin' ? 'border-green-600' : 'border-transparent hover:text-gray-700' }}">
                Tickets administration
                <span class="ml-2 inline-flex items-center rounded-full bg-[#9AF89330] px-2.5 py-0.5 text-xs font-bold text-[#066E1B]">
                    {{ $adminCount }}
                </span>
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:flex-wrap lg:items-center">
        <div class="relative min-w-[220px] flex-1">
            <i class="ri-search-line pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Rechercher par sujet, message, candidat ou responsable..."
                class="iuhm_search h-11 w-full rounded-full border border-gray-300 bg-white pl-10 pr-4 text-sm outline-none">
        </div>

        <select wire:model.live="statusFilter"
            class="iuhm_select h-11 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm">
            <option value="all">Tous les statuts</option>
            <option value="open">Ouvert</option>
            <option value="in_progress">En cours</option>
            <option value="resolved">Résolu</option>
            <option value="closed">Fermé</option>
        </select>

        <select wire:model.live="priorityFilter"
            class="iuhm_select h-11 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm">
            <option value="all">Toutes les priorités</option>
            <option value="low">Basse</option>
            <option value="medium">Moyenne</option>
            <option value="high">Haute</option>
            <option value="urgent">Urgente</option>
        </select>

        <select wire:model.live="categoryFilter"
            class="iuhm_select h-11 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm">
            <option value="all">Toutes les catégories</option>
            <option value="general">Général</option>
            <option value="technical">Technique</option>
            <option value="account">Compte</option>
            <option value="form">Formulaire</option>
            <option value="bug">Bug</option>
            <option value="other">Autre</option>
        </select>

        <select wire:model.live="assignedFilter"
            class="iuhm_select h-11 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm">
            <option value="all">Tous les responsables</option>
            <option value="unassigned">Non assigné</option>
            @foreach ($admins as $admin)
                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
            @endforeach
        </select>

        <button type="button" wire:click="resetFilters"
            class="inline-flex h-[50px] items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-5 text-sm font-semibold text-[#04103A] transition hover:bg-gray-50">
            <i class="ri-refresh-line"></i>
            Réinitialiser
        </button>
    </div>

    {{-- Tickets table --}}
    <div class="overflow-hidden rounded-[22px] bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-[#04103A] text-white">
                    <tr>
                        <th class="px-6 py-5 text-left text-xs font-semibold uppercase">#</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold uppercase">{{ $tab === 'candidats' ? 'Candidat' : 'Administration' }}</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold uppercase">Sujet</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold uppercase">Pièce jointe</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold uppercase">Catégorie</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold uppercase">Priorité</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold uppercase">Statut</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold uppercase">Responsable</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold uppercase">Date</th>
                        <th class="px-6 py-5 text-center text-xs font-semibold uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($tickets as $ticket)
                        <tr wire:key="ticket-{{ $ticket->id }}" class="bg-white transition-colors hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-semibold text-[#04103A]">#{{ $ticket->id }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if ($ticket->candidat)
                                    <div class="font-semibold text-[#04103A]">
                                        {{ $ticket->candidat->nom }} {{ $ticket->candidat->prenom }}
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500">{{ $ticket->candidat->email }}</div>
                                @elseif ($ticket->candidat_id)
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                                            <i class="ri-user-unfollow-line"></i>
                                        </span>
                                        <div>
                                            <div class="font-semibold text-[#04103A]">Candidat supprimé</div>
                                            <div class="text-xs text-slate-500">Compte introuvable</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#9AF89330] text-[#066E1B]">
                                            <i class="ri-admin-line"></i>
                                        </span>
                                        <div>
                                            <div class="font-semibold text-[#04103A]">Administration</div>
                                            <div class="text-xs text-slate-500">Ticket interne</div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-slate-900">{{ Str::limit($ticket->subject, 45) }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ Str::limit($ticket->message, 65) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($ticket->image)
                                    <a href="{{ route('uploads.show', ['path' => $ticket->image]) }}" target="_blank" rel="noopener"
                                        class="inline-block" title="Voir la pièce jointe">
                                        <img src="{{ route('uploads.show', ['path' => $ticket->image]) }}" alt="Pièce jointe"
                                            class="h-12 w-12 rounded-xl object-cover ring-1 ring-slate-200">
                                    </a>
                                @else
                                    <span class="text-xs text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                    {{ $ticket->category_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $ticket->priority_badge }}">
                                    {{ $ticket->priority_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $ticket->status_badge }}">
                                    {{ $ticket->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($ticket->assignedAdmin)
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#9AF89330] text-xs font-bold text-[#066E1B]">
                                            {{ strtoupper(substr($ticket->assignedAdmin->name, 0, 1)) }}
                                        </span>
                                        <span class="text-sm font-semibold text-[#04103A]">{{ $ticket->assignedAdmin->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs italic text-slate-400">Non assigné</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" wire:click="openTicketModal({{ $ticket->id }})"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#0f1d57]/5 text-[#0f1d57] transition hover:bg-[#0f1d57] hover:text-white"
                                        title="Voir le ticket">
                                        <i class="ri-eye-line text-base"></i>
                                    </button>
                                    <button type="button" wire:click="openDeleteModal({{ $ticket->id }})"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-600 hover:text-white"
                                        title="Supprimer le ticket">
                                        <i class="ri-delete-bin-2-fill text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-16 text-center">
                                <i class="ri-inbox-line mb-3 block text-4xl text-slate-200"></i>
                                <p class="text-sm font-semibold text-slate-500">Aucun ticket trouvé.</p>
                                <p class="mt-1 text-xs text-slate-400">Les tickets correspondant à ces critères apparaîtront ici.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-4 bg-white px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                Affichage de {{ $tickets->firstItem() ?? 0 }} à {{ $tickets->lastItem() ?? 0 }} sur {{ $tickets->total() }} tickets
            </p>
            <div>{{ $tickets->links() }}</div>
        </div>
    </div>

    {{-- Ticket detail modal --}}
    @if ($showTicketModal && $selectedTicket)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
            <div class="relative flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-xl">
                {{-- Header --}}
                <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Ticket #{{ $selectedTicket->id }}</span>
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $selectedTicket->status_badge }}">
                                {{ $selectedTicket->status_label }}
                            </span>
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $selectedTicket->priority_badge }}">
                                {{ $selectedTicket->priority_label }}
                            </span>
                        </div>
                        <h3 class="mt-2 truncate text-lg font-bold text-[#04103A]">{{ $selectedTicket->subject }}</h3>
                        <p class="mt-1 text-xs text-slate-500">
                            {{ $selectedTicket->created_at->format('d/m/Y H:i') }} · {{ $selectedTicket->category_label }}
                        </p>
                    </div>
                    <button type="button" wire:click="closeTicketModal" class="text-slate-400 transition hover:text-slate-600">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="flex-1 space-y-5 overflow-y-auto px-6 py-5">
                    {{-- Requester --}}
                    <div class="flex items-center gap-3 rounded-2xl bg-slate-50 p-4">
                        @if ($selectedTicket->candidat)
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#9AF89330] text-sm font-bold text-[#066E1B]">
                                {{ strtoupper(substr($selectedTicket->candidat->nom ?? 'C', 0, 1)) }}
                            </span>
                            <div class="min-w-0">
                                <div class="truncate text-sm font-semibold text-[#04103A]">
                                    {{ $selectedTicket->candidat->nom }} {{ $selectedTicket->candidat->prenom }}
                                </div>
                                <div class="truncate text-xs text-slate-500">{{ $selectedTicket->candidat->email }}</div>
                            </div>
                        @elseif ($selectedTicket->candidat_id)
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                                <i class="ri-user-unfollow-line"></i>
                            </span>
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-[#04103A]">Candidat supprimé</div>
                                <div class="text-xs text-slate-500">Le compte associé à ce ticket n'existe plus.</div>
                            </div>
                        @else
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#9AF89330] text-[#066E1B]">
                                <i class="ri-admin-line text-lg"></i>
                            </span>
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-[#04103A]">Administration</div>
                                <div class="text-xs text-slate-500">Ticket interne créé par l'administration</div>
                            </div>
                        @endif
                    </div>

                    {{-- Original message --}}
                    <div>
                        <p class="mb-2 text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Message</p>
                        <div class="whitespace-pre-line rounded-2xl rounded-tl-sm bg-slate-100 p-4 text-sm leading-6 text-slate-700">
                            {{ $selectedTicket->message }}
                        </div>
                    </div>

                    {{-- Uploaded image --}}
                    @if ($selectedTicket->image)
                        <div>
                            <p class="mb-2 text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Pièce jointe</p>
                            <a href="{{ route('uploads.show', ['path' => $selectedTicket->image]) }}" target="_blank" rel="noopener"
                                class="inline-block overflow-hidden rounded-2xl border border-slate-200">
                                <img src="{{ route('uploads.show', ['path' => $selectedTicket->image]) }}" alt="Pièce jointe"
                                    class="max-h-72 w-auto object-contain">
                            </a>
                        </div>
                    @endif

                    {{-- Admin response --}}
                    @if ($selectedTicket->admin_response)
                        <div>
                            <p class="mb-2 text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Réponse de l'administration</p>
                            <div class="ml-auto max-w-[92%] whitespace-pre-line rounded-2xl rounded-tr-sm bg-emerald-50 p-4 text-sm leading-6 text-emerald-900">
                                {{ $selectedTicket->admin_response }}
                            </div>
                            @if ($selectedTicket->responded_at)
                                <p class="mt-1 text-right text-xs text-slate-400">
                                    {{ $selectedTicket->responded_at->format('d/m/Y H:i') }}
                                </p>
                            @endif
                        </div>
                    @endif

                    {{-- Status update --}}
                    <div class="rounded-2xl border border-slate-200 p-4">
                        <p class="mb-3 flex items-center gap-2 text-sm font-bold text-[#04103A]">
                            <i class="ri-flag-line text-[#066E1B]"></i>
                            Changer le statut
                        </p>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <select wire:model="newStatus"
                                class="iuhm_select w-full rounded-full border border-gray-300 bg-white px-4 py-2 text-sm sm:flex-1">
                                <option value="open">Ouvert</option>
                                <option value="in_progress">En cours</option>
                                <option value="resolved">Résolu</option>
                                <option value="closed">Fermé</option>
                            </select>
                            <button type="button" wire:click="updateStatus"
                                class="inline-flex h-[50px] items-center justify-center gap-2 whitespace-nowrap rounded-full bg-[#1B264F] px-6 text-sm font-semibold text-white transition hover:bg-gray-800">
                                <i class="ri-check-line"></i>
                                Mettre à jour
                            </button>
                        </div>
                        @error('newStatus')
                            <span class="mt-2 block text-xs font-semibold text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Reply --}}
                    <div class="rounded-2xl border border-slate-200 p-4">
                        <p class="mb-3 flex items-center gap-2 text-sm font-bold text-[#04103A]">
                            <i class="ri-reply-line text-[#066E1B]"></i>
                            Répondre au ticket
                        </p>
                        <textarea wire:model="adminResponse" rows="4"
                            placeholder="Écrivez votre réponse au candidat ou à l'équipe..."
                            class="iuhm_textarea w-full rounded-2xl border border-gray-300 text-sm"></textarea>
                        @error('adminResponse')
                            <span class="mt-2 block text-xs font-semibold text-red-500">{{ $message }}</span>
                        @enderror
                        <div class="mt-3 flex justify-end">
                            <button type="button" wire:click="respondToTicket"
                                class="inline-flex h-[50px] items-center justify-center gap-2 rounded-full bg-[#1B264F] px-6 text-sm font-semibold text-white transition hover:bg-gray-800">
                                <i class="ri-send-plane-line"></i>
                                Envoyer la réponse
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4">
                    <button type="button" wire:click="openDeleteModal({{ $selectedTicket->id }})"
                        class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-50">
                        <i class="ri-delete-bin-2-fill"></i>
                        Supprimer
                    </button>
                    <button type="button" wire:click="closeTicketModal"
                        class="rounded-full border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete confirmation modal --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4">
            <div class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-xl">
                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100">
                            <i class="ri-error-warning-line text-xl text-red-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-[#04103A]">Supprimer le ticket</h3>
                    </div>
                    <button type="button" wire:click="closeDeleteModal" class="text-gray-400 transition hover:text-gray-600">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5">
                    <p class="text-sm text-gray-600">
                        Voulez-vous vraiment supprimer ce ticket ? Cette action est définitive et ne peut pas être annulée.
                    </p>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4">
                    <button type="button" wire:click="closeDeleteModal"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
                        Annuler
                    </button>
                    <button type="button" wire:click="deleteTicket" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700 disabled:opacity-60">
                        <i class="ri-delete-bin-2-fill" wire:loading.remove wire:target="deleteTicket"></i>
                        <i class="ri-loader-4-line animate-spin" wire:loading wire:target="deleteTicket"></i>
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
