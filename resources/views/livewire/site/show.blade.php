@extends('layouts.site')

@section('title', $page->getLocalized('title').' | '.config('app.name'))

@section('content')
    <section class="mx-auto max-w-7xl px-4 pb-8 pt-14 sm:px-6 lg:px-8 lg:pt-20">
        <div class="max-w-3xl">
            <h1 class="text-4xl font-bold tracking-tight text-slate-900 md:text-5xl">{{ $page->getLocalized('title') }}</h1>
            <p class="mt-4 text-lg text-slate-600">{{ $page->getLocalized('excerpt') }}</p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-5 md:grid-cols-2">
            @forelse ($page->sections as $section)
                <x-ui.card class="h-full">
                    <h2 class="text-xl font-semibold text-slate-900">{{ $section->getLocalized('heading') }}</h2>
                    <p class="mt-3 text-slate-600">{{ $section->getLocalized('body') }}</p>

                    @if ($section->cta_url && $section->getLocalized('cta_label'))
                        <x-ui.button class="mt-4" variant="ghost" size="sm" :href="$section->cta_url">
                            {{ $section->getLocalized('cta_label') }}
                        </x-ui.button>
                    @endif
                </x-ui.card>
            @empty
                <x-ui.card class="md:col-span-2">
                    <p class="text-slate-600">{{ __('ui.no_sections_yet') }}</p>
                </x-ui.card>
            @endforelse
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between gap-3">
            <h2 class="section-title">{{ __('ui.related_news') }}</h2>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            @forelse ($posts as $post)
                <x-ui.card>
                    <h3 class="text-lg font-semibold text-slate-900">{{ $post->getLocalized('title') }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $post->getLocalized('excerpt') }}</p>

                    <x-ui.button variant="ghost" size="sm" class="mt-4" :href="route('actualite.show', $post)">
                        {{ __('ui.read_more') }}
                    </x-ui.button>
                </x-ui.card>
            @empty
                <x-ui.card class="md:col-span-3">
                    <p class="text-slate-600">{{ __('ui.no_posts_yet') }}</p>
                </x-ui.card>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </section>
@endsection
