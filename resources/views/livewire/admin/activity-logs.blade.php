<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-4">Admin Activity Logs</h2>
        
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input 
                        type="text" 
                        wire:model.live="search" 
                        placeholder="Search..." 
                        class="w-full px-4 py-2 border rounded-lg"
                    >
                </div>
                
                <div>
                    <select wire:model.live="actionFilter" class="w-full px-4 py-2 border rounded-lg">
                        <option value="all">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}">{{ ucfirst(str_replace('_', ' ', $action)) }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <input 
                        type="date" 
                        wire:model.live="dateFrom" 
                        placeholder="From Date" 
                        class="w-full px-4 py-2 border rounded-lg"
                    >
                </div>
                
                <div>
                    <input 
                        type="date" 
                        wire:model.live="dateTo" 
                        placeholder="To Date" 
                        class="w-full px-4 py-2 border rounded-lg"
                    >
                </div>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $log->user->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $log->user->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ str_replace('_', ' ', $log->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $log->description }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->ip_address }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No activity logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="px-6 py-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
