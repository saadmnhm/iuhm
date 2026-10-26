@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

 {{-- Polls every 15s to detect newly sent broadcasts --}}
<div wire:poll.3000ms="refresh" @if($isArabic) dir="rtl" @endif>
    @if($current)
    {{-- Dark backdrop - blocks all interaction beneath --}}
    <div class="fixed inset-0 bg-black/55 z-[10000]"></div>

    {{-- Centered modal card --}}
    <div class="fixed inset-0 z-[10001] flex items-center justify-center p-4">
        <div class=" rounded-2xl shadow-xl overflow-hidden w-full max-w-md">

            {{-- Header --}}
            <div class="flex items-center gap-3 p-4 border-b border-gray-100" style="background: linear-gradient(135deg, #648454, #8baf74);">
                <div class="rounded-full flex items-center justify-center flex-shrink-0" style="width: 40px; height: 40px; background: rgba(255,255,255,0.2);">
                    <i class="ri-broadcast-fill text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <div class="text-white font-semibold">{{ $tr("Message de l'administration", 'رسالة من الإدارة') }}</div>
                    <div class="text-white text-xs" style="opacity: 0.75;">{{ $current->created_at->diffForHumans() }}</div>
                </div>
                @if(!empty($queue))
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white" style="background: rgba(255,255,255,0.25);">
                    +{{ count($queue) }} {{ $tr('en attente', 'بانتظار القراءة') }}
                </span>
                @endif
            </div>

            {{-- Body --}}
            <div class="p-5 bg-white">
                <h5 class="font-bold text-lg text-gray-900 mb-3">{{ $current->title }}</h5>
                <p class="text-gray-500 text-base leading-relaxed mb-0">{{ $current->message }}</p>
            </div>

            {{-- Footer - only confirm button, no close/skip --}}
            <div class="flex justify-end p-4 bg-gray-50 border-t border-gray-100">
                <button wire:click="markRead"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-white transition-colors"
                        style="background: #648454; font-size: 0.875rem;">
                    <span wire:loading wire:target="markRead" class="animate-spin">
                        <svg class="h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </span>
                    <i wire:loading.remove wire:target="markRead" class="ri-check-line"></i>
                    {{ $tr("J'ai lu et compris", 'قرأت وفهمت') }}
                </button>
            </div>
        </div>
    </div>
    @endif
</div>