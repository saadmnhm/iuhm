<header x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="iu-container">
        <div class="flex h-20 items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-lg font-semibold text-[#163260]">
                <svg class="size-5 text-[#214f95]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 11.5 12 4l8 7.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M7.5 10.5V20h9v-9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>{{ __('ui.brand_name') }}</span>
            </a>

            <nav class="hidden items-center gap-6 lg:flex">
                @if(count($navigation))
                    @foreach($navigation as $item)
                        @if($item['type'] === 'divider')
                            <div class="w-px h-6 bg-slate-200 mx-2"></div>
                        @elseif($item['type'] === 'dropdown' && $item['has_children'])
                            <div class="relative inline-block" x-data="{ dropdownOpen: false }">
                                <button
                                    @click="dropdownOpen = !dropdownOpen"
                                    @click.outside="dropdownOpen = false"
                                    class="flex items-center gap-1.5 text-sm font-semibold text-[#214f95] transition hover:text-[#122f58]"
                                    :aria-expanded="dropdownOpen.toString()"
                                >
                                    {{ $item['label'] }}
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <div
                                    x-show="dropdownOpen"
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:leave="transition ease-in duration-100"
                                    class="absolute left-0 top-full mt-2 min-w-[200px] bg-white rounded-xl shadow-lg border border-slate-200 py-2 z-50"
                                >
                                    @foreach($item['children'] as $child)
                                        <a
                                            href="{{ $child['url'] }}"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-[#214f95] transition-colors"
                                            @if($child['settings']['target_blank'] ?? false) target="_blank" rel="noopener noreferrer" @endif
                                        >
                                            {{ $child['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="relative inline-block">
                                <a
                                    href="{{ $item['url'] }}"
                                    class="text-sm font-semibold text-[#214f95] transition hover:text-[#122f58]"
                                    @if($item['settings']['target_blank'] ?? false) target="_blank" rel="noopener noreferrer" @endif
                                >
                                    {{ $item['label'] }}
                                    @if($item['icon'])
                                        <i class="{{ $item['icon'] }} ml-1 text-sm {{ $item['icon_color'] ?? 'text-[#214f95]' }}"></i>
                                    @endif
                                </a>
                                @if(request()->is($item['url']))
                                    <div class="absolute -bottom-1.5 left-0 h-0.5 w-full bg-[#10752d]"></div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                    {{-- Project Dropdown --}}
                    <div class="relative inline-block" x-data="{ projectDropdownOpen: false }">
                        <button
                            @click="projectDropdownOpen = !projectDropdownOpen"
                            @click.outside="projectDropdownOpen = false"
                            class="text-sm font-semibold text-[#214f95] transition hover:text-[#122f58] flex items-center gap-1.5"
                            :aria-expanded="projectDropdownOpen.toString()"
                        >
                            {{ __('ui.nav_projects') }}
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <div
                            x-show="projectDropdownOpen"
                            x-cloak
                            x-transition
                            class="absolute left-0 top-full mt-2 min-w-[200px] bg-white rounded-xl shadow-lg border border-slate-200 py-2 z-50"
                        >
                            @foreach($projects as $project)
                                <a href="{{ route('projects.show', $project->slug) }}" @click="projectDropdownOpen = false" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-[#214f95] transition-colors">
                                    {{ $project->getLocalized('title') }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- Fallback to hardcoded navigation if no dynamic nav exists --}}
                    <div class="relative inline-block">
                        <a href="{{ route('actualite.index') }}" class="text-sm font-semibold text-[#214f95] transition hover:text-[#122f58]">
                            {{ __('ui.nav_news') }}
                        </a>
                        @if(request()->routeIs('actualite.index'))
                            <div class="absolute -bottom-1.5 left-0 h-0.5 w-full bg-[#10752d]"></div>
                        @endif
                    </div>
                    <div class="relative inline-block">
                        <a href="{{ route('services.index') }}" class="text-sm font-semibold text-[#214f95] transition hover:text-[#122f58]">
                            {{ __('ui.nav_services') }}
                        </a>
                        @if(request()->routeIs('livewire.site.show') && request()->segment(2) === 'services')
                            <div class="absolute -bottom-1.5 left-0 h-0.5 w-full bg-[#10752d]"></div>
                        @endif
                    </div>
                    <div class="relative inline-block">
                        <a href="{{ route('articles.index') }}" class="text-sm font-semibold text-[#214f95]  transition hover:text-[#0a471b]">
                            {{ __('ui.nav_articles') ?? 'Articles' }}
                        </a>
                        @if(request()->routeIs('articles.index'))
                            <div class="absolute -bottom-1.5 left-0 h-0.5 w-full bg-[#10752d]"></div>
                        @endif
                    </div>
                    <div class="relative inline-block">
                        <a href="{{ route('resources.index') }}" class="text-sm font-semibold text-[#214f95] transition hover:text-[#122f58]">
                            Livrables
                        </a>
                        @if(request()->routeIs('resources.index'))
                            <div class="absolute -bottom-1.5 left-0 h-0.5 w-full bg-[#10752d]"></div>
                        @endif
                    </div>
                    <div class="relative inline-block">
                        <a href="{{ route('history.index') }}" class="text-sm font-semibold text-[#214f95] transition hover:text-[#122f58]">
                            {{ __('ui.history') }}
                        </a>
                        @if(request()->routeIs('history.index'))
                            <div class="absolute -bottom-1.5 left-0 h-0.5 w-full bg-[#10752d]"></div>
                        @endif
                    </div>
                    {{-- Project Dropdown --}}
                    <div class="relative inline-block" x-data="{ projectDropdownOpen: false }">
                        <button
                            @click="projectDropdownOpen = !projectDropdownOpen"
                            @click.outside="projectDropdownOpen = false"
                            class="text-sm font-semibold text-[#214f95] transition hover:text-[#122f58] flex items-center gap-1.5"
                            :aria-expanded="projectDropdownOpen.toString()"
                        >
                            {{ __('ui.nav_projects') }}
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <div
                            x-show="projectDropdownOpen"
                            x-cloak
                            x-transition
                            class="absolute left-0 top-full mt-2 min-w-[200px] bg-white rounded-xl shadow-lg border border-slate-200 py-2 z-50"
                        >
                            @foreach($projects as $project)
                                <a href="{{ route('projects.show', $project->slug) }}" @click="projectDropdownOpen = false" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-[#214f95] transition-colors">
                                    {{ $project->getLocalized('title') }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </nav>

            <div class="flex items-center gap-2 sm:gap-3">
                @include('livewire..site.partials.language-switcher')

                <a
                    href="https://zettat.iuhm.org/user/login" target="_blank" rel="noopener noreferrer"
                    class="hidden rounded-full bg-[#0f2454] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#0f2454]/30 transition hover:bg-[#0d1d46] sm:inline-flex"
                >
                    {{ __('ui.zettat') }}
                </a>

                <button
                    type="button"
                    class="inline-flex size-10 items-center justify-center rounded-xl border border-slate-200 text-slate-700 lg:hidden"
                    x-on:click="open = !open"
                    :aria-expanded="open.toString()"
                    aria-label="{{ __('ui.open_menu') }}"
                >
                    <svg x-show="!open" x-cloak class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    <svg x-show="open" x-cloak class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
        </div>

        <div
            x-show="open"
            x-cloak
            x-transition.opacity.duration.150ms
            class="border-t border-slate-200 py-4 lg:hidden"
        >
            <nav class="flex flex-col gap-1">
                @if(count($navigation))
                    @foreach($navigation as $item)
                        @if($item['type'] === 'divider')
                            <div class="w-full h-px bg-slate-200 my-2"></div>
                        @elseif($item['type'] === 'dropdown' && $item['has_children'])
                            <div class="relative" x-data="{ mobileDropdownOpen: false }">
                                <button
                                    @click="mobileDropdownOpen = !mobileDropdownOpen"
                                    class="flex items-center justify-between w-full px-3 py-2 text-sm font-semibold text-[#214f95] rounded-xl hover:bg-slate-100"
                                >
                                    {{ $item['label'] }}
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <div
                                    x-show="mobileDropdownOpen"
                                    x-transition
                                    class="pl-4 pt-2 space-y-1"
                                >
                                    @foreach($item['children'] as $child)
                                        <a
                                            href="{{ $child['url'] }}"
                                            class="block px-3 py-2 text-sm text-slate-600 hover:text-[#214f95] rounded-lg hover:bg-slate-100 transition-colors"
                                            @if($child['settings']['target_blank'] ?? false) target="_blank" rel="noopener noreferrer" @endif
                                        >
                                            {{ $child['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a
                                href="{{ $item['url'] }}"
                                class="rounded-xl px-3 py-2 text-sm font-semibold text-[#214f95] hover:bg-slate-100"
                                @if($item['settings']['target_blank'] ?? false) target="_blank" rel="noopener noreferrer" @endif
                            >
                                {{ $item['label'] }}
                            </a>
                        @endif
                    @endforeach
                    {{-- Project Dropdown --}}
                    <div class="relative inline-block" x-data="{ mobileProjectDropdownOpen: false }">
                        <button
                            @click="mobileProjectDropdownOpen = !mobileProjectDropdownOpen"
                            class="w-full flex items-center justify-between rounded-xl px-3 py-2 text-sm font-semibold text-[#214f95] hover:bg-slate-100"
                        >
                            {{ __('ui.nav_projects') }}
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <div
                            x-show="mobileProjectDropdownOpen"
                            x-cloak
                            x-transition
                            class="pl-4 pt-2 space-y-1"
                        >
                            @foreach($projects as $project)
                                <a href="{{ route('projects.show', $project->slug) }}" @click="mobileProjectDropdownOpen = false" class="block px-3 py-2 text-sm text-slate-600 hover:text-[#214f95] rounded-lg hover:bg-slate-100 transition-colors">
                                    {{ $project->getLocalized('title') }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- Fallback navigation --}}
                    <a href="{{ route('actualite.index') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-[#214f95] hover:bg-slate-100">
                        {{ __('ui.nav_news') }}
                    </a>
                    <a href="{{ route('services.index') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-[#214f95] hover:bg-slate-100">
                        {{ __('ui.nav_services') }}
                    </a>
                    <a href="{{ route('articles.index') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-[#214f95]  hover:bg-slate-100">
                        {{ __('ui.nav_articles') ?? 'Articles' }}
                    </a>
                    <a href="{{ route('resources.index') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-[#214f95] hover:bg-slate-100">
                        Livrables
                    </a>
                    <a href="{{ route('history.index') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-[#214f95] hover:bg-slate-100">
                        {{ __('ui.history') }}
                    </a>
                    {{-- Project Dropdown --}}
                    <div class="relative inline-block" x-data="{ mobileProjectDropdownOpen: false }">
                        <button
                            @click="mobileProjectDropdownOpen = !mobileProjectDropdownOpen"
                            class="w-full flex items-center justify-between rounded-xl px-3 py-2 text-sm font-semibold text-[#214f95] hover:bg-slate-100"
                        >
                            {{ __('ui.nav_projects') }}
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <div
                            x-show="mobileProjectDropdownOpen"
                            x-cloak
                            x-transition
                            class="pl-4 pt-2 space-y-1"
                        >
                            @foreach($projects as $project)
                                <a href="{{ route('projects.show', $project->slug) }}" @click="mobileProjectDropdownOpen = false" class="block px-3 py-2 text-sm text-slate-600 hover:text-[#214f95] rounded-lg hover:bg-slate-100 transition-colors">
                                    {{ $project->getLocalized('title') }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                <a href="{{ route('contact.index') }}" class="rounded-xl bg-[#0f2454] px-3 py-2 text-center text-sm font-semibold text-white">
                    {{ __('ui.contact_page_label') ?? __('ui.donate') }}
                </a>
            </nav>
        </div>
    </div>
</header>
