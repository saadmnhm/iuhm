<div>

   <div class="field-Resume-executif mt-4  ">
        <table class="table-auto bmc_table border-collapse border border-gray-300 w-full">
                <tr>
                    {{-- Partenariats clés --}}
                    <td rowspan="2" class="bmc_td" style="width:20%; height:300px;">
                        <strong>Partenariats clés</strong>
                        <textarea wire:model="canvas.partners"
                            class="bmc_textarea"
                            @if($isReadOnly) readonly @endif></textarea>
                    </td>

                    {{-- Activités clés --}}
                    <td class="bmc_td" style="width:20%; height:150px;">
                        <strong>Activités clés</strong>
                        <textarea wire:model="canvas.activities"
                            class="bmc_textarea"
                            @if($isReadOnly) readonly @endif></textarea>
                    </td>

                    {{-- Proposition de valeur --}}
                    <td rowspan="2" class="bmc_td" style="width:20%; height:300px;">
                        <strong>Proposition de valeur</strong>
                        <textarea wire:model="canvas.value_proposition"
                            class="bmc_textarea"
                            @if($isReadOnly) readonly @endif></textarea>
                    </td>

                    {{-- Relations clients --}}
                    <td class="bmc_td" style="width:20%; height:150px;">
                        <strong>Relations avec les clients</strong>
                        <textarea wire:model="canvas.customer_relationships"
                            class="bmc_textarea"
                            @if($isReadOnly) readonly @endif></textarea>
                    </td>

                    {{-- Segments de clientèle --}}
                    <td rowspan="2" class="bmc_td" style="width:20%; height:300px;">
                        <strong>Segments de clientèle</strong>
                        <textarea wire:model="canvas.customer_segments"
                            class="bmc_textarea"
                            @if($isReadOnly) readonly @endif></textarea>
                    </td>
                </tr>

                <tr>
                    {{-- Ressources clés --}}
                    <td class="bmc_td" style="height:150px;">
                        <strong>Ressources clés</strong>
                        <textarea wire:model="canvas.resources"
                            class="bmc_textarea"
                            @if($isReadOnly) readonly @endif></textarea>
                    </td>

                    {{-- Canaux --}}
                    <td class="bmc_td" style="height:150px;">
                        <strong>Les Canaux</strong>
                        <textarea wire:model="canvas.channels"
                            class="bmc_textarea"
                            @if($isReadOnly) readonly @endif></textarea>
                    </td>
                </tr>

                <tr>
                    {{-- Structure des coûts --}}
                    <td colspan="3" class="bmc_td" style="height:140px;">
                        <strong>Structure des coûts</strong>
                        <textarea wire:model="canvas.cost_structure"
                            class="bmc_textarea"
                            @if($isReadOnly) readonly @endif></textarea>
                    </td>

                    {{-- Flux de revenus --}}
                    <td colspan="2" class="bmc_td" style="height:140px;">
                        <strong>Flux de revenus</strong>
                        <textarea wire:model="canvas.revenue_streams"
                            class="bmc_textarea"
                            @if($isReadOnly) readonly @endif></textarea>
                    </td>
                </tr>
        </table>
    </div>

</div>