@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div wire:poll.3s="refreshMessages" @if($isArabic) dir="rtl" @endif class="p-4 sm:p-6 lg:p-8">

    <div class="flex items-center gap-3 mb-6">
        <div class="rounded-full flex items-center justify-center" style="width: 42px; height: 42px; background: #648454; color: #fff; font-size: 1.1rem;">
            <i class="ri-customer-service-2-line"></i>
        </div>
        <div>
            <h5 class="font-bold text-lg text-gray-900 mb-0">{{ $tr("Chat avec l'administration", 'دردشة مع الإدارة') }}</h5>
            <small class="text-gray-500">{{ $tr('Notre équipe vous répond le plus tôt possible', 'فريقنا سيرد عليك في أقرب وقت ممكن') }}</small>
        </div>
    </div>

    {{-- Chat window --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-4" style="height: 62vh;">
        <div class="p-4 overflow-y-auto flex flex-col gap-4" id="chat-messages-box"
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
                    <div class="flex justify-end">
                        <div style="max-width: 72%;">
                            <div class="rounded-2xl px-4 py-2 text-white text-sm"
                                 style="background: #648454; border-bottom-right-radius: 4px !important;">
                                {{ $msg->message }}
                            </div>
                            <div class="text-right mt-1">
                                <small class="text-gray-400" style="font-size: 0.7rem;">
                                    {{ $msg->created_at->format('H:i') }}
                                    @if($msg->is_read)
                                        <i class="ri-check-double-line text-green-300 ml-1"></i>
                                    @else
                                        <i class="ri-check-line text-gray-400 ml-1"></i>
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Admin bubble (left) --}}
                    <div class="flex items-start gap-2">
                        <div class="rounded-full flex items-center justify-center flex-shrink-0" style="width: 32px; height: 32px; background: #dbeafe; color: #2563eb; font-size: 0.85rem;">
                            <i class="ri-shield-user-line"></i>
                        </div>
                        <div style="max-width: 72%;">
                            <div class="rounded-2xl px-4 py-2 text-sm" style="background: #f1f5f9; border-bottom-left-radius: 4px !important;">
                                {{ $msg->message }}
                            </div>
                            <div class="mt-1">
                                <small class="text-gray-400" style="font-size: 0.7rem;">
                                    {{ $tr('Administration', 'الإدارة') }} · {{ $msg->created_at->format('H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
            <div class="m-auto text-center text-gray-400">
                <i class="ri-chat-3-line" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="mt-2 text-sm">{{ $tr('Aucun message. Commencez la conversation !', 'لا توجد رسائل. ابدأ المحادثة!') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Input area --}}
    <form wire:submit.prevent="sendMessage">
        @error('newMessage')
            <div class="flex items-center gap-2 rounded-xl px-4 py-2 mb-3 bg-red-50 border border-red-100 text-red-700 text-sm" role="alert">
                {{ $message }}
            </div>
        @enderror
        <div class="flex gap-2 shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
            <input
                wire:model.defer="newMessage"
                type="text"
                class="flex-1 border-0 px-4 py-3 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500"
                placeholder="{{ $tr('Écrivez votre message…', 'اكتب رسالتك…') }}"
                @keydown.enter.prevent="$wire.sendMessage()"
                autofocus
            >
            <button
                type="submit"
                class="inline-flex items-center justify-center px-5 text-white transition-colors"
                style="background: #648454;"
                wire:loading.attr="disabled"
            >
                <span wire:loading wire:target="sendMessage">
                    <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </span>
                <span wire:loading.remove wire:target="sendMessage">
                    <i class="ri-send-plane-fill text-lg"></i>
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