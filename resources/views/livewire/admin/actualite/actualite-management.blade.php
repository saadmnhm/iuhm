
<div>

    {{-- ═══════ LIST VIEW ═══════ --}}
    <div class="px-6 pb-6 pt-8 sm:px-8 sm:pb-8 sm:pt-10">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-emerald-700">CONTENT STUDIO</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Gestion d'actualités</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">Gérez vos actualités et publications.</p>
            </div>
            <a href="{{ route('admin.actualite.form', ['id' => 'new']) }}"
                    class="inline-flex items-center gap-2 self-start rounded-full bg-[#0f1d57] px-5 py-3 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(15,23,42,0.22)] transition hover:bg-[#14256f]">
                <span class="flex h-6 w-6 items-center justify-center rounded-full border border-white/25 bg-white/10">
                    <i class="ri-add-line text-sm"></i>
                </span>
                Ajouter une actualité
            </a>
        </div>

        @if(session()->has('success'))
        <div class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
            <i class="ri-check-line text-lg text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        {{-- Stats Row --}}
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
        <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="relative flex-1">
                <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" wire:model.live.debounce="search" placeholder="Chercher una actualité..."
                      class="w-full rounded-xl iuhm_input" style="padding: 0 40px;">
            </div>
            <select wire:model.live="categoryFilter"
                    class="rounded-xl iuhm_select">
                <option value="">Toutes les catégories</option>
                <option value="urban-planning">Urban Planning</option>
                <option value="sustainability">Sustainability</option>
                <option value="community">Community</option>
                <option value="innovation">Innovation</option>
            </select>
        </div>

        {{-- Data Table --}}
        <div class="mt-8 overflow-hidden rounded-[22px] ">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-[#04103a] text-white">
                        <tr>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">TITRE</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">AUTEUR</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">CATÉGORIE</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">DATE</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">VUES</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">STATUT</th>
                            <th class="px-6 py-5 text-center text-xs font-semibold text-white uppercase">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($newsItems as $news)
                        <tr class="hover:bg-gray-200 bg-gray-100 transition-colors font-bold" style="border-bottom: 10px solid #fbf8fd;">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    @if($news->image)
                                    <img src="{{ \Illuminate\Support\Str::startsWith($news->image, ['http','https']) ? $news->image : asset($news->image) }}"
                                         class="h-10 w-10 flex-shrink-0 rounded-lg object-cover" alt="">
                                    @else
                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50">
                                        <i class="ri-article-line text-indigo-300"></i>
                                    </div>
                                    @endif
                                    <span class="max-w-[220px] truncate text-[14px] font-semibold text-[#04103A]">{{ $news->title }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-[14px] text-slate-600">
                                {{ $news->author?->nom ?? $news->author?->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-5">
                                @if($news->category)
                                <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">{{ $news->category }}</span>
                                @else
                                <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-[14px] text-slate-500">
                                {{ $news->published_at ? $news->published_at->format('d M Y') : $news->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-5 text-[14px] font-semibold text-slate-700">
                                {{ number_format($news->views_count) }}
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ring-1
                                    {{ $news->is_published ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-amber-100 text-amber-700 ring-amber-200' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $news->is_published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                    {{ $news->is_published ? 'Publié' : 'Brouillon' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.actualite.form', ['id' => $news->id]) }}"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#0f1d57]/5 text-[#0f1d57] transition hover:bg-[#0f1d57] hover:text-white" title="Modifier">
                                        <i class="ri-edit-2-line text-base"></i>
                                    </a>
                                    <button type="button" wire:click="togglePublish({{ $news->id }})"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full transition
                                                   {{ $news->is_published ? 'bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white' }}"
                                            title="{{ $news->is_published ? 'Dépublier' : 'Publier' }}">
                                        <i class="ri-{{ $news->is_published ? 'eye-off' : 'eye' }}-line text-base"></i>
                                    </button>
                                    <button type="button" wire:click="delete({{ $news->id }})"
                                            wire:confirm="Supprimer cet article définitivement ?"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-600 hover:text-white" title="Supprimer">
                                        <i class="ri-delete-bin-2-fill text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <i class="ri-article-line text-4xl text-slate-200 mb-3 block"></i>
                                <p class="text-sm font-semibold text-slate-500">Aucun article trouvé.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Affichage de {{ $newsItems->firstItem() ?? 0 }} à {{ $newsItems->lastItem() ?? 0 }} sur {{ $newsItems->total() }} articles
                </p>
                <div>{{ $newsItems->links() }}</div>
            </div>
        </div>
    </div>
</div>
