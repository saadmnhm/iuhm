<div>
    <div class="part-7">
        <h3>7- Section technique</h3>
        <p>(Cette section vise à présenter un aperçu du processus de production du produit ou du service.)</p>

        <label for="lieu_projet">Où se situe le projet ?</label>
        <input type="text" id="lieu_projet" wire:model="lieu_projet"  class="form-control border w-full p-1">

        <label for="adaptation_lieu">* Le lieu du projet est-il adapté au public cible ?</label>
        <input type="text" id="adaptation_lieu" wire:model="adaptation_lieu"  class="form-control border w-full p-1">

        <p>De quels matériaux aurez-vous besoin pour fabriquer le produit ou fournir le service ? Complétez le tableau en annexe.</p>
        <p>De quels outils et équipements aurez-vous besoin pour réaliser le travail ? Complétez le tableau en annexe.</p>
        <div class="parent-table-six">
            <p>Comment livrerez-vous vos produits ou services ?</p>
            <div class="table-part-six mt-4">
                <table class="table-auto border border-gray-300 w-full">
                    <thead>
                        <tr>
                            <th  class="border px-3 py-2">Nom du produit</th>
                            <th  class="border px-3 py-2 text-center">Mode de presentation</th>
                        </tr>
                    
                    </thead>

                    <tbody>
                        @foreach($table3Rows as $index => $row)
                            <tr>
                                <td class="border px-2 py-1">
                                    <input type="text"   wire:model="table3Rows.{{ $index }}.product_name_presentation" class="border p-1 w-full" >
                                </td>

                                <td class="border px-2 py-1">
                                    <input type="text"   wire:model="table3Rows.{{ $index }}.presentation_methode" class="border p-1 w-full" >
                                </td>
                            </tr>
                            
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2">
                    <button wire:click.prevent="addTable3Row" class="text-black px-4 py-2 rounded">Add More Row</button>
                </div>
            </div> 
        </div>
        
        <div class="parent-table-7">
            <p>Comment évaluerez-vous la distribution ?</p>
            <div class="table-part-7 mt-4">
                <table class="table-auto border border-gray-300 w-full">
                    <thead>
                        <tr>
                            <th  class="border px-3 py-2">Nom du produit/service</th>
                            <th  class="border px-3 py-2 text-center">Mode de livraison</th>
                        </tr>
                    
                    </thead>

                    <tbody>
                        @foreach($table4Rows as $index => $row)
                            <tr>
                                <td class="border px-2 py-1">
                                    <input type="text"   wire:model="table4Rows.{{ $index }}.product_name_livraison" class="border p-1 w-full" >
                                </td>

                                <td class="border px-2 py-1">
                                    <input type="text"   wire:model="table4Rows.{{ $index }}.livraison_methode" class="border p-1 w-full" >
                                </td>
                            </tr>
                            
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2">
                    <button wire:click.prevent="addTable4Row" class="text-black px-4 py-2 rounded">Add More Row</button>
                </div>
            </div>  
        </div>

        <label for="benefices_from_projet">Comment percevrez-vous les bénéfices ?</label>
        <textarea id="benefices_from_projet" wire:model="benefices_from_projet" class="border p-1 w-full" ></textarea>

        <label for="valeur_projet">* Quelle valeur en retirerez-vous ? Uniquement les bénéfices ?</label>
        <textarea id="valeur_projet" wire:model="valeur_projet" class="border p-1 w-full" ></textarea>



    </div>
</div>