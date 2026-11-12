@php
    $settings = $siteSettings ?? [];

    $associationName = $settings['association_name'] ?? __('ui.brand_name');
    $associationAcronym = $settings['association_acronym'] ?? null;
    $description = $settings['footer_description'] ?? $settings['association_description'] ?? __('ui.footer_tagline');

    $logoPath = $settings['association_logo'] ?? null;
    $logoUrl = null;
    if (filled($logoPath)) {
        $logoUrl = \Illuminate\Support\Str::startsWith($logoPath, ['http://', 'https://', '//'])
            ? $logoPath
            : asset(ltrim($logoPath, '/'));
    }

    $email = $settings['contact_email'] ?? null;
    $phone = $settings['contact_phone'] ?? null;
    $whatsapp = $settings['contact_whatsapp'] ?? null;
    $website = $settings['contact_website'] ?? null;
    $address = trim(implode(', ', array_filter([
        $settings['association_address'] ?? null,
        $settings['association_city'] ?? null,
    ])));

    $socials = collect([
        ['key' => 'contact_facebook', 'label' => 'Facebook', 'href' => $settings['contact_facebook'] ?? null, 'path' => 'M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.9h2.54V9.85c0-2.52 1.5-3.91 3.77-3.91 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.78-1.63 1.57v1.89h2.78l-.45 2.9h-2.33V22c4.78-.79 8.44-4.94 8.44-9.94Z'],
        ['key' => 'contact_instagram', 'label' => 'Instagram', 'href' => $settings['contact_instagram'] ?? null, 'path' => 'M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23a3.7 3.7 0 0 1-.9 1.38c-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16Zm0 5.68a4.16 4.16 0 1 0 0 8.32 4.16 4.16 0 0 0 0-8.32Zm0 6.86a2.7 2.7 0 1 1 0-5.4 2.7 2.7 0 0 1 0 5.4Zm5.3-7.02a.97.97 0 1 1-1.94 0 .97.97 0 0 1 1.94 0Z'],
        ['key' => 'contact_linkedin', 'label' => 'LinkedIn', 'href' => $settings['contact_linkedin'] ?? null, 'path' => 'M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.34V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.45v6.29ZM5.34 7.43a2.07 2.07 0 1 1 0-4.13 2.07 2.07 0 0 1 0 4.13ZM7.12 20.45H3.55V9h3.57v11.45ZM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0Z'],
        ['key' => 'contact_youtube', 'label' => 'YouTube', 'href' => $settings['contact_youtube'] ?? null, 'path' => 'M23.5 6.2a3.02 3.02 0 0 0-2.12-2.14C19.5 3.55 12 3.55 12 3.55s-7.5 0-9.38.51A3.02 3.02 0 0 0 .5 6.2C0 8.08 0 12 0 12s0 3.92.5 5.8a3.02 3.02 0 0 0 2.12 2.14c1.88.51 9.38.51 9.38.51s7.5 0 9.38-.51a3.02 3.02 0 0 0 2.12-2.14C24 15.92 24 12 24 12s0-3.92-.5-5.8ZM9.55 15.57V8.43L15.82 12l-6.27 3.57Z'],
        ['key' => 'contact_x', 'label' => 'X', 'href' => $settings['contact_x'] ?? null, 'path' => 'M18.24 2.25h3.31l-7.23 8.26 8.5 11.24h-6.65l-5.21-6.82-5.97 6.82H1.67l7.73-8.84L1.25 2.25h6.82l4.71 6.23 5.46-6.23Zm-1.16 17.52h1.83L7.01 4.13H5.04l12.04 15.64Z'],
    ])->filter(fn ($social) => filled($social['href']));

    if (filled($whatsapp)) {
        $socials->prepend([
            'key' => 'contact_whatsapp',
            'label' => 'WhatsApp',
            'href' => \Illuminate\Support\Str::startsWith($whatsapp, ['http://', 'https://']) ? $whatsapp : 'https://wa.me/'.preg_replace('/\D+/', '', $whatsapp),
            'path' => 'M20.52 3.48A11.87 11.87 0 0 0 12.06 0C5.46 0 .1 5.36.1 11.95c0 2.1.55 4.16 1.6 5.98L0 24l6.22-1.63a11.94 11.94 0 0 0 5.84 1.49h.01c6.59 0 11.95-5.36 11.95-11.95 0-3.19-1.24-6.2-3.5-8.43Zm-8.46 18.4h-.01a9.9 9.9 0 0 1-5.05-1.38l-.36-.22-3.7.97.99-3.6-.24-.37a9.9 9.9 0 0 1-1.52-5.3c0-5.46 4.45-9.9 9.9-9.9 2.65 0 5.14 1.03 7.01 2.9a9.85 9.85 0 0 1 2.9 7.01c0 5.46-4.44 9.9-9.92 9.9Zm5.43-7.41c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.06 2.87 1.21 3.07.15.2 2.09 3.2 5.06 4.49.71.3 1.26.48 1.69.62.71.22 1.36.19 1.87.12.57-.09 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41-.07-.12-.27-.2-.57-.35Z',
        ]);
    }

    $quickLinks = collect($footerNavLinks ?? [])->filter(fn ($link) => filled($link['url'] ?? null) && filled($link['label'] ?? null));
    $pageLinks = collect($footerPages ?? [])->filter(fn ($page) => filled($page['url'] ?? null) && filled($page['label'] ?? null));
@endphp

<footer class="mt-16 border-t border-white/10 bg-[#0B1528] text-slate-300">
    <div class="iu-container py-14 sm:py-16">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-12 lg:gap-8">

            {{-- Brand --}}
            <div class="lg:col-span-4">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $associationName }}" class="h-12 w-auto max-w-[9rem] object-contain">
                    @else
                        <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-[#82E682] text-sm font-extrabold text-[#0a471b]">
                            {{ $associationAcronym ?: \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($associationName, 0, 2)) }}
                        </span>
                    @endif
                    <span class="text-lg font-bold text-white">{{ $associationName }}</span>
                </a>

                @if(filled($description))
                    <p class="mt-5 max-w-sm text-sm leading-7 text-slate-400">{{ $description }}</p>
                @endif

                @if($socials->isNotEmpty())
                    <div class="mt-6">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">{{ __('ui.footer_follow') }}</p>
                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            @foreach($socials as $social)
                                <a href="{{ $social['href'] }}" target="_blank" rel="noopener noreferrer"
                                   title="{{ $social['label'] }}" aria-label="{{ $social['label'] }}"
                                   class="flex size-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-slate-300 transition hover:border-[#82E682] hover:bg-[#82E682] hover:text-[#0a471b]">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="{{ $social['path'] }}" />
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Quick links --}}
            @if($quickLinks->isNotEmpty())
                <div class="lg:col-span-2">
                    <h3 class="text-xs font-bold uppercase tracking-[0.16em] text-white">{{ __('ui.footer_quick_links') }}</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        @foreach($quickLinks as $link)
                            <li>
                                <a href="{{ $link['url'] }}" class="inline-flex text-slate-400 transition hover:text-[#82E682]">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Useful pages --}}
            @if($pageLinks->isNotEmpty())
                <div class="lg:col-span-2">
                    <h3 class="text-xs font-bold uppercase tracking-[0.16em] text-white">{{ __('ui.footer_useful_links') }}</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        @foreach($pageLinks as $page)
                            <li>
                                <a href="{{ $page['url'] }}" class="inline-flex text-slate-400 transition hover:text-[#82E682]">
                                    {{ $page['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Contact --}}
            @if($email || $phone || $address || $website)
                <div class="lg:col-span-4">
                    <h3 class="text-xs font-bold uppercase tracking-[0.16em] text-white">{{ __('ui.footer_contact') }}</h3>
                    <ul class="mt-5 space-y-4 text-sm">
                        @if($address)
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 size-4 shrink-0 text-[#82E682]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657 13.414 20.9a1.998 1.998 0 0 1-2.827 0l-4.243-4.243a8 8 0 1 1 11.314 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <span class="text-slate-400">{{ $address }}</span>
                            </li>
                        @endif
                        @if($email)
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 size-4 shrink-0 text-[#82E682]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Z" />
                                </svg>
                                <a href="mailto:{{ $email }}" class="text-slate-400 transition hover:text-[#82E682] break-all">{{ $email }}</a>
                            </li>
                        @endif
                        @if($phone)
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 size-4 shrink-0 text-[#82E682]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 0 1 2-2h2.28a1 1 0 0 1 .95.68l1.2 3.6a1 1 0 0 1-.5 1.2l-1.5.9a12 12 0 0 0 5.4 5.4l.9-1.5a1 1 0 0 1 1.2-.5l3.6 1.2a1 1 0 0 1 .68.95V19a2 2 0 0 1-2 2h-1C9.6 21 3 14.4 3 6V5Z" />
                                </svg>
                                <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="text-slate-400 transition hover:text-[#82E682]">{{ $phone }}</a>
                            </li>
                        @endif
                        @if($website)
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 size-4 shrink-0 text-[#82E682]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                    <circle cx="12" cy="12" r="9" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18" />
                                </svg>
                                <a href="{{ $website }}" target="_blank" rel="noopener noreferrer" class="text-slate-400 transition hover:text-[#82E682] break-all">{{ parse_url($website, PHP_URL_HOST) ?: $website }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="iu-container flex flex-col items-center justify-between gap-4 py-6 text-center sm:flex-row sm:text-left">
            <p class="text-xs font-medium text-slate-500">
                &copy; {{ date('Y') }} {{ $associationName }}.
                {{ $settings['footer_copyright'] ?? __('ui.footer_copyright') }}
            </p>
            <a href="#top" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 transition hover:text-[#82E682]">
                {{ __('ui.footer_back_to_top') }}
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5m0 0-6 6m6-6 6 6" />
                </svg>
            </a>
        </div>
    </div>
</footer>
