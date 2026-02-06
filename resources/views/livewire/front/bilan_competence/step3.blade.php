<div>
    <div class="complete-form text-center">
        <p style="text-align: center; font-weight: bold;">Axe Professionnelle : المحور المهني</p>
    </div>

    <div class="field-project-description mt-4">
        <label class="disc mb-2">6. Compléter une fiche "Stages" par stage effectué (voir étape 6)</label>
    </div>
    <div class="field-project-description mt-4">
        <label class="disc mb-2">7. Compléter une fiche "Expériences professionnelles" par expérience travaillée (voir étape 7)</label>
    </div>

    <div class="field-project-description mt-4">
        <label class="disc mb-2">8. Environnement professionnel souhaité</label>
        <table class="table-auto border-gray-300 w-full">
            <thead>
                <tr>
                    <th class="border px-2 py-3 title-table"></th>
                    <th class="border px-2 py-3 title-table">Oui</th>
                    <th class="border px-2 py-3 title-table">Non</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $envItems = [
                        'travail_bureau' => 'Entreprise multinationale',
                        'travail_exterieur' => 'Grande entreprise marocaine',
                        'travail_equipe' => 'Moyenne ou petite Entreprise',
                        'travail_independant' => 'Fonction d\'encadrement',
                        'horaires_fixes' => 'Fonction de spécialité',
                        'horaires_flexibles' => 'Fonction d\'assistant',
                        'deplacement_frequent' => 'Fonction de consultant indépendant',
                    ];
                @endphp
                @foreach($envItems as $key => $label)
                <tr>
                    <td class="border px-2 py-3 title-table">{{ $label }}</td>
                    <td class="border px-2 py-3 text-center">
                        <input type="radio" wire:model="environnement_professionnel.{{ $key }}" value="oui" @if($isReadOnly) disabled @endif>
                    </td>
                    <td class="border px-2 py-3 text-center">
                        <input type="radio" wire:model="environnement_professionnel.{{ $key }}" value="non" @if($isReadOnly) disabled @endif>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="field-project-description mt-4">
        <label class="disc mb-2">9. Secteur D'activité envisagé</label>
        <div class="secteur_envisage">
            @php
                $secteurs = [
                    'Environnement et Nature', 'Industrie alimentaire', 'Textile et habillement',
                    'Assurances', 'Génie civil et travaux public', 'Industrie et artisanat technique',
                    'Établissements financiers et banques', 'Publicité et communication',
                    'Import et export', 'Art et culture', 'Tourisme et hôtellerie',
                    'Conseil, audit et expertise', 'Travail social, enseignement, santé',
                    'Vente, commerce, distribution', 'Sécurité et Transport',
                    'Informatique et ingénierie', 'Science Naturelle', 'Science Humaines'
                ];
            @endphp
            @foreach($secteurs as $secteur)
            <div>
                <label>
                    <input type="checkbox" wire:model="secteurs_activite" value="{{ $secteur }}" @if($isReadOnly) disabled @endif>
                    {{ $secteur }}
                </label>
            </div>
            @endforeach
        </div>
    </div>
</div>
