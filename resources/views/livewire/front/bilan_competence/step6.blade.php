<div>
        <div class="stages ">
        <div class="complete-form text-center">
            <p style="font-size: 2rem; margin-top: 3rem;">Stages</p>
        </div>
        <div class="field-project-description mt-4">
            <label class=" disc mb-2" for="project-description">Nom de la société</label>
            <input class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif>
            @error('description') <span>{{ $message }}</span> @enderror
        </div>
        <div class="field-project-description mt-4">
            <label class=" disc mb-2" for="project-description">Le lieu</label>
            <input class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif>
            @error('description') <span>{{ $message }}</span> @enderror
        </div>  
        <div class="field-project-description mt-4">
            <label class=" disc mb-2" for="project-description">Secteur d'activité</label>
            <input class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif>
            @error('description') <span>{{ $message }}</span> @enderror
        </div>  
        <div class="field-project-description mt-4">
            <label class=" disc mb-2" for="project-description">Durée du stage</label>
            <input class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif>
            @error('description') <span>{{ $message }}</span> @enderror
        </div>   
        <div class="field-project-description mt-4">
            <label class=" disc mb-2" for="project-description">Responsabilités</label>
            <textarea class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif></textarea>
            @error('description') <span>{{ $message }}</span> @enderror
        </div> 
        <div class="field-project-description mt-4">
            <label class=" disc mb-2" for="project-description">Compétences  acquises </label>
            <p>EX :Je suis capable de + verbe + objet + contexte</p>
            <textarea class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif></textarea>
            @error('description') <span>{{ $message }}</span> @enderror
        </div>     
        <div class="field-project-description mt-4">
            <label class=" disc mb-2" for="project-description">Obstacles rencontrés</label>
            <textarea class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif></textarea>
            @error('description') <span>{{ $message }}</span> @enderror
        </div>
        <div class="field-project-description mt-4">
            <label class=" disc mb-2" for="project-description">Réflexions personnelles </label>
            <input class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif>
            @error('description') <span>{{ $message }}</span> @enderror
        </div> 
        <div class="field-project-description mt-4">
            <label class=" disc mb-2" for="project-description">Qu'est ce qui t'as plu dans le stage</label>
            <input class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif>
            @error('description') <span>{{ $message }}</span> @enderror
        </div> 
        <div class="field-project-description mt-4">
            <label class=" disc mb-2" for="project-description">Qu'est ce qui t'as déplu</label>
            <input class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif>
            @error('description') <span>{{ $message }}</span> @enderror
        </div> 
        <div class="field-project-description mt-4">
            <label class=" disc mb-2" for="project-description">Qu'as-tu appris sur toi-même</label>
            <input class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif>
            @error('description') <span>{{ $message }}</span> @enderror
        </div> 

    </div>
</div>