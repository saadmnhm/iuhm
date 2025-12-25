<div class="table-part-14">
    <div class="Table-14">
        <p>Supplément de matières premières</p>
        <table class="table-auto border border-gray-300 w-full">
            <thead>
                <tr>
                    <th></th>
                    <th  class="border px-3 py-2">Matières premières</th>
                    <th  class="border px-3 py-2 text-center">Comment se les procurer</th>
                    <th  class="border px-3 py-2 text-center">Fournisseur</th>
                </tr>
            
            </thead>

            <tbody>
                @foreach($table6Rows as $index => $row)
                    <tr>
                        <td class="border px-2 py-1">
                            {{ $index + 1 }}
                        </td>

                        <td class="border px-2 py-1">
                            <input type="text"   wire:model="table6Rows.{{ $index }}.matiere_premiere" class="border p-1 w-full" >
                        </td>

                        <td class="border px-2 py-1">
                            <input type="text"   wire:model="table6Rows.{{ $index }}.comment_procurer" class="border p-1 w-full" >
                        </td>
                        <td class="border px-2 py-1">
                            <input type="text"   wire:model="table6Rows.{{ $index }}.fournisseur_matiere" class="border p-1 w-full" >
                        </td>
                    </tr>
                    
                @endforeach
            </tbody>
        </table>
        <div class="mt-2">
            <button wire:click.prevent="addTable6Row" class="text-black px-4 py-2 rounded">Add More Row</button>
        </div>
    </div>
</div>