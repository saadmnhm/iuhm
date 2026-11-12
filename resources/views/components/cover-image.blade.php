@props(['src' => null, 'alt' => '', 'class' => '', 'iconClass' => 'size-10'])

@if (filled($src))
    <img src="{{ $src }}" alt="{{ $alt }}" loading="lazy" class="{{ $class }}">
@else
    <div class="flex items-center justify-center bg-gradient-to-br from-[#0f3d2e] via-[#173466] to-[#214f95] text-white/70 {{ $class }}"
         role="img" aria-label="{{ $alt ?: __('ui.image_unavailable') }}">
        <svg class="{{ $iconClass }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5 12 3l9 6.5" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 9v11h13V9" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 20v-6h5v6" />
        </svg>
    </div>
@endif
