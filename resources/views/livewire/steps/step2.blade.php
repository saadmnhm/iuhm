<div class="step-2">
    <style>

    </style>
    <div class="second-part mt-4">
        <h3 class="step-title">2- Description des produits et services</h3>
        <p class="instructions">Dans cette section, vous fournirez une description détaillée des produits ou services que vous proposerez. L'objectif de cette section est de fournir une description détaillée de votre offre</p>
        <p class="block disc mb-2">* Quels produits ou services proposerez-vous ?</p>

        <table class="table-auto border-collapse border border-gray-300 w-full">
            <thead>
                <tr>
                    <th class="title-table border px-4 py-2">Nom du produit ou du service</th>
                    <th class="title-table border px-4 py-2">Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach($table1Rows as $index => $row)
                    <tr>
                        <td class="border px-4 py-2">
                            <textarea   wire:model="table1Rows.{{ $index }}.product_name" class="border p-1 w-full" ></textarea>
                        </td>
                        <td class="border px-4 py-2">
                            <textarea   wire:model="table1Rows.{{ $index }}.description" class="border p-1 w-full" ></textarea>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-2">
            <button wire:click.prevent="addTable1Row" class="more-row">Ajouter des lignes</button>
        </div>
    </div>
    <div class="third-part">
        <h3 class="step-title">3- Marché des produits/services</h3>
        <p class="instructions">(Cette section décrit le marché actuel des produits ou services proposés par l'entreprise.)</p>

        <div class="field-public_cible mb-4">
            <label for="public_cible" class="block disc mb-2 font-semibold">*Quel est le public cible ?</label>
            <input class="form-control" id="public_cible" wire:model="public_cible" >
        </div>

        <div class="field-concurrent mb-4">
            <label for="concurrent" class="block disc mb-2 font-semibold">* Qui sont les concurrents potentiels ?</label>
            <input class="form-control" id="concurrent" wire:model="concurrent" >
        </div>

        <div class="field-volume_produits_locaux mb-4">
            <label for="volume_produits_locaux" class="block disc mb-2 font-semibold">* Quel est le volume des produits locaux similaires sur le marché ?</label>
            <input class="form-control" id="volume_produits_locaux" wire:model="volume_produits_locaux" >
        </div>

        <div class="field-volume_demande mb-4">
            <label for="volume_demande" class="block disc mb-2 font-semibold">* Le volume de la demande des années précédentes.</label>
            <input class="form-control" id="volume_demande" wire:model="volume_demande" >
        </div>

        <div class="field-demande_offre mb-4">
            <label for="demande_offre" class="block disc mb-2 font-semibold">* La demande est-elle supérieure à l'offre, ou inversement ?</label>
            <input class="form-control" id="demande_offre" wire:model="demande_offre" >
        </div>

        <div class="field-motivations_achat mb-4">
            <label for="motivations_achat" class="block disc mb-2 font-semibold">* Quelles sont les principales motivations d'achat de vos clients ?</label>
            <input class="form-control" id="motivations_achat" wire:model="motivations_achat" >
        </div>

        <div class="field-raison_choix_client mb-4">
            <label for="raison_choix_client" class="block disc mb-2 font-semibold">* Quelles sont les raisons pour lesquelles les clients choisiraient vos produits ou services par rapport à ceux de la concurrence ?</label>
            <input class="form-control" id="raison_choix_client" wire:model="raison_choix_client" >
        </div>

    </div>
    
</div>