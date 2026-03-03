<div x-data="deleteModal()" x-cloak class="p-8 bg-gray-50 min-h-screen">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Projects -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Projects</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalProjects }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>



    </div>
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Projects</h1>
            <p class="text-sm text-gray-500 mt-1">
                Manage existing projects or create a new one
            </p>
        </div>

        <a href="{{ route('admin.programe.create') }}" class="px-4 py-2 bg-green-logo text-white rounded-lg transition">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create New Project
            </span>
        </a>
    </div>

    <!-- Projects Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
        @forelse($projects as $project)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-200 flex flex-col overflow-hidden group">
            {{-- Card header strip --}}
            <div class="h-2 bg-gradient-to-r from-green-500 to-emerald-400"></div>
            <div class="p-6 flex-1 flex flex-col">
                {{-- Title row --}}
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-12 h-12 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center flex-shrink-0">
                            <i class="{{ $project->icon }} text-2xl text-green-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-bold text-gray-900 truncate group-hover:text-green-700 transition">
                                {{ $project->project_name }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">ID #{{ $project->id }}</p>
                        </div>
                    </div>
                </div>

                {{-- Meta info --}}
                <div class="space-y-2 flex-1">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i class="ri-user-line text-gray-400 text-base"></i>
                        <span>{{ $project->user->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i class="ri-calendar-line text-gray-400 text-base"></i>
                        <span>{{ $project->created_at->format('d M Y') }}</span>
                    </div>
                    @if($project->formulaires_count ?? null)
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i class="ri-file-list-3-line text-gray-400 text-base"></i>
                        <span>{{ $project->formulaires_count }} formulaire(s)</span>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 mt-5 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.project.submissions', $project->id) }}"
                       class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition"
                       title="Voir les soumissions">
                        <i class="ri-file-list-3-line"></i> Soumissions
                    </a>
                    <a href="{{ route('admin.programe.edit', $project->id) }}"
                       class="flex items-center justify-center gap-1.5 px-3 py-2 bg-green-50 hover:bg-green-100 text-green-700 text-xs font-semibold rounded-lg transition"
                       title="Modifier">
                        <i class="ri-edit-line"></i> Modifier
                    </a>
                    <button type="button"
                            class="flex items-center justify-center gap-1.5 px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition"
                            title="Supprimer"
                            @click.prevent="open({{ $project->id }}, '{{ addslashes($project->project_name) }}')">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
            <i class="ri-folder-open-line text-6xl text-gray-300 mb-4 block"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun projet</h3>
            <p class="text-gray-400 mb-6">Commencez par créer votre premier projet.</p>
            <a href="{{ route('admin.programe.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                <i class="ri-add-line"></i> Créer un projet
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-2">{{ $projects->links() }}</div>

    <!-- Delete Confirmation Modal (Alpine + Tailwind) -->
    <div x-show="show" x-transition.opacity class="fixed inset-0 z-40 flex items-center justify-center bg-black/40">
        <div @click.away="show = false" x-show="show" x-transition class="bg-white rounded-lg shadow-lg max-w-lg w-full mx-4">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">Confirmer la suppression</h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600" x-text="modalText">Voulez-vous vraiment supprimer cet élément ? Cette action est irréversible.</p>
            </div>
            <div class="px-6 py-4 border-t flex justify-end gap-3">
                <button @click.prevent="show = false" type="button" class="px-4 py-2 rounded bg-gray-200 text-gray-700">Annuler</button>
                <button @click="$wire.delete(deleteId); show = false" type="button" class="px-4 py-2 rounded bg-red-600 text-white">Supprimer</button>
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
                return this.deleteName ? `Voulez-vous vraiment supprimer "${this.deleteName}" ? Cette action est irréversible.` : 'Voulez-vous vraiment supprimer cet élément ? Cette action est irréversible.';
            },
            open(id, name) {
                this.deleteId = id;
                // ensure single quotes don't break attribute
                this.deleteName = name;
                this.show = true;
            }
        }
    }
</script>
<!-- end file -->
