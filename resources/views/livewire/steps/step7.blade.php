<div class="table-part-13">
    <div class="Table-13">
        <p>Supplément machines et équipements</p>
        <table class="table-auto border border-gray-300 w-full">
            <thead>
                <tr>
                    <th></th>
                    <th  class="border px-3 py-2">Machines et équipements</th>
                    <th  class="border px-3 py-2 text-center">Référence</th>
                    <th  class="border px-3 py-2 text-center">Prix</th>
                </tr>
            
            </thead>

            <tbody>
                @foreach($table5Rows as $index => $row)
                    <tr>
                        <td class="border px-2 py-1">
                            {{ $index + 1 }}
                        </td>

                        <td class="border px-2 py-1">
                            <input type="text"   wire:model="table5Rows.{{ $index }}.equipement" class="border p-1 w-full" >
                        </td>

                        <td class="border px-2 py-1">
                            <input type="text"   wire:model="table5Rows.{{ $index }}.reference" class="border p-1 w-full" >
                        </td>
                        <td class="border px-2 py-1">
                            <input type="number"   wire:model="table5Rows.{{ $index }}.prix_equipement" class="border p-1 w-full" >
                        </td>
                    </tr>
                    
                @endforeach
            </tbody>
        </table>
        <div class="mt-2">
            <button wire:click.prevent="addTable5Row" class="text-black px-4 py-2 rounded">Add More Row</button>
        </div>
    </div>
</div>