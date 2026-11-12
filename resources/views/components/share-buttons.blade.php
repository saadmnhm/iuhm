@props(['url' => null, 'title' => '', 'heading' => null])

@php
    $shareUrl = $url ?? url()->current();
    $shareTitle = $title ?? '';
    $encodedUrl = rawurlencode($shareUrl);
    $encodedTitle = rawurlencode($shareTitle);
    $networks = [
        [
            'name' => 'Facebook',
            'href' => 'https://www.facebook.com/sharer/sharer.php?u='.$encodedUrl,
            'class' => 'bg-[#1877F2] hover:bg-[#0f63d0]',
            'path' => 'M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.9h2.54V9.85c0-2.52 1.5-3.91 3.77-3.91 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.78-1.63 1.57v1.89h2.78l-.45 2.9h-2.33V22c4.78-.79 8.44-4.94 8.44-9.94Z',
        ],
        [
            'name' => 'WhatsApp',
            'href' => 'https://wa.me/?text='.$encodedTitle.'%20'.$encodedUrl,
            'class' => 'bg-[#25D366] hover:bg-[#1cb954]',
            'path' => 'M20.52 3.48A11.87 11.87 0 0 0 12.06 0C5.46 0 .1 5.36.1 11.95c0 2.1.55 4.16 1.6 5.98L0 24l6.22-1.63a11.94 11.94 0 0 0 5.84 1.49h.01c6.59 0 11.95-5.36 11.95-11.95 0-3.19-1.24-6.2-3.5-8.43Zm-8.46 18.4h-.01a9.9 9.9 0 0 1-5.05-1.38l-.36-.22-3.7.97.99-3.6-.24-.37a9.9 9.9 0 0 1-1.52-5.3c0-5.46 4.45-9.9 9.9-9.9 2.65 0 5.14 1.03 7.01 2.9a9.85 9.85 0 0 1 2.9 7.01c0 5.46-4.44 9.9-9.92 9.9Zm5.43-7.41c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.06 2.87 1.21 3.07.15.2 2.09 3.2 5.06 4.49.71.3 1.26.48 1.69.62.71.22 1.36.19 1.87.12.57-.09 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41-.07-.12-.27-.2-.57-.35Z',
        ],
        [
            'name' => 'LinkedIn',
            'href' => 'https://www.linkedin.com/sharing/share-offsite/?url='.$encodedUrl,
            'class' => 'bg-[#0A66C2] hover:bg-[#084f96]',
            'path' => 'M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.34V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.45v6.29ZM5.34 7.43a2.07 2.07 0 1 1 0-4.13 2.07 2.07 0 0 1 0 4.13ZM7.12 20.45H3.55V9h3.57v11.45ZM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0Z',
        ],
        [
            'name' => 'X',
            'href' => 'https://twitter.com/intent/tweet?url='.$encodedUrl.'&text='.$encodedTitle,
            'class' => 'bg-[#0B1528] hover:bg-[#162D5A]',
            'path' => 'M18.24 2.25h3.31l-7.23 8.26 8.5 11.24h-6.65l-5.21-6.82-5.97 6.82H1.67l7.73-8.84L1.25 2.25h6.82l4.71 6.23 5.46-6.23Zm-1.16 17.52h1.83L7.01 4.13H5.04l12.04 15.64Z',
        ],
    ];
@endphp

<div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-sm shadow-slate-200 border border-slate-100"
     x-data="{
        copied: false,
        copyLink() {
            const url = this.$el.dataset.shareUrl;
            const done = () => { this.copied = true; setTimeout(() => this.copied = false, 2200); };
            const fallback = () => {
                const el = document.createElement('textarea');
                el.value = url;
                el.style.position = 'fixed';
                el.style.opacity = '0';
                document.body.appendChild(el);
                el.select();
                try { document.execCommand('copy'); } catch (e) {}
                document.body.removeChild(el);
                done();
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(done).catch(fallback);
            } else {
                fallback();
            }
        }
     }"
     data-share-url="{{ $shareUrl }}">
    <h3 class="text-[1.1rem] font-bold text-[#0B1528] mb-6">{{ $heading ?? __('ui.share') }}</h3>

    <div class="flex flex-wrap gap-3">
        @foreach($networks as $network)
            <a href="{{ $network['href'] }}"
               target="_blank"
               rel="noopener noreferrer"
               title="{{ __('ui.share_on', ['network' => $network['name']]) }}"
               aria-label="{{ __('ui.share_on', ['network' => $network['name']]) }}"
               class="size-11 rounded-full text-white flex items-center justify-center shadow-md shadow-slate-800/15 transition {{ $network['class'] }}">
                <svg class="size-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="{{ $network['path'] }}" />
                </svg>
            </a>
        @endforeach

        <button type="button"
                x-on:click="copyLink()"
                class="size-11 rounded-full bg-[#EAECEF] text-slate-600 flex items-center justify-center hover:bg-slate-200 transition"
                :class="copied ? '!bg-[#82E682] !text-[#0a471b]' : ''"
                :title="copied ? '{{ __('ui.link_copied') }}' : '{{ __('ui.copy_link') }}'"
                :aria-label="copied ? '{{ __('ui.link_copied') }}' : '{{ __('ui.copy_link') }}'">
            <svg x-show="!copied" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 0 0-5.656 0l-4 4a4 4 0 1 0 5.656 5.656l1.102-1.101m-.758-4.899a4 4 0 0 0 5.656 0l4-4a4 4 0 0 0-5.656-5.656l-1.1 1.1" />
            </svg>
            <svg x-show="copied" x-cloak class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
            </svg>
        </button>
    </div>

    <p class="mt-3 text-xs font-semibold text-[#10752d] h-4" x-show="copied" x-cloak>{{ __('ui.link_copied') }}</p>
</div>
