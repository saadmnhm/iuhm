<div>
    <div class="fourd-part">
        <h3 class="step-title">4. Stratégie marketing</h3>
        <p class="instructions">(Cette section explique comment commercialiser le produit ou le service. Vos efforts marketing doivent être axés sur les publics cibles.)</p>

        <div class="field-méthodes_marketing mb-2">
            <label class="block disc mb-2 font-semibold" for="méthodes_marketing">Quelles méthodes marketing allez-vous utiliser ?</label>
            <input class="form-control" id="méthodes_marketing" wire:model="méthodes_marketing" >
        </div>

        <div class="field-adaptation_methodes mb-2">
            <label class="block disc mb-2 font-semibold"  for="adaptation_methodes">Ces méthodes sont-elles adaptées à votre public cible (expliquez) ?</label>
            <input class="form-control" id="adaptation_methodes" wire:model="adaptation_methodes" >
        </div>
        <div class="field-differenciation_marketing mb-2">
            <label class="block disc mb-2 font-semibold" for="differenciation_marketing">Comment vous différencier de vos concurrents en matière de marketing ?</label>
            <input class="form-control" id="differenciation_marketing" wire:model="differenciation_marketing" >
        </div>

    </div>

    <div class="part-five">
        <h3 class="step-title" >5- Entreprise et personnel.</h3>
        <p class="instructions">(Le projet nécessite du personnel, et cette section déterminera le nombre d'employés requis. Il est également nécessaire d'anticiper l'arrivée de nouveaux produits ou services, ce qui augmentera les besoins en personnel.)</p>
        <p class="block disc mb-2">De combien d'utilisateurs aurez-vous besoin ?</p>
        <div class="table-part-five mt-4">

            <table class="table-auto  border-gray-300 w-full">
                <thead>
                    <tr>
                        <th rowspan="2" class="border px-3 py-2 title-table">Titre d'emploi</th>
                        <th colspan="2" class="border px-3 py-2 text-center title-table">nombre</th>
                    </tr>
                    <tr>
                        <th class="border px-3 py-2 title-table">Première année</th>
                        <th class="border px-3 py-2 title-table">deuxième année</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($table2Rows as $index => $row)
                        <tr>
                            <td class="border px-2 py-1">
                                <input type="text" wire:model.live="table2Rows.{{ $index }}.item" class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model.live="table2Rows.{{ $index }}.price_1" min="0" step="0.01" class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model.live="table2Rows.{{ $index }}.price_2" min="0" step="0.01" class="form-control border w-full p-1">
                            </td>

                        
                        </tr>
                    @endforeach

                    {{-- TOTAL ROW --}}
                    <tr class="bg-gray-100 font-bold">
                        <td  class="border px-3 py-2 text-right title-table">
                            Total
                        </td>
                        <td class="border px-3 py-2">
                            {{ number_format($this->total1, 2) }} MAD
                        </td>
                        <td class="border px-3 py-2">
                            {{ number_format($this->total2, 2) }} MAD
                        </td>
                    </tr>
                </tbody>
            </table>

            <button wire:click="addTable2Row" class="more-row">Ajouter des lignes</button>
        </div>



    </div>

    <div class="part-six">
        <h3 class="step-title mt-3">5- Le calendrier</h3>
        <p class="instructions">(Cette section vise à définir un échéancier pour que les travaux soient réalisés avec précision et perfection afin de mettre en œuvre le produit ou le service fourni.)</p>
        <div class="table-part-six mt-4">
            <table class="table-auto  border-gray-300 w-full">
                <thead>
                    <tr>
                        <th  class="border px-3 py-2 title-table">Tâche</th>
                        <th  class="border px-3 py-2 text-center title-table">Date / Échéance prévue</th>
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
                            <td class="border px-2 py-1 title-table">
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