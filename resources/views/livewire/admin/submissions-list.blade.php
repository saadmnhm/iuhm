<div>
    <!-- Header with Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex-1 w-full md:w-auto">
                <input type="text" 
                       wire:model.live="search" 
                       placeholder="Rechercher par nom, prénom ou email..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div class="w-full md:w-auto">
                <select wire:model.live="filterAddress" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Toutes les adresses</option>
                    <option value="Ain Sbaa">Ain Sbaa</option>
                    <option value="Hay Mohamadi">Hay Mohamadi</option>
                    <option value="Roches noires">Roches noires</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Candidats Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="ri-user-line mr-2"></i>Liste des Candidats
                <span class="text-sm text-gray-500 ml-2">({{ $candidats->total() }} total)</span>
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Soumissions</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($candidats as $candidat)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($candidat->profile_image)
                                    <img class="w-10 h-10 rounded-full mr-3 object-cover" 
                                         src="{{ asset('uploads/'.$candidat->profile_image) }}" 
                                         alt="{{ $candidat->nom }}">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold mr-3">
                                        {{ strtoupper(substr($candidat->nom, 0, 1)) }}{{ strtoupper(substr($candidat->prenom, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $candidat->nom }} {{ $candidat->prenom }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <i class="ri-calendar-line"></i> Inscrit le {{ $candidat->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <i class="ri-mail-line text-gray-400"></i> {{ $candidat->email }}
                            </div>
                            @if($candidat->phone)
                            <div class="text-sm text-gray-500">
                                <i class="ri-phone-line text-gray-400"></i> {{ $candidat->phone }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($candidat->address)
                            <span class="px-3 py-1 text-xs font-medium rounded-full 
                                @if($candidat->address == 'Hay Mohamadi') bg-green-100 text-green-800
                                @elseif($candidat->address == 'Ain Sbaa') bg-purple-100 text-purple-800
                                @elseif($candidat->address == 'Roches noires') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $candidat->address }}
                            </span>
                            @else
                            <span class="text-sm text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                @php
                                    $total = $candidat->business_plans_count + 
                                             $candidat->etude_marches_count + 
                                             $candidat->evaluation_idees_count + 
                                             $candidat->bmcs_count + 
                                             $candidat->bilan_competences_count;
                                @endphp
                                
                                <div class="flex items-center gap-1">
                                    <span class="text-2xl font-bold text-indigo-600">{{ $total }}</span>
                                    <span class="text-xs text-gray-500">formulaires</span>
                                </div>
                                
                                @if($total > 0)
                                <div class="flex gap-1">
                                    @if($candidat->business_plans_count > 0)
                                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-semibold" title="Business Plan">{{ $candidat->business_plans_count }}</span>
                                    @endif
                                    @if($candidat->etude_marches_count > 0)
                                        <span class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-semibold" title="Étude de Marché">{{ $candidat->etude_marches_count }}</span>
                                    @endif
                                    @if($candidat->evaluation_idees_count > 0)
                                        <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xs font-semibold" title="Évaluation d'Idée">{{ $candidat->evaluation_idees_count }}</span>
                                    @endif
                                    @if($candidat->bmcs_count > 0)
                                        <span class="w-6 h-6 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center text-xs font-semibold" title="BMC">{{ $candidat->bmcs_count }}</span>
                                    @endif
                                    @if($candidat->bilan_competences_count > 0)
                                        <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-xs font-semibold" title="Bilan de Compétences">{{ $candidat->bilan_competences_count }}</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('admin.candidat.submissions', $candidat->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                                <i class="ri-eye-line mr-2"></i> Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="ri-inbox-line text-4xl text-gray-300 mb-2"></i>
                            <p>Aucun candidat trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($candidats->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $candidats->links() }}
        </div>
        @endif
    </div>
</div>
