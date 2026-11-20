<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Harness</title>
    @livewireStyles
    @stack('head-scripts')
</head>
<body style="font-family: sans-serif; padding: 24px;">
    {{ $slot }}
    @livewireScripts
</body>
</html>
