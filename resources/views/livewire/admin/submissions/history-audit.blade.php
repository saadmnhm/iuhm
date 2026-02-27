<div class="max-w-7xl mx-auto">

    {{-- ═══ Tabs ═══ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex border-b border-gray-100">
            <button wire:click="$set('tab', 'history')"
                    class="flex-1 py-3 text-center text-sm font-semibold transition {{ $tab === 'history' ? 'text-indigo-700 border-b-2 border-indigo-600 bg-indigo-50/50' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="ri-git-commit-line mr-1"></i> Historique des modifications
            </button>
            <button wire:click="$set('tab', 'activity')"
                    class="flex-1 py-3 text-center text-sm font-semibold transition {{ $tab === 'activity' ? 'text-indigo-700 border-b-2 border-indigo-600 bg-indigo-50/50' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="ri-history-line mr-1"></i> Journal d'activité admin
            </button>
        </div>

        {{-- Toolbar --}}
        <div class="p-4 flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px] relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" wire:model.live="search" placeholder="Rechercher..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">
            </div>
            <select wire:model.live="actionFilter" class="border border-gray-300 rounded-lg text-sm py-2.5 px-3">
                <option value="all">Toutes les actions</option>
                @foreach($actions as $action)
                <option value="{{ $action }}">{{ $action }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- ═══ HISTORY TAB ═══ --}}
    @if($tab === 'history')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($items as $item)
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        @if($item->action === 'status_changed')
                            <i class="ri-exchange-line text-indigo-600"></i>
                        @elseif($item->action === 'reviewer_assigned')
                            <i class="ri-user-follow-line text-blue-600"></i>
                        @else
                            <i class="ri-git-commit-line text-gray-500"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded bg-indigo-100 text-indigo-800">
                                {{ \App\Models\SubmissionHistory::ACTION_LABELS[$item->action] ?? $item->action }}
                            </span>
                            <span class="text-xs text-gray-400">{{ class_basename($item->subject_type) }} #{{ $item->subject_id }}</span>
                        </div>
                        <div class="flex items-center gap-2 mt-1 text-sm">
                            @if($item->old_value)
                            <span class="px-2 py-0.5 bg-red-50 text-red-700 text-xs rounded line-through">{{ $item->old_value }}</span>
                            <i class="ri-arrow-right-s-line text-gray-400"></i>
                            @endif
                            @if($item->new_value)
                            <span class="px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded font-medium">{{ $item->new_value }}</span>
                            @endif
                        </div>
                        @if($item->notes)
                        <p class="text-xs text-gray-500 mt-1 italic"><i class="ri-sticky-note-line mr-1"></i>{{ $item->notes }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-1.5 text-xs text-gray-400">
                            <span><i class="ri-user-line mr-1"></i>{{ $item->changedByUser->name ?? 'Système' }}</span>
                            <span><i class="ri-time-line mr-1"></i>{{ $item->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <i class="ri-git-commit-line text-4xl text-gray-300 block mb-2"></i>
                <p class="text-gray-500">Aucun historique trouvé</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ═══ ACTIVITY TAB ═══ --}}
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($items as $item)
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="ri-history-line text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded bg-green-100 text-green-800">{{ $item->action }}</span>
                            @if($item->subject_type)
                            <span class="text-xs text-gray-400">{{ class_basename($item->subject_type) }}{{ $item->subject_id ? ' #'.$item->subject_id : '' }}</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-700 mt-1">{{ $item->description }}</p>
                        <div class="flex items-center gap-3 mt-1.5 text-xs text-gray-400">
                            <span><i class="ri-user-line mr-1"></i>{{ $item->user->name ?? 'Système' }}</span>
                            <span><i class="ri-time-line mr-1"></i>{{ $item->created_at->format('d/m/Y H:i') }}</span>
                            @if($item->browser)
                            <span><i class="ri-global-line mr-1"></i>{{ $item->browser }} / {{ $item->platform }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <i class="ri-history-line text-4xl text-gray-300 block mb-2"></i>
                <p class="text-gray-500">Aucune activité trouvée</p>
            </div>
            @endforelse
        </div>
    </div>
    @endif

    <div class="mt-6">{{ $items->links() }}</div>
</div>
