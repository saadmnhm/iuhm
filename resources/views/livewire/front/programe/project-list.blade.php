<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Available Projects</h1>
            <p class="text-gray-600">Browse and participate in projects that match your profile</p>
        </div>

        <!-- Search -->
        <div class="mb-8">
            <div class="relative">
                <input type="text" 
                       wire:model.live="search"
                       placeholder="Search projects..."
                       class="w-full px-6 py-4 pl-14 border rounded-xl focus:ring-2 focus:ring-blue-500 text-lg">
                <i class="ri-search-line absolute left-5 top-5 text-gray-400 text-xl"></i>
            </div>
        </div>

        <!-- Projects Grid -->
        @if($projects->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($projects as $project)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden group">
                        <!-- Card Header -->
                        <div class="h-2 bg-gradient-to-r from-blue-500 to-purple-500"></div>
                        
                        <div class="p-6">
                            <!-- Project Name -->
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition">
                                {{ $project->project_name }}
                            </h3>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $project->description }}
                            </p>

                            <!-- Stats -->
                            <div class="flex items-center justify-between mb-6 text-sm">
                                <div class="flex items-center gap-2 text-blue-600">
                                    <i class="ri-file-list-line"></i>
                                    <span>{{ $project->formulaires->count() }} Formulaires</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i class="ri-user-line"></i>
                                    <span>Age: {{ $project->min_age }}-{{ $project->max_age }}</span>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $project->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $project->status }}
                                </span>
                            </div>

                            <!-- Action Button -->
                            <a href="{{ route('form.project.detail', $project->id) }}"
                               class="block w-full px-6 py-3 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition font-medium">
                                View Project
                                <i class="ri-arrow-right-line ml-2"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $projects->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <i class="ri-folder-open-line text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Projects Found</h3>
                <p class="text-gray-500">
                    @if($search)
                        No projects match your search. Try different keywords.
                    @else
                        There are no active projects available at the moment that match your profile.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
