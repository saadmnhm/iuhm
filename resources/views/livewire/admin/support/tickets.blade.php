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
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
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
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-center">
                        <button wire:click="openResponseModal({{ $ticket->id }})" 
                            class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition text-xs font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                            Répondre
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">
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
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Répondre au Ticket #{{ $selectedTicketId }}</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Réponse</label>
                    <textarea wire:model="adminResponse" rows="5" 
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        placeholder="Votre réponse..."></textarea>
                    @error('adminResponse') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select wire:model="newStatus" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                        <option value="open">Ouvert</option>
                        <option value="in_progress">En cours</option>
                        <option value="resolved">Résolu</option>
                        <option value="closed">Fermé</option>
                    </select>
                </div>

                <div class="flex gap-3 justify-end">
                    <button wire:click="closeModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                        Annuler
                    </button>
                    <button wire:click="respondToTicket" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                        Envoyer la réponse
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
