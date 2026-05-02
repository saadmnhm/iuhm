    <div class="">

        <div class="px-6 pb-6 pt-8 sm:px-8 sm:pb-8 sm:pt-10">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-emerald-700">System Configuration</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Gestion des rôles utilisateur</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">
                        Gérer les hiérarchies organisationnelles en créant des autorisations granulaires pour les membres de l'équipe au sein de l'écosystème Initiative Urbaine
                    </p>
                </div>

                <button type="button" wire:click="openCreate()" class="inline-flex items-center gap-2 self-start rounded-full bg-[#0f1d57] px-5 py-3 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(15,23,42,0.22)] transition hover:bg-[#14256f]">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full border border-white/25 bg-white/10">
                        <i class="ri-user-add-line text-sm"></i>
                    </span>
                    Créer un rôle
                </button>
            </div>

            @if($successMsg)
            <div class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
                <i class="ri-check-line text-lg text-emerald-600"></i>
                <span>{{ $successMsg }}</span>
                <button wire:click="$set('successMsg', null)" class="ml-auto text-emerald-500 hover:text-emerald-700"><i class="ri-close-line text-lg"></i></button>
            </div>
            @endif
            @if($errorMsg)
            <div class="mt-6 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-sm">
                <i class="ri-error-warning-line text-lg text-rose-500"></i>
                <span>{{ $errorMsg }}</span>
                <button wire:click="$set('errorMsg', null)" class="ml-auto text-rose-500 hover:text-rose-700"><i class="ri-close-line text-lg"></i></button>
            </div>
            @endif

            <div class="mt-8 flex items-center gap-8 border-b border-slate-200">
                <button type="button" wire:click="setTab('roles')" class="relative pb-4 text-sm font-semibold {{ $tab === 'roles' ? 'text-slate-900' : 'text-slate-400' }} after:absolute after:inset-x-0 after:bottom-0 after:h-0.5 after:rounded-full {{ $tab === 'roles' ? 'after:bg-emerald-500' : 'after:bg-transparent' }}">
                    Liste des rôles
                </button>
                <button type="button" wire:click="setTab('logs')" class="relative pb-4 text-sm font-medium {{ $tab === 'logs' ? 'text-slate-900' : 'text-slate-400' }} after:absolute after:inset-x-0 after:bottom-0 after:h-0.5 after:rounded-full {{ $tab === 'logs' ? 'after:bg-emerald-500' : 'after:bg-transparent' }}">
                    Logs d'audit
                </button>
            </div>
        </div>

        <div class="px-4 pb-8 sm:px-6">
            @if($tab === 'roles')
            <div class="overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-[0_18px_40px_rgba(15,23,42,0.06)]">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-[#0f1d57] text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.16em]">Date de création</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.16em]">Nom du rôle</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.16em]">Permissions</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.16em]">Status</th>
                                <th class="px-6 py-4 text-center text-[11px] font-bold uppercase tracking-[0.16em]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-slate-50/50">
                            @forelse($roles as $role)
                            @php
                                $cls = \App\Models\Role::colorClasses($role->color);
                                $permissionKeys = $role->relationLoaded('permissions') ? $role->permissions->pluck('module_key')->values() : collect();
                                $permissionLabels = $permissionKeys->map(function ($key) use ($allModules) {
                                    return $allModules[$key]['label'] ?? $key;
                                })->values();
                                $badgeCount = max($permissionLabels->count() - 2, 0);
                                $statusLabel = $role->can_access_admin ? 'Active' : 'Deactivated';
                                $statusClasses = $role->can_access_admin
                                    ? 'bg-emerald-100 text-emerald-700 ring-emerald-200'
                                    : 'bg-slate-200 text-slate-500 ring-slate-200';
                            @endphp
                            <tr class="transition hover:bg-white">
                                <td class="px-6 py-6 text-[16px] font-semibold text-[#45464E]">
                                    {{ $role->created_at ? $role->created_at->format('d M Y') : '—' }}
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-4">
                                        <div>
                                            <div class="font-bold text-[16px] text-[#04103A]">{{ $role->label }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                        @foreach($permissionLabels->take(2) as $label)
                                        <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-full border border-white bg-emerald-200 px-2 text-[10px] font-black uppercase tracking-wide text-emerald-800 shadow-sm">
                                            {{ \Illuminate\Support\Str::of($label)->replace(['Gestion des ', 'Gestion '], '')->words(2, '')->upper()->substr(0, 3) }}
                                        </span>
                                        @endforeach
                                        <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-full border border-white bg-[#DDE1FF] px-2 text-[10px] font-black uppercase tracking-wide text-emerald-800 shadow-sm">ALL</span>
                                        @if($badgeCount > 0)
                                        <span class="inline-flex h-7 items-center justify-center rounded-full border border-white bg-slate-100 px-2 text-[11px] font-semibold text-slate-500 shadow-sm">+{{ $badgeCount }}</span>
                                        @endif
                                </td>
                                <td class="px-6 py-6">
                                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold ring-1 {{ $statusClasses }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $role->can_access_admin ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center justify-center gap-2 text-[#0f1d57]">
                                        <button type="button" wire:click="openEdit({{ $role->id }})" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#0f1d57]/5 transition hover:bg-[#0f1d57] hover:text-white" title="Modifier">
                                            <i class="ri-edit-2-line text-base"></i>
                                        </button>
                                        <button type="button" wire:click="openDeleteModal({{ $role->id }})" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-600 hover:text-white" title="Supprimer">
                                            <i class="ri-delete-bin-2-fill text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">
                                    Aucun rôle disponible.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col gap-4 border-t border-slate-200 bg-white px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-500">
                        Affichage de {{ $roles->firstItem() ?? 0 }} à {{ $roles->lastItem() ?? 0 }} sur {{ $roles->total() }} rôles
                    </p>
                    <div>{{ $roles->links('vendor.pagination.circle') }}</div>
                </div>
            </div>
            @else
            <div class="overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-[0_18px_40px_rgba(15,23,42,0.06)]">
                <div class="border-b border-slate-200 bg-slate-50 p-5">
                    <div class="grid gap-3 md:grid-cols-4">
                        <div class="relative md:col-span-2">
                            <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" wire:model.live="logsSearch" placeholder="Rechercher..." class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
                        </div>
                        <select wire:model.live="logsActionFilter" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
                            <option value="all">Toutes les actions</option>
                            @foreach($actions as $action)
                            <option value="{{ $action }}">{{ ucfirst(str_replace('_', ' ', $action)) }}</option>
                            @endforeach
                        </select>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="date" wire:model.live="logsDateFrom" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
                            <input type="date" wire:model.live="logsDateTo" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-[#0f1d57] text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.16em]">Utilisateur</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.16em]">Action</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.16em]">Description</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.16em]">IP</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.16em]">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-slate-50/50">
                            @forelse(($logs ?? collect()) as $log)
                            <tr class="transition hover:bg-white">
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-slate-900">{{ optional($log->user)->name ?? 'Système' }}</div>
                                    <div class="text-xs text-slate-400">{{ optional($log->user)->email ?? '—' }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">{{ str_replace('_', ' ', $log->action) }}</span>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-600">{{ $log->description }}</td>
                                <td class="px-6 py-5 text-sm text-slate-500">{{ $log->ip_address }}</td>
                                <td class="px-6 py-5 text-sm text-slate-500">{{ $log->created_at?->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">Aucun log trouvé.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 px-6 py-5">
                    <div>{{ $logs?->links('vendor.pagination.circle') }}</div>
                </div>
            </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════════════
             MODALS CONTAINER
        ══════════════════════════════════════════════════════════ --}}
        <div class="modals-container">
    @if($showRoleModal)
    @php
        $wizardSteps = [
            1 => 'Nom du rôle',
            2 => 'Permissions',
            3 => 'Validation',
        ];
        $selectedPermissionCount = count($roleWizardPerms);
    @endphp
    <div x-show="$wire.showRoleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 p-2 md:p-4 backdrop-blur-sm overflow-y-auto" wire:click.self="$set('showRoleModal', false)" style="display: none;">
        <div class="w-full max-w-lg md:max-w-2xl lg:max-w-3xl overflow-hidden rounded-2xl md:rounded-[20px] bg-white shadow-[0_30px_90px_rgba(0,0,0,0.35)] my-4 md:my-0 flex flex-col max-h-[90vh]" wire:key="role-modal">
            <div class="flex items-start justify-between gap-2 px-4 md:px-6 pb-3 md:pb-4 pt-4 md:pt-5 shrink-0">
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg md:text-2xl font-extrabold tracking-tight text-[#0f1d57] truncate">
                        {{ $editingRoleId ? 'Modifier un rôle utilisateur' : 'Ajouter un nouveau rôle utilisateur' }}
                    </h3>
                    <p class="mt-0.5  text-[16px]  text-[#45464E]">Définissez les niveaux d'accès pour ce profil.</p>
                </div>
                <button type="button" type="button" wire:click="$set('showRoleModal', false)" class="inline-flex h-8 md:h-9 w-8 md:w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 flex-shrink-0">
                    <i class="ri-close-line text-lg md:text-xl"></i>
                </button>
            </div>

            <div class="px-4 md:px-6 pb-4 md:pb-6 shrink-0 border-b border-slate-200">
                <div class="flex items-center gap-1 md:gap-2 overflow-x-auto pb-2 scrollbar-hide">
                    @foreach($wizardSteps as $step => $label)
                    @php
                        $isActive = $roleModalStep === $step;
                        $isDone = $roleModalStep > $step;
                    @endphp
                    <div class="flex items-center gap-1 md:gap-2 flex-shrink-0">
                        <div class="flex items-center gap-1.5 md:gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full text-[10px] md:text-[11px] font-bold {{ $isDone ? 'bg-emerald-600 text-white' : ($isActive ? 'bg-[#0f1d57] text-white' : 'border border-slate-300 text-slate-400') }}">
                                {{ $isDone ? '✓' : $step }}
                            </span>
                            <span class="text-[15px] font-bold  uppercase tracking-[0.14em] {{ $isActive ? 'text-[#0f1d57]' : ($isDone ? 'text-emerald-700' : 'text-slate-400') }} whitespace-nowrap">{{ $label }}</span>
                        </div>
                        @if($step < 3)
                        <div class="mx-1 md:mx-2 h-px w-8 md:w-14 {{ $roleModalStep > $step ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="px-4 md:px-6 py-4 md:py-6 flex-1 overflow-y-auto">
                @if($roleModalStep === 1)
                <div class="space-y-5">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-800">Entrer le nom du rôle à créer</label>
                        <input type="text" wire:model="roleLabel" placeholder="ex: Coordinateur de projet" class="w-full rounded-2xl text-[#76767F] bg-[#E4E1E6] px-5 py-4 text-[18px] font-semibold outline-none transition ">
                        @error('roleLabel') <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p> @enderror
                        <p class="mt-2 text-center text-xs text-slate-500">Le nom doit être unique et comporter au moins 3 caractères.</p>
                    </div>

                </div>
                @elseif($roleModalStep === 2)
                <div class="space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <div class="flex items-center justify-between gap-4">
                            <label class="flex items-center gap-3 text-sm font-bold uppercase tracking-[0.14em] text-[#0f1d57]">
                                <input type="checkbox" wire:click="$set('roleWizardPerms', {{ json_encode(array_keys($allModules)) }})" class="h-4 w-4 rounded border-slate-300 text-[#0f1d57] focus:ring-[#0f1d57]">
                                Tout sélectionner
                            </label>
                            <span class="text-xs text-slate-500">{{ count($allModules) }} permissions disponibles</span>
                        </div>
                    </div>

                    <div class="grid gap-3 rounded-3xl border border-slate-200 bg-[#f6f4fb] p-4 sm:grid-cols-2">
                        @foreach($allModules as $key => $def)
                        <label wire:key="wizard-perm-{{ $key }}" class="flex cursor-pointer items-start gap-3 rounded-2xl border border-transparent bg-white px-4 py-3 transition hover:border-slate-200 hover:shadow-sm">
                            <input type="checkbox" wire:model="roleWizardPerms" value="{{ $key }}" class="mt-1 h-4 w-4 rounded border-slate-300 text-[#0f1d57] focus:ring-[#0f1d57]">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">{{ $def['label'] }}</div>
                                <p class="mt-1 text-xs leading-5 text-slate-500">{{ $def['description'] ?? "Autoriser l'accès à ce module." }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-800">
                        <i class="ri-information-line mr-2 align-middle"></i>
                        Les permissions accordées ici seront immédiatement actives dès la validation finale.
                    </div>
                </div>
                @else
                <div class="space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Nom affiché</p>
                            <p class="mt-2 text-lg font-semibold text-slate-900">{{ $roleLabel }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $roleName }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Résumé des accès</p>
                            <p class="mt-2 text-lg font-semibold text-slate-900">{{ $selectedPermissionCount }} permission(s)</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $canAccessAdmin ? 'Accès admin activé' : 'Accès admin désactivé' }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-700">Permissions sélectionnées</span>
                            <span class="text-xs text-slate-400">{{ $selectedPermissionCount }} au total</span>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @forelse($roleWizardPerms as $permissionKey)
                                <span class="rounded-full bg-[#0f1d57]/5 px-3 py-1 text-xs font-semibold text-[#0f1d57]">{{ $allModules[$permissionKey]['label'] ?? $permissionKey }}</span>
                            @empty
                                <span class="text-sm text-slate-500">Aucune permission sélectionnée.</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                        Vérifiez les informations avant d'enregistrer. Vous pourrez toujours modifier ce rôle plus tard.
                    </div>
                </div>
                @endif
            </div>

            <div class="flex flex-col gap-2 md:gap-3 border-t border-slate-200 px-4 md:px-6 py-4 md:py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8 shrink-0 bg-slate-50">
                <button type="button" wire:click="$set('showRoleModal', false)" class="rounded-full border border-slate-300 px-4 md:px-5 py-2 md:py-2.5 text-xs md:text-sm font-medium text-slate-700 transition hover:bg-slate-50">Annuler</button>
                <div class="flex items-center gap-2 md:gap-3">
                    @if($roleModalStep > 1)
                    <button type="button" wire:click="previousRoleStep()" class="flex-1 sm:flex-none rounded-full border border-slate-300 px-4 md:px-5 py-2 md:py-2.5 text-xs md:text-sm font-medium text-slate-700 transition hover:bg-slate-50">Précédent</button>
                    @endif
                    @if($roleModalStep < 3)
                    <button type="button" wire:click="nextRoleStep()" wire:loading.attr="disabled" class="flex-1 sm:flex-none rounded-full bg-[#0f1d57] px-4 md:px-5 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-white transition hover:bg-[#14256f] disabled:opacity-50">Continuer</button>
                    @else
                    <button type="button" wire:click="saveRole()" wire:loading.attr="disabled" class="flex-1 sm:flex-none rounded-full bg-[#0f1d57] px-4 md:px-5 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-white transition hover:bg-[#14256f] disabled:opacity-50">
                        <span wire:loading.remove wire:target="saveRole">{{ $editingRoleId ? 'Enregistrer' : 'Ajouter' }}</span>
                        <span wire:loading wire:target="saveRole"><i class="ri-loader-4-line animate-spin mr-1"></i>...</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($showDeleteModal)
    <div x-show="$wire.showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 p-4 backdrop-blur-sm" wire:click.self="$set('showDeleteModal', false)" style="display: none;">
        <div class="w-full max-w-md rounded-2xl md:rounded-[18px] bg-white shadow-[0_30px_90px_rgba(0,0,0,0.35)] overflow-hidden flex flex-col max-h-[90vh]">
            <div class="flex justify-end px-4 md:px-6 pt-4 md:pt-5 shrink-0">
                <button type="button" wire:click="$set('showDeleteModal', false)" class="inline-flex h-8 md:h-9 w-8 md:w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                    <i class="ri-close-line text-lg md:text-xl"></i>
                </button>
            </div>

            <div class="px-4 md:px-6 pb-6 flex-1 overflow-y-auto">
                <div class="mb-4 md:mb-5 inline-flex h-12 md:h-14 w-12 md:w-14 items-center justify-center rounded-xl md:rounded-2xl bg-rose-50 text-rose-500">
                    <i class="ri-user-unfollow-line text-xl md:text-2xl"></i>
                </div>
                <h3 class="text-xl md:text-2xl font-extrabold tracking-tight text-[#0f1d57]">Désactiver le rôle utilisateur</h3>
                <p class="mt-2 md:mt-3 text-xs md:text-sm leading-6 text-slate-600">
                    Êtes-vous sûr de vouloir désactiver le rôle <strong class="text-[#0f1d57] font-semibold">{{ $deletingRoleLabel }}</strong> ?
                </p>

                <div class="mt-4 md:mt-5 rounded-xl md:rounded-2xl border border-rose-200 bg-rose-50 p-3 md:p-4">
                    <div class="text-[10px] md:text-[11px] font-black uppercase tracking-[0.18em] text-rose-500">Impact</div>
                    <p class="mt-1.5 md:mt-2 text-xs md:text-sm leading-6 text-slate-600">
                        Les utilisateurs liés à ce rôle perdront l'accès aux fonctionnalités associées. Cette action peut être annulée plus tard depuis les éléments archivés.
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 md:gap-3 items-center justify-between border-t border-slate-200 px-4 md:px-6 py-4 md:py-5 shrink-0 bg-slate-50">
                <button wire:click="$set('showDeleteModal', false)" class="w-full sm:w-auto rounded-full border border-slate-300 px-4 md:px-5 py-2 md:py-2.5 text-xs md:text-sm font-medium text-slate-700 transition hover:bg-slate-50">Annuler</button>
                <button wire:click="deleteRole()" wire:loading.attr="disabled" class="w-full sm:w-auto rounded-full bg-rose-600 px-4 md:px-5 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-white transition hover:bg-rose-700 disabled:opacity-50">
                    <span wire:loading.remove wire:target="deleteRole">Désactiver</span>
                    <span wire:loading wire:target="deleteRole"><i class="ri-loader-4-line animate-spin mr-1"></i>...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

