<div>
    @if($editMode || $showModal)
    {{-- ═══════ CREATE / EDIT FORM ═══════ --}}
    <form wire:submit.prevent="save">
    <div class="px-6 pb-10 pt-8 sm:px-8 sm:pt-10">

        {{-- Page Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <button type="button" wire:click="resetForm()"
                        class="mb-3 inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-700 transition">
                    <i class="ri-arrow-left-line"></i> Retour
                </button>
                <span class="block text-[11px] font-extrabold uppercase tracking-[0.28em] text-emerald-600">CONTENT STUDIO</span>
                <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
                    {{ $editMode ? 'Modifier le livrable' : 'Ajouter un livrable' }}
                </h2>
                <p class="mt-2 text-sm text-slate-500 max-w-lg">
                    {{ $editMode ? 'Mettez à jour les informations du livrable.' : 'Ajoutez un nouveau livrable ou document de projet.' }}
                </p>
            </div>
            <div class="flex flex-shrink-0 items-center gap-3 sm:pt-8">
                <button type="button" wire:click="resetForm()"
                        class="rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                    Annuler
                </button>
                <button type="submit"
                        class="rounded-full bg-[#0f1d57] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#14256f] transition shadow-sm">
                    {{ $editMode ? 'Mettre à jour' : 'Enregistrer' }}
                </button>
            </div>
        </div>

        @if(session()->has('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            <i class="ri-check-line text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session()->has('error'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            <i class="ri-error-warning-line text-rose-500"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        {{-- Two-column grid --}}
        <div class="grid gap-6 lg:grid-cols-3">

            {{-- LEFT (2/3): Main content --}}
            <div class="space-y-5 lg:col-span-2">

                {{-- Title --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <label class="block text-[11px] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-3">
                        Titre du livrable <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" wire:model="title"
                           placeholder="Titre du livrable..."
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-base font-medium text-slate-900 placeholder-slate-300 outline-none focus:border-[#0f1d57] focus:bg-white focus:ring-4 focus:ring-[#0f1d57]/10 transition">
                    @error('title') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <label class="block text-[11px] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-3">
                        Description
                    </label>
                    <textarea wire:model="description" rows="5"
                              placeholder="Description du livrable..."
                              class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 placeholder-slate-300 outline-none focus:border-[#0f1d57] focus:bg-white focus:ring-4 focus:ring-[#0f1d57]/10 resize-none transition"></textarea>
                    @error('description') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- File Upload --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-4">Fichier du livrable</p>
                    @if($file && !$newFile)
                    <div class="mb-4 flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100">
                            <i class="ri-file-line text-blue-600"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-700">Fichier existant</p>
                            <a href="{{ asset($file) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Voir le fichier</a>
                        </div>
                    </div>
                    @endif
                    <label class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 py-8 text-center hover:bg-slate-100 transition">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-sm mb-3">
                            <i class="ri-upload-cloud-2-line text-xl text-slate-400"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">{{ $editMode ? 'Remplacer le fichier' : 'Téléverser le fichier' }}</p>
                        <p class="mt-1 text-[11px] text-slate-400">PDF, DOC, DOCX, XLS, XLSX, ZIP — Max 5 MB</p>
                        <input type="file" wire:model="newFile" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip" class="hidden">
                    </label>
                    @if($newFile)
                    <p class="mt-2 text-xs text-emerald-600 text-center">Nouveau fichier sélectionné</p>
                    @endif
                    @error('newFile') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

            </div>

            {{-- RIGHT (1/3): Metadata --}}
            <div class="space-y-5">

                {{-- Status --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-3">Statut</p>
                    <select wire:model="status"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
                        <option value="pending">En attente</option>
                        <option value="completed">Complété</option>
                        <option value="overdue">En retard</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Due Date --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-3">Date d'échéance</p>
                    <input type="date" wire:model="due_date"
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
                    @error('due_date') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Category --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-3">Category</p>
                    <input type="text" wire:model="category"
                           placeholder="ex: Rapport, Étude, Présentation..."
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
                    @error('category') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Publication Status --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.12em] text-slate-400 mb-4">Visibilité</p>
                    <div class="flex gap-1.5 rounded-full bg-slate-100 p-1">
                        <button type="button" wire:click="$set('is_published', false)"
                                class="flex-1 rounded-full py-2 text-xs font-bold transition
                                       {{ !$is_published ? 'bg-[#0f1d57] text-white shadow' : 'text-slate-500 hover:text-slate-700' }}">
                            Privé
                        </button>
                        <button type="button" wire:click="$set('is_published', true)"
                                class="flex-1 rounded-full py-2 text-xs font-bold transition
                                       {{ $is_published ? 'bg-[#0f1d57] text-white shadow' : 'text-slate-500 hover:text-slate-700' }}">
                            Publié
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>
    </form>

    @else
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
            <button type="button" wire:click="openCreate()"
                    class="inline-flex items-center gap-2 self-start rounded-full bg-[#0f1d57] px-5 py-3 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(15,23,42,0.22)] transition hover:bg-[#14256f]">
                <span class="flex h-6 w-6 items-center justify-center rounded-full border border-white/25 bg-white/10">
                    <i class="ri-add-line text-sm"></i>
                </span>
                Ajouter un livrable
            </button>
        </div>

        @if(session()->has('success'))
        <div class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
            <i class="ri-check-line text-lg text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        {{-- Stats --}}
        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.05em] text-slate-500">TOTAL</p>
                        <p class="mt-2 text-3xl font-black text-slate-900">{{ $totalDeliverables ?? 0 }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-2xl text-blue-600">
                        <i class="ri-file-list-3-line"></i>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.05em] text-slate-500">COMPLÉTÉS</p>
                        <p class="mt-2 text-3xl font-black text-slate-900">{{ $completedDeliverables ?? 0 }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-2xl text-emerald-600">
                        <i class="ri-checkbox-circle-line"></i>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.05em] text-slate-500">PUBLIÉS</p>
                        <p class="mt-2 text-3xl font-black text-slate-900">{{ $publishedDeliverables ?? 0 }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-2xl text-amber-600">
                        <i class="ri-global-line"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search & Filter --}}
        <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" wire:model.live.debounce="search" placeholder="Rechercher un livrable..."
                       class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
            </div>
            <select wire:model.live="statusFilter"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
                <option value="all">Tous</option>
                <option value="published">Publiés</option>
                <option value="draft">Non publiés</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="mt-8 overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-[0_18px_40px_rgba(15,23,42,0.06)]">
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
                        @forelse($deliverables as $deliverable)
                        <tr class="transition hover:bg-white">
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
                                    <button type="button" wire:click="openEdit({{ $deliverable->id }})"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#0f1d57]/5 text-[#0f1d57] transition hover:bg-[#0f1d57] hover:text-white" title="Modifier">
                                        <i class="ri-edit-2-line text-base"></i>
                                    </button>
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

            <div class="flex flex-col gap-4 border-t border-slate-200 bg-white px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Affichage de {{ $deliverables->firstItem() ?? 0 }} à {{ $deliverables->lastItem() ?? 0 }} sur {{ $deliverables->total() }} livrables
                </p>
                <div>{{ $deliverables->links() }}</div>
            </div>
        </div>

    </div>
    @endif
</div>
