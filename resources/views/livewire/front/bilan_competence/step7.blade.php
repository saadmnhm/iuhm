<div>
    <div class="exp-professional">
        <div class="complete-form text-center">
            <p style="font-size: 2rem; margin-top: 3rem;">Expériences professionnelles</p>
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="exp-societe">Nom de la société</label>
            <input class="form-control" id="exp-societe" wire:model="exp_societe" @if($isReadOnly) readonly @endif>
            @error('exp_societe') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="exp-lieu">Le lieu</label>
            <input class="form-control" id="exp-lieu" wire:model="exp_lieu" @if($isReadOnly) readonly @endif>
            @error('exp_lieu') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="exp-secteur">Secteur d'activité</label>
            <input class="form-control" id="exp-secteur" wire:model="exp_secteur" @if($isReadOnly) readonly @endif>
            @error('exp_secteur') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="exp-duree">Durée de l'expérience</label>
            <input class="form-control" id="exp-duree" wire:model="exp_duree" @if($isReadOnly) readonly @endif>
            @error('exp_duree') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="exp-responsabilites">Responsabilités</label>
            <textarea class="form-control" id="exp-responsabilites" wire:model="exp_responsabilites" @if($isReadOnly) readonly @endif></textarea>
            @error('exp_responsabilites') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="exp-competences">Compétences acquises</label>
            <p>EX : Je suis capable de + verbe + objet + contexte</p>
            <textarea class="form-control" id="exp-competences" wire:model="exp_competences" @if($isReadOnly) readonly @endif></textarea>
            @error('exp_competences') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="exp-obstacles">Obstacles rencontrés</label>
            <textarea class="form-control" id="exp-obstacles" wire:model="exp_obstacles" @if($isReadOnly) readonly @endif></textarea>
            @error('exp_obstacles') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="exp-integration">Moyens d'intégration du poste</label>
            <ul class="choix_list">
                <li>Réseau</li>
                <li>En ligne</li>
                <li>Anapec</li>
                <li>Porte à porte</li>
                <li>Autre</li>
            </ul>
            <input class="form-control" id="exp-integration" wire:model="exp_integration" @if($isReadOnly) readonly @endif>
            @error('exp_integration') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="exp-depart">Motif de départ</label>
            <input class="form-control" id="exp-depart" wire:model="exp_depart" @if($isReadOnly) readonly @endif>
            @error('exp_depart') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="exp-reflexions">Réflexions personnelles</label>
            <input class="form-control" id="exp-reflexions" wire:model="exp_reflexions" @if($isReadOnly) readonly @endif>
            @error('exp_reflexions') <span>{{ $message }}</span> @enderror
        </div>
    </div>
</div>
