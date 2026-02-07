<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.formulaires.index') }}"
                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <i class="ri-arrow-left-line text-xl"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Soumissions: {{ $form->title }}</h2>
                <p class="text-gray-500 text-sm mt-1">{{ $submissions->total() }} soumission(s) au total</p>
            </div>
        </div>
        <a href="{{ route('admin.formulaires.edit', $form->id) }}"
            class="flex items-center gap-2 px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition border border-blue-200">
            <i class="ri-edit-line"></i> Modifier le formulaire
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[250px]">
                <div class="relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Rechercher par nom, prénom ou CIN..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none text-sm">
                </div>
            </div>
            <select wire:model.live="filterStatus"
                class="px-4 py-2.5 rounded-lg border border-gray-200 focus:border-green-500 outline-none text-sm">
                <option value="">Tous les statuts</option>
                <option value="draft">Brouillon</option>
                <option value="submitted">Soumis</option>
                <option value="in_review">En révision</option>
                <option value="approved">Approuvé</option>
                <option value="rejected">Rejeté</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étape</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Soumis le</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($submissions as $sub)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-sm font-medium">
                                    {{ strtoupper(substr($sub->candidat->first_name ?? '?', 0, 1)) }}{{ strtoupper(substr($sub->candidat->last_name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $sub->candidat->first_name ?? '' }} {{ $sub->candidat->last_name ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $sub->candidat->cin ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $sub->status_badge_color }}-100 text-{{ $sub->status_badge_color }}-800">
                                {{ $sub->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $sub->current_step }} / {{ $form->steps->count() }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $sub->submitted_at ? $sub->submitted_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.formulaires.submission.detail', $sub->id) }}"
                                    class="px-3 py-1.5 text-xs text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    <i class="ri-eye-line"></i> Voir
                                </a>
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open"
                                        class="px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-50 rounded-lg transition">
                                        <i class="ri-arrow-down-s-line"></i> Statut
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition
                                        class="absolute right-0 mt-1 w-36 bg-white rounded-lg shadow-lg border py-1 z-30">
                                        @foreach(['submitted' => 'Soumis', 'in_review' => 'En révision', 'approved' => 'Approuvé', 'rejected' => 'Rejeté'] as $key => $label)
                                            <button wire:click="updateStatus({{ $sub->id }}, '{{ $key }}')" @click="open = false"
                                                class="block w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-50">
                                                {{ $label }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <i class="ri-inbox-line text-3xl mb-2"></i>
                            <p>Aucune soumission pour ce formulaire</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $submissions->links() }}
    </div>
</div>
