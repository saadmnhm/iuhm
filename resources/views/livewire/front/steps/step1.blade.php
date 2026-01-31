<div class="step-1">
    <h3 class="step-title">{{ __('messages.General_Informations') }}</h3>
    


    <div class="field-project-name mt-4">
        <label class=" disc mb-2" for="project-name">{{ __('messages.nom_de_projet') }}</label>
        <input  class="form-control" id="project-name" wire:model="project_name" >
        @error('project_name') <span>{{ $message }}</span> @enderror
    </div> 

    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="project-description">{{ __('messages.description_du_projet') }}</label>
        <textarea class="form-control" id="project-description" wire:model="description" ></textarea>
        @error('description') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-legal-structure mt-4">
        <label class=" disc mb-2" for="legal-structure">{{ __('messages.juridique_projet') }}</label>
        <input class="form-control" id="legal-structure" wire:model="legal_structure" >
        @error('legal_structure') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume">{{ __('messages.resume_executif') }}</label>
        <p class="instructions">{{ __('messages.instruction1') }}</p>
        <textarea class="form-control" id="resume" wire:model="resume_executif"  rows="9"></textarea>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>


</div>

