<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center">
                <i class="ri-broadcast-fill text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Messages Broadcast</h1>
                <p class="text-gray-500 text-sm">Envoyez des notifications popup aux candidats</p>
            </div>
        </div>
        <button wire:click="openForm"
                class="flex items-center gap-2 bg-green-50 text-green-700  px-4 py-2 rounded-xl text-sm font-medium transition">
            <i class="ri-add-line"></i> Nouveau message
        </button>
    </div>

    {{-- Flash --}}
    @if(session()->has('broadcast_success'))
    <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-4 flex items-center gap-2">
        <i class="ri-checkbox-circle-fill text-green-500"></i>
        {{ session('broadcast_success') }}
    </div>
    @endif

    {{-- Compose form --}}
    @if($showForm)
    <div class="bg-white rounded-xl shadow-sm border border-amber-100 p-6 mb-6">
        <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="ri-send-plane-2-line text-amber-500"></i> Composer un message
        </h2>

        <div class="grid grid-cols-1 gap-4">
            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                <input wire:model.defer="title" type="text" placeholder="Ex: Information importante"
                       class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:border-amber-400
                              @error('title') border-red-400 @else border-gray-200 @enderror">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Message --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Message *</label>
                <textarea wire:model.defer="message" rows="4" placeholder="Contenu du message…"
                          class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:border-amber-400 resize-none
                                 @error('message') border-red-400 @else border-gray-200 @enderror"></textarea>
                @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Target type --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Destinataires *</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model.live="targetType" type="radio" value="all" class="text-amber-500">
                        <span class="text-sm">Tous les candidats</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model.live="targetType" type="radio" value="selected" class="text-amber-500">
                        <span class="text-sm">Sélection</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model.live="targetType" type="radio" value="single" class="text-amber-500">
                        <span class="text-sm">Un seul</span>
                    </label>
                </div>
            </div>

            {{-- Candidat search (shared for single/selected) --}}
            @if($targetType !== 'all')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher un candidat</label>
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="Nom, prénom, email ou matricule…"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-amber-400">
            </div>

            @if($targetType === 'single')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Candidat *</label>
                <select wire:model.defer="singleId"
                        class="w-full px-3 py-2 border rounded-lg text-sm @error('singleId') border-red-400 @else border-gray-200 @enderror">
                    <option value="">-- Choisir un candidat --</option>
                    @foreach($candidats as $c)
                        <option value="{{ $c->id }}">{{ $c->prenom }} {{ $c->nom }} ({{ $c->matricule ?? $c->email }})</option>
                    @endforeach
                </select>
                @error('singleId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            @elseif($targetType === 'selected')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Candidats *</label>
                <div class="border border-gray-200 rounded-lg overflow-y-auto" style="max-height:180px;">
                    @forelse($candidats as $c)
                    <label class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 cursor-pointer border-b border-gray-50">
                        <input wire:model.defer="selectedIds" type="checkbox" value="{{ $c->id }}"
                               class="text-amber-500 rounded">
                        <span class="text-sm text-gray-800">
                            {{ $c->prenom }} {{ $c->nom }}
                            <span class="text-xs text-gray-400 ml-1">{{ $c->matricule ?? $c->email }}</span>
                        </span>
                    </label>
                    @empty
                    <div class="px-4 py-3 text-sm text-gray-400">Aucun candidat trouvé.</div>
                    @endforelse
                </div>
                @if(!empty($selectedIds))
                    <p class="text-xs text-gray-500 mt-1">{{ count($selectedIds) }} candidat(s) sélectionné(s)</p>
                @endif
                @error('selectedIds') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            @endif
            @endif
        </div>

        <div class="flex justify-end gap-3 mt-5">
            <button wire:click="$set('showForm', false)"
                    class="px-4 py-2 border border-gray-200  bg-red-50 text-red-700 rounded-xl text-sm hover:bg-gray-50 transition">
                Annuler
            </button>
            <button wire:click="sendBroadcast"
                    wire:loading.attr="disabled"
                    class="flex items-center gap-2 bg-green-50 text-green-700  px-5 py-2 rounded-xl text-sm font-medium transition">
                <span wire:loading wire:target="sendBroadcast" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                <i wire:loading.remove wire:target="sendBroadcast" class="ri-broadcast-fill"></i>
                Diffuser le message
            </button>
        </div>
    </div>
    @endif

    {{-- History table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-700 text-sm">Historique des broadcasts</h3>
        </div>

        @forelse($broadcasts as $b)
        <div class="flex items-start gap-4 px-5 py-4 border-b border-gray-50 hover:bg-gray-50 transition">
            {{-- Status dot --}}
            <div class="mt-1 flex-shrink-0">
                <span class="w-2.5 h-2.5 rounded-full inline-block {{ $b->is_active ? 'bg-green-500' : 'bg-gray-300' }}"></span>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <span class="font-semibold text-gray-900 text-sm">{{ $b->title }}</span>
                        <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $b->target_type === 'all' ? 'bg-blue-100 text-blue-700' :
                               ($b->target_type === 'single' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700') }}">
                            @if($b->target_type === 'all') Tous
                            @elseif($b->target_type === 'single') Individuel
                            @else Sélection ({{ count($b->target_candidat_ids ?? []) }})
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if($b->is_active)
                        <button wire:click="deactivate({{ $b->id }})"
                                class="text-xs text-gray-500 hover:text-orange-600 transition px-2 py-1 rounded border border-gray-200 hover:border-orange-300">
                            Désactiver
                        </button>
                        @endif
                        <button wire:click="delete({{ $b->id }})"
                                onclick="return confirm('Supprimer ce broadcast?')"
                                class="text-xs text-red-500 hover:text-red-700 transition px-2 py-1 rounded border border-red-100 hover:border-red-300">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-1">{{ \Str::limit($b->message, 120) }}</p>
                <div class="text-xs text-gray-400 mt-1 flex items-center gap-3">
                    <span><i class="ri-user-line mr-1"></i>{{ $b->admin?->name ?? 'Admin' }}</span>
                    <span><i class="ri-time-line mr-1"></i>{{ $b->created_at->format('d/m/Y H:i') }}</span>
                    <span><i class="ri-eye-line mr-1"></i>{{ $b->reads->count() }} lu(s)</span>
                </div>
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center py-12 text-gray-400">
            <i class="ri-broadcast-line text-5xl opacity-30 mb-3"></i>
            <p class="text-sm">Aucun broadcast envoyé.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $broadcasts->links() }}</div>
</div>
