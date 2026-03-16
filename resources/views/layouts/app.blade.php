<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/admin/image/favicon.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/site/css/styles.css') }}?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
<div class="dashboard-container">
    <div class="dashboard-wrapper d-flex">
        <livewire:front.dashboard.aside />
        
        <div class="main-content">
            <livewire:front.dashboard.navbar :pageTitle="$pageTitle ?? 'Dashboard'" />            
           
            <div class="content-area">
                {{ $slot }}
            </div>
        </div>
    </div>
    <livewire:front.dashboard.broadcast-popup />
</div>


    @livewireScripts

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="{{ asset('assets/site/js/scripts.js') }}?v=<?= time() ?>"></script>
<script>
    // Handle Livewire token mismatch and keep active sessions alive
    document.addEventListener('livewire:init', () => {
        let handlingSessionExpired = false;
        const keepAliveUrl = `{{ route('user.keep-alive') }}`;

        const pingSession = () => {
            fetch(keepAliveUrl, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                cache: 'no-store',
            }).catch(() => {});
        };

        pingSession();
        setInterval(pingSession, 240000);

        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                pingSession();
            }
        });

        Livewire.hook('request', ({ fail }) => {
            fail(({ status, preventDefault }) => {
                if (status === 419) { // CSRF token mismatch / session expired
                    preventDefault();
                    if (handlingSessionExpired) {
                        return;
                    }
                    handlingSessionExpired = true;

                    const toast = document.createElement('div');
                    toast.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);background:#f57c00;color:#fff;padding:14px 28px;border-radius:10px;font-size:14px;font-weight:600;z-index:9999;box-shadow:0 4px 20px rgba(0,0,0,0.2);';
                    toast.textContent = 'Session expirée. Redirection vers la connexion…';
                    document.body.appendChild(toast);

                    const redirectTo = `{{ route('user.login') }}?expired=1&_t=${Date.now()}`;
                    setTimeout(() => window.location.replace(redirectTo), 1200);
                }
            });
        });
    });
</script>
</body>
</html>