<div class="step-2">
    <style>

    </style>
    <div class="second-part mt-4">
        <h3 class="step-title">{{ __('messages.Description_produits_services') }}</h3>
        <p class="instructions">{{ __('messages.instruction2') }}</p>
        <p class="block disc mb-2">{{ __('messages.ervices_proposerez') }}</p>

        <table class="table-auto border-collapse border border-gray-300 w-full">
            <thead>
                <tr>
                    <th class="title-table border px-4 py-2">{{ __('messages.Nom_produit_service') }}</th>
                    <th class="title-table border px-4 py-2">{{ __('messages.description') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($table1Rows as $index => $row)
                    <tr>
                        <td class="border px-4 py-2">
                            <textarea wire:model="table1Rows.{{ $index }}.product_name" class="border p-1 w-full" @if($isReadOnly) readonly @endif></textarea>
                        </td>
                        <td class="border px-4 py-2">
                            <textarea wire:model="table1Rows.{{ $index }}.description" class="border p-1 w-full" @if($isReadOnly) readonly @endif></textarea>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(!$isReadOnly)
        <div class="mt-2">
            <button wire:click.prevent="addTable1Row" class="more-row">{{ __('messages.ajouter_lignes') }}</button>
        </div>
        @endif
    </div>
    <div class="third-part">
        <h3 class="step-title">{{ __('messages.marche_produits_services') }}</h3>
        <p class="instructions">{{ __('messages.instruction3') }}</p>

        <div class="field-public_cible mb-4">
            <label for="public_cible" class="block disc mb-2 font-semibold">{{ __('messages.public_cible') }}</label>
            <input class="form-control" id="public_cible" wire:model="public_cible" @if($isReadOnly) readonly @endif>
        </div>

        <div class="field-concurrent mb-4">
            <label for="concurrent" class="block disc mb-2 font-semibold">{{ __('messages.concurrents_potentiels') }}</label>
            <input class="form-control" id="concurrent" wire:model="concurrent" @if($isReadOnly) readonly @endif>
        </div>

        <div class="field-volume_produits_locaux mb-4">
            <label for="volume_produits_locaux" class="block disc mb-2 font-semibold">{{ __('messages.volume_des_produits_locaux') }}</label>
            <input class="form-control" id="volume_produits_locaux" wire:model="volume_produits_locaux" @if($isReadOnly) readonly @endif>
        </div>

        <div class="field-volume_demande mb-4">
            <label for="volume_demande" class="block disc mb-2 font-semibold">{{ __('messages.volume_demande_annees_precedentes') }}</label>
            <input class="form-control" id="volume_demande" wire:model="volume_demande" @if($isReadOnly) readonly @endif>
        </div>

        <div class="field-demande_offre mb-4">
            <label for="demande_offre" class="block disc mb-2 font-semibold">{{ __('messages.demande_superieure_offre') }}</label>
            <input class="form-control" id="demande_offre" wire:model="demande_offre" @if($isReadOnly) readonly @endif>
        </div>

        <div class="field-motivations_achat mb-4">
            <label for="motivations_achat" class="block disc mb-2 font-semibold">{{ __('messages.motivations_achat') }}</label>
            <input class="form-control" id="motivations_achat" wire:model="motivations_achat" @if($isReadOnly) readonly @endif>
        </div>

        <div class="field-raison_choix_client mb-4">
            <label for="raison_choix_client" class="block disc mb-2 font-semibold">{{ __('messages.raison_choix_client') }}</label>
            <input class="form-control" id="raison_choix_client" wire:model="raison_choix_client" @if($isReadOnly) readonly @endif>
        </div>

    </div>
    
</div>