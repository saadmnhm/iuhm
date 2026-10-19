@extends('layouts.site')

@section('title', $page?->getLocalized('title') ?? __('ui.history_page_title') .' | '.config('app.name'))

@section('content')
    <section class="bg-white py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-16 text-center">
                @if($page?->hero_image)
                <div class="mx-auto max-w-4xl mb-10 rounded-[2.5rem] overflow-hidden shadow-xl shadow-slate-200 border border-slate-100">
                    <img src="{{ \Illuminate\Support\Str::startsWith($page->hero_image, ['http','https']) ? $page->hero_image : asset($page->hero_image) }}"
                         alt="{{ $page->getLocalized('title') }}"
                         class="w-full h-auto block">
                </div>
                @endif
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#2d4b86]">{{ $page?->getLocalized('excerpt') ?? __('ui.history_page_label') }}</p>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-[#16254d] sm:text-4xl">{{ $page?->getLocalized('title') ?? __('ui.history_page_title') }}</h2>
            </div>

            {{-- Content --}}
            <div class="prose prose-lg prose-slate max-w-3xl mx-auto text-slate-600 leading-relaxed">
                {!! $page?->getLocalized('content') ?? __('ui.home_history_description') !!}
            </div>

        </div>
    </section>
@endsection
