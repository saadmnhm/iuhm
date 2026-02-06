<div class="max-w-6xl mx-auto">
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Détails du Candidat</h3>
            <a href="{{ route('admin.candidats.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>

        <div class="p-6">
            <!-- Candidat Avatar and Name -->
            <div class="flex items-center pb-6 border-b border-gray-200 mb-6">
                <div class="w-24 h-24 rounded-full bg-green-logo flex items-center justify-center text-white text-3xl font-semibold mr-6">
                    {{ strtoupper(substr($candidat->nom, 0, 1)) }}{{ strtoupper(substr($candidat->prenom, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h4 class="text-2xl font-semibold text-gray-900 mb-1">{{ $candidat->nom }} {{ $candidat->prenom }}</h4>
                    <p class="text-gray-500">{{ $candidat->email }}</p>
                </div>
                <div>
                    @if($candidat->is_active)
                        <span class="px-4 py-2 text-sm font-medium rounded-full bg-green-100 text-green-800">
                            Actif
                        </span>
                    @else
                        <span class="px-4 py-2 text-sm font-medium rounded-full bg-red-100 text-red-800">
                            Inactif
                        </span>
                    @endif
                </div>
            </div>

            <!-- Candidat Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">ID</span>
                        <span class="text-base text-gray-900">#{{ $candidat->id }}</span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Login</span>
                        <span class="text-base text-gray-900">{{ $candidat->login }}</span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Email</span>
                        <span class="text-base text-gray-900">{{ $candidat->email ?? 'N/A' }}</span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Téléphone</span>
                        <span class="text-base text-gray-900">{{ $candidat->phone ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Genre</span>
                        <span class="text-base text-gray-900">{{ $candidat->gender ? ucfirst($candidat->gender) : 'N/A' }}</span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Adresse</span>
                        <span class="text-base text-gray-900">{{ $candidat->address ?? 'N/A' }}</span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Inscrit le</span>
                        <span class="text-base text-gray-900">{{ $candidat->created_at->format('d/m/Y') }}</span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Total Projets</span>
                        <span class="text-base text-gray-900 font-semibold">{{ $candidat->projects()->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Formulaires Soumis ({{ $candidat->projects()->count() }})</h3>
        </div>

        @if($candidat->projects()->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom du Projet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($candidat->projects()->latest()->get() as $project)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $project->form_type_badge_color }}-100 text-{{ $project->form_type_badge_color }}-800">
                                {{ $project->form_type_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $project->project_name ?? 'Sans titre' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-status-badge :status="$project->status" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $project->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.projects.show', $project->id) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                Voir le projet
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-gray-500">Aucun projet soumis</p>
        </div>
        @endif
    </div>
</div>
