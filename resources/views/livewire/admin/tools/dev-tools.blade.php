<div class="max-w-7xl mx-auto">

    {{-- Success message --}}
    @if($clearMessage)
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
         class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3">
        <i class="ri-checkbox-circle-line text-green-500 text-xl"></i>
        <span class="font-medium">{{ $clearMessage }}</span>
    </div>
    @endif

    {{-- Tab navigation --}}
    <div class="flex gap-1 bg-white rounded-xl shadow-sm border border-gray-100 p-1 mb-6">
        @foreach(['cache' => ['ri-database-2-line', 'Cache'], 'system' => ['ri-information-line', 'System'], 'modules' => ['ri-puzzle-line', 'Modules'], 'routes' => ['ri-route-line', 'Routes']] as $tab => $meta)
        <button wire:click="$set('activeTab', '{{ $tab }}')"
                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-lg text-sm font-semibold transition
                       {{ $activeTab === $tab ? 'bg-indigo-600 text-white shadow' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="{{ $meta[0] }}"></i> {{ $meta[1] }}
        </button>
        @endforeach
    </div>

    {{-- ═══ CACHE TAB ═══ --}}
    @if($activeTab === 'cache')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @php $cs = $this->cacheStatus; @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-semibold text-gray-700">Config Cache</h4>
                <span class="w-3 h-3 rounded-full {{ $cs['config_cached'] ? 'bg-green-400' : 'bg-gray-300' }}"></span>
            </div>
            <p class="text-xs text-gray-500 mb-3">{{ $cs['config_cached'] ? 'Cached' : 'Not cached' }}</p>
            <button wire:click="clearCache('config')" class="w-full px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg border border-amber-200 transition">
                <i class="ri-delete-bin-line mr-1"></i> Clear Config
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-semibold text-gray-700">Route Cache</h4>
                <span class="w-3 h-3 rounded-full {{ $cs['routes_cached'] ? 'bg-green-400' : 'bg-gray-300' }}"></span>
            </div>
            <p class="text-xs text-gray-500 mb-3">{{ $cs['routes_cached'] ? 'Cached' : 'Not cached' }}</p>
            <button wire:click="clearCache('route')" class="w-full px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg border border-amber-200 transition">
                <i class="ri-delete-bin-line mr-1"></i> Clear Routes
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-semibold text-gray-700">View Cache</h4>
                <span class="w-3 h-3 rounded-full {{ $cs['views_cached'] ? 'bg-green-400' : 'bg-gray-300' }}"></span>
            </div>
            <p class="text-xs text-gray-500 mb-3">{{ $cs['views_count'] }} compiled views</p>
            <button wire:click="clearCache('view')" class="w-full px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg border border-amber-200 transition">
                <i class="ri-delete-bin-line mr-1"></i> Clear Views
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h4 class="text-sm font-semibold text-gray-700 mb-2">Storage Info</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Sessions</span><span class="font-medium">{{ $cs['sessions_count'] }} files</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Log file</span><span class="font-medium">{{ $cs['log_size'] }}</span></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-center">
            <button wire:click="clearCache('all')" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <i class="ri-refresh-line mr-1"></i> Clear ALL Cache
            </button>
        </div>
    </div>
    @endif

    {{-- ═══ SYSTEM TAB ═══ --}}
    @if($activeTab === 'system')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4"><i class="ri-server-line mr-2 text-indigo-500"></i>System Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($this->systemInfo as $key => $val)
            <div class="flex items-center justify-between px-4 py-3 rounded-lg {{ $loop->even ? 'bg-gray-50' : 'bg-white' }} border border-gray-100">
                <span class="text-sm text-gray-600 font-medium">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                <span class="text-sm font-semibold {{ $key === 'debug_mode' && $val === 'ON' ? 'text-red-600' : 'text-gray-800' }}">{{ $val }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══ MODULES TAB ═══ --}}
    @if($activeTab === 'modules')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800"><i class="ri-puzzle-line mr-2 text-indigo-500"></i>Livewire Components</h3>
            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full">{{ count($this->modules) }} components</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Component</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">File</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($this->modules as $mod)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3"><span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">{{ $mod['type'] }}</span></td>
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $mod['name'] }}</td>
                        <td class="px-6 py-3 text-gray-500 text-xs">{{ $mod['path'] }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $mod['size'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ═══ ROUTES TAB ═══ --}}
    @if($activeTab === 'routes')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800"><i class="ri-route-line mr-2 text-indigo-500"></i>Admin Routes</h3>
            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full">{{ count($this->routes) }} routes</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">URI</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($this->routes as $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            @foreach(explode('|', $r['method']) as $m)
                            <span class="px-2 py-0.5 text-xs rounded font-bold
                                {{ $m === 'GET' ? 'bg-green-100 text-green-700' : ($m === 'POST' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">{{ $m }}</span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-800">{{ $r['uri'] }}</td>
                        <td class="px-4 py-3 text-indigo-600 text-xs">{{ $r['name'] }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs truncate" style="max-width:250px">{{ class_basename($r['action']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
