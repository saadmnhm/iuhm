@props([
    'images' => [],
    'alt' => '',
    'aspect' => 'aspect-[16/10]',
    'rounded' => 'rounded-[2rem]',
    'class' => '',
    'showThumbnails' => false,
    'showCounter' => true,
    'showArrows' => true,
])

@php
    $slides = collect($images)
        ->filter(fn ($src) => filled($src))
        ->values()
        ->all();
    $count = count($slides);
    $carouselId = 'carousel-'.\Illuminate\Support\Str::uuid();
@endphp

@if ($count === 0)
    <div class="{{ $aspect }} {{ $rounded }} {{ $class }} relative overflow-hidden bg-gradient-to-br from-[#0f3d2e] via-[#173466] to-[#214f95]">
        <div class="absolute inset-0 flex items-center justify-center text-white/60"
             role="img" aria-label="{{ $alt ?: __('ui.image_unavailable') }}">
            <svg class="size-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5 12 3l9 6.5" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 9v11h13V9" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 20v-6h5v6" />
            </svg>
        </div>
        @if (! $slot->isEmpty())
            {{ $slot }}
        @endif
    </div>
@elseif ($count === 1)
    <div class="{{ $aspect }} {{ $rounded }} {{ $class }} relative overflow-hidden bg-slate-100">
        <img src="{{ $slides[0] }}" alt="{{ $alt }}" loading="lazy" class="h-full w-full object-cover">
        @if (! $slot->isEmpty())
            {{ $slot }}
        @endif
    </div>
@else
    <div
        id="{{ $carouselId }}"
        x-data="{
            active: 0,
            count: {{ $count }},
            touchX: null,
            go(index) { this.active = ((index % this.count) + this.count) % this.count; },
            next() { this.go(this.active + 1); },
            prev() { this.go(this.active - 1); },
            startSwipe(event) { this.touchX = event.touches[0]?.clientX ?? null; },
            endSwipe(event) {
                if (this.touchX === null) return;
                const distance = (event.changedTouches[0]?.clientX ?? this.touchX) - this.touchX;
                if (Math.abs(distance) > 40) {
                    distance < 0 ? this.next() : this.prev();
                }
                this.touchX = null;
            },
        }"
        x-init="active = 0"
        class="group/carousel relative z-20 {{ $class }}"
        role="region"
        aria-roledescription="carousel"
        aria-label="{{ $alt ?: __('ui.gallery') }}"
        tabindex="0"
        @keydown.arrow-left.prevent="prev()"
        @keydown.arrow-right.prevent="next()"
        @touchstart.passive="startSwipe($event)"
        @touchend.passive="endSwipe($event)"
    >
        <div class="{{ $aspect }} {{ $rounded }} relative overflow-hidden bg-slate-100">
            @foreach ($slides as $index => $src)
                <div x-show="active === {{ $index }}"
                     @if ($index > 0) x-cloak @endif
                     x-transition.opacity.duration.300ms
                     class="absolute inset-0"
                     role="group"
                     aria-roledescription="slide"
                     aria-label="{{ $index + 1 }} / {{ $count }}">
                    <img src="{{ $src }}"
                         alt="{{ $alt }}{{ $count > 1 ? ' — '.($index + 1) : '' }}"
                         loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                         class="h-full w-full object-cover">
                </div>
            @endforeach

            @if (! $slot->isEmpty())
                {{ $slot }}
            @endif

            @if ($showArrows)
                <button type="button"
                        @click.stop.prevent="prev()"
                        class="absolute left-3 top-1/2 z-30 inline-flex size-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/30 bg-black/35 text-white shadow-lg backdrop-blur-sm transition hover:bg-black/55 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#82E682] sm:size-11"
                        aria-label="{{ __('ui.previous_image') }}">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                        <path d="m15 18-6-6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <button type="button"
                        @click.stop.prevent="next()"
                        class="absolute right-3 top-1/2 z-30 inline-flex size-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/30 bg-black/35 text-white shadow-lg backdrop-blur-sm transition hover:bg-black/55 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#82E682] sm:size-11"
                        aria-label="{{ __('ui.next_image') }}">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                        <path d="m9 6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            @endif

            @if ($showCounter)
                <div class="pointer-events-none absolute bottom-3 right-3 z-30 rounded-full bg-black/55 px-3 py-1 text-[0.65rem] font-extrabold tracking-wide text-white backdrop-blur-sm"
                     aria-live="polite">
                    <span x-text="active + 1">{{ 1 }}</span> / {{ $count }}
                </div>
            @endif

            <div class="absolute bottom-3 left-1/2 z-30 flex -translate-x-1/2 items-center gap-1.5 rounded-full bg-black/25 px-2.5 py-1.5 backdrop-blur-sm">
                @foreach ($slides as $index => $src)
                    <button type="button"
                            @click.stop.prevent="go({{ $index }})"
                            class="size-2 rounded-full transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
                            :class="active === {{ $index }} ? 'w-5 bg-white' : 'bg-white/55 hover:bg-white/80'"
                            aria-label="{{ __('ui.go_to_image', ['number' => $index + 1]) }}"></button>
                @endforeach
            </div>
        </div>

        @if ($showThumbnails)
            <div class="mt-4 flex gap-2 overflow-x-auto pb-1" role="tablist" aria-label="{{ __('ui.gallery_thumbnails') }}">
                @foreach ($slides as $index => $src)
                    <button type="button"
                            @click.stop.prevent="go({{ $index }})"
                            class="relative h-16 w-24 shrink-0 overflow-hidden rounded-xl border-2 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-[#10752d]"
                            :class="active === {{ $index }} ? 'border-[#10752d] opacity-100' : 'border-transparent opacity-60 hover:opacity-100'"
                            role="tab"
                            :aria-selected="active === {{ $index }}"
                            aria-label="{{ __('ui.go_to_image', ['number' => $index + 1]) }}">
                        <img src="{{ $src }}" alt="" loading="lazy" class="h-full w-full object-cover">
                    </button>
                @endforeach
            </div>
        @endif
    </div>
@endif
