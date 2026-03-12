<div wire:poll.3s="refreshMessages">

    {{-- Back + Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.chat.list') }}"
           class="flex items-center gap-1 text-gray-500 hover:text-blue-600 transition text-sm">
            <i class="ri-arrow-left-line text-lg"></i> Retour
        </a>
        <div class="flex items-center gap-3">
            @if($candidat->profile_image)
                <img src="{{ asset('uploads/' . $candidat->profile_image) }}"
                     class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm" alt="">
            @else
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                    {{ strtoupper(substr($candidat->prenom ?? '?', 0, 1)) }}
                </div>
            @endif
            <div>
                <div class="font-semibold text-gray-900">{{ $candidat->prenom }} {{ $candidat->nom }}</div>
                <div class="text-xs text-gray-400">{{ $candidat->email }}</div>
            </div>
        </div>
        <a href="{{ route('admin.candidats.show', $candidat->id) }}"
           class="ml-auto text-sm text-blue-600 hover:underline flex items-center gap-1">
            <i class="ri-external-link-line"></i> Voir le dossier
        </a>
    </div>

    {{-- Chat window --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-3">
        <div class="p-4 overflow-y-auto flex flex-col gap-3"
             id="admin-chat-box"
             style="height:60vh;"
             x-data
             x-init="
                const b = document.getElementById('admin-chat-box');
                b.scrollTop = b.scrollHeight;
             "
             x-on:message-sent.window="
                $nextTick(() => {
                    const b = document.getElementById('admin-chat-box');
                    b.scrollTop = b.scrollHeight;
                });
             ">

            @forelse($messages as $msg)
                @if($msg->sender_type === 'admin')
                    {{-- Admin bubble (right) --}}
                    <div class="flex justify-end">
                        <div style="max-width:72%;">
                            <div class="rounded-2xl px-4 py-2 text-white text-sm"
                                 style="background:#2563eb;border-bottom-right-radius:4px;">
                                {{ $msg->message }}
                            </div>
                            <div class="text-right mt-1 flex items-center justify-end gap-1">
                                <span class="text-xs text-gray-400">
                                    {{ $msg->adminSender?->name ?? 'Admin' }} · {{ $msg->created_at->format('H:i') }}
                                </span>
                                @if($msg->is_read)
                                    <i class="ri-check-double-line text-blue-500 text-xs"></i>
                                @else
                                    <i class="ri-check-line text-gray-400 text-xs"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Candidat bubble (left) --}}
                    <div class="flex justify-start gap-2">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 text-gray-500 text-sm">
                            {{ strtoupper(substr($candidat->prenom ?? '?', 0, 1)) }}
                        </div>
                        <div style="max-width:72%;">
                            <div class="rounded-2xl px-4 py-2 text-gray-800 text-sm"
                                 style="background:#f1f5f9;border-bottom-left-radius:4px;">
                                {{ $msg->message }}
                            </div>
                            <div class="mt-1">
                                <span class="text-xs text-gray-400">{{ $msg->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <i class="ri-chat-3-line text-5xl opacity-30 mb-2"></i>
                    <p class="text-sm">Aucun message pour l'instant.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Input --}}
    <form wire:submit.prevent="sendMessage" class="flex gap-2">
        @error('newMessage')
            <div class="text-red-500 text-xs mb-1">{{ $message }}</div>
        @enderror
        <input
            wire:model.defer="newMessage"
            type="text"
            placeholder="Répondre à {{ $candidat->prenom }}…"
            class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-400"
        >
        <button
            type="submit"
            wire:loading.attr="disabled"
            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-medium transition"
        >
            <span wire:loading wire:target="sendMessage" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
            <i wire:loading.remove wire:target="sendMessage" class="ri-send-plane-fill"></i>
            <span wire:loading.remove wire:target="sendMessage">Envoyer</span>
        </button>
    </form>
</div>

<script>
    document.addEventListener('livewire:updated', () => {
        const box = document.getElementById('admin-chat-box');
        if (box) box.scrollTop = box.scrollHeight;
    });
</script>
