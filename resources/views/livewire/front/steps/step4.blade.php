<div>
    <div class="part-7">
        <h3 class="step-title">7- Section technique</h3>
        <p class="instructions">(Cette section vise à présenter un aperçu du processus de production du produit ou du service.)</p>

        <div class="field-lieu_projet mb-2">
            <label for="lieu_projet" class="block disc mb-2 font-semibold">Où se situe le projet ?</label>
            <input type="text" id="lieu_projet" wire:model="lieu_projet"  class="form-control border w-full p-1">
        </div>

        <div class="field-adaptation_lieu mb-2">
            <label  for="adaptation_lieu" class="block disc mb-2 font-semibold">* Le lieu du projet est-il adapté au public cible ?</label>
            <input type="text" id="adaptation_lieu" wire:model="adaptation_lieu"  class="form-control border w-full p-1">
        </div>

        <p class="instructions">De quels matériaux aurez-vous besoin pour fabriquer le produit ou fournir le service ? Complétez le tableau en annexe. étape(7/8)</p>
        <p class="instructions">De quels outils et équipements aurez-vous besoin pour réaliser le travail ? Complétez le tableau en annexe. étape(8/8)</p>
       
        <hr class="my-4">

        <div class="parent-table-six">
            <p class="block disc mb-2">Comment Presenterez-vous vos produits ou services ?</p>
            <div class="table-part-six mt-4">
                <table class="table-auto border border-gray-300 w-full">
                    <thead>
                        <tr>
                            <th  class="border px-3 py-2 title-table">Nom du produit</th>
                            <th  class="border px-3 py-2 text-center title-table">Mode de presentation</th>
                        </tr>
                    
                    </thead>

                    <tbody>
                        @foreach($table3Rows as $index => $row)
                            <tr>
                                <td class="border px-2 py-3 ">
                                    <input type="text"   wire:model="table3Rows.{{ $index }}.product_name_presentation" class="border p-1 w-full" >
                                </td>

                                <td class="border px-2 py-3 ">
                                    <input type="text"   wire:model="table3Rows.{{ $index }}.presentation_methode" class="border p-1 w-full" >
                                </td>
                            </tr>
                            
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2">
                    <button wire:click.prevent="addTable3Row" class="more-row">Ajouter des lignes</button>
                </div>
            </div> 
        </div>
        <hr class="my-4">
        <div class="parent-table-7">
            <p class="block disc mb-2">Comment évaluerez-vous la distribution ?</p>
            <div class="table-part-7 mt-4">
                <table class="table-auto border border-gray-300 w-full">
                    <thead>
                        <tr>
                            <th  class="border px-3 py-2 title-table">Nom du produit/service</th>
                            <th  class="border px-3 py-2 text-center title-table">Mode de livraison</th>
                        </tr>
                    
                    </thead>

                    <tbody>
                        @foreach($table4Rows as $index => $row)
                            <tr>
                                <td class="border px-2 py-3 ">
                                    <input type="text"   wire:model="table4Rows.{{ $index }}.product_name_livraison" class="border p-1 w-full" >
                                </td>

                                <td class="border px-2 py-3 ">
                                    <input type="text"   wire:model="table4Rows.{{ $index }}.livraison_methode" class="border p-1 w-full" >
                                </td>
                            </tr>
                            
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2">
                    <button wire:click.prevent="addTable4Row" class="more-row">Ajouter des lignes</button>
                </div>
            </div>  
        </div>

        <hr class="my-4">

        <div class="field-benefices_from_projet mb-2">
            <label for="benefices_from_projet" class="block disc mb-2 font-semibold">Comment percevrez-vous les bénéfices ?</label>
            <textarea id="benefices_from_projet" wire:model="benefices_from_projet" class="border p-1 w-full" ></textarea>
        </div>
        <div class="field-valeur_projet mb-2">
            <label for="valeur_projet" class="block disc mb-2 font-semibold">* Quelle valeur en retirerez-vous ? Uniquement les bénéfices ?</label>
            <textarea id="valeur_projet" wire:model="valeur_projet" class="border p-1 w-full" ></textarea>
        </div>



    </div>
</div>