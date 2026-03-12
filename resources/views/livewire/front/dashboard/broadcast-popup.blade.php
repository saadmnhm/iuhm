
{{-- Polls every 15s to detect newly sent broadcasts --}}
<div wire:poll.3000ms="refresh">
	@if($current)
	{{-- Dark backdrop - blocks all interaction beneath --}}
	<div style="position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:10000;"></div>

	{{-- Centered modal card --}}
	<div style="position:fixed;inset:0;z-index:10001;display:flex;align-items:center;justify-content:center;padding:1rem;">
		<div class="card border-0 shadow-lg rounded-4 overflow-hidden w-100" style="max-width:480px;">

			{{-- Header --}}
			<div class="card-header d-flex align-items-center gap-2 border-0 py-3"
				 style="background:linear-gradient(135deg,#648454,#8baf74);">
				<div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
					 style="width:40px;height:40px;background:rgba(255,255,255,.2);">
					<i class="ri-broadcast-fill text-white fs-5"></i>
				</div>
				<div class="flex-grow-1">
					<div class="text-white fw-bold" style="font-size:.9rem;">Message de l'Administration</div>
					<div class="text-white" style="opacity:.75;font-size:.72rem;">
						{{ $current->created_at->diffForHumans() }}
					</div>
				</div>
				@if(!empty($queue))
					<span class="badge text-white rounded-pill" style="background:rgba(255,255,255,.25);font-size:.7rem;">
						+{{ count($queue) }} en attente
					</span>
				@endif
			</div>

			{{-- Body --}}
			<div class="card-body py-4 px-4">
				<h5 class="fw-bold mb-3" style="color:#2d3748;">{{ $current->title }}</h5>
				<p class="text-muted mb-0" style="line-height:1.75;font-size:.9rem;">{{ $current->message }}</p>
			</div>

			{{-- Footer - only confirm button, no close/skip --}}
			<div class="card-footer border-0 bg-light d-flex justify-content-end align-items-center py-3 px-4">
				<button wire:click="markRead"
						wire:loading.attr="disabled"
						class="btn fw-semibold text-white px-4 py-2 d-flex align-items-center gap-2"
						style="background:#648454;border-radius:.6rem;font-size:.875rem;">
					<span wire:loading wire:target="markRead"
						  class="spinner-border spinner-border-sm" role="status"></span>
					<i wire:loading.remove wire:target="markRead" class="ri-check-line"></i>
					J'ai lu et compris
				</button>
			</div>
		</div>
	</div>
	@endif
</div>

