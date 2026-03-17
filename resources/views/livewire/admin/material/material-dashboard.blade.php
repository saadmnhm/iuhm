<div class="max-w-7xl mx-auto">

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3">
        <i class="ri-checkbox-circle-line text-green-500 text-xl"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Statistics --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        @foreach([
            ['Total', $statistics['total'], 'ri-archive-line', 'blue'],
            ['Disponible', $statistics['disponible'], 'ri-checkbox-circle-line', 'green'],
            ['En utilisation', $statistics['en_utilisation'], 'ri-user-settings-line', 'indigo'],
            ['Maintenance', $statistics['maintenance'], 'ri-tools-line', 'amber'],
            ['Stock bas', $statistics['low_stock'], 'ri-alert-line', 'red'],
            ['Valeur totale', number_format($statistics['valeur_totale'], 0) . ' MAD', 'ri-money-dollar-circle-line', 'purple'],
        ] as [$label, $value, $icon, $color])
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-{{ $color }}-100 flex items-center justify-center">
                    <i class="{{ $icon }} text-{{ $color }}-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ $label }}</p>
                    <p class="text-lg font-bold text-gray-900">{{ $value }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tabs --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex border-b border-gray-100">
            <button wire:click="$set('tab', 'inventory')" class="px-6 py-3 text-sm font-semibold transition {{ $tab === 'inventory' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="ri-archive-line mr-1"></i> Inventaire
            </button>
            <button wire:click="$set('tab', 'categories')" class="px-6 py-3 text-sm font-semibold transition {{ $tab === 'categories' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="ri-price-tag-3-line mr-1"></i> Catégories
            </button>
        </div>

        @if($tab === 'inventory')
        <div class="p-4">
            {{-- Toolbar --}}
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <div class="flex-1 min-w-[200px] relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live="search" placeholder="Rechercher par nom, référence, emplacement..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">
                </div>
                <select wire:model.live="categoryFilter" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                    <option value="all">Toutes catégories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="statusFilter" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                    <option value="all">Tous statuts</option>
                    <option value="disponible">Disponible</option>
                    <option value="en_utilisation">En utilisation</option>
                    <option value="en_maintenance">En maintenance</option>
                    <option value="retire">Retiré</option>
                </select>
                <select wire:model.live="etatFilter" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                    <option value="all">Tous états</option>
                    <option value="neuf">Neuf</option>
                    <option value="bon">Bon</option>
                    <option value="usage">Usagé</option>
                    <option value="defectueux">Défectueux</option>
                    <option value="hors_service">Hors service</option>
                </select>
                <a href="{{ route('admin.material.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <i class="ri-add-line mr-1"></i> Nouveau Matériel
                </a>
                <a href="{{ route('admin.material.print.inventory') }}" target="_blank" class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <i class="ri-printer-line mr-1"></i> Imprimer Inventaire
                </a>
            </div>

            {{-- Material Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($materials as $m)
                @php
                    $statusColors = ['disponible' => 'bg-green-100 text-green-800', 'en_utilisation' => 'bg-blue-100 text-blue-800', 'en_maintenance' => 'bg-amber-100 text-amber-800', 'retire' => 'bg-gray-100 text-gray-600'];
                    $etatColors = ['neuf' => 'bg-emerald-100 text-emerald-800', 'bon' => 'bg-green-100 text-green-800', 'usage' => 'bg-amber-100 text-amber-800', 'defectueux' => 'bg-orange-100 text-orange-800', 'hors_service' => 'bg-red-100 text-red-800'];
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition overflow-hidden">
                    {{-- Photo --}}
                    @if($m->primaryPhoto)
                    <img src="{{ asset('uploads/' . $m->primaryPhoto->file_path) }}" alt="{{ $m->name }}" class="w-full h-40 object-cover">
                    @else
                    <div class="w-full h-40 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        <i class="ri-archive-line text-4xl text-gray-300"></i>
                    </div>
                    @endif

                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $m->name }}</h4>
                                <p class="text-xs text-gray-500 font-mono">{{ $m->reference }}</p>
                            </div>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $statusColors[$m->status] ?? '' }}">{{ ucfirst(str_replace('_', ' ', $m->status)) }}</span>
                        </div>

                        <div class="space-y-1 text-sm mb-3">
                            @if($m->category)
                            <div class="flex items-center gap-2 text-gray-600"><i class="ri-price-tag-3-line text-gray-400 w-4"></i> {{ $m->category->name }}</div>
                            @endif
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="ri-stack-line text-gray-400 w-4"></i> Qté: <span class="font-bold {{ $m->quantity <= $m->quantity_min && $m->quantity_min > 0 ? 'text-red-600' : '' }}">{{ $m->quantity }}</span>
                            </div>
                            @if($m->emplacement)
                            <div class="flex items-center gap-2 text-gray-600"><i class="ri-map-pin-line text-gray-400 w-4"></i> {{ $m->emplacement }}</div>
                            @endif
                            @if($m->prix_unitaire)
                            <div class="flex items-center gap-2 text-gray-600"><i class="ri-money-dollar-circle-line text-gray-400 w-4"></i> {{ number_format($m->prix_unitaire, 2) }} MAD</div>
                            @endif
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $etatColors[$m->etat] ?? '' }}">{{ ucfirst($m->etat) }}</span>
                            </div>
                        </div>

                        <div class="flex gap-2 pt-3 border-t border-gray-100">
                            <a href="{{ route('admin.material.show', $m->id) }}" class="flex-1 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg transition text-center">
                                <i class="ri-eye-line mr-1"></i> Voir
                            </a>
                            <a href="{{ route('admin.material.edit', $m->id) }}" class="flex-1 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition text-center">
                                <i class="ri-pencil-line mr-1"></i> Modifier
                            </a>
                            <a href="{{ route('admin.material.print.fiche', $m->id) }}" target="_blank" class="px-3 py-2 bg-green-50 hover:bg-green-100 text-green-700 text-xs font-semibold rounded-lg transition">
                                <i class="ri-printer-line"></i>
                            </a>
                            <button wire:click="deleteMaterial({{ $m->id }})" wire:confirm="Supprimer ce matériel ?"
                                    class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-lg transition">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
                    <i class="ri-archive-line text-5xl text-gray-300 mb-3 block"></i>
                    <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucun matériel trouvé</h3>
                    <p class="text-sm text-gray-500">Ajoutez du matériel pour commencer la gestion d'inventaire.</p>
                </div>
                @endforelse
            </div>
            <div class="mt-6">{{ $materials->links() }}</div>
        </div>
        @endif

        @if($tab === 'categories')
        <div class="p-4">
            <div class="flex flex-wrap gap-4 items-end mb-6 bg-gray-50 rounded-lg p-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nom de la catégorie</label>
                    <input type="text" wire:model="newCatName" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none" placeholder="Ex: Informatique">
                </div>
                <button wire:click="createCategory" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <i class="ri-add-line mr-1"></i> Ajouter
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($categories as $cat)
                <div class="flex items-center justify-between bg-white rounded-lg border border-gray-200 px-4 py-3">
                    <div>
                        <span class="text-sm font-medium text-gray-800">{{ $cat->name }}</span>
                        <span class="text-xs text-gray-400 ml-2">({{ $cat->materials()->count() }} articles)</span>
                    </div>
                    <button wire:click="deleteCategory({{ $cat->id }})" wire:confirm="Supprimer cette catégorie ?"
                            class="text-red-500 hover:text-red-700 text-xs"><i class="ri-close-line text-lg"></i></button>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
