@php
    $currentLocale = app()->getLocale();
    $languageLabels = [
        'ar' => __('ui.arabic'),
        'fr' => __('ui.french'),
        'en' => __('ui.english'),
    ];
@endphp

<div x-data="{ open: false }" class="relative">
    <button
        type="button"
        class="inline-flex size-10 items-center justify-center rounded-full border border-slate-200 bg-white text-[#214f95] shadow-sm transition hover:bg-slate-50"
        x-on:click="open = !open"
        aria-label="{{ __('ui.change_language') }}"
    >
        <svg class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 3c4.97 0 9 4.03 9 9s-4.03 9-9 9-9-4.03-9-9 4.03-9 9-9Z" stroke="currentColor" stroke-width="1.6" />
            <path d="M3 12h18M12 3a14.4 14.4 0 0 1 0 18M12 3a14.4 14.4 0 0 0 0 18" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" />
        </svg>
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition.opacity.duration.120ms
        x-on:click.outside="open = false"
        class="absolute end-0 z-30 mt-2 w-44 overflow-hidden rounded-2xl border border-slate-200 bg-white p-1 shadow-xl"
    >
        @foreach (config('app.supported_locales', ['en']) as $locale)
            <form method="POST" action="{{ route('locale.update', $locale) }}">
                @csrf

                <button
                    type="submit"
                    @class([
                        'flex w-full items-center justify-between rounded-xl px-3 py-2 text-start text-sm text-slate-700 transition hover:bg-slate-100',
                        'bg-slate-100 font-semibold' => $currentLocale === $locale,
                    ])
                >
                    <span>{{ $languageLabels[$locale] ?? strtoupper($locale) }}</span>
                    <span class="text-xs uppercase text-slate-500">{{ $locale }}</span>
                </button>
            </form>
        @endforeach
    </div>
</div>
