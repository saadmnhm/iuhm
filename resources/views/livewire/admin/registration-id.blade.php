<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
            <h2 class="text-lg font-bold text-gray-800">Ajouter un numéro d'enregistrement</h2>
        </div>
        <div class="p-6">
            @if($projectId)
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Numéro d'enregistrement</label>
                    <input type="text" wire:model="registration" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
                <button wire:click="save" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                    Enregistrer
                </button>
            </div>
            @else
            <p class="text-gray-500 text-center py-8">Projet introuvable.</p>
            @endif
        </div>
    </div>
</div>
