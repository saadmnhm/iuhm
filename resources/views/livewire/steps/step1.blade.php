<div class="step-1">
    <h3>general info</h3>
    <div class="field-project-name">
        <label for="project-name">nom de projet</label>
        <input  class="form-control" id="project-name" wire:model="project_name" >
        @error('project_name') <span>{{ $message }}</span> @enderror
    </div> 
    <div class="field-ceo-name">
        <label for="ceo-name">nom du CEO</label>
        <input class="form-control" id="ceo-name" wire:model="ceo_name" >
        @error('ceo_name') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-project-description">
        <label for="project-description">description du projet</label>
        <textarea class="form-control" id="project-description" wire:model="description" ></textarea>
        @error('description') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-legal-structure">
        <label for="legal-structure">Forme juridique du projet</label>
        <input class="form-control" id="legal-structure" wire:model="legal_structure" >
        @error('legal_structure') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif">
        <label for="resume">Résumé exécutif</label>
        <p>(Le résumé analytique donne un aperçu du contenu du plan d'affaires. Cette section devrait idéalement être rédigée après la finalisation du plan.)</p>
        <textarea class="form-control" id="resume" wire:model="resume_executif"  rows="9"></textarea>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>


</div>

