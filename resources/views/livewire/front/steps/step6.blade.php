<div>
    <div class="parent-table-11 mt-4">
        <div class="table-11">
            <p class="block disc mb-2">{{ __('messages.frais_prevus') }}</p>
            <table class="table-auto border border-gray-300 w-full">
                <thead>
                    <tr>
                        <th class="border px-2 py-3 title-table">{{ __('messages.les_frais') }}</th>
                        <th class="border px-2 py-3 title-table">{{ __('messages.premier_annee') }}</th>
                        <th class="border px-2 py-3 title-table">{{ __('messages.deuxieme_annee') }}</th>
                        <th class="border px-2 py-3 title-table">{{ __('messages.troisieme_annee') }}</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                  {{ __('messages.achats') }}

                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="achat_prevue_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="achat_prevue_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="achat_prevue_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                 {{ __('messages.frais_fonctionnement') }}
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="frais_fonctionnement_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="frais_fonctionnement_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="frais_fonctionnement_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                {{ __('messages.charges_personnel') }}
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="charges_personnel_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="charges_personnel_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="charges_personnel_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                {{ __('messages.dettes') }}
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="dettes_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="dettes_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="dettes_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                {{ __('messages.etablissement_bancaire') }}
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="etablissement_bancaire_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="etablissement_bancaire_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="etablissement_bancaire_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                Fournisseurs
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="fournisseurs_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="fournisseurs_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="fournisseurs_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                Autres dettes
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="autres_dettes_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="autres_dettes_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="autres_dettes_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                Autres charges
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="autres_charges_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="autres_charges_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="autres_charges_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>                                                                        
                        
                </tbody>
            </table>
        </div>

    </div>
    <div class="parent-table-12 mt-4">
        <div class="table-12">
            <p class="block disc mb-2">Résultat ?</p>
            <table class="table-auto  border-gray-300 w-full">
                <thead>
                    <tr>
                        <th class="border px-2 py-3 border-0"></th>
                        <th class="border px-2 py-3 title-table">Premier année</th>
                        <th class="border px-2 py-3 title-table">Deuxième année</th>
                        <th class="border px-2 py-3 title-table">Troisième année</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                  Revenus

                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="revenus_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="revenus_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="revenus_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                Dépenses
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="depenses_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="depenses_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="depenses_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                Résultat (Revenus - Dépenses)
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="resultat_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="resultat_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="resultat_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>

    </div>
    <div class="field-generer_profits mt-5 mb-2">
        <label for="generer_profits" class="block disc mb-2 font-semibold">Quand le projet commencera-t-il à générer des profits ? (Analyse des événements de rupture)</label>
        <textarea class="form-control" id="generer_profits" wire:model="generer_profits"  rows="9" @if($isReadOnly) readonly @endif></textarea>
    </div>
    <div class="field-projet_durable mt-5 mb-2">
        <label for="projet_durable" class="block disc mb-2 font-semibold">Dans quelle mesure le projet est-il durable et stable ? (Analyse coûts-avantages)</label>
        <textarea class="form-control" id="projet_durable" wire:model="projet_durable"  rows="9" @if($isReadOnly) readonly @endif></textarea>
    </div>

</div>