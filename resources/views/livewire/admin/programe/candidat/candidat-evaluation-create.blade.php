<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3">
            <i class="ri-checkbox-circle-line text-green-500 text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <i class="ri-survey-line text-indigo-600"></i>
                Grille d'evaluation candidat
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                {{ $candidat?->nom }} {{ $candidat?->prenom }} - {{ $project?->project_name }}
            </p>
        </div>

        <form wire:submit="save" class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-700">Motivation (0-20)</label>
                    <input type="number" min="0" max="20" wire:model="motivationScore" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @error('motivationScore') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">Profil (0-20)</label>
                    <input type="number" min="0" max="20" wire:model="profileScore" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @error('profileScore') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">Viabilite (0-20)</label>
                    <input type="number" min="0" max="20" wire:model="viabilityScore" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @error('viabilityScore') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-3 text-sm text-indigo-800">
                Score total: <strong>{{ (int) $motivationScore + (int) $profileScore + (int) $viabilityScore }}</strong> / 60
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700">Commentaire</label>
                <textarea wire:model="evaluationComment" rows="5" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Observations de l'evaluateur..."></textarea>
                @error('evaluationComment') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('admin.candidat.submissions', ['id' => $candidatId, 'projectId' => $projectId]) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
