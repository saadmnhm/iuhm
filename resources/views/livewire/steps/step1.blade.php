<div class="step-1">
    <h3>general info</h3>
    <div class="field-project-name">
        <label for="project-name">nom de projet</label>
        <input id="project-name" wire:model="name" placeholder="Name">
        @error('name') <span>{{ $message }}</span> @enderror
    </div> 
    <div class="field-ceo-name">
        <label for="ceo-name">nom du CEO</label>
        <input id="ceo-name" wire:model="ceo_name" placeholder="CEO Name">
        @error('ceo_name') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-project-description">
        <label for="project-description">description du projet</label>
        <textarea id="project-description" wire:model="description" placeholder="Project Description"></textarea>
        @error('description') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-legal-structure">
        <label for="">Forme juridique du projet</label>
        <input id="legal-structure" wire:model="legal_structure" placeholder="Legal Structure">
        @error('legal_structure') <span>{{ $message }}</span> @enderror
    </div>


</div>

