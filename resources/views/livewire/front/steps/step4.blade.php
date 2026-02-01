<div>
    <div class="part-7">
        <h3 class="step-title">{{ __('messages.section_technique') }}</h3>
        <p class="instructions">{{ __('messages.instruction_technique') }}</p>

        <div class="field-lieu_projet mb-2">
            <label for="lieu_projet" class="block disc mb-2 font-semibold">{{ __('messages.lieu_projet') }}</label>
            <input type="text" id="lieu_projet" wire:model="lieu_projet"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
        </div>

        <div class="field-adaptation_lieu mb-2">
            <label  for="adaptation_lieu" class="block disc mb-2 font-semibold">{{ __('messages.adaptation_lieu') }}</label>
            <input type="text" id="adaptation_lieu" wire:model="adaptation_lieu"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
        </div>

        <p class="instructions">{{ __('messages.materiaux_necessaires') }}</p>
        <p class="instructions">{{ __('messages.outils_necessaires') }}</p>
       
        <hr class="my-4">

        <div class="parent-table-six">
            <p class="block disc mb-2">{{ __('messages.comment_presenter_produits') }}</p>
            <div class="table-part-six mt-4">
                <table class="table-auto border border-gray-300 w-full">
                    <thead>
                        <tr>
                            <th  class="border px-3 py-2 title-table">{{ __('messages.nom_produit') }}</th>
                            <th  class="border px-3 py-2 text-center title-table">{{ __('messages.mode_presentation') }}</th>
                        </tr>
                    
                    </thead>

                    <tbody>
                        @foreach($table3Rows as $index => $row)
                            <tr>
                                <td class="border px-2 py-3 ">
                                    <input type="text"   wire:model="table3Rows.{{ $index }}.product_name_presentation" class="border p-1 w-full" @if($isReadOnly) readonly @endif>
                                </td>

                                <td class="border px-2 py-3 ">
                                    <input type="text"   wire:model="table3Rows.{{ $index }}.presentation_methode" class="border p-1 w-full" @if($isReadOnly) readonly @endif>
                                </td>
                            </tr>
                            
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2">
                    @if(!$isReadOnly)
                    <button wire:click.prevent="addTable3Row" class="more-row">{{ __('messages.ajouter_lignes') }}</button>
                    @endif
                </div>
            </div> 
        </div>
        <hr class="my-4">
        <div class="parent-table-7">
            <p class="block disc mb-2">{{ __('messages.comment_evaluer_distribution') }}</p>
            <div class="table-part-7 mt-4">
                <table class="table-auto border border-gray-300 w-full">
                    <thead>
                        <tr>
                            <th  class="border px-3 py-2 title-table">{{ __('messages.nom_produit_service') }}</th>
                            <th  class="border px-3 py-2 text-center title-table">{{ __('messages.mode_livraison') }}</th>
                        </tr>
                    
                    </thead>

                    <tbody>
                        @foreach($table4Rows as $index => $row)
                            <tr>
                                <td class="border px-2 py-3 ">
                                    <input type="text"   wire:model="table4Rows.{{ $index }}.product_name_livraison" class="border p-1 w-full" @if($isReadOnly) readonly @endif>
                                </td>

                                <td class="border px-2 py-3 ">
                                    <input type="text"   wire:model="table4Rows.{{ $index }}.livraison_methode" class="border p-1 w-full" @if($isReadOnly) readonly @endif>
                                </td>
                            </tr>
                            
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2">
                    @if(!$isReadOnly)
                    <button wire:click.prevent="addTable4Row" class="more-row">{{ __('messages.ajouter_lignes') }}</button>
                    @endif
                </div>
            </div>  
        </div>

        <hr class="my-4">

        <div class="field-benefices_from_projet mb-2">
            <label for="benefices_from_projet" class="block disc mb-2 font-semibold">{{ __('messages.comment_percevoir_benefices') }}</label>
            <textarea id="benefices_from_projet" wire:model="benefices_from_projet" class="border p-1 w-full" @if($isReadOnly) readonly @endif></textarea>
        </div>
        <div class="field-valeur_projet mb-2">
            <label for="valeur_projet" class="block disc mb-2 font-semibold">{{ __('messages.valeur_retirerez') }}</label>
            <textarea id="valeur_projet" wire:model="valeur_projet" class="border p-1 w-full" @if($isReadOnly) readonly @endif></textarea>
        </div>



    </div>
</div>