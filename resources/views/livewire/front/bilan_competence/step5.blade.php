<div>

    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="project-description">12. Quelles vous-pouvez contraintes accepter  </label>
        <table class="table-auto  border-gray-300 w-full">
            <thead>
                <tr>
                    <th class="border px-2 py-3 title-table"></th>
                    <th class="border px-2 py-3 title-table">Oui</th>
                    <th class="border px-2 py-3 title-table">Non</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td class="border px-2 py-3 title-table">
                                fréquents Déplacements

                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model.live="revenus_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model.live="revenus_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        
                    </tr>
                    <tr>
                        <td class="border px-2 py-3 title-table">
                            lointains Déplacements
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model.live="depenses_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model.live="depenses_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        
                    </tr>
                    <tr>
                        <td class="border px-2 py-3 title-table">
                                irréguliers Horaires
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model="resultat_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model="resultat_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        
                    </tr>
                    <tr>
                        <td class="border px-2 py-3 title-table">
                                irréguliers travail de  Jours 
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model="resultat_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model="resultat_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        
                    </tr>
                    <tr>
                        <td class="border px-2 py-3 title-table">
                                remplir à chiffrés Objectifs
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model="resultat_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model="resultat_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        
                    </tr>
                    <tr>
                        <td class="border px-2 py-3 title-table">
                                commission la à Salaire
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model="resultat_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model="resultat_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        
                    </tr>
                    <tr>
                        <td class="border px-2 py-3 title-table">
                                Participation à des obligations mondaines ou sociales
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model="resultat_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model="resultat_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        
                    </tr>
                    <tr>
                        <td class="border px-2 py-3 title-table">
                                Salaire fixe + commission
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model="resultat_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        <td class="border px-2 py-3">
                            <input type="number" wire:model="resultat_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                        </td>

                        
                    </tr>
            </tbody>
        </table>
    </div>
    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="project-description">13. Définissez vos  exigences</label>
        <div class="représente_travail my-3">
                <div>
                    <p class="disc_p">Salaire élevé</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Salaire équitable</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
            

                <div>
                    <p class="disc_p">Environnement de travail accueillant</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Prestations sociales attrayantes</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Possibilité de promotion</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Possibilité de formation continue</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Responsabilités importantes</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Réguliers Horaires réguliers</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Horaires a la carte</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Travail en équipe</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Grande marge d'autonomie</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Taches variées</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Affinité de caractère avec le supérieur hierarchique</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>

                <div>
                    <p class="disc_p">Déplacements dans le Maroc</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Déplacements à l'international</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Travail avec des objectifs mesurables mesurés et clairs</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Calme Travail</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Travail stressant</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
                <div>
                    <p class="disc_p">Autre</p>
                    <label for="">OUI</label>
                    <input type="checkbox" name="test" id="">
                    
                </div>
           
        </div>
    </div>  

    <div class="complete-form">

        <p style="font-size: 2rem;">Tu as terminé ton bilan de compétences !</p>
        <p style="font-size: 1rem;">Merci pour ta participation. Tu peux désormais réfléchir à un ou plusieurs objectifs professionnels précis</p>
    </div>
    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="project-description">Mes réflexions</label>
        <textarea class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif></textarea>
        @error('description') <span>{{ $message }}</span> @enderror
    </div>


</div>