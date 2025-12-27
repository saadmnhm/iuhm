<div>
    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Projects</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Male Submissions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['male'] }}</p>
                </div>
                <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Female Submissions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['female'] }}</p>
                </div>
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">All Project Submissions</h3>
        </div>


        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($projects as $project)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $project->project_title }}</div>
                            <div class="text-sm text-gray-500">#{{ $project->id }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($project->profile_image)
                                    <img class="h-10 w-10 rounded-full object-cover" src=" uploads/{{ $project->profile_image  }}" alt="{{ $project->ceo_name }}">
                                @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center text-white text-sm font-semibold mr-3">
                                    {{ strtoupper(substr($project->ceo_name, 0, 1)) }}
                                </div>
                                @endif

                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $project->ceo_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $project->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $project->gender == 'male' ? 'bg-cyan-100 text-cyan-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ ucfirst($project->gender ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $project->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.projects.show', $project->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No projects found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $projects->links() }}
        </div>
    </div>
</div>
