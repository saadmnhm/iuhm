<div class="max-w-4xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('admin.finance.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
            <i class="ri-arrow-left-line mr-1"></i> Retour aux transactions
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="ri-pencil-line text-indigo-600"></i> Modifier Transaction
        </h2>

        <form wire:submit.prevent="save" class="space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Type *</label>
                    <select wire:model.live="type" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                        <option value="depense">Dépense</option>
                        <option value="revenue">Revenue</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catégorie</label>
                    <select wire:model="category_id" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                        <option value="">-- Sélectionner --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Libellé *</label>
                    <input type="text" wire:model="label" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    @error('label') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Montant (MAD) *</label>
                    <input type="number" step="0.01" wire:model="amount" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Date *</label>
                    <input type="date" wire:model="date_transaction" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Mode de paiement</label>
                    <select wire:model="mode_paiement" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                        <option value="espece">Espèce</option>
                        <option value="cheque">Chèque</option>
                        <option value="virement">Virement</option>
                        <option value="carte">Carte bancaire</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Statut</label>
                    <select wire:model="status" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                        <option value="valide">Validé</option>
                        <option value="en_attente">En attente</option>
                        <option value="annule">Annulé</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Bénéficiaire / Fournisseur</label>
                <input type="text" wire:model="beneficiaire" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Description / Justification</label>
                <textarea wire:model="description" rows="3" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none resize-none"></textarea>
            </div>

            {{-- Existing Attachments --}}
            @if(count($existingAttachments) > 0)
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">Pièces jointes existantes</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($existingAttachments as $att)
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                        <i class="ri-file-line text-indigo-600"></i>
                        <span>{{ $att['file_name'] }}</span>
                        <button type="button" wire:click="removeAttachment({{ $att['id'] }})" wire:confirm="Supprimer cette pièce jointe ?"
                                class="text-red-500 hover:text-red-700"><i class="ri-close-line"></i></button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- New Attachments --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">Ajouter des pièces jointes</label>
                <input type="file" wire:model="newAttachments" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.finance.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Annuler</a>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">
                    <i class="ri-save-line mr-1"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
