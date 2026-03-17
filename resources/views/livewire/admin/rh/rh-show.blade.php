<div class="max-w-4xl mx-auto">
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('admin.rh.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
            <i class="ri-arrow-left-line mr-1"></i> Retour
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.rh.edit', $employee->id) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <i class="ri-pencil-line mr-1"></i> Modifier
            </a>
            <a href="{{ route('admin.rh.print.attestation', $employee->id) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <i class="ri-file-text-line mr-1"></i> Attestation
            </a>
            <a href="{{ route('admin.rh.print.fiche', $employee->id) }}" target="_blank" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <i class="ri-printer-line mr-1"></i> Fiche PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-5 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-indigo-100">
            <div class="flex items-center gap-4">
                @if($employee->photo_path)
                <img src="{{ asset('uploads/' . $employee->photo_path) }}" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow">
                @else
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-2xl font-bold shadow">
                    {{ strtoupper(substr($employee->nom, 0, 1)) }}{{ strtoupper(substr($employee->prenom, 0, 1)) }}
                </div>
                @endif
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $employee->nom }} {{ $employee->prenom }}</h2>
                    <p class="text-sm text-indigo-600 font-medium">{{ $employee->poste ?? '-' }}</p>
                    @php
                        $statusColors = ['active' => 'bg-green-100 text-green-800', 'inactive' => 'bg-red-100 text-red-800', 'en_conge' => 'bg-amber-100 text-amber-800', 'quitte' => 'bg-gray-100 text-gray-600'];
                        $statusLabels = ['active' => 'Actif', 'inactive' => 'Inactif', 'en_conge' => 'En congé', 'quitte' => 'Quitté'];
                    @endphp
                    <span class="mt-1 inline-block px-3 py-0.5 text-xs font-bold rounded-full {{ $statusColors[$employee->status] ?? '' }}">
                        {{ $statusLabels[$employee->status] ?? $employee->status }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Matricule</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $employee->matricule ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">CIN</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $employee->cin ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Genre</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $employee->gender ? ucfirst($employee->gender) : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Email</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $employee->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Téléphone</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $employee->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Date naissance</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $employee->date_naissance ? $employee->date_naissance->format('d/m/Y') : '-' }}</p>
                </div>
            </div>

            <hr class="my-6 border-gray-100">

            <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                <i class="ri-briefcase-line text-indigo-600"></i> Informations Professionnelles
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Poste</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $employee->poste ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Département</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $employee->departement ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Type de contrat</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $employee->contrat_type }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Date d'embauche</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $employee->date_embauche ? $employee->date_embauche->format('d/m/Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Fin de contrat</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $employee->date_fin_contrat ? $employee->date_fin_contrat->format('d/m/Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Salaire</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $employee->salaire ? number_format($employee->salaire, 2) . ' MAD' : '-' }}</p>
                </div>
            </div>

            @if($employee->address)
            <div class="mt-4">
                <p class="text-xs text-gray-500 font-medium uppercase">Adresse</p>
                <p class="text-sm font-semibold text-gray-900">{{ $employee->address }}</p>
            </div>
            @endif

            @if($employee->notes)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 font-medium uppercase mb-1">Notes</p>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $employee->notes }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
