<div class="max-w-4xl mx-auto">
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('admin.finance.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
            <i class="ri-arrow-left-line mr-1"></i> Retour
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.edit', $transaction->id) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <i class="ri-pencil-line mr-1"></i> Modifier
            </a>
            <a href="{{ route('admin.finance.print', $transaction->id) }}" target="_blank" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <i class="ri-printer-line mr-1"></i> Imprimer PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-4 {{ $transaction->type === 'revenue' ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-100' : 'bg-gradient-to-r from-red-50 to-orange-50 border-b border-red-100' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $transaction->type === 'revenue' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $transaction->type === 'revenue' ? 'REVENU' : 'DÉPENSE' }}
                    </span>
                    <h2 class="text-xl font-bold text-gray-900 mt-2">{{ $transaction->label }}</h2>
                    <p class="text-sm text-gray-500 font-mono">{{ $transaction->reference }}</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold {{ $transaction->type === 'revenue' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $transaction->type === 'revenue' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} MAD
                    </p>
                    <p class="text-sm text-gray-500 mt-1">{{ $transaction->date_transaction->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Catégorie</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $transaction->category->name ?? 'Non catégorisé' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Mode de paiement</p>
                    <p class="text-sm font-semibold text-gray-900 capitalize">{{ $transaction->mode_paiement ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Statut</p>
                    @php
                        $statusColors = ['valide' => 'bg-green-100 text-green-800', 'en_attente' => 'bg-amber-100 text-amber-800', 'annule' => 'bg-red-100 text-red-800'];
                    @endphp
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $statusColors[$transaction->status] ?? 'bg-gray-100' }}">
                        {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Bénéficiaire</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $transaction->beneficiaire ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Créé par</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $transaction->creator->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">Date création</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            @if($transaction->description)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 font-medium uppercase mb-1">Description / Justification</p>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $transaction->description }}</p>
            </div>
            @endif

            {{-- Attachments --}}
            @if($transaction->attachments->count() > 0)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 font-medium uppercase mb-3">
                    <i class="ri-attachment-line mr-1"></i> Pièces jointes ({{ $transaction->attachments->count() }})
                </p>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($transaction->attachments as $att)
                    <div class="border border-gray-200 rounded-lg p-3 hover:shadow-md transition">
                        @if(str_starts_with($att->mime_type, 'image/'))
                        <img src="{{ asset('uploads/' . $att->file_path) }}" alt="{{ $att->file_name }}" class="w-full h-32 object-cover rounded-lg mb-2">
                        @else
                        <div class="flex items-center justify-center h-32 bg-gray-50 rounded-lg mb-2">
                            <i class="ri-file-text-line text-4xl text-gray-400"></i>
                        </div>
                        @endif
                        <p class="text-xs font-medium text-gray-700 truncate">{{ $att->file_name }}</p>
                        <a href="{{ asset('uploads/' . $att->file_path) }}" target="_blank" download class="text-xs text-indigo-600 hover:text-indigo-800">
                            <i class="ri-download-line mr-1"></i> Télécharger
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
