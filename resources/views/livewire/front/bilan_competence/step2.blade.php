<div>
    <div class="complete-form text-center">
        <p style="text-align: center; font-weight: bold;">Axe de formation : الجانب التعليمي</p>
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2" for="niveau-etude">Niveau D'étude</label>
        <input class="form-control" id="niveau-etude" wire:model="niveau_etude" @if($isReadOnly) readonly @endif>
        @error('niveau_etude') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2" for="diplomes-obtenus">Diplômes obtenus</label>
        <input class="form-control" id="diplomes-obtenus" wire:model="diplomes_obtenus" @if($isReadOnly) readonly @endif>
        @error('diplomes_obtenus') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2" for="annee-obtention">Année d'obtention</label>
        <input class="form-control" id="annee-obtention" wire:model="annee_obtention" @if($isReadOnly) readonly @endif>
        @error('annee_obtention') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2" for="etablissement-obtention">Établissement d'obtention</label>
        <input class="form-control" id="etablissement-obtention" wire:model="etablissement_obtention" @if($isReadOnly) readonly @endif>
        @error('etablissement_obtention') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <table class="table-auto border-collapse border border-gray-300 w-full">
            <thead>
                <tr>
                    <th class="title-table border px-4 py-2">Acquises</th>
                    <th class="title-table border px-4 py-2">Lacunes</th>
                    <th class="title-table border px-4 py-2">Compétences à développer</th>
                    @if(!$isReadOnly)<th class="border px-2 py-2" style="width:50px;"></th>@endif
                </tr>
            </thead>
            <tbody>
                @foreach($competences_formation as $index => $row)
                    <tr>
                        <td class="border px-4 py-2">
                            <textarea wire:model="competences_formation.{{ $index }}.acquise" class="border p-1 w-full" @if($isReadOnly) readonly @endif></textarea>
                        </td>
                        <td class="border px-4 py-2">
                            <textarea wire:model="competences_formation.{{ $index }}.lacune" class="border p-1 w-full" @if($isReadOnly) readonly @endif></textarea>
                        </td>
                        <td class="border px-4 py-2">
                            <textarea wire:model="competences_formation.{{ $index }}.a_developper" class="border p-1 w-full" @if($isReadOnly) readonly @endif></textarea>
                        </td>
                        @if(!$isReadOnly)
                        <td class="border px-2 py-2">
                            @if(count($competences_formation) > 1)
                                <button wire:click="removeCompetenceFormation({{ $index }})" class="text-red-500">✕</button>
                            @endif
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(!$isReadOnly)
        <div class="mt-2">
            <button wire:click.prevent="addCompetenceFormation" class="more-row">{{ __('messages.ajouter_lignes') }}</button>
        </div>
        @endif
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2">5. Est-ce que vous avez besoin des autres formations ?</label>
        <div class="checktf">
            <label><input type="radio" wire:model="besoin_formations" value="oui" @if($isReadOnly) disabled @endif> OUI</label>
            <label><input type="radio" wire:model="besoin_formations" value="non" @if($isReadOnly) disabled @endif> NON</label>
        </div>
        @error('besoin_formations') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-project-description mt-4">
        <label class="disc mb-2" for="type-formations">Si oui, Quel type de formation ?</label>
        <input class="form-control" id="type-formations" wire:model="type_formations" @if($isReadOnly) readonly @endif>
        @error('type_formations') <span>{{ $message }}</span> @enderror
    </div>
</div>
