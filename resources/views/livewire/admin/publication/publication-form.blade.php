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
