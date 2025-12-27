<div class="table-part-14">
    <div class="Table-14">
        <p class="block disc mb-2">{{ __('messages.supplement_matieres_premieres') }}</p>
        <table class="table-auto  border-gray-300 w-full">
            <thead>
                <tr>
                    <th></th>
                    <th  class="border px-3 py-3 title-table">{{ __('messages.matieres_premieres') }}</th>
                    <th  class="border px-3 py-3 text-center title-table">{{ __('messages.comment_procurer') }}</th>
                    <th  class="border px-3 py-3 text-center title-table">{{ __('messages.fournisseur') }}</th>
                </tr>
            
            </thead>

            <tbody>
                @foreach($table6Rows as $index => $row)
                    <tr>
                        <td class="border px-2 py-3 title-table">
                            {{ $index + 1 }}
                        </td>

                        <td class="border px-2 py-3">
                            <input type="text"   wire:model="table6Rows.{{ $index }}.matiere_premiere" class=" form-control border p-1 w-full" >
                        </td>

                        <td class="border px-2 py-3">
                            <input type="text"   wire:model="table6Rows.{{ $index }}.comment_procurer" class="form-control border p-1 w-full" >
                        </td>
                        <td class="border px-2 py-3">
                            <input type="text"   wire:model="table6Rows.{{ $index }}.fournisseur_matiere" class="form-control border p-1 w-full" >
                        </td>
                    </tr>
                    
                @endforeach
            </tbody>
        </table>
        <div class="mt-2">
            <button wire:click.prevent="addTable6Row" class="more-row">{{ __('messages.ajouter_lignes') }}</button>
        </div>
    </div>
</div>