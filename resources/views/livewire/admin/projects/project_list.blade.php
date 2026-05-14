<div x-data="{ tab: 'list', ...deleteModal() }" x-cloak class="p-6 bg-gray-50 min-h-screen">

    
           
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 leading-tight">Gestion des Projets</h1>
            <p class="text-sm text-gray-500 mt-1">Curation et gestion de projets participatifs à l'échelle des districts métropolitains.</p>
        </div>
        <a href="{{ route('admin.programe.create') }}"
        class="inline-flex items-center gap-2 px-5 py-3 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-xl transition whitespace-nowrap shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            Ajouter un Projet
        </a>
    </div>


    <!-- Statistics Grid -->
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

   
    <div class="mb-5">
        <div class="flex gap-6 px-6 pt-4 pb-0 border-b border-gray-100">
            <button @click="tab='list'"
                    :class="tab==='list' ? 'border-b-2 border-gray-900 text-[#04103A] font-semibold' : 'text-gray-400 hover:text-gray-600'"
                    class="pb-3 text-sm transition">
                Liste des articles
            </button>
            <button @click="tab='logs'"
                    :class="tab==='logs' ? 'border-b-2 border-gray-900 text-[#04103A] font-semibold' : 'text-gray-400 hover:text-gray-600'"
                    class="pb-3 text-sm transition">
                Logs d'audit
            </button>
        </div>
    </div>

    <div x-show="tab==='list'">
        <div class="mt-8 overflow-hidden rounded-[22px] ">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-[#04103A] text-white">
                    <tr>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Nom du Projet</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Créé Par</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Âge Éligible</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Date de Création</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-white uppercase">Status</th>
                        <th class="px-6 py-5 text-center text-xs font-semibold text-white uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($projects as $project)
                    <tr class="hover:bg-gray-200 bg-gray-100 transition-colors font-bold" style="border-bottom: 10px solid #fbf8fd;">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                    <i class="{{ $project->icon ?? 'ri-leaf-line' }} text-green-600 text-base"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $project->project_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $project->formulaires_count ?? 0 }} formulaires</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-sm font-medium text-gray-800">{{ $project->user->name ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $locs = is_array($project->allowed_location_ids) ? $project->allowed_location_ids : [];
                                $locNames = collect($locs)->take(3)->map(fn($l) => is_array($l) ? ($l['name'] ?? $l) : $l);
                            @endphp
                            @if($locNames->isNotEmpty())
                                <div class="flex flex-col gap-0.5">
                                    @foreach($locNames as $loc)
                                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">{{ $loc }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600 whitespace-nowrap">
                            {{ $project->created_at->format('d M Y') }}
                        </td>
                        <td class="px-5 py-4">
                            @if($project->is_active)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                                    Deactivated
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.programe.edit', $project->id) }}"
                                   class="text-gray-500 hover:text-gray-800 transition p-1.5 rounded hover:bg-gray-100"
                                   title="Modifier">
                                    <i class="ri-pencil-line text-base"></i>
                                </a>
                                <button type="button"
                                        class="text-red-400 hover:text-red-600 transition p-1.5 rounded hover:bg-red-50"
                                        title="Supprimer"
                                        @click.prevent="open({{ $project->id }}, '{{ addslashes($project->project_name) }}')">
                                    <i class="ri-delete-bin-line text-base"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <i class="ri-folder-open-line text-5xl text-gray-300 block mb-3"></i>
                            <p class="text-gray-500 font-medium">Aucun projet trouvé</p>
                            <a href="{{ route('admin.programe.create') }}"
                               class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-gray-900 text-white text-sm rounded-lg hover:bg-gray-800 transition">
                                <i class="ri-add-line"></i> Créer un projet
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4   rounded-lg px-5 py-3 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-gray-500">
                Affichage de {{ $projects->firstItem() ?? 0 }} à {{ $projects->lastItem() ?? 0 }} sur {{ $projects->total() }} projets
            </p>
            {{ $projects->links() }}
        </div>
    </div>

    {{-- Logs tab placeholder --}}
    <div x-show="tab==='logs'" class="bg-white rounded-xl border border-gray-200 p-10 text-center">
        <i class="ri-history-line text-5xl text-gray-300 block mb-3"></i>
        <p class="text-gray-400 text-sm">Les logs d'audit seront affichés ici.</p>
    </div>

    {{-- Delete Modal --}}
    <div x-show="show" x-transition.opacity
         class="fixed inset-0 z-40 flex items-center justify-center bg-black/40">
        <div @click.away="show = false" x-show="show" x-transition
             class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
            <div class="px-6 py-4 border-b flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-red-50 flex items-center justify-center">
                    <i class="ri-delete-bin-line text-red-500"></i>
                </div>
                <h3 class="text-base font-semibold text-gray-900">Confirmer la suppression</h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600" x-text="modalText"></p>
            </div>
            <div class="px-6 py-4 border-t flex justify-end gap-3">
                <button @click.prevent="show = false" type="button"
                        class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200 transition">
                    Annuler
                </button>
                <button @click="$wire.delete(deleteId); show = false" type="button"
                        class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">
                    Supprimer
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    function deleteModal() {
        return {
            show: false,
            deleteId: null,
            deleteName: '',
            get modalText() {
                return this.deleteName
                    ? `Voulez-vous vraiment supprimer "${this.deleteName}" ? Cette action est irréversible.`
                    : 'Voulez-vous vraiment supprimer cet élément ? Cette action est irréversible.';
            },
            open(id, name) {
                this.deleteId = id;
                this.deleteName = name;
                this.show = true;
            }
        }
    }
</script>
