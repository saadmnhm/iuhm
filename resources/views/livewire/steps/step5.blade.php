<div>
    <div class="part-8">
        <h3>8- Capacités</h3>
        <p>(Cette section vise à évaluer le niveau de difficulté du projet et votre capacité à le mener à bien.)</p>
        
        <label for="step_8_1">* Possédez-vous les compétences nécessaires à la réalisation du projet ?</label>
        <input type="text" id="step_8_1" wire:model="step_8_1"  class="form-control border w-full p-1">

        <label for="step_8_2">* Pouvez-vous fournir le matériel et les outils nécessaires à la réalisation du projet ?</label>
        <input type="text" id="step_8_2" wire:model="step_8_2"  class="form-control border w-full p-1">

        <label for="step_8_3">* Avez-vous l’expérience nécessaire pour mener à bien le projet ?</label>
        <input type="text" id="step_8_3" wire:model="step_8_3"  class="form-control border w-full p-1">

        <label for="step_8_4">* Pouvez-vous obtenir les fonds nécessaires au démarrage du projet ?</label>
        <input type="text" id="step_8_4" wire:model="step_8_4"  class="form-control border w-full p-1">



    </div>
    <div class="parent-table-9 mt-4">
        <p>Quels sont les principaux risques liés au projet et comment comptez-vous les atténuer ?</p>
        <div class="table-9">
            <h3>10- Prévisions financières</h3>
            <p>Programme d'investissement</p>
            <table class="table-auto border border-gray-300 w-full">
                <tbody>
                        <tr>
                            <td class="border px-2 py-1">
                                Coûts de création d'entreprise

                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="couts_creation"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                Préparation de l'entreprise
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="preparation_entreprise"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                Achat de machines
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="achat_machines"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                Achat de matières premières (six mois)
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="achat_matieres_premieres"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                Autres coûts (six mois)
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="autres_couts"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                Total
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="total"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>

    </div>
    
    <div class="parent-table-10 mt-4">
        <p>Quels sont les principaux risques liés au projet et comment comptez-vous les atténuer ?</p>
        <div class="table-10">
            <h3>10- Prévisions financières</h3>
            <p>Programme d'investissement</p>
            <table class="table-auto border border-gray-300 w-full">
                <thead>
                    <tr>
                        <th class="border px-2 py-1">Quelles sont les prévisions de revenus</th>
                        <th class="border px-2 py-1">Premier année</th>
                        <th class="border px-2 py-1">Deuxième année</th>
                        <th class="border px-2 py-1">Troisième année</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td class="border px-2 py-1">
                                 Ventes

                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="ventes_premiere_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="ventes_deuxieme_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="ventes_troisieme_annee"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                 Services
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="services_premiere_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="services_deuxieme_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="services_troisieme_annee"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                aide financière
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="aide_financiere_premiere_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="aide_financiere_deuxieme_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="aide_financiere_troisieme_annee"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                Revenus financiers
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="revenus_financiers_premiere_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="revenus_financiers_deuxieme_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="revenus_financiers_troisieme_annee"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                Autres revenus
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="autres_revenus_premiere_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="autres_revenus_deuxieme_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="autres_revenus_troisieme_annee"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                total
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="total_premiere_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="total_deuxieme_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="total_troisieme_annee"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>

    </div> 
</div>