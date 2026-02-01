<div>
    <div class="fourd-part">
        <h3 class="step-title">{{ __('messages.strategie_marketing') }}</h3>
        <p class="instructions">{{ __('messages.instruction4') }}</p>

        <div class="field-méthodes_marketing mb-2">
            <label class="block disc mb-2 font-semibold" for="méthodes_marketing">{{ __('messages.methodes_marketing') }}</label>
            <input class="form-control" id="méthodes_marketing" wire:model="méthodes_marketing" @if($isReadOnly) readonly @endif>
        </div>

        <div class="field-adaptation_methodes mb-2">
            <label class="block disc mb-2 font-semibold"  for="adaptation_methodes">{{ __('messages.adaptation_methodes') }}</label>
            <input class="form-control" id="adaptation_methodes" wire:model="adaptation_methodes" @if($isReadOnly) readonly @endif>
        </div>
        <div class="field-differenciation_marketing mb-2">
            <label class="block disc mb-2 font-semibold" for="differenciation_marketing">{{ __('messages.differenciation_marketing') }}</label>
            <input class="form-control" id="differenciation_marketing" wire:model="differenciation_marketing" @if($isReadOnly) readonly @endif>
        </div>

    </div>

    <div class="part-five">
        <h3 class="step-title" >{{ __('messages.entreprise_personnel') }}</h3>
        <p class="instructions">{{ __('messages.instruction5') }}</p>
        <p class="block disc mb-2">{{ __('messages.nombre_utilisateurs') }}</p>
        <div class="table-part-five mt-4">

            <table class="table-auto  border-gray-300 w-full">
                <thead>
                    <tr>
                        <th rowspan="2" class="border px-3 py-2 title-table">{{ __('messages.titre_emploi') }}</th>
                        <th colspan="2" class="border px-3 py-2 text-center title-table">{{ __('messages.nombre') }}</th>
                    </tr>
                    <tr>
                        <th class="border px-3 py-2 title-table">{{ __('messages.premiere_annee') }}</th>
                        <th class="border px-3 py-2 title-table">{{ __('messages.deuxieme_annee') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($table2Rows as $index => $row)
                        <tr>
                            <td class="border px-2 py-1">
                                <input type="text" wire:model.live="table2Rows.{{ $index }}.item" class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model.live="table2Rows.{{ $index }}.total_employee_1" min="0" step="0.01" class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model.live="table2Rows.{{ $index }}.total_employee_2" min="0" step="0.01" class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                        
                        </tr>
                    @endforeach

                    {{-- TOTAL ROW --}}
                    <tr class="bg-gray-100 font-bold">
                        <td  class="border px-3 py-2 text-right title-table">
                            {{ __('messages.total') }}
                        </td>
                        <td class="border px-3 py-2">
                            {{ number_format($this->total1, 2) }} 
                        </td>
                        <td class="border px-3 py-2">
                            {{ number_format($this->total2, 2) }} 
                        </td>
                    </tr>
                </tbody>
            </table>

            @if(!$isReadOnly)
            <button wire:click="addTable2Row" class="more-row">{{ __('messages.ajouter_lignes') }}</button>
            @endif
        </div>



    </div>

    <div class="part-six">
        <h3 class="step-title mt-3">{{ __('messages.calendrier') }}</h3>
        <p class="instructions">{{ __('messages.instruction_calendrier') }}</p>
        <div class="table-part-six mt-4">
            <table class="table-auto  border-gray-300 w-full">
                <thead>
                    <tr>
                        <th  class="border px-3 py-2 title-table">{{ __('messages.tache') }}</th>
                        <th  class="border px-3 py-2 text-center title-table">{{ __('messages.date_echeance') }}</th>
                    </tr>
                
                </thead>

                <tbody>
                        <tr>
                            <td class="border px-2 py-1">
                                {{ __('messages.finalisation_plan_affaires') }}
                            </td>

                            <td class="border px-2 py-1">
                                <input type="text" wire:model="plan_affaires"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                {{ __('messages.obtention_financement') }}
                            </td>

                            <td class="border px-2 py-1">
                                <input type="text" wire:model="obtention_financement"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                {{ __('messages.ouverture_proces') }}
                            </td>

                            <td class="border px-2 py-1">
                                <input type="text" wire:model="ouverture_proces"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                {{ __('messages.lancement_recrutement') }}
                            </td>

                            <td class="border px-2 py-1">
                                <input type="text" wire:model="lancement_recrutement"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1">
                                {{ __('messages.ouverture_definitive') }}
                            </td>

                            <td class="border px-2 py-1">
                                <input type="text" wire:model="ouverture_definitive"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1 title-table">
                                {{ __('messages.duree') }}
                            </td>

                            <td class="border px-2 py-1">
                                <input type="text" wire:model="duree"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>