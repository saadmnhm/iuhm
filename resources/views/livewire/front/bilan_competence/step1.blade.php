<div class="step-1">
    <h3 class="step-title"></h3>
    
<div class="complete-form text-center">
    <p style="text-align: center; font-weight: bold;">الجانب الشخصي : personnel Axe</p>
</div>
    <div class="field-project-name mt-4">
        <label class=" disc mb-2" for="project-name">1. Citez vos qualités et vos défauts :</label>
        <table class="table-auto border-collapse border border-gray-300 w-full">
            <thead>
                <tr>
                    <th class="title-table border px-4 py-2">Qualités</th>
                    <th class="title-table border px-4 py-2">Défauts</th>
                </tr>
            </thead>
            <tbody>
                @foreach($table1Rows as $index => $row)
                    <tr>
                        <td class="border px-4 py-2">
                            <textarea wire:model="table1Rows.{{ $index }}.product_name" class="border p-1 w-full" @if($isReadOnly) readonly @endif></textarea>
                        </td>
                        <td class="border px-4 py-2">
                            <textarea wire:model="table1Rows.{{ $index }}.description" class="border p-1 w-full" @if($isReadOnly) readonly @endif></textarea>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(!$isReadOnly)
        <div class="mt-2">
            <button wire:click.prevent="addTable1Row" class="more-row">{{ __('messages.ajouter_lignes') }}</button>
        </div>
        @endif
        @error('project_name') <span>{{ $message }}</span> @enderror
    </div> 

    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="project-description">2. En quoi ces qualités peuvent-elles contribuer à votre réussite professionnelle ?</label>
        <textarea class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif></textarea>
        @error('description') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-legal-structure mt-4">
        <label class=" disc mb-2" for="legal-structure">3. En quoi ces défauts peuvent-ils freiner votre réussite professionnelle ?</label>
        <textarea class="form-control" id="legal-structure" wire:model="legal_structure" @if($isReadOnly) readonly @endif></textarea>
        @error('legal_structure') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume">4. Quels sont tes loisirs ?</label>
        <p class="instructions"></p>
        <input class="form-control" id="resume" wire:model="resume_executif"  rows="9" @if($isReadOnly) readonly @endif>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>



 






</div>

