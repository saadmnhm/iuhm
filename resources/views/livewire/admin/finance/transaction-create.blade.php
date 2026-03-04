<div class="max-w-4xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('admin.finance.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
            <i class="ri-arrow-left-line mr-1"></i> Retour aux transactions
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="ri-add-circle-line text-indigo-600"></i> Nouvelle Transaction
        </h2>

        <form wire:submit.prevent="save" class="space-y-5">
            {{-- Type --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Type de transaction *</label>
                    <select wire:model.live="type" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                        <option value="depense">Dépense</option>
                        <option value="revenue">Revenue</option>
                    </select>
                    @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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

            {{-- Label & Amount --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Libellé *</label>
                    <input type="text" wire:model="label" placeholder="Ex: Achat fournitures bureau" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    @error('label') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Montant (MAD) *</label>
                    <input type="number" step="0.01" wire:model="amount" placeholder="0.00" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Date & Mode --}}
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Date *</label>
                    <input type="date" wire:model="date_transaction" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    @error('date_transaction') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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

            {{-- Bénéficiaire --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Bénéficiaire / Fournisseur</label>
                <input type="text" wire:model="beneficiaire" placeholder="Nom du bénéficiaire ou fournisseur" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Description / Justification</label>
                <textarea wire:model="description" rows="3" placeholder="Détails de la transaction, justification..." class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none resize-none"></textarea>
            </div>

            {{-- Attachments --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">
                    <i class="ri-attachment-line mr-1"></i> Pièces jointes (reçu, facture, bilan, photo...)
                </label>
                <input type="file" wire:model="attachments" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-400 mt-1">Formats: images, PDF, Word, Excel. Max 10 Mo par fichier.</p>
                @error('attachments.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                @if($attachments)
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($attachments as $idx => $file)
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">
                        <i class="ri-file-line"></i> {{ $file->getClientOriginalName() }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.finance.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Annuler</a>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">
                    <i class="ri-save-line mr-1"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
