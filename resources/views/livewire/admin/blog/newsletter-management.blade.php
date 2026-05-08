<div>
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestion des Infolettres</h1>
                <p class="text-gray-600 mt-2">Gérez les infolettres et les communications. Créez, publiez et suivez les envois.</p>
            </div>
           
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm uppercase tracking-wide">TOTAL INFOLETTRES</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalNewsletters }}</p>
                    </div>
                    <div class="text-4xl">📬</div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm uppercase tracking-wide">PUBLIÉES</p>
                        <p class="text-3xl font-bold text-green-600">{{ $publishedNewsletters }}</p>
                    </div>
                    <div class="text-4xl text-green-400">✓</div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm uppercase tracking-wide">ENVOYÉES</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $sentNewsletters }}</p>
                    </div>
                    <div class="text-4xl text-blue-400">📧</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white p-4 rounded-lg border border-gray-200 mb-6">
        <div class="flex gap-4 items-center">
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Rechercher une infolettre..." 
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            />
            <select 
                wire:model.live="statusFilter" 
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            >
                <option value="all">Tous les statuts</option>
                <option value="published">Publiées</option>
                <option value="draft">Brouillons</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-900 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">TITRE</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">N° ÉDITION</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">DATE DE PUBLICATION</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">DATE D'ENVOI</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">STATUT</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">ACTIONS</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($newsletters as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $item->title }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $item->issue_number ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if ($item->published_at)
                                {{ $item->published_at->format('d M Y') }}
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if ($item->sent_at)
                                {{ $item->sent_at->format('d M Y H:i') }}
                            @else
                                <span class="text-gray-500">Non envoyée</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if ($item->is_published)
                                <span class="inline-flex items-center bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                    ✓ Publiée
                                </span>
                            @else
                                <span class="inline-flex items-center bg-gray-100 text-gray-800 text-xs px-3 py-1 rounded-full">
                                    Brouillon
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="flex gap-2">
                                <button 
                                    wire:click="openEdit({{ $item->id }})" 
                                    class="text-indigo-600 hover:text-indigo-900"
                                    title="Modifier"
                                >
                                    ✎
                                </button>
                                <button 
                                    wire:click="togglePublish({{ $item->id }})" 
                                    class="text-blue-600 hover:text-blue-900"
                                >
                                    {{ $item->is_published ? '🔒' : '🔓' }}
                                </button>
                                <button 
                                    wire:click="delete({{ $item->id }})" 
                                    onclick="confirm('Êtes-vous sûr?') || event.stopImmediatePropagation()"
                                    class="text-red-600 hover:text-red-900"
                                    title="Supprimer"
                                >
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $newsletters->links() }}
    </div>


</div>
