<div class="max-w-7xl mx-auto">

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3">
        <i class="ri-checkbox-circle-line text-green-500 text-xl"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    {{-- ═══ Financial Summary Cards ═══ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['Solde Actuel', number_format($solde, 2) . ' MAD', 'ri-wallet-3-line', $solde >= 0 ? 'green' : 'red'],
            ['Total Revenus', number_format($totalRevenue, 2) . ' MAD', 'ri-arrow-up-circle-line', 'blue'],
            ['Total Dépenses', number_format($totalDepense, 2) . ' MAD', 'ri-arrow-down-circle-line', 'red'],
            ['Ce Mois', number_format($monthlyRevenue - $monthlyDepense, 2) . ' MAD', 'ri-calendar-check-line', 'purple'],
        ] as [$label, $value, $icon, $color])
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase">{{ $label }}</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ $value }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-{{ $color }}-100 flex items-center justify-center">
                    <i class="{{ $icon }} text-{{ $color }}-600 text-lg"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Monthly breakdown --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h4 class="text-sm font-semibold text-gray-700 mb-2"><i class="ri-arrow-up-circle-line text-green-500 mr-1"></i>Revenus ce mois</h4>
            <p class="text-2xl font-bold text-green-600">{{ number_format($monthlyRevenue, 2) }} MAD</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h4 class="text-sm font-semibold text-gray-700 mb-2"><i class="ri-arrow-down-circle-line text-red-500 mr-1"></i>Dépenses ce mois</h4>
            <p class="text-2xl font-bold text-red-600">{{ number_format($monthlyDepense, 2) }} MAD</p>
        </div>
    </div>

    {{-- ═══ Tab Navigation ═══ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex border-b border-gray-100">
            <button wire:click="$set('tab', 'transactions')" class="px-6 py-3 text-sm font-semibold transition {{ $tab === 'transactions' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="ri-exchange-funds-line mr-1"></i> Transactions
            </button>
            <button wire:click="$set('tab', 'charges')" class="px-6 py-3 text-sm font-semibold transition {{ $tab === 'charges' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="ri-repeat-line mr-1"></i> Charges Récurrentes
            </button>
            <button wire:click="$set('tab', 'categories')" class="px-6 py-3 text-sm font-semibold transition {{ $tab === 'categories' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="ri-price-tag-3-line mr-1"></i> Catégories
            </button>
        </div>

        {{-- ═══ TRANSACTIONS TAB ═══ --}}
        @if($tab === 'transactions')
        <div class="p-4">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <div class="flex-1 min-w-[200px] relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live="search" placeholder="Rechercher par libellé, référence, bénéficiaire..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">
                </div>
                <select wire:model.live="typeFilter" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                    <option value="all">Tous types</option>
                    <option value="revenue">Revenus</option>
                    <option value="depense">Dépenses</option>
                </select>
                <select wire:model.live="categoryFilter" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                    <option value="all">Toutes catégories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <input type="date" wire:model.live="dateFrom" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                <input type="date" wire:model.live="dateTo" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                <a href="{{ route('admin.finance.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <i class="ri-add-line mr-1"></i> Nouvelle Transaction
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="px-4 py-3 font-semibold text-gray-600">Réf.</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Date</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Type</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Libellé</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Catégorie</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Montant</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Mode</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Pièces</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($transactions as $t)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $t->reference }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $t->date_transaction->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                @if($t->type === 'revenue')
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800"><i class="ri-arrow-up-line"></i> Revenu</span>
                                @else
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800"><i class="ri-arrow-down-line"></i> Dépense</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $t->label }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $t->category->name ?? '-' }}</td>
                            <td class="px-4 py-3 font-bold {{ $t->type === 'revenue' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $t->type === 'revenue' ? '+' : '-' }}{{ number_format($t->amount, 2) }} MAD
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $t->mode_paiement ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($t->attachments->count() > 0)
                                <span class="inline-flex items-center gap-1 text-indigo-600 text-xs">
                                    <i class="ri-attachment-line"></i> {{ $t->attachments->count() }}
                                </span>
                                @else
                                <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.finance.show', $t->id) }}" class="p-1.5 rounded-lg hover:bg-blue-50 text-blue-600 transition" title="Voir">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.finance.edit', $t->id) }}" class="p-1.5 rounded-lg hover:bg-indigo-50 text-indigo-600 transition" title="Modifier">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <a href="{{ route('admin.finance.print', $t->id) }}" target="_blank" class="p-1.5 rounded-lg hover:bg-green-50 text-green-600 transition" title="Imprimer PDF">
                                        <i class="ri-printer-line"></i>
                                    </a>
                                    <button wire:click="deleteTransaction({{ $t->id }})" wire:confirm="Supprimer cette transaction ?"
                                            class="p-1.5 rounded-lg hover:bg-red-50 text-red-600 transition" title="Supprimer">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center">
                                <i class="ri-exchange-funds-line text-4xl text-gray-300 block mb-2"></i>
                                <p class="text-gray-500">Aucune transaction trouvée</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $transactions->links() }}</div>
        </div>
        @endif

        {{-- ═══ CHARGES TAB ═══ --}}
        @if($tab === 'charges')
        <div class="p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Charges Récurrentes (Eau, WiFi, Électricité, Loyer...)</h3>
                <a href="{{ route('admin.finance.charges.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <i class="ri-add-line mr-1"></i> Nouvelle Charge
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($charges as $charge)
                <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $charge->label }}</h4>
                            <p class="text-xs text-gray-500">{{ $charge->fournisseur ?? 'Non spécifié' }}</p>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $charge->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $charge->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Montant:</span><span class="font-bold text-red-600">{{ number_format($charge->montant, 2) }} MAD</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Fréquence:</span><span class="font-medium capitalize">{{ $charge->frequence }}</span></div>
                        @if($charge->date_echeance)
                        <div class="flex justify-between"><span class="text-gray-500">Échéance:</span><span>{{ $charge->date_echeance->format('d/m/Y') }}</span></div>
                        @endif
                    </div>
                    <div class="flex gap-2 mt-3 pt-3 border-t border-gray-100">
                        <button wire:click="deleteCharge({{ $charge->id }})" wire:confirm="Supprimer cette charge ?"
                                class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-lg transition">
                            <i class="ri-delete-bin-line mr-1"></i> Supprimer
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <i class="ri-repeat-line text-4xl text-gray-300 block mb-2"></i>
                    <p class="text-gray-500">Aucune charge récurrente</p>
                </div>
                @endforelse
            </div>
        </div>
        @endif

        {{-- ═══ CATEGORIES TAB ═══ --}}
        @if($tab === 'categories')
        <div class="p-4">
            <div class="flex flex-wrap gap-4 items-end mb-6 bg-gray-50 rounded-lg p-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nom de la catégorie</label>
                    <input type="text" wire:model="newCatName" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none" placeholder="Ex: Fournitures de bureau">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Type</label>
                    <select wire:model="newCatType" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="depense">Dépense</option>
                        <option value="revenue">Revenue</option>
                    </select>
                </div>
                <button wire:click="createCategory" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <i class="ri-add-line mr-1"></i> Ajouter
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-400"></span> Catégories Dépenses
                    </h4>
                    @foreach($categories->where('type', 'depense') as $cat)
                    <div class="flex items-center justify-between bg-white rounded-lg border border-gray-200 px-4 py-2.5 mb-2">
                        <span class="text-sm font-medium text-gray-800">{{ $cat->name }}</span>
                        <button wire:click="deleteCategory({{ $cat->id }})" wire:confirm="Supprimer cette catégorie ?"
                                class="text-red-500 hover:text-red-700 text-xs"><i class="ri-close-line"></i></button>
                    </div>
                    @endforeach
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-green-400"></span> Catégories Revenus
                    </h4>
                    @foreach($categories->where('type', 'revenue') as $cat)
                    <div class="flex items-center justify-between bg-white rounded-lg border border-gray-200 px-4 py-2.5 mb-2">
                        <span class="text-sm font-medium text-gray-800">{{ $cat->name }}</span>
                        <button wire:click="deleteCategory({{ $cat->id }})" wire:confirm="Supprimer cette catégorie ?"
                                class="text-red-500 hover:text-red-700 text-xs"><i class="ri-close-line"></i></button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
