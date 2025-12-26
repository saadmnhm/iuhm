<div class="table-part-13">
    <div class="Table-13">
        <p  class="block disc mb-2">Supplément machines et équipements</p>
        <table class="table-auto  border-gray-300 w-full">
            <thead>
                <tr>
                    <th></th>
                    <th  class="border px-3 py-3 title-table">Machines et équipements</th>
                    <th  class="border px-3 py-3  text-center title-table">Référence</th>
                    <th  class="border px-3 py-3  text-center title-table">Prix</th>
                </tr>
            
            </thead>

            <tbody>
                @foreach($table5Rows as $index => $row)
                    <tr>
                        <td class="border px-2 py-3 title-table">
                            {{ $index + 1 }}
                        </td>

                        <td class="border px-2 py-3">
                            <input type="text"   wire:model="table5Rows.{{ $index }}.equipement" class="form-control border p-1 w-full" >
                        </td>

                        <td class="border px-2 py-3">
                            <input type="text"   wire:model="table5Rows.{{ $index }}.reference" class=" form-control border p-1 w-full" >
                        </td>
                        <td class="border px-2 py-3">
                            <input type="number"   wire:model="table5Rows.{{ $index }}.prix_equipement" class="form-control border p-1 w-full" >
                        </td>
                    </tr>
                    
                @endforeach
            </tbody>
        </table>
        <div class="mt-2">
            <button wire:click.prevent="addTable5Row" class="more-row">Ajouter des lignes</button>
        </div>
    </div>
</div>