<div>
    <div class="complete-form text-center">
        <p style="text-align: center; font-weight: bold;">Axe de formation :  الجانب التعليمي</p>
    </div>

    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume">Niveau D’étude</label>
        <p class="instructions"></p>
        <input class="form-control" id="resume" wire:model="resume_executif"  rows="9" @if($isReadOnly) readonly @endif>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume">Diplômes obtenus </label>
        <p class="instructions"></p>
        <input class="form-control" id="resume" wire:model="resume_executif"  rows="9" @if($isReadOnly) readonly @endif>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume">Année d'obtention</label>
        <p class="instructions"></p>
        <input class="form-control" id="resume" wire:model="resume_executif"  rows="9" @if($isReadOnly) readonly @endif>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume"> Etablissement d'obtention </label>
        <p class="instructions"></p>
        <input class="form-control" id="resume" wire:model="resume_executif"  rows="9" @if($isReadOnly) readonly @endif>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <table class="table-auto border-collapse border border-gray-300 w-full">
            <thead>
                <tr>
                    <th class="title-table border px-4 py-2">Acquises</th>
                    <th class="title-table border px-4 py-2">Lacunes</th>
                    <th class="title-table border px-4 py-2">Compétences à développer</th>
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
                        <td class="border px-4 py-2">
                                <textarea wire:model="table1Rows.{{ $index }}.test" class="border p-1 w-full" @if($isReadOnly) readonly @endif></textarea>
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
    </div>

    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume">5. Est-ce que vous avez besoin des autres formations ?</label>
        <p class="instructions"></p>
        <div class="checktf">
            <label for="">OUI</label>
            <input type="radio" name="test" id="">
            <label for="">NON</label>
            <input type="radio" name="test" id="">
        </div>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="project-description">Si oui, Quel type de formation ?</label>
        <input class="form-control" id="project-description" wire:model="description" @if($isReadOnly) readonly @endif>
        @error('description') <span>{{ $message }}</span> @enderror
    </div>




</div>