<div>
    <div class="field-project-description mt-4">
        <label class="disc mb-2">12. Quelles contraintes pouvez-vous accepter</label>
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
                    $contraintes = [
                        'deplacement' => 'Déplacements fréquents',
                        'horaires_variables' => 'Déplacements lointains',
                        'travail_weekend' => 'Horaires irréguliers',
                        'travail_nuit' => 'Jours de travail irréguliers',
                        'port_charges' => 'Objectifs chiffrés à remplir',
                        'travail_exterieur_meteo' => 'Salaire à la commission',
                        'travail_repetitif' => 'Participation à des obligations mondaines ou sociales',
                        'pression_resultats' => 'Salaire fixe + commission',
                    ];
                @endphp
                @foreach($contraintes as $key => $label)
                <tr>
                    <td class="border px-2 py-3 title-table">{{ $label }}</td>
                    <td class="border px-2 py-3 text-center">
                        <input type="radio" wire:model="contraintes_acceptees.{{ $key }}" value="oui" @if($isReadOnly) disabled @endif>
                    </td>
                    <td class="border px-2 py-3 text-center">
                        <input type="radio" wire:model="contraintes_acceptees.{{ $key }}" value="non" @if($isReadOnly) disabled @endif>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="field-project-description mt-4">
        <label class="disc mb-2">13. Définissez vos exigences</label>
        <div class="représente_travail my-3">
            @php
                $exigencesList = [
                    'Salaire élevé', 'Salaire équitable', 'Environnement de travail accueillant',
                    'Prestations sociales attrayantes', 'Possibilité de promotion',
                    'Possibilité de formation continue', 'Responsabilités importantes',
                    'Horaires réguliers', 'Horaires à la carte', 'Travail en équipe',
                    'Grande marge d\'autonomie', 'Tâches variées',
                    'Affinité de caractère avec le supérieur hiérarchique',
                    'Déplacements dans le Maroc', 'Déplacements à l\'international',
                    'Travail avec des objectifs mesurables et clairs',
                    'Travail calme', 'Travail stressant', 'Autre'
                ];
            @endphp
            @foreach($exigencesList as $exigence)
            <div>
                <label>
                    <input type="checkbox" wire:model="exigences" value="{{ $exigence }}" @if($isReadOnly) disabled @endif>
                    <span class="disc_p">{{ $exigence }}</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>

    <div class="complete-form">
        <p style="font-size: 2rem;">Tu as terminé ton bilan de compétences !</p>
        <p style="font-size: 1rem;">Merci pour ta participation. Tu peux désormais réfléchir à un ou plusieurs objectifs professionnels précis</p>
    </div>

    <div class="field-project-description mt-4">
        <label class="disc mb-2" for="reflexions-personnelles">Mes réflexions</label>
        <textarea class="form-control" id="reflexions-personnelles" wire:model="reflexions_personnelles" @if($isReadOnly) readonly @endif></textarea>
        @error('reflexions_personnelles') <span>{{ $message }}</span> @enderror
    </div>
</div>
