<div class="p-8 bg-gray-50 min-h-screen">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Projects -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Projects</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">3</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total active</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">2</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
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

        <a href="{{ route('admin.programe_zettat.create') }}" class="px-4 py-2 bg-green-logo text-white rounded-lg transition">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create New Project
            </span>
        </a>
    </div>

    <!-- Projects Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wide">
                <tr>
                    <th class="px-6 py-4 text-left">#</th>
                    <th class="px-6 py-4 text-left">Project</th>
                    <th class="px-6 py-4 text-left">Created By</th>
                    <th class="px-6 py-4 text-left">Created At</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                <!-- Row -->
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">1</td>
                    <td class="px-6 py-4 font-medium text-gray-800">
                        Student Management System
                    </td>
                    <td class="px-6 py-4 text-gray-700">
                        Ahmed El Amrani
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        12 Jan 2026
                    </td>
                    <td class="px-6 py-4 text-right space-x-3">
                       
                        <button class="text-green-600 hover:text-green-800" title="Edit" onclick="window.location.href='{{ route('admin.programe_zettat.edit') }}'">
                            ✏️
                        </button>
                        <button class="text-red-600 hover:text-red-800" title="Delete">
                            🗑
                        </button>
                    </td>
                </tr>

                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">2</td>
                    <td class="px-6 py-4 font-medium text-gray-800">
                        E-Learning Platform
                    </td>
                    <td class="px-6 py-4 text-gray-700">
                        Sara Benali
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        05 Feb 2026
                    </td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <button class="text-green-600 hover:text-green-800">✏️</button>
                        <button class="text-red-600 hover:text-red-800">🗑</button>
                    </td>
                </tr>

                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">3</td>
                    <td class="px-6 py-4 font-medium text-gray-800">
                        HR Management Tool
                    </td>
                    <td class="px-6 py-4 text-gray-700">
                        Youssef Ait Ali
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        20 Feb 2026
                    </td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <button class="text-green-600 hover:text-green-800">✏️</button>
                        <button class="text-red-600 hover:text-red-800">🗑</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
