@php
    $sectionMenuItems = function (string $section, int $limit = 6): array {
        try {
            if (! \Illuminate\Support\Facades\Schema::hasTable('section_items')) {
                return [];
            }

            return \App\Models\SectionItem::query()
                ->section($section)
                ->published()
                ->latest('published_at')
                ->limit($limit)
                ->get()
                ->map(fn (\App\Models\SectionItem $item) => [
                    'label' => $item->getLocalized('title'),
                    'url' => route($section.'.show', $item->slug),
                ])
                ->all();
        } catch (\Throwable $e) {
            return [];
        }
    };

    $navItems = [
        ['label' => __('ui.nav_news'), 'url' => route('actualite.index'), 'active' => request()->routeIs('actualite.*')],
        [
            'label' => __('ui.nav_clc'),
            'active' => request()->routeIs('clc.*'),
            'menu' => [
                'all_url' => route('clc.index'),
                'items' => $sectionMenuItems(\App\Models\SectionItem::SECTION_CLC),
            ],
        ],
        [
            'label' => __('ui.nav_zettat'),
            'active' => request()->routeIs('zettat.*'),
            'menu' => [
                'all_url' => route('zettat.index'),
                'items' => $sectionMenuItems(\App\Models\SectionItem::SECTION_ZETTAT),
            ],
        ],
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
        <div class="flex h-20 items-center justify-between gap-3">
            <a href="{{ route('home') }}" class="inline-flex shrink-0 items-center gap-2 text-lg font-semibold text-[#163260]">
                <img src="{{ asset('assets/iuhm_pysage.png') }}" alt="{{ __('ui.brand_name') }}" class="h-10 w-auto max-w-[8rem] object-contain">
            </a>

            <nav class="hidden items-center gap-3.5 lg:flex xl:gap-6" aria-label="{{ __('ui.navigation') }}">
                @foreach($navItems as $item)
                    @if(! empty($item['menu']))
                        <div class="relative"
                             x-data="{ open: false }"
                             x-on:mouseenter="open = true"
                             x-on:mouseleave="open = false"
                             x-on:click.outside="open = false"
                             x-on:keydown.escape.window="open = false">
                            <button type="button"
                                    x-on:click="open = !open"
                                    x-on:focus="open = true"
                                    x-bind:aria-expanded="open.toString()"
                                    aria-haspopup="true"
                                    class="relative inline-flex items-center gap-1 whitespace-nowrap text-[0.78rem] font-semibold transition hover:text-[#122f58] xl:text-[0.8rem] {{ $item['active'] ? 'text-[#10752d]' : 'text-[#214f95]' }}">
                                {{ $item['label'] }}
                                <svg class="size-3.5 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                </svg>
                                @if($item['active'])
                                    <span class="absolute -bottom-1.5 left-0 h-0.5 w-full bg-[#10752d]"></span>
                                @endif
                            </button>

                            <div x-show="open"
                                 x-cloak
                                 x-transition.opacity.duration.150ms
                                 class="absolute left-0 top-full z-50 w-56 pt-2">
                                <div class="rounded-xl border border-slate-200 bg-white py-1.5 shadow-lg shadow-slate-900/10">
                                    @forelse($item['menu']['items'] as $child)
                                        <a href="{{ $child['url'] }}"
                                           class="block truncate px-4 py-2 text-[0.8rem] font-semibold text-[#214f95] transition hover:bg-slate-50 hover:text-[#10752d]">
                                            {{ $child['label'] }}
                                        </a>
                                    @empty
                                        <span class="block px-4 py-2 text-xs text-slate-400">{{ __('ui.section_empty_title') }}</span>
                                    @endforelse

                                    <div class="my-1 border-t border-slate-100"></div>

                                    <a href="{{ $item['menu']['all_url'] }}"
                                       class="block px-4 py-2 text-[0.78rem] font-bold text-[#10752d] transition hover:bg-slate-50">
                                        {{ __('ui.view_all') }} {{ $item['label'] }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ $item['url'] }}"
                           class="relative whitespace-nowrap text-[0.78rem] xl:text-[0.8rem] font-semibold transition hover:text-[#122f58] {{ $item['active'] ? 'text-[#10752d]' : 'text-[#214f95]' }}">
                            {{ $item['label'] }}
                            @if($item['active'])
                                <span class="absolute -bottom-1.5 left-0 h-0.5 w-full bg-[#10752d]"></span>
                            @endif
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                @include('livewire.site.partials.language-switcher')

                <button type="button"
                        class="inline-flex size-10 items-center justify-center rounded-xl border border-slate-200 text-slate-700 lg:hidden"
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
             class="border-t border-slate-200 py-4 lg:hidden">
            <nav class="flex flex-col gap-1" aria-label="{{ __('ui.navigation') }}">
                @foreach($navItems as $item)
                    @if(! empty($item['menu']))
                        <div x-data="{ sub: false }">
                            <button type="button"
                                    x-on:click="sub = !sub"
                                    x-bind:aria-expanded="sub.toString()"
                                    class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-sm font-semibold transition {{ $item['active'] ? 'bg-[#DCFCE7] text-[#0a471b]' : 'text-[#214f95] hover:bg-slate-100' }}">
                                {{ $item['label'] }}
                                <svg class="size-4 transition-transform duration-200" x-bind:class="sub ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="sub" x-cloak class="mt-1 space-y-1 pl-3">
                                @foreach($item['menu']['items'] as $child)
                                    <a href="{{ $child['url'] }}"
                                       class="block rounded-lg px-3 py-2 text-[0.82rem] font-semibold text-[#214f95] transition hover:bg-slate-100">
                                        {{ $child['label'] }}
                                    </a>
                                @endforeach
                                <a href="{{ $item['menu']['all_url'] }}"
                                   class="block rounded-lg px-3 py-2 text-[0.78rem] font-bold text-[#10752d] transition hover:bg-slate-100">
                                    {{ __('ui.view_all') }} {{ $item['label'] }}
                                </a>
                            </div>
                        </div>
                    @else
                        <a href="{{ $item['url'] }}"
                           class="rounded-xl px-3 py-2 text-sm font-semibold transition {{ $item['active'] ? 'bg-[#DCFCE7] text-[#0a471b]' : 'text-[#214f95] hover:bg-slate-100' }}">
                            {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>
    </div>
</header>