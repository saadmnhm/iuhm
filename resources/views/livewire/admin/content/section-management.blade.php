<div>
    <div class="px-6 pb-6 pt-8 sm:px-8 sm:pb-8 sm:pt-10">
        {{-- Header --}}
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-emerald-700">CONTENT STUDIO</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Gestion {{ $sectionLabel }}</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">
                    Créez, publiez et organisez les contenus de la section {{ $sectionLabel }}.
                </p>
            </div>

            <a href="{{ route('admin.'.$section.'.form', ['id' => 'new']) }}"
               class="inline-flex items-center gap-2 self-start rounded-full bg-[#0f1d57] px-5 py-3 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(15,23,42,0.22)] transition hover:bg-[#14256f]">
                <span class="flex h-6 w-6 items-center justify-center rounded-full border border-white/25 bg-white/10">
                    <i class="ri-add-line text-sm"></i>
                </span>
                Nouveau contenu
            </a>
        </div>

        @if(session()->has('success'))
            <div class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
                <i class="ri-check-line text-lg text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Stats --}}
        <div class="mt-10 mb-10 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach($stats as $stat)
                <div class="rounded-2xl bg-white p-5 shadow-sm">
                    <div class="mb-3 flex justify-between">
                        <p class="mt-0.5 text-[14px] font-bold uppercase tracking-[0.05em] text-[#45464E]">{{ $stat['label'] }}</p>
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#9AF89330]">
                            <i class="{{ $stat['icon'] }} text-xl text-[#04103A]"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-black text-slate-900">{{ $stat['data'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Search & filter --}}
        <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Rechercher dans {{ $sectionLabel }}..."
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
                            <th class="px-6 py-5 text-left text-xs font-semibold uppercase text-white">Contenu</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold uppercase text-white">Catégorie</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold uppercase text-white">Images</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold uppercase text-white">Vues</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold uppercase text-white">Publié</th>
                            <th class="px-6 py-5 text-center text-xs font-semibold uppercase text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($items as $item)
                            <tr class="bg-gray-100 font-bold transition-colors hover:bg-gray-200" style="border-bottom: 10px solid #fbf8fd;">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        @if($item->image_url)
                                            <img src="{{ $item->image_url }}" alt=""
                                                 class="h-10 w-10 flex-shrink-0 rounded-lg object-cover">
                                        @else
                                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50">
                                                <i class="{{ $meta['icon'] }} text-indigo-300"></i>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <span class="block max-w-[260px] truncate text-[14px] font-semibold text-[#04103A]">
                                                {{ $item->getLocalized('title') }}
                                            </span>
                                            <span class="block max-w-[260px] truncate text-[11px] font-medium text-slate-400">
                                                {{ $item->published_at?->translatedFormat('d M Y') ?? 'Non publié' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-[14px] text-slate-600">{{ $item->category_label ?? '—' }}</td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                        <i class="ri-image-line"></i>
                                        {{ count($item->carousel_images) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-[14px] text-slate-600">{{ number_format($item->views_count) }}</td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ring-1
                                        {{ $item->is_published ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-amber-100 text-amber-700 ring-amber-200' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $item->is_published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                        {{ $item->is_published ? 'Publié' : 'Brouillon' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.'.$section.'.form', ['id' => $item->id]) }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#0f1d57]/5 text-[#0f1d57] transition hover:bg-[#0f1d57] hover:text-white" title="Modifier">
                                            <i class="ri-edit-2-line text-base"></i>
                                        </a>
                                        <button type="button" wire:click="togglePublish({{ $item->id }})"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full transition
                                                    {{ $item->is_published ? 'bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white' }}"
                                                title="{{ $item->is_published ? 'Dépublier' : 'Publier' }}">
                                            <i class="ri-{{ $item->is_published ? 'eye-off' : 'eye' }}-line text-base"></i>
                                        </button>
                                        <button type="button" wire:click="delete({{ $item->id }})"
                                                wire:confirm="Supprimer ce contenu définitivement ?"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-600 hover:text-white" title="Supprimer">
                                            <i class="ri-delete-bin-2-fill text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <i class="{{ $meta['icon'] }} mb-3 block text-4xl text-slate-200"></i>
                                    <p class="text-sm font-semibold text-slate-500">Aucun contenu {{ $sectionLabel }} trouvé.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Affichage de {{ $items->firstItem() ?? 0 }} à {{ $items->lastItem() ?? 0 }} sur {{ $items->total() }} contenus
                </p>
                <div>{{ $items->links() }}</div>
            </div>
        </div>
    </div>
</div>
