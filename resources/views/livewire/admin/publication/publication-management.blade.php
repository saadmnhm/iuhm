<div>

    {{-- ═══════ LIST VIEW ═══════ --}}
    <div class="px-6 pb-6 pt-8 sm:px-8 sm:pb-8 sm:pt-10">

        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-emerald-700">CONTENT STUDIO</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Livrables</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">
                    Gérez les livrables et documents de projet de l'initiative.
                </p>
            </div>
            <a wire:navigate href="{{ route('admin.publication.form', [ 'id' => 'new' ]) }}"
                    class="inline-flex items-center gap-2 self-start rounded-full bg-[#0f1d57] px-5 py-3 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(15,23,42,0.22)] transition hover:bg-[#14256f]">
                <span class="flex h-6 w-6 items-center justify-center rounded-full border border-white/25 bg-white/10">
                    <i class="ri-add-line text-sm"></i>
                </span>
                Ajouter un livrable
            </a>
        </div>

        @if(session()->has('success'))
        <div class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
            <i class="ri-check-line text-lg text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

       
        {{-- Stats Row --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-3 mb-10 mt-10">
            @foreach ($stats_card as $item )
            
            <div class="rounded-2xl  bg-white p-5 shadow-sm">
                <div class="flex justify-between mb-3">
                    <p class="text-[14px] font-bold uppercase tracking-[0.05em] text-[#45464E] mt-0.5">{{ $item['label'] }}</p>
                    
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#9AF89330]">
                        <i class="{{ $item['icon'] }} text-xl text-[#04103A]"></i>
                    </div>
                    
                </div>
                <p class="text-2xl font-black text-slate-900">{{ $item['data'] }}</p>
                
            </div>
            @endforeach

        </div>
        {{-- Search & Filter --}}
        <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" wire:model.live.debounce="search" placeholder="Rechercher un livrable..."
                        class="w-full rounded-xl iuhm_input" style="padding: 0 40px;">
            </div>
            <select wire:model.live="statusFilter"
                     class="rounded-xl iuhm_select">
                <option value="all">Tous</option>
                <option value="published">Publiés</option>
                <option value="draft">Non publiés</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="mt-8 overflow-hidden rounded-[22px] ">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-[#04103A] text-white">
                        <tr>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Titre</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Catégorie</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Statut</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Échéance</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Publication</th>
                            <th class="px-6 py-5 text-center text-xs font-semibold text-white uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-slate-50/50">
                        @forelse($publications as $deliverable)
                        <tr class="hover:bg-gray-200 bg-gray-100 transition-colors font-bold" style="border-bottom: 10px solid #fbf8fd;">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-slate-100">
                                        <i class="ri-file-text-line text-slate-500"></i>
                                    </div>
                                    <span class="max-w-[200px] truncate text-[14px] font-semibold text-[#04103A]">{{ $deliverable->title }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                @if($deliverable->category)
                                <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">{{ $deliverable->category }}</span>
                                @else
                                <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                @php
                                    $statusColors = [
                                        'completed' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
                                        'pending'   => 'bg-amber-100 text-amber-700 ring-amber-200',
                                        'overdue'   => 'bg-rose-100 text-rose-700 ring-rose-200',
                                    ];
                                    $statusLabels = ['completed' => 'Complété', 'pending' => 'En attente', 'overdue' => 'En retard'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ring-1 {{ $statusColors[$deliverable->status] ?? 'bg-slate-100 text-slate-600 ring-slate-200' }}">
                                    {{ $statusLabels[$deliverable->status] ?? $deliverable->status }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-[14px] text-slate-500">
                                {{ $deliverable->due_date ? $deliverable->due_date->format('d M Y') : '—' }}
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ring-1
                                    {{ $deliverable->is_published ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-600 ring-slate-200' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $deliverable->is_published ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $deliverable->is_published ? 'Publié' : 'Privé' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <a wire:navigate href="{{ route('admin.publication.form', [ 'id' => $deliverable->id ]) }}"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#0f1d57]/5 text-[#0f1d57] transition hover:bg-[#0f1d57] hover:text-white" title="Modifier">
                                        <i class="ri-edit-2-line text-base"></i>
                                    </a>
                                    <button type="button" wire:click="togglePublish({{ $deliverable->id }})"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full transition
                                                   {{ $deliverable->is_published ? 'bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white' }}"
                                            title="{{ $deliverable->is_published ? 'Rendre privé' : 'Publier' }}">
                                        <i class="ri-{{ $deliverable->is_published ? 'eye-off' : 'eye' }}-line text-base"></i>
                                    </button>
                                    @if($deliverable->file_url)
                                    <a href="{{ asset($deliverable->file_url) }}" target="_blank"
                                       class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-blue-600 transition hover:bg-blue-600 hover:text-white" title="Télécharger">
                                        <i class="ri-download-line text-base"></i>
                                    </a>
                                    @endif
                                    <button type="button" wire:click="delete({{ $deliverable->id }})"
                                            wire:confirm="Supprimer ce livrable définitivement ?"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-600 hover:text-white" title="Supprimer">
                                        <i class="ri-delete-bin-2-fill text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <i class="ri-file-list-3-line text-4xl text-slate-200 mb-3 block"></i>
                                <p class="text-sm font-semibold text-slate-500">Aucun livrable trouvé.</p>
                                <p class="text-xs text-slate-400 mt-1">Ajoutez votre premier livrable en cliquant sur "Ajouter un livrable".</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Affichage de {{ $publications->firstItem() ?? 0 }} à {{ $publications->lastItem() ?? 0 }} sur {{ $publications->total() }} livrables
                </p>
                <div>{{ $publications->links() }}</div>
            </div>
        </div>

    </div>
</div>
