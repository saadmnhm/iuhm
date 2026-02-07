<div>
    <div class="field-project-description mt-4">
        <label class="disc mb-2">10. Fonctions Envisagées</label>
        <div class="function_envisage">
            <div class="row mt-3">
                @php
                    $fonctions = [
                        'Direction générale', 'Marketing et vente', 'Gestion de projet', 'Conseil',
                        'Ressources humaines', 'Finance et comptabilité', 'Production', 'Logistique',
                        'Recherche et développement', 'Communication', 'Informatique', 'Qualité',
                        'Achats', 'Commerce international', 'Formation', 'Administration',
                        'Juridique', 'Autre'
                    ];
                @endphp
                @foreach($fonctions as $fonction)
                <div class="col-6">
                    <input type="checkbox" wire:model="fonctions_envisagees" value="{{ $fonction }}" @if($isReadOnly) disabled @endif>
                    <label>
                        <span class="disc_p">{{ $fonction }}</span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="field-project-description mt-4">
        <label class="disc mb-2">11. Que représente le travail pour Vous</label>
        <div class="représente_travail my-3">
            @php
                $representations = [
                    'Le moyen d\'exercer les responsabilités', 'Un engagement personnel',
                    'Un investissement', 'Le moyen de préparer l\'avenir',
                    'Un moyen de me valoriser', 'Un outil de promotion social',
                    'Un moyen de gagner beaucoup d\'argent',
                    'Un moyen d\'assurer ma subsistance (et celle de ma famille)',
                    'La sécurité', 'Une nécessité', 'Autre'
                ];
            @endphp
            @foreach($representations as $item)
            <div>
                <input type="checkbox" wire:model="representation_travail" value="{{ $item }}" @if($isReadOnly) disabled @endif>
                <label>
                    <span class="disc_p">{{ $item }}</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
</div>
