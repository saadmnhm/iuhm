<div>
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-2xl font-bold text-gray-800">{{ $statistics['total'] }}</div>
            <div class="text-sm text-gray-500">Total Tickets</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-yellow-200">
            <div class="text-2xl font-bold text-yellow-600">{{ $statistics['open'] }}</div>
            <div class="text-sm text-gray-500">Ouverts</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-blue-200">
            <div class="text-2xl font-bold text-blue-600">{{ $statistics['in_progress'] }}</div>
            <div class="text-sm text-gray-500">En cours</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-green-200">
            <div class="text-2xl font-bold text-green-600">{{ $statistics['resolved'] }}</div>
            <div class="text-sm text-gray-500">Résolus</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input wire:model.live.debounce.300ms="search" type="text" 
                    placeholder="Rechercher par sujet ou candidat..." 
                    class="w-full rounded-lg border-gray-300  focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
            <div>
                <select wire:model.live="statusFilter" class="rounded-lg border-gray-300 shadow-sm text-sm">
                    <option value="all">Tous les statuts</option>
                    <option value="open">Ouvert</option>
                    <option value="in_progress">En cours</option>
                    <option value="resolved">Résolu</option>
                    <option value="closed">Fermé</option>
                </select>
            </div>
            <div>
                <select wire:model.live="priorityFilter" class="rounded-lg border-gray-300 shadow-sm text-sm">
                    <option value="all">Toutes priorités</option>
                    <option value="low">Basse</option>
                    <option value="medium">Moyenne</option>
                    <option value="high">Haute</option>
                    <option value="urgent">Urgente</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">#</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Candidat</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Sujet</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Catégorie</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Priorité</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Statut</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Responsable</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Date</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-gray-500">{{ $ticket->id }}</td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-800">{{ $ticket->candidat->nom ?? 'N/A' }} {{ $ticket->candidat->prenom ?? '' }}</div>
                        <div class="text-xs text-gray-400">{{ $ticket->candidat->email ?? '' }}</div>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ Str::limit($ticket->subject, 40) }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ ucfirst($ticket->category) }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->priority_badge }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->status_badge }}">
                            {{ $ticket->status_label }}
                        </span>
                        {{-- Quick status actions --}}
                        <div class="flex gap-1 mt-1">
                            @if($ticket->status !== 'in_progress')
                            <button wire:click="changeStatus({{ $ticket->id }}, 'in_progress')"
                                class="text-xs px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition" title="Marquer en cours">
                                <i class="ri-time-line"></i>
                            </button>
                            @endif
                            @if($ticket->status !== 'resolved')
                            <button wire:click="changeStatus({{ $ticket->id }}, 'resolved')"
                                class="text-xs px-1.5 py-0.5 bg-green-50 text-green-600 rounded hover:bg-green-100 transition" title="Résoudre">
                                <i class="ri-check-line"></i>
                            </button>
                            @endif
                            @if($ticket->status !== 'closed')
                            <button wire:click="changeStatus({{ $ticket->id }}, 'closed')"
                                class="text-xs px-1.5 py-0.5 bg-gray-100 text-gray-500 rounded hover:bg-gray-200 transition" title="Fermer">
                                <i class="ri-close-line"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        @if($ticket->assignedAdmin)
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                {{ strtoupper(substr($ticket->assignedAdmin->name, 0, 1)) }}
                            </div>
                            <span class="text-xs text-gray-700">{{ $ticket->assignedAdmin->name }}</span>
                        </div>
                        @else
                        <span class="text-xs text-gray-400 italic">Non assigné</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-center">
                        <button wire:click="openResponseModal({{ $ticket->id }})" 
                            class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition text-xs font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                        </button>
                        <button wire:click="opendeleteConfirmation({{ $ticket->id }})"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 text-center">
                            Delete
                        </button>
                        

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-8 text-center text-gray-400">
                        Aucun ticket trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $tickets->links() }}
        </div>
    </div>

    <!-- Response Modal -->
    @if($showResponseModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeModal"></div>
        {{-- Modal panel --}}
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg z-10">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="ri-reply-line mr-2 text-indigo-500"></i>Répondre au Ticket #{{ $selectedTicketId }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-xl">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Réponse</label>
                    <textarea wire:model="adminResponse" rows="5" 
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition"
                        placeholder="Votre réponse..."></textarea>
                    @error('adminResponse') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-3 justify-end">
                    <button wire:click="closeModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                        Annuler
                    </button>
                    <button wire:click="respondToTicket" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                        <i class="ri-send-plane-line mr-1"></i> Envoyer la réponse
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div x-data="{ open: @entangle('showDeleteModal') }" x-cloak>
        <div
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center"
        >
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

            {{-- Panel --}}
            <div
                x-show="open"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 z-10 overflow-hidden"
                @click.stop
            >
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Delete Ticket</h3>
                    </div>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5">
                    <p class="text-gray-600 text-sm">
                        Are you sure you want to delete
                        <span class="font-semibold text-gray-900">this ticket</span>?
                        This action is permanent and cannot be undone.
                    </p>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <button
                        @click="open = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="deleteTicket"
                        wire:loading.attr="disabled"
                        class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors disabled:opacity-60"
                    >
                        <svg wire:loading wire:target="deleteTicket" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        Delete Ticket
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
