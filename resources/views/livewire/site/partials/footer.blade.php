@php
    // Static public footer: no database or backend dependency.
    $footerName = 'Initiative Urbaine';
    $footerAcronym = 'IU';
    $footerDescription = __('ui.footer_tagline');
    $footerCopyright = __('ui.footer_copyright');

    $footerEmail =  __('ui.home_contact_email');
    $footerPhone = __('ui.home_contact_phone');
    $footerWebsite = 'https://iuhm.org';
    $footerAddress = 'Hay Mohammadi, Casablanca';

    $footerQuickLinks = [
        ['label' => __('ui.nav_news'), 'url' => route('actualite.index')],
        ['label' => __('ui.nav_clc'), 'url' => route('clc.index')],
        ['label' => __('ui.nav_zettat'), 'url' => route('zettat.index')],
        ['label' => __('ui.nav_services'), 'url' => route('services.index')],
        ['label' => __('ui.nav_articles'), 'url' => route('articles.index')],
        ['label' => __('ui.nav_deliverables'), 'url' => route('resources.index')],
        ['label' => __('ui.history'), 'url' => route('history.index')],
        ['label' => __('ui.nav_projects'), 'url' => route('projects.index')],
    ];

    $footerPageLinks = [
        ['label' => __('ui.home'), 'url' => route('home')],
        ['label' => __('ui.contact_page_label'), 'url' => route('contact.index')],
    ];
@endphp

<footer class=" border-t border-white/10 bg-[#0B1528] text-slate-300">
    <div class="iu-container py-14 sm:py-16">
<div class="flex flex-col items-center justify-center gap-12 text-center md:flex-row md:items-start md:justify-center md:gap-20">

    <div class="flex max-w-sm flex-col items-center">
        <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-3">
            <img src="{{ asset('assets/logowhite.png') }}" alt="{{ __('ui.brand_name') }}" class="w-[210px] object-contain">
        </a>

        @if(filled($footerDescription))
            <p class="mt-5 text-sm leading-7 text-slate-400">{{ $footerDescription }}</p>
        @endif

        <div class="mt-6 flex items-center justify-center gap-3">
            <a href="https://wa.me/+212678624197" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"
               class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-800/80 text-slate-300 transition hover:bg-emerald-500 hover:text-white">
                <i class="fa-brands fa-whatsapp text-lg"></i>
            </a>

            <a href="https://www.instagram.com/initiative.urbaine" target="_blank" rel="noopener noreferrer" aria-label="Instagram"
               class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-800/80 text-slate-300 transition hover:bg-pink-600 hover:text-white">
                <i class="fa-brands fa-instagram text-lg"></i>
            </a>

            <a href="https://web.facebook.com/AIUHM" target="_blank" rel="noopener noreferrer" aria-label="Facebook"
               class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-800/80 text-slate-300 transition hover:bg-blue-600 hover:text-white">
                <i class="fa-brands fa-facebook-f text-lg"></i>
            </a>
        </div>
    </div>

    <div class="flex max-w-sm flex-col items-center">
        <h3 class="text-xs font-bold uppercase tracking-[0.16em] text-white">Horaires</h3>
        <p class="mt-5 text-sm leading-7 text-slate-400">Du Lundi au Vendredi</p>
        <p class="mt-5 text-sm leading-7 text-slate-400">De 8h30 à 18h30</p>
    </div>

    <div class="flex max-w-sm flex-col items-center">
        <h3 class="text-xs font-bold uppercase tracking-[0.16em] text-white">{{ __('ui.footer_contact') }}</h3>
        <ul class="mt-5 space-y-4 text-sm">
            <li class="flex items-start justify-center gap-3">
                <svg class="mt-0.5 size-4 shrink-0 text-[#82E682]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657 13.414 20.9a1.998 1.998 0 0 1-2.827 0l-4.243-4.243a8 8 0 1 1 11.314 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                <span class="text-slate-400">{{ $footerAddress }}</span>
            </li>
            <li class="flex items-start justify-center gap-3">
                <svg class="mt-0.5 size-4 shrink-0 text-[#82E682]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Z" />
                </svg>
                <a href="mailto:{{ $footerEmail }}" class="break-all text-slate-400 transition hover:text-[#82E682]">{{ $footerEmail }}</a>
            </li>
            <li class="flex items-start justify-center gap-3">
                <svg class="mt-0.5 size-4 shrink-0 text-[#82E682]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 0 1 2-2h2.28a1 1 0 0 1 .95.68l1.2 3.6a1 1 0 0 1-.5 1.2l-1.5.9a12 12 0 0 0 5.4 5.4l.9-1.5a1 1 0 0 1 1.2-.5l3.6 1.2a1 1 0 0 1 .68.95V19a2 2 0 0 1-2 2h-1C9.6 21 3 14.4 3 6V5Z" />
                </svg>
                <a href="tel:{{ preg_replace('/\s+/', '', $footerPhone) }}" class="text-slate-400 transition hover:text-[#82E682]">{{ __('ui.home_contact_phone') }}</a>
            </li>
            <li class="flex items-start justify-center gap-3">
                <svg class="mt-0.5 size-4 shrink-0 text-[#82E682]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <circle cx="12" cy="12" r="9" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18" />
                </svg>
                <a href="{{ $footerWebsite }}" target="_blank" rel="noopener noreferrer" class="break-all text-slate-400 transition hover:text-[#82E682]">
                    {{ parse_url($footerWebsite, PHP_URL_HOST) ?: $footerWebsite }}
                </a>
            </li>
        </ul>
    </div>

</div>
    </div>

    <div class="border-t border-white/10">
        <div class="iu-container flex flex-col items-center justify-between gap-4 py-6 text-center sm:flex-row sm:text-left">
            <p class="text-xs font-medium text-slate-500">
                &copy; {{ date('Y') }} {{ $footerName }}. {{ $footerCopyright }}
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
