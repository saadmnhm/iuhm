<div>
    <div class="part-8">
        <h3 class="step-title">{{ __('messages.capacites') }}</h3>
        <p class="instructions">{{ __('messages.instruction_capacites') }}</p>

        <div class="field-step_8_1 mb-2">
            <label for="step_8_1" class="block disc mb-2 font-semibold" >{{ __('messages.competences_necessaires') }}</label>
            <input type="text" id="step_8_1" wire:model="step_8_1"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
        </div>

        <div class="field-step_8_2 mb-2">
            <label for="step_8_2" class="block disc mb-2 font-semibold" >{{ __('messages.materiel_necessaire') }}</label>
            <input type="text" id="step_8_2" wire:model="step_8_2"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
        </div>
        
        <div class="field-step_8_3 mb-2">
            <label for="step_8_3" class="block disc mb-2 font-semibold" >{{ __('messages.experience_necessaire') }}</label>
            <input type="text" id="step_8_3" wire:model="step_8_3"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
        </div>

        <div class="field-step_8_4 mb-2">
            <label for="step_8_4" class="block disc mb-2 font-semibold" >{{ __('messages.fonds_necessaires') }}</label>
            <input type="text" id="step_8_4" wire:model="step_8_4"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
        </div>

    </div>

    <div class="parent-table-9 mt-4">
        <div class="table-9">
            <h3  class="step-title">{{ __('messages.previsions_financieres') }}</h3>
            <p class="block disc mb-2">{{ __('messages.programme_investissement') }}</p>
            <table class="table-auto border border-gray-300 w-full">
                <tbody>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                {{ __('messages.couts_creation_entreprise') }}

                            </td>

                            <td class="border px-2 py-1 ">
                                <input type="number" wire:model.live="couts_creation"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                {{ __('messages.preparation_entreprise') }}
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model.live="preparation_entreprise"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                {{ __('messages.achat_machines') }}
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model.live="achat_machines"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                {{ __('messages.achat_matieres_premieres_six_mois') }}
                            </td>

                            <td class="border px-2 py-1">
                                <input type="number" wire:model.live="achat_matieres_premieres"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                {{ __('messages.autres_couts_six_mois') }}
                            </td>

                            <td class="border px-2 py-3 ">
                                <input type="number" wire:model.live="autres_couts"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                {{ __('messages.total') }}
                            </td>

                            <td class="border px-2 py-3 ">
                                <input type="number" wire:model="total" readonly class="form-control border w-full p-1 bg-gray-100">
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>

    </div>
    
    <div class="parent-table-10 mt-4">
        <div class="table-10">
            <h3 class="step-title">{{ __('messages.previsions_financieres_10') }}</h3>
            <p class="block disc mb-2">{{ __('messages.programme_investissement') }}</p>
            <table class="table-auto border border-gray-300 w-full">
                <thead>
                    <tr>
                        <th class="border px-2 py-3 title-table">{{ __('messages.previsions_revenus') }}</th>
                        <th class="border px-2 py-3 title-table">{{ __('messages.premier_annee') }}</th>
                        <th class="border px-2 py-3 title-table">{{ __('messages.deuxieme_annee') }}</th>
                        <th class="border px-2 py-3 title-table">{{ __('messages.troisieme_annee') }}</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                 {{ __('messages.ventes') }}

                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="ventes_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="ventes_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="ventes_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                 {{ __('messages.services') }}
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="services_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="services_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="services_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table" >
                                {{ __('messages.aide_financiere') }}
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="aide_financiere_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="aide_financiere_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="aide_financiere_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                {{ __('messages.revenus_financiers') }}
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="revenus_financiers_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="revenus_financiers_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="revenus_financiers_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-3 title-table">
                                {{ __('messages.autres_revenus') }}
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="autres_revenus_premiere_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="autres_revenus_deuxieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>

                            <td class="border px-2 py-3">
                                <input type="number" wire:model.live="autres_revenus_troisieme_annee"  class="form-control border w-full p-1" @if($isReadOnly) readonly @endif>
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>

    </div> 
</div>