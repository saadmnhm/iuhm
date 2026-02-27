<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
            <h2 class="text-lg font-bold text-gray-800">Business Model Canvas - Détails</h2>
        </div>
        <div class="p-6">
            @if($bmc)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Candidat</p>
                    <p class="font-medium">{{ $bmc->candidat->nom ?? '' }} {{ $bmc->candidat->prenom ?? '' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date de création</p>
                    <p class="font-medium">{{ $bmc->created_at?->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800"><i class="ri-information-line mr-1"></i>Cette page est un formulaire statique hérité. Utilisez les formulaires dynamiques pour une meilleure expérience.</p>
            </div>
            @else
            <p class="text-gray-500 text-center py-8">Aucune donnée trouvée.</p>
            @endif
        </div>
    </div>
</div>
