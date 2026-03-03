<div class="bg-gray-50 min-h-screen p-8">

    <!-- Page Header -->
     <div class="bg-white rounded-xl shadow-sm p-3 mb-6 border border-gray-100">

        <div class=" flex items-center justify-end ">
            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 me-3">Active</span>
            <a href="{{ route('admin.programe.edit', $project->id) }}" class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-700 transition">
                Edit Project
            </a>
        </div>
     </div>

    <!-- Statistic Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Projects -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Submissions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>


        <!-- Completed -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Completed</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['completed'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <!-- Completion Rate -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Completion Rate</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['completion_rate'] }}%</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Fields Card -->
    <div class="mb-10 ">
        <h2 class="text-lg font-medium text-gray-800 mb-4">Formulaire Attaché</h2>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">

            @foreach($statistics['by_formulaire'] as $formulaire)
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">{{ $formulaire['title'] }}</p>
                        @if($formulaire['is_active'])
                            <p class="text-2xl font-bold mt-1
                            text-green-600
                            ">
                                ✓
                            </p>
                        @else
                            <p class="text-2xl font-bold mt-1
                            text-red-600
                            ">
                                ✗
                            </p>
                        @endif
                    </div>
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center
                    " style="color: {{ $formulaire['color'] ?? '#6366f1' }}; background-color: {{ $formulaire['color'] ?? '#6366f1' }}30;
                    ">
                        <i class="{{ $formulaire['icon'] ?? 'ri-file-list-3-line' }} text-2xl"></i>
                    </div>
                </div>
            </div>
            @endforeach
            <!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    <!-- Submissions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Recent Project Submissions</h3>

        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr> 
                        <th class="px-4 py-3 text-left font-medium text-gray-600">#</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 ">Candidat</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Projet</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Statut</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 ">Responsable</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Date</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!--[if BLOCK]><![endif]-->
                    @forelse($userSubmissions as $userSub)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-500">{{ $userSub->user->id ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold text-xs">
                                    @if($userSub->user->profile_image)
                                        <img src="{{ asset('uploads/' . $userSub->user->profile_image) }}" alt="{{ $userSub->user->nom }} {{ $userSub->user->prenom }}" class="w-full h-full object-cover rounded-full">
                                    @else
                                            {{ substr($userSub->user->name ?? '', 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">
                                        @if($userSub->is_candidat)
                                            {{ $userSub->user->nom }} {{ $userSub->user->prenom }}
                                        @else
                                            {{ $userSub->user->name }}
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400">{{ $userSub->user->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-gray-700">{{ $project->project_name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $userSub->completed > 0 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $userSub->completed > 0 ? 'Soumis' : 'Brouillon' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($userSub->is_candidat && $userSub->user->reviewer)
                            @php
                                $rBadge = match($userSub->user->review_status) {
                                    'approved'  => 'bg-green-100 text-green-800',
                                    'rejected'  => 'bg-red-100 text-red-800',
                                    'in_review' => 'bg-purple-100 text-purple-800',
                                    default     => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $rBadge }}">
                                <i class="ri-user-star-line"></i> {{ $userSub->user->reviewer->name }}
                            </span>
                            @else
                            <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $userSub->last_activity ? \Carbon\Carbon::parse($userSub->last_activity)->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('admin.candidat.submissions', $userSub->person_id) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition text-xs font-medium">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                            <i class="ri-inbox-line text-4xl mb-2"></i>
                            <p>No submissions found for this project.</p>
                        </td>
                    </tr>
                    @endforelse
                    <!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($userSubmissions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $userSubmissions->links() }}
        </div>
        @endif
    </div>

</div>

