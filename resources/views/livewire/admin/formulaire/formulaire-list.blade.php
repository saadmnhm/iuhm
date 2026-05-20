<div class="">
    <div class="px-6 pb-6 ">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-2xl">
                <p class="green_title_1">System Configuration</p>
                <h2 class="iuhm_title_1">Gestion des Formulaires</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">
                    Gérer les formulaires en configurant les champs, les règles de validation et les parcours de saisie afin d'optimiser la collecte et la structuration des données au sein de l'écosystème Initiative Urbaine
                </p>
            </div>

            <a href="{{ route('admin.formulaires.create') }}" class="w-75 h-12.5 text-center p-2 content-center bg-[#1B264F] text-white text-[16px] font-normal rounded-full hover:bg-gray-800 transition">
                <i class="ri-add-line text-sm"></i>
                Créer un Formulaires
            </a>
        </div>

        <!-- Tabs Navigation -->
        <div class="mt-8  rounded-t-[22px] overflow-hidden">
            <div class="flex gap-0 px-6">
                <button wire:click="$set('activeTab', 'list')" class="px-4 py-4 text-sm font-semibold transition border-b-2 {{ $activeTab === 'list' ? 'px-6 py-4 text-[18px] font-bold text-[#172554] border-b-2 border-green-600' : 'px-6 py-4 text-[18px] font-bold text-[#172554] border-b-2 border-transparent hover:text-gray-700' }}">
                    <i class="ri-list-check-2 mr-2"></i>Formulaires ({{ $forms->total() }})
                </button>
                <button wire:click="$set('activeTab', 'audit-logs')" class="px-4 py-4 text-sm font-semibold transition border-b-2 {{ $activeTab === 'audit-logs' ? 'px-6 py-4 text-[18px] font-bold text-[#172554] border-b-2 border-green-600' : 'px-6 py-4 text-[18px] font-bold text-[#172554] border-b-2 border-transparent hover:text-gray-700' }}">
                    <i class="ri-history-line mr-2"></i>Journaux d'audit ({{ $auditLogs->total() }})
                </button>
            </div>
        </div>

        <!-- List Tab -->
        @if($activeTab === 'list')
        <div class="overflow-hidden rounded-b-[22px] ">
            <div class="p-5">
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" wire:model.live.debounce="search" placeholder="Rechercher un formulaire..." class="w-full iuhm_search rounded-xl ">
                    </div>
                    <select wire:model.live="filterStatus" class="rounded-xl iuhm_select border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead >
                        <tr class="bg-[#04103A] border-b border-gray-100">
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase rounded-tl-[10px]">TITRE DU FORMULAIRE</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">DESCRIPTION</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">ETAPES</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">STATUS</th>
                            <th class="px-6 py-5 text-center text-xs font-semibold text-white uppercase rounded-tr-[10px]">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($forms as $form)
                        <tr class="hover:bg-gray-200 bg-gray-100 transition-colors font-bold" style="border-bottom: 10px solid #fbf8fd;">
                            <td class="px-6 py-6 text-[16px] font-semibold text-[#45464E]">{{ $form->title }}</td>
                            <td class="px-6 py-6 text-[14px] text-[#04103A]">{{ Str::limit($form->introduction, 60) }}</td>
                            <td class="px-6 py-6 text-[16px] text-[#04103A]">{{ $form->steps_count ?? 0 }}</td>
                            <td class="px-6 py-6">
                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold ring-1 {{ $form->is_active ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-slate-200 text-slate-500 ring-slate-200' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $form->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $form->is_active ? 'Active' : 'Deactivated' }}
                                </span>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex items-center justify-center gap-2 text-[#0f1d57]">
                                    <a href="{{ route('admin.formulaires.edit', $form->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#0f1d57]/5 transition hover:bg-[#0f1d57] hover:text-white" title="Modifier">
                                        <i class="ri-edit-2-line text-base"></i>
                                    </a>
                                    <button type="button" wire:click="deleteForm({{ $form->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer ce formulaire ?" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-600 hover:text-white" title="Supprimer">
                                        <i class="ri-delete-bin-2-fill text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">Aucun formulaire trouvé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-4 border-t border-slate-200 bg-white px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Affichage de {{ $forms->firstItem() ?? 0 }} à {{ $forms->lastItem() ?? 0 }} sur {{ $forms->total() }} formulaires
                </p>
                <div>{{ $forms->links('vendor.pagination.circle') }}</div>
            </div>
        </div>
        @endif

        <!-- Audit Logs Tab -->
        @if($activeTab === 'audit-logs')
        <div class="overflow-hidden rounded-b-[22px] border border-slate-200 border-t-0 bg-white shadow-[0_18px_40px_rgba(15,23,42,0.06)]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-[#04103A] border-b border-gray-100">
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase rounded-tl-[10px]">UTILISATEUR</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">ACTION</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">DESCRIPTION</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase rounded-tr-[10px]">DATE</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($auditLogs as $log)
                        <tr class="hover:bg-gray-200 bg-gray-100 transition-colors font-bold" style="border-bottom: 10px solid #fbf8fd;">
                            <td class="px-6 py-4 text-sm text-slate-700">
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-xs font-semibold">
                                        {{ substr($log->user?->nom ?? 'A', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $log->user?->nom ?? 'System' }}</p>
                                        <p class="text-xs text-slate-500">{{ $log->user?->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold
                                    @if($log->action === 'formulaire_created') bg-blue-100 text-blue-700
                                    @elseif($log->action === 'formulaire_updated') bg-amber-100 text-amber-700
                                    @elseif($log->action === 'formulaire_deleted') bg-rose-100 text-rose-700
                                    @elseif($log->action === 'formulaire_toggled') bg-purple-100 text-purple-700
                                    @elseif($log->action === 'formulaire_duplicated') bg-cyan-100 text-cyan-700
                                    @else bg-slate-100 text-slate-700
                                    @endif">
                                    <i class="ri-circle-fill text-[0.375rem]"></i>
                                    {{ str_replace('_', ' ', ucfirst($log->action)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $log->description }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $log->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-sm text-slate-500">Aucun journal d'audit.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-4 border-t border-slate-200 bg-white px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Affichage de {{ $auditLogs->firstItem() ?? 0 }} à {{ $auditLogs->lastItem() ?? 0 }} sur {{ $auditLogs->total() }} journaux
                </p>
                <div>{{ $auditLogs->links('vendor.pagination.circle') }}</div>
            </div>
        </div>
        @endif
    </div>
</div>
