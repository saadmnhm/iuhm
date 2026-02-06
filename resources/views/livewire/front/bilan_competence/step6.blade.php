<div>
    <div class="stages">
        <div class="complete-form text-center">
            <p style="font-size: 2rem; margin-top: 3rem;">Stages</p>
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="stage-societe">Nom de la société</label>
            <input class="form-control" id="stage-societe" wire:model="stage_societe" @if($isReadOnly) readonly @endif>
            @error('stage_societe') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="stage-lieu">Le lieu</label>
            <input class="form-control" id="stage-lieu" wire:model="stage_lieu" @if($isReadOnly) readonly @endif>
            @error('stage_lieu') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="stage-secteur">Secteur d'activité</label>
            <input class="form-control" id="stage-secteur" wire:model="stage_secteur" @if($isReadOnly) readonly @endif>
            @error('stage_secteur') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="stage-duree">Durée du stage</label>
            <input class="form-control" id="stage-duree" wire:model="stage_duree" @if($isReadOnly) readonly @endif>
            @error('stage_duree') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="stage-responsabilites">Responsabilités</label>
            <textarea class="form-control" id="stage-responsabilites" wire:model="stage_responsabilites" @if($isReadOnly) readonly @endif></textarea>
            @error('stage_responsabilites') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="stage-competences">Compétences acquises</label>
            <p>EX : Je suis capable de + verbe + objet + contexte</p>
            <textarea class="form-control" id="stage-competences" wire:model="stage_competences" @if($isReadOnly) readonly @endif></textarea>
            @error('stage_competences') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="stage-obstacles">Obstacles rencontrés</label>
            <textarea class="form-control" id="stage-obstacles" wire:model="stage_obstacles" @if($isReadOnly) readonly @endif></textarea>
            @error('stage_obstacles') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="stage-reflexions">Réflexions personnelles</label>
            <input class="form-control" id="stage-reflexions" wire:model="stage_reflexions" @if($isReadOnly) readonly @endif>
            @error('stage_reflexions') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="stage-plu">Qu'est ce qui t'as plu dans le stage</label>
            <input class="form-control" id="stage-plu" wire:model="stage_plu" @if($isReadOnly) readonly @endif>
            @error('stage_plu') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="stage-deplu">Qu'est ce qui t'as déplu</label>
            <input class="form-control" id="stage-deplu" wire:model="stage_deplu" @if($isReadOnly) readonly @endif>
            @error('stage_deplu') <span>{{ $message }}</span> @enderror
        </div>

        <div class="field-project-description mt-4">
            <label class="disc mb-2" for="stage-appris">Qu'as-tu appris sur toi-même</label>
            <input class="form-control" id="stage-appris" wire:model="stage_appris" @if($isReadOnly) readonly @endif>
            @error('stage_appris') <span>{{ $message }}</span> @enderror
        </div>
    </div>
</div>
