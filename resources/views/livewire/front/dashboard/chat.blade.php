<div wire:poll.3s="refreshMessages" class="container py-4">

    <div class="d-flex align-items-center gap-2 mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center"
             style="width:42px;height:42px;background:#648454;color:#fff;font-size:1.1rem;">
            <i class="ri-customer-service-2-line"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0">Chat avec l'Administration</h5>
            <small class="text-muted">Notre équipe vous répond le plus tôt possible</small>
        </div>
    </div>

    {{-- Chat window --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3" style="height:62vh;">
        <div class="card-body p-3 overflow-auto d-flex flex-column gap-3"
             id="chat-messages-box"
             x-data
             x-init="
                const box = document.getElementById('chat-messages-box');
                box.scrollTop = box.scrollHeight;
             "
             x-on:message-sent.window="
                $nextTick(() => {
                    const b = document.getElementById('chat-messages-box');
                    b.scrollTop = b.scrollHeight;
                });
             ">

            @forelse($messages as $msg)
                @if($msg->sender_type === 'candidat')
                    {{-- Candidat bubble (right) --}}
                    <div class="d-flex justify-content-end">
                        <div style="max-width:72%;">
                            <div class="rounded-4 px-3 py-2 text-white small"
                                 style="background:#648454;border-bottom-right-radius:4px !important;">
                                {{ $msg->message }}
                            </div>
                            <div class="text-end mt-1">
                                <small class="text-muted" style="font-size:.7rem;">
                                    {{ $msg->created_at->format('H:i') }}
                                    @if($msg->is_read)
                                        <i class="ri-check-double-line text-primary ms-1"></i>
                                    @else
                                        <i class="ri-check-line text-muted ms-1"></i>
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Admin bubble (left) --}}
                    <div class="d-flex justify-content-start gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:32px;height:32px;background:#2563eb15;color:#2563eb;font-size:.85rem;">
                            <i class="ri-shield-user-line"></i>
                        </div>
                        <div style="max-width:72%;">
                            <div class="rounded-4 px-3 py-2 small"
                                 style="background:#f1f5f9;border-bottom-left-radius:4px !important;">
                                {{ $msg->message }}
                            </div>
                            <div class="mt-1">
                                <small class="text-muted" style="font-size:.7rem;">
                                    Administration · {{ $msg->created_at->format('H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="m-auto text-center text-muted">
                    <i class="ri-chat-3-line" style="font-size:3rem;opacity:.3;"></i>
                    <p class="mt-2 small">Aucun message. Commencez la conversation!</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Input area --}}
    <form wire:submit.prevent="sendMessage">
        @error('newMessage')
            <div class="alert alert-danger py-1 px-3 mb-2 small">{{ $message }}</div>
        @enderror
        <div class="input-group shadow-sm rounded-4 overflow-hidden border">
            <input
                wire:model.defer="newMessage"
                type="text"
                class="form-control border-0 ps-4"
                placeholder="Écrivez votre message…"
                style="background:#f8fafc;"
                @keydown.enter.prevent="$wire.sendMessage()"
                autofocus
            >
            <button
                type="submit"
                class="btn px-4 text-white"
                style="background:#648454;"
                wire:loading.attr="disabled"
            >
                <span wire:loading wire:target="sendMessage">
                    <span class="spinner-border spinner-border-sm"></span>
                </span>
                <span wire:loading.remove wire:target="sendMessage">
                    <i class="ri-send-plane-fill"></i>
                </span>
            </button>
        </div>
    </form>
</div>

<script>
    // Auto-scroll to bottom after Livewire re-renders (polling)
    document.addEventListener('livewire:updated', () => {
        const box = document.getElementById('chat-messages-box');
        if (box) box.scrollTop = box.scrollHeight;
    });
</script>
