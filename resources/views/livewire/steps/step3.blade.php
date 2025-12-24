<div>
    <div class="fourd-part">
        <h3>4. Stratégie marketing</h3>
        <p>(Cette section explique comment commercialiser le produit ou le service. Vos efforts marketing doivent être axés sur les publics cibles.)</p>
        
        <label for="méthodes_marketing">Quelles méthodes marketing allez-vous utiliser ?</label>
        <input class="form-control" id="méthodes_marketing" wire:model="méthodes_marketing" >

        <label for="adaptation_methodes">Ces méthodes sont-elles adaptées à votre public cible (expliquez) ?</label>
        <input class="form-control" id="adaptation_methodes" wire:model="adaptation_methodes" >

        <label for="differenciation_marketing">Comment vous différencier de vos concurrents en matière de marketing ?</label>
        <input class="form-control" id="differenciation_marketing" wire:model="differenciation_marketing" >

    </div>

    <div class="part-five">
        <h3>5- Entreprise et personnel.</h3>
        <p>(Le projet nécessite du personnel, et cette section déterminera le nombre d'employés requis. Il est également nécessaire d'anticiper l'arrivée de nouveaux produits ou services, ce qui augmentera les besoins en personnel.)</p>
        <p>De combien d'utilisateurs aurez-vous besoin ?</p>
        <div class="table-part-five mt-4">

            <table class="table-auto border border-gray-300 w-full">
                <thead>
                    <tr>
                        <th rowspan="2" class="border px-3 py-2">Titre d'emploi</th>
                        <th colspan="2" class="border px-3 py-2 text-center">nombre</th>
                    </tr>
                    <tr>
                        <th class="border px-3 py-2">Première année</th>
                        <th class="border px-3 py-2">deuxième année</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($table2Rows as $index => $row)
                        <tr>
                            <td class="border px-2 py-1">
                                <input type="text" wire:model.live="table2Rows.{{ $index }}.item" class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model.live="" min="0" step="0.01" class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model.live="table2Rows.{{ $index }}.price_2" min="0" step="0.01" class="form-control border w-full p-1">
                            </td>

                        
                        </tr>
                    @endforeach

                    {{-- TOTAL ROW --}}
                    <tr class="bg-gray-100 font-bold">
                        <td  class="border px-3 py-2 text-right">
                            Total
                        </td>
                        <td class="border px-3 py-2">
                            {{ number_format($this->total1, 2) }} MAD
                        </td>
                        <td class="border px-3 py-2">
                            {{ number_format($this->total2, 2) }} MAD
                        </td>
                        <td class="border"></td>
                    </tr>
                </tbody>
            </table>

            <button wire:click="addTable2Row" class="mt-3  text-black px-4 py-2 rounded">+ Add Row</button>
        </div>



    </div>

    <div class="part-six">
        <h3>5- Le calendrier</h3>
        <p>(Cette section vise à définir un échéancier pour que les travaux soient réalisés avec précision et perfection afin de mettre en œuvre le produit ou le service fourni.)</p>
        <div class="table-part-six mt-4">
            <table class="table-auto border border-gray-300 w-full">
                <thead>
                    <tr>
                        <th  class="border px-3 py-2">Tâche</th>
                        <th  class="border px-3 py-2 text-center">Date / Échéance prévue</th>
                    </tr>
                
                </thead>

                <tbody>
                        <tr>
                            <td class="border px-2 py-1">
                                Finalisation du plan d'affaires
                            </td>

                            <td class="border px-2 py-1">
                                <input type="text" wire:model="plan_affaires"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                Obtention du financement
                            </td>

                            <td class="border px-2 py-1">
                                <input type="text" wire:model="obtention_financement"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                L’ouverture du procès
                            </td>

                            <td class="border px-2 py-1">
                                <input type="text" wire:model="ouverture_proces"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                Lancement du recrutement
                            </td>

                            <td class="border px-2 py-1">
                                <input type="text" wire:model="lancement_recrutement"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                Ouverture définitive
                            </td>

                            <td class="border px-2 py-1">
                                <input type="text" wire:model="ouverture_definitive"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                Durée
                            </td>

                            <td class="border px-2 py-1">
                                <input type="text" wire:model="duree"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>