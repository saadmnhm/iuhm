<div class="step-2">
    <style>

    </style>
    <div class="second-part mt-4">
        <h3>2- Description des produits et services</h3>
        <p>Dans cette section, vous fournirez une description détaillée des produits ou services que vous proposerez. L'objectif de cette section est de fournir une description détaillée de votre offre</p>
        <h4>* Quels produits ou services proposerez-vous ?</h4>

        <table class="table-auto border-collapse border border-gray-300 w-full">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Nom du produit ou du service</th>
                    <th class="border px-4 py-2">Description</th>
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
            <button wire:click.prevent="addTable1Row" class="text-black px-4 py-2 rounded">Add More Row</button>
            <button wire:click.prevent="save" class="text-black px-4 py-2 rounded">Save</button>
        </div>
    </div>
    <div class="third-part">
        <h3>3- Marché des produits/services</h3>
        <p>(Cette section décrit le marché actuel des produits ou services proposés par l'entreprise.)</p>

        <label for="public_cible">*Quel est le public cible ?</label>
        <input class="form-control" id="public_cible" wire:model="public_cible" >

        <label for="concurrent">* Qui sont les concurrents potentiels ?</label>
        <input class="form-control" id="concurrent" wire:model="concurrent" >

        <label for="volume_produits_locaux">* Le volume de produits locaux par rapport au volume de produits importés.</label>
        <input class="form-control" id="volume_produits_locaux" wire:model="volume_produits_locaux" >

        <label for="volume_demande">* Le volume de la demande des années précédentes.</label>
        <input class="form-control" id="volume_demande" wire:model="volume_demande" >

        <label for="demande_offre">* La demande est-elle supérieure à l'offre, ou inversement ?</label>
        <input class="form-control" id="demande_offre" wire:model="demande_offre" >

        <label for="motivations_achat">* Quelles sont les motivations d'achat des clients ?</label>
        <input class="form-control" id="motivations_achat" wire:model="motivations_achat" >

        <label for="raison_choix_client">* Pourquoi le client vous choisirait-il (qu'est-ce qui vous différencie de vos concurrents) ?</label>
        <input class="form-control" id="raison_choix_client" wire:model="raison_choix_client" >


    </div>
    
    <div class="mt-4">
       
    </div>
</div>