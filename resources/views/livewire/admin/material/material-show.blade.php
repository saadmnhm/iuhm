<div class="max-w-5xl mx-auto">
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('admin.material.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
            <i class="ri-arrow-left-line mr-1"></i> Retour
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.material.edit', $material->id) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <i class="ri-pencil-line mr-1"></i> Modifier
            </a>
            <a href="{{ route('admin.material.print.fiche', $material->id) }}" target="_blank" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <i class="ri-printer-line mr-1"></i> Imprimer Fiche
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Photo gallery --}}
                @if($material->attachments->count() > 0)
                <div class="grid grid-cols-3 gap-1">
                    @foreach($material->attachments as $att)
                    <img src="{{ asset('storage/' . $att->file_path) }}" alt="{{ $att->file_name }}" class="w-full h-48 object-cover {{ $loop->first ? 'col-span-3 h-64' : '' }}">
                    @endforeach
                </div>
                @else
                <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                    <i class="ri-archive-line text-6xl text-gray-300"></i>
                </div>
                @endif

                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $material->name }}</h2>
                            <p class="text-sm text-gray-500 font-mono">{{ $material->reference }}</p>
                        </div>
                        @php
                            $statusColors = ['disponible' => 'bg-green-100 text-green-800', 'en_utilisation' => 'bg-blue-100 text-blue-800', 'en_maintenance' => 'bg-amber-100 text-amber-800', 'retire' => 'bg-gray-100 text-gray-600'];
                        @endphp
                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusColors[$material->status] ?? '' }}">{{ ucfirst(str_replace('_', ' ', $material->status)) }}</span>
                    </div>

                    @if($material->description)
                    <p class="text-sm text-gray-700 mb-4">{{ $material->description }}</p>
                    @endif

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div><p class="text-xs text-gray-500 uppercase font-medium">Catégorie</p><p class="text-sm font-semibold">{{ $material->category->name ?? '-' }}</p></div>
                        <div><p class="text-xs text-gray-500 uppercase font-medium">Quantité</p><p class="text-sm font-semibold">{{ $material->quantity }}</p></div>
                        <div><p class="text-xs text-gray-500 uppercase font-medium">État</p><p class="text-sm font-semibold capitalize">{{ $material->etat }}</p></div>
                        <div><p class="text-xs text-gray-500 uppercase font-medium">Prix unitaire</p><p class="text-sm font-semibold">{{ $material->prix_unitaire ? number_format($material->prix_unitaire, 2) . ' MAD' : '-' }}</p></div>
                        <div><p class="text-xs text-gray-500 uppercase font-medium">Valeur totale</p><p class="text-sm font-semibold">{{ $material->valeur_totale ? number_format($material->valeur_totale, 2) . ' MAD' : '-' }}</p></div>
                        <div><p class="text-xs text-gray-500 uppercase font-medium">Emplacement</p><p class="text-sm font-semibold">{{ $material->emplacement ?? '-' }}</p></div>
                        <div><p class="text-xs text-gray-500 uppercase font-medium">Fournisseur</p><p class="text-sm font-semibold">{{ $material->fournisseur ?? '-' }}</p></div>
                        <div><p class="text-xs text-gray-500 uppercase font-medium">N° de série</p><p class="text-sm font-semibold">{{ $material->numero_serie ?? '-' }}</p></div>
                        <div><p class="text-xs text-gray-500 uppercase font-medium">Date acquisition</p><p class="text-sm font-semibold">{{ $material->date_acquisition ? $material->date_acquisition->format('d/m/Y') : '-' }}</p></div>
                    </div>

                    @if($material->notes)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-medium mb-1">Notes</p>
                        <p class="text-sm text-gray-700">{{ $material->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Movements --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-bold text-gray-700 mb-3"><i class="ri-exchange-line mr-1"></i> Derniers mouvements</h3>
                @forelse($material->movements->take(5) as $mv)
                <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                    <div class="w-8 h-8 rounded-full {{ $mv->type === 'entree' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} flex items-center justify-center text-xs">
                        <i class="{{ $mv->type === 'entree' ? 'ri-arrow-down-line' : 'ri-arrow-up-line' }}"></i>
                    </div>
                    <div class="text-xs">
                        <p class="font-semibold text-gray-800 capitalize">{{ $mv->type }} ({{ $mv->quantity }})</p>
                        <p class="text-gray-500">{{ $mv->motif ?? '-' }} · {{ $mv->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-4">Aucun mouvement</p>
                @endforelse
            </div>

            {{-- Maintenance history --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-bold text-gray-700 mb-3"><i class="ri-tools-line mr-1"></i> Maintenance</h3>
                @forelse($material->maintenances->take(5) as $maint)
                <div class="py-2 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                    <p class="text-xs font-semibold text-gray-800">{{ $maint->type_maintenance }} - {{ $maint->date_maintenance->format('d/m/Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $maint->description ?? '-' }}</p>
                    @if($maint->cout)
                    <p class="text-xs font-bold text-red-600">{{ number_format($maint->cout, 2) }} MAD</p>
                    @endif
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-4">Aucune maintenance</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
