<div class="max-w-7xl mx-auto">

    {{-- Feedback --}}
    @if($successMsg)
    <div class="mb-5 flex items-center gap-2 p-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl">
        <i class="ri-check-circle-fill text-lg text-green-500"></i> {{ $successMsg }}
        <button wire:click="$set('successMsg', null)" class="ml-auto text-green-400 hover:text-green-600"><i class="ri-close-line"></i></button>
    </div>
    @endif
    @if($errorMsg)
    <div class="mb-5 flex items-center gap-2 p-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl">
        <i class="ri-error-warning-fill text-lg text-red-500"></i> {{ $errorMsg }}
        <button wire:click="$set('errorMsg', null)" class="ml-auto text-red-400 hover:text-red-600"><i class="ri-close-line"></i></button>
    </div>
    @endif

    {{-- ═══ Header ═══ --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Rôles & Permissions</h2>
            <p class="text-sm text-gray-500 mt-1">Créez des rôles personnalisés et configurez leurs accès aux modules.</p>
        </div>
        <button wire:click="openCreate"
                class="flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
            <i class="ri-add-line"></i> Nouveau rôle
        </button>
    </div>

    {{-- ═══ Stats ═══ --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        @foreach([
            ['icon' => 'ri-shield-user-line',    'color' => 'indigo', 'label' => 'Total rôles',        'val' => $roles->count()],
            ['icon' => 'ri-lock-line',           'color' => 'purple', 'label' => 'Rôles système',      'val' => $roles->where('is_system', true)->count()],
            ['icon' => 'ri-user-settings-line',  'color' => 'green',  'label' => 'Rôles personnalisés','val' => $roles->where('is_system', false)->count()],
        ] as $stat)
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 bg-{{ $stat['color'] }}-100 rounded-lg flex items-center justify-center shrink-0">
                <i class="{{ $stat['icon'] }} text-{{ $stat['color'] }}-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">{{ $stat['label'] }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stat['val'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ═══ Roles Grid ═══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($roles as $role)
        @php $cls = \App\Models\Role::colorClasses($role->color); @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="h-1.5 {{ $cls['bar'] }}"></div>
            <div class="p-5">

                {{-- Role identity --}}
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full {{ $cls['bg'] }} flex items-center justify-center text-white font-bold text-sm shrink-0">
                            {{ strtoupper(substr($role->label, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 leading-tight">{{ $role->label }}</h3>
                            <code class="text-xs text-gray-400 font-mono">{{ $role->name }}</code>
                        </div>
                    </div>
                    @if($role->is_system)
                    <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded-full font-medium shrink-0">Système</span>
                    @endif
                </div>

                {{-- Badges --}}
                <div class="flex items-center gap-2 mb-4 flex-wrap">
                    <span class="px-2.5 py-0.5 text-xs rounded-full {{ $cls['badge'] }} font-medium">
                        @if($role->name === 'super_admin')
                            <i class="ri-star-line mr-0.5"></i> Accès total
                        @else
                            <i class="ri-key-line mr-0.5"></i> {{ $role->permissions_count }} module(s)
                        @endif
                    </span>
                    @if($role->can_access_admin)
                    <span class="px-2.5 py-0.5 text-xs rounded-full bg-sky-50 text-sky-700 font-medium">
                        <i class="ri-computer-line mr-0.5"></i> Admin
                    </span>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 border-t border-gray-100 pt-3">
                    <button wire:click="openPermissions('{{ $role->name }}')"
                            class="flex-1 text-center py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                        <i class="ri-shield-keyhole-line mr-1"></i> Permissions
                    </button>
                    <button wire:click="openEdit({{ $role->id }})"
                            class="flex-1 text-center py-1.5 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                        <i class="ri-edit-line mr-1"></i> Modifier
                    </button>
                    @if(!$role->is_system)
                    <button wire:click="deleteRole({{ $role->id }})"
                            wire:confirm="Supprimer le rôle « {{ $role->label }} » ?"
                            class="py-1.5 px-3 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ══════════════════════════════════════════════════════════
         CREATE / EDIT ROLE MODAL
    ══════════════════════════════════════════════════════════ --}}
    @if($showRoleModal)
    <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" wire:click.self="$set('showRoleModal', false)">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md" wire:key="role-modal">

            {{-- Modal header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">
                    {{ $editingRoleId ? 'Modifier le rôle' : 'Nouveau rôle' }}
                </h3>
                <button wire:click="$set('showRoleModal', false)" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            {{-- Modal body --}}
            <div class="p-6 space-y-5">

                {{-- Technical name (hidden for system roles) --}}
                @if(!$editingIsSystem)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nom technique
                        <span class="text-gray-400 font-normal text-xs ml-1">lettres_minuscules, chiffres et _</span>
                    </label>
                    <input type="text" wire:model="roleName" placeholder="ex: responsable_rh"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-indigo-400 outline-none transition">
                    @error('roleName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @else
                <div class="p-3 bg-gray-50 rounded-lg flex items-center gap-2 text-sm text-gray-600">
                    <i class="ri-lock-line text-gray-400"></i>
                    Nom technique : <code class="font-mono font-medium ml-1">{{ $roleName }}</code>
                    <span class="ml-auto text-xs text-gray-400">(non modifiable)</span>
                </div>
                @endif

                {{-- Display label --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom d'affichage</label>
                    <input type="text" wire:model="roleLabel" placeholder="ex: Responsable Régional"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">
                    @error('roleLabel') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Color picker --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Couleur du badge</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($colors as $c)
                        @php
                            $bgMap = [
                                'blue'   => 'bg-blue-500',   'green'  => 'bg-green-500',
                                'red'    => 'bg-red-500',    'yellow' => 'bg-yellow-400',
                                'purple' => 'bg-purple-500', 'orange' => 'bg-orange-500',
                                'pink'   => 'bg-pink-500',   'indigo' => 'bg-indigo-500',
                                'gray'   => 'bg-gray-400',
                            ];
                        @endphp
                        <button type="button" wire:click="$set('roleColor', '{{ $c }}')"
                                title="{{ ucfirst($c) }}"
                                class="w-8 h-8 rounded-full {{ $bgMap[$c] ?? 'bg-gray-400' }} transition-transform
                                       {{ $roleColor === $c ? 'ring-2 ring-offset-2 ring-gray-600 scale-110' : 'hover:scale-105' }}">
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Can access admin toggle --}}
                <!-- <div class="flex items-center justify-between p-3.5 bg-gray-50 border border-gray-200 rounded-xl">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Accès panel admin</p>
                        <p class="text-xs text-gray-500 mt-0.5">Ce rôle peut se connecter à l'interface admin</p>
                    </div>
                    <button type="button" wire:click="$set('canAccessAdmin', {{ $canAccessAdmin ? 'false' : 'true' }})"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors
                                   {{ $canAccessAdmin ? 'bg-indigo-600' : 'bg-gray-300' }}">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                                     {{ $canAccessAdmin ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div> -->
            </div>

            {{-- Modal footer --}}
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                <button wire:click="$set('showRoleModal', false)"
                        class="px-5 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Annuler
                </button>
                <button wire:click="saveRole" wire:loading.attr="disabled"
                        class="px-5 py-2 text-sm font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition disabled:opacity-50">
                    <span wire:loading.remove wire:target="saveRole">Enregistrer</span>
                    <span wire:loading wire:target="saveRole"><i class="ri-loader-4-line animate-spin mr-1"></i>...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════
         PERMISSIONS MODAL
    ══════════════════════════════════════════════════════════ --}}
    @if($showPermsModal)
    <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" wire:click.self="$set('showPermsModal', false)">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col" wire:key="perms-modal">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Permissions — {{ $permsRoleLabel }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Cochez les modules que ce rôle peut accéder</p>
                </div>
                <button wire:click="$set('showPermsModal', false)" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            @if($permsIsSuperAdmin)
            {{-- Super admin notice --}}
            <div class="p-10 text-center text-gray-500 flex-1">
                <i class="ri-shield-star-fill text-5xl text-purple-200 block mb-3"></i>
                <p class="font-semibold text-gray-700">Super Admin a accès à tous les modules</p>
                <p class="text-sm mt-1">Les permissions ne sont pas modifiables pour ce rôle système.</p>
            </div>
            @else
            {{-- Module checkboxes --}}
            <div class="flex-1 overflow-y-auto p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-gray-500">{{ count($selectedPerms) }} module(s) sélectionné(s) sur {{ count($allModules) }}</p>
                    <div class="flex gap-2">
                        <button type="button"
                                wire:click="$set('selectedPerms', {{ json_encode(array_keys($allModules)) }})"
                                class="text-xs text-indigo-600 hover:underline">Tout sélectionner</button>
                        <span class="text-gray-300">|</span>
                        <button type="button"
                                wire:click="$set('selectedPerms', [])"
                                class="text-xs text-red-500 hover:underline">Tout désélectionner</button>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    @foreach($allModules as $key => $def)
                    <label wire:key="perm-{{ $key }}"
                           class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition
                                  {{ in_array($key, $selectedPerms)
                                        ? 'border-indigo-400 bg-indigo-50 shadow-sm'
                                        : 'border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/30' }}">
                        <input type="checkbox" wire:model="selectedPerms" value="{{ $key }}"
                               class="w-4 h-4 rounded text-indigo-600 border-gray-300 focus:ring-indigo-400 shrink-0">
                        <i class="{{ $def['icon'] }} text-base {{ in_array($key, $selectedPerms) ? 'text-indigo-500' : 'text-gray-400' }}"></i>
                        <span class="text-sm font-medium text-gray-700">{{ $def['label'] }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl shrink-0">
                <span class="text-xs text-gray-500">
                    <i class="ri-information-line mr-1"></i>
                    Les changements prennent effet immédiatement.
                </span>
                <div class="flex gap-3">
                    <button wire:click="$set('showPermsModal', false)"
                            class="px-5 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button wire:click="savePermissions" wire:loading.attr="disabled"
                            class="px-5 py-2 text-sm font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition disabled:opacity-50">
                        <span wire:loading.remove wire:target="savePermissions">Enregistrer</span>
                        <span wire:loading wire:target="savePermissions"><i class="ri-loader-4-line animate-spin mr-1"></i>...</span>
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

</div>
