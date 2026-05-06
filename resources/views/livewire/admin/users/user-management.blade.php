<div x-data="{ tab: 'users' }" x-cloak>
    <!-- Page Header -->
    <div class="mb-8">
        <div class="text-m font-bold text-[#066E1B] uppercase tracking-wide mb-2">SYSTEM CONFIGURATION</div>
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-[36px] font-bold text-[#04103A]">Gestion des accès utilisateur</h1>
                <p class="text-gray-600 text-[18px] mt-2">Gérer les hiérarchies organisationnelles en créant des autorisations granulaires pour les membres de l'équipe au sein de l'écosystème Initiative Urbaine</p>
            </div>
            @if(in_array($currentUserRole, ['admin', 'super_admin'], true))
            <button wire:click="openCreateModal" class="w-65 h-12.5 text-center p-2 content-center bg-[#1B264F] text-white text-[16px] font-normal rounded-full hover:bg-gray-800 transition">
                <i class="ri-shield-user-line text-[19px] relative right-1" ></i> Créer un utilisateur
            </button>
            @endif
        </div>
    </div>

    @if (session()->has('success'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 3000)" 
            x-show="show"
            x-transition
            class="fixed top-5 right-5 z-50"
        >
            <div class="flex items-center gap-3 bg-green-500 text-white px-5 py-3 rounded-xl shadow-lg">
                <i class="ri-checkbox-circle-fill text-xl"></i>
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-2 text-white hover:opacity-70">
                    ✕
                </button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 3000)" 
            x-show="show"
            x-transition
            class="fixed top-5 right-5 z-50"
        >
            <div class="flex items-center gap-3 bg-red-500 text-white px-5 py-3 rounded-xl shadow-lg">
                <i class="ri-error-warning-fill text-xl"></i>
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="ml-2 text-white hover:opacity-70">
                    ✕
                </button>
            </div>
        </div>
    @endif

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @foreach($stat_section as $stat)
            <div class="bg-white rounded-[30px] shadow-sm h-32 p-6 border border-gray-100">
                <div class="flex justify-between  items-start">
                    <div>
                        <p class="text-gray-500 text-[18px] font-bold ">{{ $stat['label'] }}</p>
                        <p class="text-3xl font-bold text-[#04103A] mt-5">{{ $stat['value'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-[#9af89330] rounded-lg flex items-center justify-center">
                        <i class="{{ $stat['icon'] }} text-[#066E1B] text-[21px]"></i>
                    </div>
                </div>
            </div>
        @endforeach


    </div>

    <!-- Users Table -->
    <div>
        <!-- Tabs -->
        <div class="mb-10 rounded-t-xl border-gray-100">
            <div class="flex border-b border-gray-100 px-6">
                <button @click.prevent="tab = 'users'" x-bind:class="tab === 'users' ? 'px-6 py-4 text-[18px] font-bold text-[#172554] border-b-2 border-green-600' : 'px-6 py-4 text-[18px] font-bold text-[#172554] border-b-2 border-transparent hover:text-gray-700'">
                    Liste des utilisateurs IUHM
                </button>
                <button @click.prevent="tab = 'beneficiaries'" x-bind:class="tab === 'beneficiaries' ? 'px-6 py-4 text-[18px] font-bold text-[#172554] border-b-2 border-green-600' : 'px-6 py-4 text-[18px] font-bold text-[#172554] border-b-2 border-transparent hover:text-gray-700'">
                    Liste des Bénéficiaires
                </button>
                <button @click.prevent="tab = 'logs'" x-bind:class="tab === 'logs' ? 'px-6 py-4 text-[18px] font-bold text-[#172554] border-b-2 border-green-600' : 'px-6 py-4 text-[18px] font-bold text-[#172554] border-b-2 border-transparent hover:text-gray-700'">
                    Logs d'audit
                </button>
            </div>
        </div>

        <div x-show="tab === 'users'" class="space-y-4">
            <div class=" rounded-b-xl overflow-hidden ">
                <div class="px-6 py-4 flex justify-between gap-3">
                    <select wire:model.live="adminRoleFilter" class="h-11 border border-gray-300 rounded-full text-sm py-2 px-4 bg-white">
                        <option value="all">Tous les rôles</option>
                        @foreach($allRoles as $role)
                            <option value="{{ $role['name'] }}">{{ $role['label'] }}</option>
                        @endforeach
                    </select>

                    <div class="group relative w-10 hover:w-72 focus-within:w-72 overflow-hidden transition-all duration-300">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                           <input type="text"
                               wire:model.live="adminSearch"
                               placeholder="Rechercher un administrateur..."
                               class="h-11 w-full pl-10 pr-4 border border-gray-300 rounded-full text-sm   outline-none bg-white">
                    </div>
                </div>

                <div class="px-6 py-4 flex items-center justify-between gap-3 text-sm text-gray-600">
                    <div>
                        Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }} sur {{ $users->total() }} utilisateurs
                    </div>
                    <div>{{ $users->links('vendor.pagination.circle') }}</div>
                </div>

                <table class="w-full">
                    <thead>
                        <tr class="bg-[#04103A] border-b border-gray-100">
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase rounded-tl-[10px]">ID</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase" width="10%">E-MAIL</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">PRÉNOM</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">CRÉATION/MISE À JOUR</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">RÔLE</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">STATUT</th>
                            <th class="px-6 py-5 text-center text-xs font-semibold text-white uppercase rounded-tr-[10px]">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @if($users->count() > 0)
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-200 bg-gray-100 transition-colors font-bold" style="border-bottom: 10px solid #fbf8fd;">
                                    <td class="px-6 py-4 text-sm text-[#04103A]">{{ $user->id }}</td>
                                    <td class="px-6 py-4 text-sm text-[#04103A]">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-sm text-[#04103A]">
                                        <div>{{ $user->nom }}</div>
                                        <div class="text-xs text-[#04103A] mt-1">{{ $user->prenom ?? $user->nom }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-[#04103A]">
                                        <div>{{ $user->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-[#04103A] mt-1">{{ $user->updated_at->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-[#04103A]">
                                            @php
                                                $roleModel = collect($allRoles)->firstWhere('name', $user->role);
                                                $cls = \App\Models\Role::colorClasses(data_get($roleModel, 'color', 'gray'));
                                            @endphp
                                        <span class="px-3 py-1 text-xs rounded-full {{ $cls['badge'] }}">
                                            {{ data_get($roleModel, 'label', ucfirst(str_replace('_', ' ', $user->role))) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-[#04103A]">
                                        @if($user->is_active ?? true)
                                            <span class="inline-flex items-center gap-2">
                                                <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                                                <span class="text-green-600">Active</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2">
                                                <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                                <span class="text-gray-500">Deactivated</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        <div class="flex justify-center gap-2">
                                            <button wire:click="openEditModal({{ $user->id }}, 'admin')"
                                               class="p-2 text-gray-600 hover:text-[#04103A] hover:bg-gray-100 rounded-lg transition"
                                               title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </button>
                                            @if($currentUserRole === 'super_admin')
                                                <button wire:click="openDeleteModal({{ $user->id }})"
                                                    class="p-2 text-red-600 hover:text-red-900 hover:bg-red-100 rounded-lg transition"
                                                    title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="7">
                                    <div class="p-12 text-center">
                                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <p class="text-gray-500">Aucun utilisateur trouvé</p>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>


                <div class="px-6 py-4 flex items-center justify-between gap-3 text-sm text-gray-600">
                    <div>
                        Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }} sur {{ $users->total() }} utilisateurs
                    </div>
                    <div>{{ $users->links('vendor.pagination.circle') }}</div>
                </div>
            </div>
        </div>

        <div x-show="tab === 'beneficiaries'" class="space-y-4">
            <div class=" rounded-b-xl overflow-hidden ">
                <div class="px-6 py-4 flex justify-between gap-3">


                    <select wire:model.live="candidatStatusFilter" class="h-11 border border-gray-300 rounded-full text-sm py-2 px-4 bg-white">
                        <option value="all">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="inactive">Désactivé</option>
                    </select>

                    <div class="group relative w-10 hover:w-72 focus-within:w-72 overflow-hidden transition-all duration-300">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                           <input type="text"
                               wire:model.live="candidatSearch"
                               placeholder="Rechercher un bénéficiaire..."
                               class="h-11 w-full pl-10 pr-4 border border-gray-300 rounded-full text-sm  outline-none bg-white">
                    </div>
                </div>

                <div class="px-6 py-4 flex items-center justify-between gap-3 text-sm text-gray-600">
                    <div>
                        Affichage de {{ $candidat->firstItem() ?? 0 }} à {{ $candidat->lastItem() ?? 0 }} sur {{ $candidat->total() }} bénéficiaires
                    </div>
                    <div>{{ $candidat->links('vendor.pagination.circle') }}</div>
                </div>

                <table class="w-full">
                    <thead>
                        <tr class="bg-[#04103A] border-b border-gray-100">
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase rounded-tl-[10px]">ID</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase" width="10%">E-MAIL</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">PRÉNOM</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">CRÉATION/MISE À JOUR</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">MATRICULE</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">STATUT</th>
                            <th class="px-6 py-5 text-center text-xs font-semibold text-white uppercase rounded-tr-[10px]">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @if($candidat->count() > 0)
                            @foreach($candidat as $item)
                                <tr class="hover:bg-gray-200 bg-gray-100 transition-colors text-[#04103A] font-bold" style="border-bottom: 15px solid #fbf8fd;">
                                    <td class="px-6 py-4 text-sm">{{ $item->id }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $item->email }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <div>{{ $item->nom }}</div>
                                        <div class="text-xs mt-1">{{ $item->prenom }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div>{{ $item->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs mt-1">{{ $item->updated_at->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ $item->matricule ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($item->is_active ?? true)
                                            <span class="inline-flex items-center gap-2">
                                                <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                                                <span class="text-green-600">Active</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2">
                                                <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                                <span class="text-gray-500">Deactivated</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        <div class="flex justify-center gap-2">
                                             <button wire:click="openEditModal({{ $item->id }}, 'candidat')"
                                               class="p-2 text-gray-600 hover:text-[#04103A] hover:bg-gray-100 rounded-lg transition"
                                               title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </button>
                                            @if($currentUserRole === 'super_admin')
                                                <button wire:click="openDeleteModal({{ $item->id }})"
                                                    class="p-2 text-red-600 hover:text-red-900 hover:bg-red-100 rounded-lg transition"
                                                    title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="7">
                                    <div class="p-12 text-center">
                                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <p class="text-gray-500">Aucun bénéficiaire trouvé</p>
                                    </div>
                                </td>
                            </tr>
                            @endif

                        </tbody>
                    </table>

                <div class="px-6 py-4 flex items-center justify-between gap-3 text-sm text-gray-600">
                    <div>
                        Affichage de {{ $candidat->firstItem() ?? 0 }} à {{ $candidat->lastItem() ?? 0 }} sur {{ $candidat->total() }} bénéficiaires
                    </div>
                    <div>{{ $candidat->links('vendor.pagination.circle') }}</div>
                </div>
            </div>
        </div>

        <div x-show="tab === 'logs'" class="border border-gray-100 rounded-b-xl bg-white p-6">
            <div class="text-gray-700">
                <h3 class="text-lg font-semibold mb-2">Logs d'audit</h3>
                <p class="text-sm text-gray-500">Contenu des logs d'audit disponible ici (coming soon).</p>
            </div>
        </div>

    </div>

    {{-- include cleaned modal to avoid duplicates and ensure correct bindings --}}
    @include('livewire.admin.users.user-management-modal-clean')

    <!-- Delete Confirmation Modal -->
    <div x-data="{ open: @entangle('showDeleteModal').live }" x-cloak>
        <div
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center"
        >
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

            {{-- Panel --}}
            <div
                x-show="open"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 z-10 overflow-hidden"
                @click.stop
            >
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-[#04103A]">Delete Admin</h3>
                    </div>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5">
                    <p class="text-gray-600 text-sm">
                        Are you sure you want to delete
                        <span class="font-semibold text-[#04103A]">{{ trim(($selectedUser?->nom ?? '') . ' ' . ($selectedUser?->prenom ?? '')) ?: ($selectedUser?->name ?? 'this user') }}</span>?
                        This action is permanent and cannot be undone.
                    </p>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <button
                        @click="open = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-200 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="deleteUser"
                        wire:loading.attr="disabled"
                        class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors disabled:opacity-60"
                    >
                        <svg wire:loading wire:target="deleteUser" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        Delete Admin
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
