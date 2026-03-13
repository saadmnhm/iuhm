@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif>
    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center shrink-0"
                 style="width:44px;height:44px;background:linear-gradient(135deg,#648454,#8baf74);">
                <i class="ri-broadcast-fill text-white fs-5"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0" style="color:#2d3748;">{{ $tr('Historique des messages', 'سجل الرسائل') }}</h4>
                <p class="text-muted mb-0" style="font-size:.8rem;">{{ $tr("Tous les messages diffusés par l'administration", 'جميع الرسائل المرسلة من الإدارة') }}</p>
            </div>
        </div>
    </div>



    {{-- Messages list --}}
    <div class="d-flex flex-column gap-3">
        @forelse($broadcasts as $broadcast)
        @php $isRead = in_array($broadcast->id, $readIds); @endphp
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden {{ !$isRead ? 'border-start border-4' : '' }}"
             style="{{ !$isRead ? 'border-left:4px solid #648454 !important;' : '' }}">

            <div class="card-body py-4 px-4">
                <div class="d-flex align-items-start gap-3">

                    {{-- Icon --}}
                    <div class="rounded-3 d-flex align-items-center justify-content-center shrink-0"
                         style="width:42px;height:42px;background:{{ $isRead ? '#f0f0f0' : 'linear-gradient(135deg,#648454,#8baf74)' }}">
                        <i class="ri-notification-3-{{ $isRead ? 'line' : 'fill' }} {{ $isRead ? 'text-muted' : 'text-white' }} fs-5"></i>
                    </div>

                    {{-- Content --}}
                    <div class="grow min-w-0">
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <span class="fw-bold" style="color:#2d3748;font-size:.95rem;">{{ $broadcast->title }}</span>
                            @if(!$isRead)
                                <span class="badge rounded-pill text-white" style="background:#648454;font-size:.65rem;">{{ $tr('Nouveau', 'جديد') }}</span>
                            @endif
                        </div>

                        <p class="text-muted mb-2" style="font-size:.875rem;line-height:1.7;">{{ $broadcast->message }}</p>

                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <span class="text-muted d-flex align-items-center gap-1" style="font-size:.75rem;">
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
                                <span class="text-success d-flex align-items-center gap-1" style="font-size:.75rem;">
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
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="ri-inbox-line fs-1 text-muted" style="opacity:.4;"></i>
                </div>
                <p class="text-muted mb-0" style="font-size:.9rem;">
                    @if($search)
                        {{ $tr('Aucun message trouvé pour', 'لم يتم العثور على أي رسالة لـ') }} « {{ $search }} »
                    @elseif($filter === 'unread')
                        {{ $tr('Vous avez lu tous vos messages !', 'لقد قرأت كل رسائلك!') }}
                    @else
                        {{ $tr('Aucun message diffusé pour le moment.', 'لا توجد رسائل حاليًا.') }}
                    @endif
                </p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($broadcasts->hasPages())
    <div class="mt-4">
        {{ $broadcasts->links() }}
    </div>
    @endif
</div>
