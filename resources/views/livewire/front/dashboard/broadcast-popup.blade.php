<div>
    @if($current)
<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-4"
    class="position-fixed bottom-0 end-0 p-3"
    style="z-index:9999;max-width:420px;width:100%;"
>
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        {{-- Header --}}
        <div class="card-header d-flex align-items-center gap-2 border-0 py-3"
             style="background:linear-gradient(135deg,#648454,#8baf74);">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width:36px;height:36px;background:rgba(255,255,255,.2);">
                <i class="ri-notification-3-fill text-white fs-6"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-white fw-semibold small">Message de l'Administration</div>
                <div class="text-white small" style="opacity:.75;font-size:.7rem;">
                    {{ $current->created_at->diffForHumans() }}
                </div>
            </div>
            <button wire:click="dismiss" class="btn btn-sm text-white p-0" style="background:none;border:none;">
                <i class="ri-close-line fs-5"></i>
            </button>
        </div>
        {{-- Body --}}
        <div class="card-body py-3 px-4">
            <h6 class="fw-bold mb-2">{{ $current->title }}</h6>
            <p class="text-muted small mb-0" style="line-height:1.6;">{{ $current->message }}</p>
        </div>
        {{-- Footer --}}
        <div class="card-footer bg-light border-0 d-flex justify-content-between align-items-center py-2 px-4">
            @if(!empty($queue))
                <small class="text-muted">{{ count($queue) }} autre(s) message(s)</small>
            @else
                <span></span>
            @endif
            <button wire:click="dismiss"
                    class="btn btn-sm fw-semibold text-white px-3"
                    style="background:#648454;border-radius:.5rem;">
                OK, compris
            </button>
        </div>
    </div>
</div>
@endif

</div>