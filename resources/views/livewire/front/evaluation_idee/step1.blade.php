<div class="step-1">
    <h3 class="step-title"></h3>
    


    <div class="field-project-name mt-4">
        <label class=" disc mb-2" for="project-name">1. L’idéé de mon projet</label>
        <textarea  class="form-control" id="project-name" wire:model="project_name" @if($isReadOnly) readonly @endif></textarea>
        @error('project_name') <span>{{ $message }}</span> @enderror
    </div> 

    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="project-description">2. Résumes ton ideé en une phrase </label>
        <input class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif>
        @error('description') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-legal-structure mt-4">
        <label class=" disc mb-2" for="legal-structure">3. A quel besoin précis répond mon idée de projet ?</label>
        <textarea class="form-control" id="legal-structure" wire:model="legal_structure" @if($isReadOnly) readonly @endif></textarea>
        @error('legal_structure') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume">4. Quels sont les produits ou services que vous proposez </label>
        <p class="instructions"></p>
        <textarea class="form-control" id="resume" wire:model="resume_executif"  rows="9" @if($isReadOnly) readonly @endif></textarea>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume">5. Ai-je identifié qui pourraient être mes clients ? Qui sont ?</label>
        <p class="instructions"></p>
        <textarea class="form-control" id="resume" wire:model="resume_executif"  rows="9" @if($isReadOnly) readonly @endif></textarea>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume">6. Mon idée existe-t-elle déjà sur le marché ?</label>
        <p class="instructions"></p>
        <textarea class="form-control" id="resume" wire:model="resume_executif"  rows="9" @if($isReadOnly) readonly @endif></textarea>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume">7. Quelle est la valeur ajoutée de l'idée de Votre projet?</label>
        <p class="instructions"></p>
        <textarea class="form-control" id="resume" wire:model="resume_executif"  rows="9" @if($isReadOnly) readonly @endif></textarea>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume">8. Quelles sont les resultats prévues</label>
        <p class="instructions"></p>
        <textarea class="form-control" id="resume" wire:model="resume_executif"  rows="9" @if($isReadOnly) readonly @endif></textarea>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume">9. Mes proches comprennent-ils mon idée quand je leur en parle ?</label>
        <p class="instructions"></p>
        <div class="checktf">
            <label for="">OUI</label>
            <input type="radio" name="test" id="">
            <label for="">NON</label>
            <input type="radio" name="test" id="">
        </div>

        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume">10. Leurs réactions et commentaires sont-ils positifs ?</label>
        <p class="instructions"></p>
        <div class="checktf">
            <label for="">OUI</label>
            <input type="radio" name="test2" id="">
            <label for="">NON</label>
            <input type="radio" name="test2" id="">
        </div>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>

</div>

