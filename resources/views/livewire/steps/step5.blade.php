<div>
    <div class="part-8">
        <h3 class="step-title">8- Capacités</h3>
        <p class="instructions">(Cette section vise à évaluer le niveau de difficulté du projet et votre capacité à le mener à bien.)</p>

        <div class="field-step_8_1 mb-2">
            <label for="step_8_1" class="block disc mb-2 font-semibold" >* Possédez-vous les compétences nécessaires à la réalisation du projet ?</label>
            <input type="text" id="step_8_1" wire:model="step_8_1"  class="form-control border w-full p-1">
        </div>

        <div class="field-step_8_2 mb-2">
            <label for="step_8_2" class="block disc mb-2 font-semibold" >* Pouvez-vous fournir le matériel et les outils nécessaires à la réalisation du projet ?</label>
            <input type="text" id="step_8_2" wire:model="step_8_2"  class="form-control border w-full p-1">
        </div>
        
        <div class="field-step_8_3 mb-2">
            <label for="step_8_3" class="block disc mb-2 font-semibold" >* Avez-vous l’expérience nécessaire pour mener à bien le projet ?</label>
            <input type="text" id="step_8_3" wire:model="step_8_3"  class="form-control border w-full p-1">
        </div>

        <div class="field-step_8_4 mb-2">
            <label for="step_8_4" class="block disc mb-2 font-semibold" >* Pouvez-vous obtenir les fonds nécessaires au démarrage du projet ?</label>
            <input type="text" id="step_8_4" wire:model="step_8_4"  class="form-control border w-full p-1">
        </div>

    </div>

    <div class="parent-table-9 mt-4">
        <div class="table-9">
            <h3  class="step-title">9- Prévisions financières</h3>
            <p class="block disc mb-2">Programme d'investissement</p>
            <table class="table-auto border border-gray-300 w-full">
                <tbody>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                Coûts de création d'entreprise

                            </td>

                            <td class="border px-2 py-1 ">
                                <input type="number" wire:model="couts_creation"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                Préparation de l'entreprise
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="preparation_entreprise"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                Achat de machines
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="achat_machines"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                Achat de matières premières (six mois)
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model="achat_matieres_premieres"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                Autres coûts (six mois)
                            </td>

                            <td class="border px-2 py-3 ">
                                <input type="number" wire:model="autres_couts"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                Total
                            </td>

                            <td class="border px-2 py-3 ">
                                <input type="number" wire:model="total"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>

    </div>
    
    <div class="parent-table-10 mt-4">
        <div class="table-10">
            <h3 class="step-title">10- Prévisions financières</h3>
            <p class="block disc mb-2">Programme d'investissement</p>
            <table class="table-auto border border-gray-300 w-full">
                <thead>
                    <tr>
                        <th class="border px-2 py-3 title-table">Quelles sont les prévisions de revenus</th>
                        <th class="border px-2 py-3 title-table">Premier année</th>
                        <th class="border px-2 py-3 title-table">Deuxième année</th>
                        <th class="border px-2 py-3 title-table">Troisième année</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                 Ventes

                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="ventes_premiere_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="ventes_deuxieme_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="ventes_troisieme_annee"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                 Services
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="services_premiere_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="services_deuxieme_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="services_troisieme_annee"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table" >
                                aide financière
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="aide_financiere_premiere_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="aide_financiere_deuxieme_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="aide_financiere_troisieme_annee"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                Revenus financiers
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="revenus_financiers_premiere_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="revenus_financiers_deuxieme_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="revenus_financiers_troisieme_annee"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                Autres revenus
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="autres_revenus_premiere_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="autres_revenus_deuxieme_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="autres_revenus_troisieme_annee"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                total
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="total_premiere_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="total_deuxieme_annee"  class="form-control border w-full p-1">
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model="total_troisieme_annee"  class="form-control border w-full p-1">
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>

    </div> 
</div>