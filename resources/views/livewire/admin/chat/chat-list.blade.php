<div>
    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center">
                <i class="ri-chat-3-fill text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Messagerie Candidats</h1>
                <p class="text-gray-500 text-sm">Gérez les conversations avec les candidats</p>
            </div>
        </div>
        @if($totalUnread > 0)
        <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-sm font-semibold px-3 py-1 rounded-full">
            <i class="ri-chat-unread-line"></i> {{ $totalUnread }} non lu(s)
        </span>
        @endif
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4">
        <div class="relative">
            <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Rechercher par nom, prénom, email ou matricule…"
                class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-400"
            >
        </div>
    </div>

    {{-- Conversation list --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @forelse($candidats as $candidat)
        <a href="{{ route('admin.chat.conversation', $candidat->id) }}"
           class="flex items-center gap-4 px-5 py-4 border-b border-gray-50 hover:bg-gray-50 transition group">

            {{-- Avatar --}}
            <div class="relative flex-shrink-0">
                @if($candidat->profile_image)
                    <img src="{{ asset('uploads/' . $candidat->profile_image) }}"
                         class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm" alt="">
                @else
                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-lg">
                        {{ strtoupper(substr($candidat->prenom ?? '?', 0, 1)) }}
                    </div>
                @endif
                @if($candidat->unread_count > 0)
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                        {{ $candidat->unread_count > 9 ? '9+' : $candidat->unread_count }}
                    </span>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-gray-900 group-hover:text-blue-700 transition">
                        {{ $candidat->prenom }} {{ $candidat->nom }}
                    </span>
                    @if($candidat->last_message)
                    <span class="text-xs text-gray-400 flex-shrink-0 ml-2">
                        {{ $candidat->last_message->created_at->diffForHumans() }}
                    </span>
                    @endif
                </div>
                <div class="flex items-center justify-between mt-0.5">
                    <p class="text-sm text-gray-500 truncate">
                        @if($candidat->last_message)
                            @if($candidat->last_message->sender_type === 'admin')
                                <span class="text-gray-400">Vous: </span>
                            @endif
                            {{ \Str::limit($candidat->last_message->message, 60) }}
                        @else
                            <em>Aucun message</em>
                        @endif
                    </p>
                    @if($candidat->unread_count > 0)
                    <span class="flex-shrink-0 ml-2 bg-blue-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $candidat->unread_count }}
                    </span>
                    @endif
                </div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $candidat->email }}</div>
            </div>

            <i class="ri-arrow-right-s-line text-gray-300 group-hover:text-blue-400 transition text-xl flex-shrink-0"></i>
        </a>
        @empty
        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
            <i class="ri-chat-off-line text-5xl mb-3 opacity-40"></i>
            <p class="text-sm">
                @if($search)
                    Aucun résultat pour "{{ $search }}"
                @else
                    Aucune conversation pour l'instant.
                @endif
            </p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $candidats->links() }}
    </div>
</div>
