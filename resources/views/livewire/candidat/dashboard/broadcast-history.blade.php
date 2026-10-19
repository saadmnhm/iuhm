@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif class="p-4 sm:p-6 lg:p-8">
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="rounded-xl flex items-center justify-center flex-shrink-0" style="width: 44px; height: 44px; background: linear-gradient(135deg, #648454, #8baf74);">
                <i class="ri-broadcast-fill text-white text-xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-lg text-gray-900 mb-0">{{ $tr('Historique des messages', 'سجل الرسائل') }}</h4>
                <p class="text-gray-500 text-sm mb-0">{{ $tr("Tous les messages diffusés par l'administration", 'جميع الرسائل المرسلة من الإدارة') }}</p>
            </div>
        </div>
    </div>

    {{-- Messages list --}}
    <div class="space-y-3">
        @forelse($broadcasts as $broadcast)
        @php $isRead = in_array($broadcast->id, $readIds); @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden {{ !$isRead ? 'border-l-4 border-green-600' : '' }}">

            <div class="p-4">
                <div class="flex items-start gap-3">

                    {{-- Icon --}}
                    <div class="rounded-xl flex items-center justify-center flex-shrink-0" style="width: 42px; height: 42px; background: {{ $isRead ? '#f0f0f0' : 'linear-gradient(135deg,#648454,#8baf74)' }}">
                        <i class="ri-notification-3-{{ $isRead ? 'line' : 'fill' }} {{ $isRead ? 'text-gray-400' : 'text-white' }} text-xl"></i>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span class="font-semibold text-gray-900">{{ $broadcast->title }}</span>
                            @if(!$isRead)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white" style="background: #648454;">{{ $tr('Nouveau', 'جديد') }}</span>
                            @endif
                        </div>

                        <p class="text-gray-500 text-sm leading-relaxed mb-2">{{ $broadcast->message }}</p>

                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="text-gray-400 flex items-center gap-1 text-xs">
                                <i class="ri-time-line"></i>
                                {{ $broadcast->created_at->diffForHumans() }}
                                &mdash; {{ $broadcast->created_at->format('d/m/Y à H:i') }}
                            </span>
                            @if($isRead)
                            @php
                                $readRecord = \App\Models\BroadcastRead::where('broadcast_id', $broadcast->id)
                                    ->where('candidat_id', auth()->guard('candidat')->id())
                                    ->first();
                            @endphp
                            <span class="text-green-600 flex items-center gap-1 text-xs">
                                <i class="ri-checkbox-circle-fill"></i>
                                {{ $tr('Lu', 'مقروء') }} {{ $readRecord?->read_at ? \Carbon\Carbon::parse($readRecord->read_at)->diffForHumans() : '' }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="mb-3">
                <i class="ri-inbox-line text-5xl text-gray-300" style="opacity: 0.4;"></i>
            </div>
            <p class="text-gray-500 text-base mb-0">
                @if($search)
                    {{ $tr('Aucun message trouvé pour', 'لم يتم العثور على أي رسالة لـ') }} « {{ $search }} »
                @elseif($filter === 'unread')
                    {{ $tr('Vous avez lu tous vos messages !', 'لقد قرأت كل رسائلك!') }}
                @else
                    {{ $tr('Aucun message diffusé pour le moment.', 'لا توجد رسائل حاليًا.') }}
                @endif
            </p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($broadcasts->hasPages())
    <div class="mt-6">
        {{ $broadcasts->links() }}
    </div>
    @endif
</div>