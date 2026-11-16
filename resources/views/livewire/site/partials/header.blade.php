@php
    // Static public navigation: no database or backend dependency.
    $navLinks = [
        ['label' => __('ui.nav_news'), 'url' => route('actualite.index'), 'active' => request()->routeIs('actualite.*')],
        ['label' => __('ui.nav_clc'), 'url' => route('clc.index'), 'active' => request()->routeIs('clc.*')],
        ['label' => __('ui.nav_zettat'), 'url' => route('zettat.index'), 'active' => request()->routeIs('zettat.*')],
        ['label' => __('ui.nav_services'), 'url' => route('services.index'), 'active' => request()->routeIs('services.*')],
        ['label' => __('ui.nav_articles'), 'url' => route('articles.index'), 'active' => request()->routeIs('articles.*')],
        ['label' => __('ui.nav_deliverables'), 'url' => route('resources.index'), 'active' => request()->routeIs('resources.*')],
        ['label' => __('ui.history'), 'url' => route('history.index'), 'active' => request()->routeIs('history.*')],
        ['label' => __('ui.nav_projects'), 'url' => route('projects.index'), 'active' => request()->routeIs('projects.*')],
        ['label' => __('ui.contact_page_label'), 'url' => route('contact.index'), 'active' => request()->routeIs('contact.*')],
    ];
@endphp

<header x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="iu-container">
        <div class="flex h-20 items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="inline-flex w-[180px] items-center gap-2 text-lg font-semibold text-[#163260]">
                <svg class="size-5 shrink-0 text-[#214f95]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 11.5 12 4l8 7.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M7.5 10.5V20h9v-9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <img src="{{ asset('assets/iuhm_pysage.png') }}" alt="{{ __('ui.brand_name') }}" class="h-10 w-auto max-w-[8rem] object-contain">
            </a>

            <nav class="hidden items-center gap-5 xl:flex xl:gap-6" aria-label="{{ __('ui.navigation') }}">
                @foreach($navLinks as $link)
                    <a href="{{ $link['url'] }}"
                       class="relative text-[0.8rem] font-semibold transition hover:text-[#122f58] {{ $link['active'] ? 'text-[#10752d]' : 'text-[#214f95]' }}">
                        {{ $link['label'] }}
                        @if($link['active'])
                            <span class="absolute -bottom-1.5 left-0 h-0.5 w-full bg-[#10752d]"></span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2 sm:gap-3">
                @include('livewire.site.partials.language-switcher')

                <a href="https://zettat.iuhm.org/user/login" target="_blank" rel="noopener noreferrer"
                   class="hidden rounded-full bg-[#0f2454] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#0f2454]/30 transition hover:bg-[#0d1d46] sm:inline-flex">
                    {{ __('ui.zettat') }}
                </a>

                <button type="button"
                        class="inline-flex size-10 items-center justify-center rounded-xl border border-slate-200 text-slate-700 xl:hidden"
                        x-on:click="open = !open"
                        :aria-expanded="open.toString()"
                        aria-label="{{ __('ui.open_menu') }}">
                    <svg x-show="!open" x-cloak class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    <svg x-show="open" x-cloak class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="open"
             x-cloak
             x-transition.opacity.duration.150ms
             class="border-t border-slate-200 py-4 xl:hidden">
            <nav class="flex flex-col gap-1" aria-label="{{ __('ui.navigation') }}">
                @foreach($navLinks as $link)
                    <a href="{{ $link['url'] }}"
                       class="rounded-xl px-3 py-2 text-sm font-semibold transition {{ $link['active'] ? 'bg-[#DCFCE7] text-[#0a471b]' : 'text-[#214f95] hover:bg-slate-100' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach

                <a href="https://zettat.iuhm.org/user/login" target="_blank" rel="noopener noreferrer"
                   class="mt-2 rounded-xl bg-[#0f2454] px-3 py-2 text-center text-sm font-semibold text-white">
                    {{ __('ui.zettat') }}
                </a>
            </nav>
        </div>
    </div>
</header>
