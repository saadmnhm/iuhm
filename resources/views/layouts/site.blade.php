<!DOCTYPE html>
@php
    $locale = app()->getLocale();
    $isRtl = in_array($locale, config('app.rtl_locales', []), true);
@endphp
<html lang="{{ $locale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', config('app.name'))</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @unless (app()->runningUnitTests())
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endunless
        @livewireStyles
    </head>
    <body class="site-shell">
        <div class="relative min-h-screen">
            @include('livewire.site.partials.header')

            <main class="pb-16">

                @yield('content')
            </main>

            @include('.livewire.site.partials.footer')
        </div>

        @stack('modals')
        @stack('scripts')
        @livewireScripts
    </body>
</html>