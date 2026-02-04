<div>
    <div class="complete-form text-center">
        <p style="text-align: center; font-weight: bold;">Professionnelle Axe : المحور المهني </p>
    </div>

    

    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="project-description">6. Compléter une fiche " Stages " par stage effectué</label>
    </div>
    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="project-description">7. Compléter une fiche "Expériences professionnelles" par expérience travaillée</label>
    </div>
    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="project-description">8. souhaité professionnel Environnement</label>
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
                                Entreprise multinationale

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
                            Grande entreprise marocaine
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
                                Moyenne ou petite Entreprise
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
                                Fonction d’encadrement
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
                                Fonction de spécialité
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
                                Fonction d’assistant
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
                                Fonction de consultant indépendant
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
        <label class=" disc mb-2" for="project-description">9. Secteur D’activité envisagé</label>
        <div class="secteur_envisage">
            <div>
                <p>Environnement et Nature</p>
                <label for="">OUI</label>
                <input type="radio" name="test" id="">
                <label for="">Non</label>
                <input type="radio" name="" id="">
            </div>
            <div>
                <p>Industrie alimentaire</p>
                <label for="">OUI</label>
                <input type="radio" name="test" id="">
                <label for="">Non</label>
                <input type="radio" name="" id="">
            </div>
            <div>
                <p>Textile et habillemen</p>
                <label for="">OUI</label>
                <input type="radio" name="test" id="">
                <label for="">Non</label>
                <input type="radio" name="" id="">
            </div>
            <div>
                <p>Assurances</p>
                <label for="">OUI</label>
                <input type="radio" name="test" id="">
                <label for="">Non</label>
                <input type="radio" name="" id="">
            </div>
            <div>
                <p>Génie civil et travaux public</p>
                <label for="">OUI</label>
                <input type="radio" name="test" id="">
                <label for="">Non</label>
                <input type="radio" name="" id="">
            </div>
            <div>
                <p>Industrie et artisanat technique</p>
                <label for="">OUI</label>
                <input type="radio" name="test" id="">
                <label for="">Non</label>
                <input type="radio" name="" id="">
            </div>
            <div>
                <p>Établissements financiers et banques</p>
                <label for="">OUI</label>
                <input type="radio" name="test" id="">
                <label for="">Non</label>
                <input type="radio" name="" id="">
            </div>
            <div>
                <p>Publicité et communication</p>
                <label for="">OUI</label>
                <input type="radio" name="test" id="">
                <label for="">Non</label>
                <input type="radio" name="" id="">
            </div>
            <div>
                <p>Import et export</p>
                <label for="">OUI</label>
                <input type="radio" name="test" id="">
                <label for="">Non</label>
                <input type="radio" name="" id="">
            </div>
            <div>
                <p>Art et culture</p>
                <label for="">OUI</label>
                <input type="checkbox" name="test" id="">
                
            </div>
            <div>
                <p>Tourisme et hôtellerie</p>
                <label for="">OUI</label>
                <input type="checkbox" name="test" id="">
                
            </div>
            <div>
                <p>Conseil ،audit et expertise</p>
                <label for="">OUI</label>
                <input type="checkbox" name="test" id="">
                
            </div>
            <div>
                <p>Travail social ،enseignement, sante</p>
                <label for="">OUI</label>
                <input type="checkbox" name="test" id="">
                
            </div>
            <div>
                <p>Vente, /commerce/distribution</p>
                <label for="">OUI</label>
                <input type="checkbox" name="test" id="">
                
            </div>
            <div>
                <p>Sécurité et Transport</p>
                <label for="">OUI</label>
                <input type="checkbox" name="test" id="">
                
            </div>
            <div>
                <p>Informatique et ingénierie </p>
                <label for="">OUI</label>
                <input type="checkbox" name="test" id="">
                
            </div>
            <div>
                <p>Science Naturelle</p>
                <label for="">OUI</label>
                <input type="checkbox" name="test" id="">
                
            </div>
            <div>
                <p>Science Humaines</p>
                <label for="">OUI</label>
                <input type="checkbox" name="test" id="">
                
            </div>
           
        </div>
    </div>
   
</div>