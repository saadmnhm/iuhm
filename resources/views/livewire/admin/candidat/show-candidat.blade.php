<div class="max-w-7xl mx-auto">
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    

        <div class="p-6">
            <div class="flex items-center pb-6 border-b border-gray-200 mb-6">
                <div class="w-24 h-24 rounded-full bg-green-logo flex items-center justify-center text-white text-3xl font-semibold mr-6">
                @if($candidat->profile_image)
                    <img src="{{ asset('uploads/' . $candidat->profile_image) }}" alt="{{ $candidat->nom }} {{ $candidat->prenom }}" class="w-full h-full object-cover rounded-full">
                @else
                {{ strtoupper(substr($candidat->nom, 0, 1) . substr($candidat->prenom, 0, 1)) }}
                @endif
                </div>
                <div class="flex-1">
                    <h4 class="text-2xl font-semibold text-gray-900 mb-1">{{ $candidat->nom }} {{ $candidat->prenom }}</h4>
                    <p class="text-gray-500">{{ $candidat->email }}</p>
                    <p class="text-xs text-gray-400 mt-1">ID: #{{ $candidat->id }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if($candidat->is_active)
                        <span class="px-4 py-2 text-sm font-medium rounded-full bg-green-100 text-green-800">
                            Actif
                        </span>
                    @else
                        <span class="px-4 py-2 text-sm font-medium rounded-full bg-red-100 text-red-800">
                            Inactif
                        </span>
                    @endif

                    <div x-data="{ open:false }" class="relative">
                        <button type="button" @click="open = !open" class="px-3 py-2 bg-slate-700 text-white rounded-lg text-sm hover:bg-slate-800">
                            Actions <i class="ri-arrow-down-s-line"></i>
                        </button>
                        <div x-show="open" x-cloak @click.away="open=false" class="absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-lg shadow-lg p-2 z-40">
                            <a href="{{ route('admin.candidats.edit', $candidat->id) }}" class="block px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700">
                                <i class="ri-sort-asc mr-1"></i>ordre des formulaires
                            </a>
                            <a href="{{ route('admin.candidat.submissions', $candidat->id) }}" class="block px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700">
                                <i class="ri-flow-chart mr-1"></i> Gérer soumissions
                            </a>
                            <a href="{{ route('admin.candidats.print.fiche_inscription', $candidat->id) }}" target="_blank" class="block px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700">
                                <i class="ri-printer-line mr-1"></i> Imprimer fiche de renseignement
                            </a>
                            <a href="{{ route('admin.candidats.edit', $candidat->id) }}" class="block px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700">
                                <i class="ri-edit-line mr-1"></i> Éditer
                            </a>
                            <a href="{{ route('admin.candidats.index') }}" class="block px-3 py-2 rounded hover:bg-gray-50 text-sm text-gray-700">
                                <i class="ri-arrow-left-line mr-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Genre</span>
                        <span class="text-base text-gray-900">{{ $candidat->gender ? ucfirst($candidat->gender) : 'N/A' }}</span>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Téléphone</span>
                        <span class="text-base text-gray-900">{{ $candidat->phone ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Matricule</span>
                        <span class="text-base text-gray-900">{{ $candidat->matricule ?: '—' }}</span>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Adresse</span>
                        <span class="text-base text-gray-900">{{ $candidat->address ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Inscrit le</span>
                        <span class="text-base text-gray-900">{{ $candidat->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Classement Formation (admin)</span>
                        @php
                            $badge = match($candidat->ranking_feedback_status ?? 'pending') {
                                'good' => 'bg-green-100 text-green-800',
                                'not_good' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-700'
                            };
                            $label = match($candidat->ranking_feedback_status ?? 'pending') {
                                'good' => 'Good',
                                'not_good' => 'Not good',
                                default => 'Pending'
                            };
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badge }}">{{ $label }}</span>
                        @if($candidat->ranking_feedback_note)
                            <p class="text-xs text-gray-600 mt-2">{{ $candidat->ranking_feedback_note }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Soumissions dynamiques ({{ $dynamicSubmissions->count() }})</h3>
        </div>

                @if($dynamicSubmissions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projet</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Formulaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Soumis le</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($dynamicSubmissions as $sub)
                    <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $sub['project_name'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $sub['form_title'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <x-status-badge :status="$sub['status']" />
                        </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $sub['effective_order'] ?? 'Global' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sub['submitted_at'] ? \Carbon\Carbon::parse($sub['submitted_at'])->format('d/m/Y H:i') : '—' }}</td>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-gray-500">Aucun projet soumis</p>
        </div>
        @endif
    </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Historique candidat (logs)</h3>
                </div>
                <div class="p-4 space-y-3 max-h-96 overflow-y-auto">
                    @forelse($recentLogs as $log)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between gap-2">
                                <div class="text-sm font-semibold text-gray-800">{{ $log->action }}</div>
                                <div class="text-xs text-gray-400">{{ $log->created_at?->format('d/m/Y H:i') }}</div>
                            </div>
                            <div class="text-sm text-gray-600 mt-1">{{ $log->description }}</div>
                            <div class="text-xs text-gray-400 mt-1">Par: {{ $log->user?->name ?? 'Système' }} · IP: {{ $log->ip_address }}</div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Aucun log trouvé pour ce candidat.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h4 class="font-semibold text-gray-900">Projets éligibles</h4>
                </div>
                <div class="p-4 space-y-2 max-h-144 overflow-y-auto">
                    @foreach($eligibleProjects as $p)
                        <div class="border rounded-lg p-3 {{ $p['eligible'] ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-gray-50' }}">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-semibold text-gray-800">{{ $p['project_name'] }}</p>
                                <span class="text-xs px-2 py-0.5 rounded {{ $p['eligible'] ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                                    {{ $p['eligible'] ? 'Eligible' : 'Non éligible' }}
                                </span>
                            </div>
                            @if(!$p['eligible'] && !empty($p['reasons']))
                                <ul class="mt-1 text-xs text-gray-500 list-disc pl-4">
                                    @foreach($p['reasons'] as $r)
                                        <li>{{ $r }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @if($showRankingModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click.self="$set('showRankingModal', false)">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden z-10">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Classement formation (admin)</h3>
                <button wire:click="$set('showRankingModal', false)" class="text-gray-400 hover:text-gray-600"><i class="ri-close-line text-xl"></i></button>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Décision</label>
                    <select wire:model="rankingStatus" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="pending">Pending</option>
                        <option value="good">Good</option>
                        <option value="not_good">Not good</option>
                    </select>
                    @error('rankingStatus') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Message/Note au candidat</label>
                    <textarea wire:model="rankingNote" rows="4" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Texte personnalisable..."></textarea>
                    @error('rankingNote') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-2">
                <button wire:click="$set('showRankingModal', false)" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">Annuler</button>
                <button wire:click="saveRankingDecision" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm">Enregistrer</button>
            </div>
        </div>
    </div>
    @endif
</div>
