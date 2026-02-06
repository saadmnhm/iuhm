<div class="step-1">
    <h3 class="step-title"></h3>
    <div class="complete-form text-center">
        <p style="text-align: center; font-weight: bold;">الجانب الشخصي : Axe personnel</p>
    </div>

    <div class="field-project-name mt-4">
        <label class="disc mb-2">1. Citez vos qualités et vos défauts :</label>
        <table class="table-auto border-collapse border border-gray-300 w-full">
            <thead>
                <tr>
                    <th class="title-table border px-4 py-2">Qualités</th>
                    <th class="title-table border px-4 py-2">Défauts</th>
                    @if(!$isReadOnly)<th class="border px-2 py-2" style="width:50px;"></th>@endif
                </tr>
            </thead>
            <tbody>
                @foreach($qualites_defauts as $index => $row)
                    <tr>
                        <td class="border px-4 py-2">
                            <textarea wire:model="qualites_defauts.{{ $index }}.qualite" class="border p-1 w-full" @if($isReadOnly) readonly @endif></textarea>
                        </td>
                        <td class="border px-4 py-2">
                            <textarea wire:model="qualites_defauts.{{ $index }}.defaut" class="border p-1 w-full" @if($isReadOnly) readonly @endif></textarea>
                        </td>
                        @if(!$isReadOnly)
                        <td class="border px-2 py-2">
                            @if(count($qualites_defauts) > 1)
                                <button wire:click="removeQualiteDefaut({{ $index }})" class="text-red-500">✕</button>
                            @endif
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(!$isReadOnly)
        <div class="mt-2">
            <button wire:click.prevent="addQualiteDefaut" class="more-row">{{ __('messages.ajouter_lignes') }}</button>
        </div>
        @endif
    </div>

    <div class="field-project-description mt-4">
        <label class="disc mb-2" for="qualites-contribution">2. En quoi ces qualités peuvent-elles contribuer à votre réussite professionnelle ?</label>
        <textarea class="form-control" id="qualites-contribution" wire:model="qualites_contribution" @if($isReadOnly) readonly @endif></textarea>
        @error('qualites_contribution') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-legal-structure mt-4">
        <label class="disc mb-2" for="defauts-freins">3. En quoi ces défauts peuvent-ils freiner votre réussite professionnelle ?</label>
        <textarea class="form-control" id="defauts-freins" wire:model="defauts_freins" @if($isReadOnly) readonly @endif></textarea>
        @error('defauts_freins') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2" for="loisirs">4. Quels sont tes loisirs ?</label>
        <input class="form-control" id="loisirs" wire:model="loisirs" @if($isReadOnly) readonly @endif>
        @error('loisirs') <span>{{ $message }}</span> @enderror
    </div>
</div>
