
<div x-data="{ open: false }" x-cloak @window.close-project-modal="open = false">

    {{-- ═══════ LIST VIEW ═══════ --}}
    <div class="px-6 pb-6 pt-8 sm:px-8 sm:pb-8 sm:pt-10">

        {{-- Header --}}
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-emerald-700">CONTENT STUDIO</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Gestion des Projets</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">
                    Ajoutez, modifiez et publiez les projets qui apparaîtront sur le site web.
                </p>
            </div>
            <button type="button" @click="open = true"
                    class="inline-flex items-center gap-2 self-start rounded-full bg-[#0f1d57] px-5 py-3 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(15,23,42,0.22)] transition hover:bg-[#14256f]">
                <span class="flex h-6 w-6 items-center justify-center rounded-full border border-white/25 bg-white/10">
                    <i class="ri-add-line text-sm"></i>
                </span>
                Nouveau projet
            </button>
        </div>

        @if(session()->has('success'))
        <div class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
            <i class="ri-check-line text-lg text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session()->has('error'))
        <div class="mt-6 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-sm">
            <i class="ri-error-warning-line text-lg text-rose-600"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 mb-10 mt-10">
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
                <input type="text" wire:model.live.debounce="search" placeholder="Rechercher un projet..."
                       class="w-full rounded-xl iuhm_input" style="padding: 0 40px;">
            </div>
            <select wire:model.live="statusFilter" class="rounded-xl iuhm_select">
                <option value="all">Tous les statuts</option>
                <option value="published">Publiés</option>
                <option value="draft">Brouillons</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="mt-8 overflow-hidden rounded-[22px]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-[#04103A] text-white">
                        <tr>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Image</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Titre</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Lieu</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Statut</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Publié</th>
                            <th class="px-6 py-5 text-center text-xs font-semibold text-white uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($projects as $project)
                        <tr class="hover:bg-gray-200 bg-gray-100 transition-colors font-bold" style="border-bottom: 10px solid #fbf8fd;">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    @if($project->featured_image)
                                    <img src="{{ \Illuminate\Support\Str::startsWith($project->featured_image, ['http','https']) ? $project->featured_image : asset($project->featured_image) }}"
                                         class="h-10 w-10 flex-shrink-0 rounded-lg object-cover" alt="">
                                    @else
                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50">
                                        <i class="ri-file-line text-indigo-300"></i>
                                    </div>
                                    @endif
                                    <span class="max-w-[220px] truncate text-[14px] font-semibold text-[#04103A]">{{ $project->getLocalized('title') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-[14px] text-slate-600">{{ $project->getLocalized('title') }}</td>
                            <td class="px-6 py-5 text-[14px] text-slate-600">{{ $project->location ?? '—' }}</td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ring-1
                                    {{ $project->status === 'completed' ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-700 ring-blue-200' : 'bg-slate-100 text-slate-700 ring-slate-200') }}">
                                    {{ $project->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ring-1
                                    {{ $project->is_published ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-amber-100 text-amber-700 ring-amber-200' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $project->is_published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                    {{ $project->is_published ? 'Publié' : 'Brouillon' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" @click="open = true" wire:click="openEdit({{ $project->id }})"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#0f1d57]/5 text-[#0f1d57] transition hover:bg-[#0f1d57] hover:text-white" title="Modifier">
                                        <i class="ri-edit-2-line text-base"></i>
                                    </button>
                                    <button type="button" wire:click="togglePublish({{ $project->id }})"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full transition
                                           {{ $project->is_published ? 'bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white' }}"
                                            title="{{ $project->is_published ? 'Dépublier' : 'Publier' }}">
                                        <i class="ri-{{ $project->is_published ? 'eye-off' : 'eye' }}-line text-base"></i>
                                    </button>
                                    <button type="button" wire:click="delete({{ $project->id }})"
                                            wire:confirm="Supprimer ce projet définitivement ?"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-600 hover:text-white" title="Supprimer">
                                        <i class="ri-delete-bin-2-fill text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <i class="ri-file-line text-4xl text-slate-200 mb-3 block"></i>
                                <p class="text-sm font-semibold text-slate-500">Aucun projet trouvé.</p>
                                <p class="text-xs text-slate-400 mt-1">Créez votre premier projet en cliquant sur "Nouveau projet".</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-4  px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Affichage de {{ $projects->firstItem() ?? 0 }} à {{ $projects->lastItem() ?? 0 }} sur {{ $projects->total() }} projets
                </p>
                <div>{{ $projects->links() }}</div>
            </div>
        </div>

    {{-- Create / Edit Modal (Alpine - opens without backend request) --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
        @click.outside="open = false"
    >
        <div class="w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-2xl" @click.stop>
            <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900">{{ $editMode ? 'Modifier le projet' : 'Nouveau projet' }}</h2>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>

            <form wire:submit.prevent="save" class="p-6 space-y-6">
                {{-- Title --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Titre (FR) <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="title" placeholder="Titre du projet"
                           class="w-full rounded-xl iuhm_input">
                    @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Title AR --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Titre (AR)</label>
                    <input type="text" wire:model="title_ar" placeholder="Titre en arabe"
                           class="w-full rounded-xl iuhm_input">
                </div>

                {{-- Slug --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Slug</label>
                    <input type="text" wire:model="slug" placeholder="mon-projet"
                           class="w-full rounded-xl iuhm_input">
                </div>

                {{-- Short Description --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Description courte (FR)</label>
                    <textarea wire:model="short_description" rows="3"
                              class="w-full rounded-xl iuhm_input"></textarea>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Description (FR)</label>
                    <textarea wire:model="description" rows="5"
                              class="w-full rounded-xl iuhm_input"></textarea>
                </div>

                {{-- Location --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Lieu</label>
                        <input type="text" wire:model="location" placeholder="Casablanca, Maroc"
                               class="w-full rounded-xl iuhm_input">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Partenaire</label>
                        <input type="text" wire:model="partner" placeholder="Nom du partenaire"
                               class="w-full rounded-xl iuhm_input">
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Statut</label>
                    <select wire:model="status"
                            class="w-full rounded-xl iuhm_input">
                        <option value="planned">Planifié</option>
                        <option value="in_progress">En cours</option>
                        <option value="completed">Terminé</option>
                        <option value="archived">Archivé</option>
                    </select>
                </div>

                {{-- Image --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Image mise en avant</label>
                    <input type="file" wire:model="newImage"
                           class="w-full rounded-xl iuhm_input"
                           accept="image/*">
                    @if(isset($featured_image) && $featured_image)
                    <div class="mt-2">
                        <img src="{{ \Illuminate\Support\Str::startsWith($featured_image, ['http','https']) ? $featured_image : asset($featured_image) }}"
                             class="h-24 w-auto rounded-lg object-cover border border-slate-200" alt="">
                        <p class="mt-1 text-xs text-slate-500">Image actuelle</p>
                    </div>
                    @endif
                </div>

                {{-- Toggle switches --}}
                <div class="flex items-center gap-6 pt-4 border-t border-slate-200">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model="is_published" id="is_published"
                               class="w-5 h-5 rounded border-slate-300 text-[#10752d] focus:ring-[#10752d]">
                        <label for="is_published" class="text-sm font-medium text-slate-700">Publié sur le site</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model="is_featured" id="is_featured"
                               class="w-5 h-5 rounded border-slate-300 text-[#10752d] focus:ring-[#10752d]">
                        <label for="is_featured" class="text-sm font-medium text-slate-700">À la une (Featured)</label>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                    <button type="button" @click="open = false"
                            class="px-5 py-3 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl font-medium transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-5 py-3 text-white bg-[#10752d] hover:bg-[#0c5921] rounded-xl font-semibold shadow-lg shadow-[#10752d]/30 transition-colors">
                        <span wire:loading.remove wire:target="save">
                            {{ $editMode ? 'Mettre à jour' : 'Créer le projet' }}
                        </span>
                        <span wire:loading wire:target="save">Enregistrement...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
