<div>
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-2xl font-bold text-gray-800">{{ $statistics['total'] }}</div>
            <div class="text-sm text-gray-500">Total Soumissions</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-2xl font-bold text-yellow-600">{{ $statistics['draft'] }}</div>
            <div class="text-sm text-gray-500">Brouillons</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-2xl font-bold text-blue-600">{{ $statistics['submitted'] }}</div>
            <div class="text-sm text-gray-500">Soumis</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-2xl font-bold text-green-600">{{ $statistics['approved'] }}</div>
            <div class="text-sm text-gray-500">Approuvés</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-2xl font-bold text-red-600">{{ $statistics['rejected'] }}</div>
            <div class="text-sm text-gray-500">Rejetés</div>
        </div>
    </div>

    <!-- Form Type Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        @foreach($formTypes as $key => $label)
        <div class="bg-white rounded-xl shadow-sm p-3 border border-gray-100">
            <div class="text-lg font-bold text-gray-800">{{ $statistics[$key] ?? 0 }}</div>
            <div class="text-xs text-gray-500">{{ $label }}</div>
        </div>
        @endforeach
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input wire:model.live.debounce.300ms="search" type="text" 
                    placeholder="Rechercher par nom, projet, email..." 
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
            <div>
                <select wire:model.live="statusFilter" class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="all">Tous les statuts</option>
                    <option value="draft">Brouillon</option>
                    <option value="submitted">Soumis</option>
                    <option value="in_review">En révision</option>
                    <option value="approved">Approuvé</option>
                    <option value="rejected">Rejeté</option>
                </select>
            </div>
            <div>
                <select wire:model.live="formTypeFilter" class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="all">Tous les formulaires</option>
                    @foreach($formTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">#</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Candidat</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Formulaire</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Projet</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Statut</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Date</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($projects as $project)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-gray-500">{{ $project->id }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold text-xs">
                                {{ strtoupper(substr($project->candidat->nom ?? 'N', 0, 1)) }}{{ strtoupper(substr($project->candidat->prenom ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">{{ $project->candidat->nom ?? 'N/A' }} {{ $project->candidat->prenom ?? '' }}</div>
                                <div class="text-xs text-gray-400">{{ $project->candidat->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->form_type_badge_color }}">
                            {{ $project->form_type_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ Str::limit($project->project_name ?? 'Sans titre', 30) }}</td>
                    <td class="px-4 py-3">
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-700',
                                'submitted' => 'bg-blue-100 text-blue-700',
                                'in_review' => 'bg-yellow-100 text-yellow-700',
                                'approved' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                            ];
                            $statusLabels = [
                                'draft' => 'Brouillon',
                                'submitted' => 'Soumis',
                                'in_review' => 'En révision',
                                'approved' => 'Approuvé',
                                'rejected' => 'Rejeté',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$project->status] ?? 'bg-gray-100' }}">
                            {{ $statusLabels[$project->status] ?? $project->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $project->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.projects.show', $project->id) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition text-xs font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Voir
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        Aucune soumission trouvée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $projects->links() }}
        </div>
    </div>
</div>
