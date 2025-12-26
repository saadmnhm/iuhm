<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>

<div
    x-data="{
        open: !localStorage.getItem('termsAccepted'),
        agreed: false
    }"
    x-show="open"
    x-cloak
    class="overlay"
>
    <div class="modal-card">
        <h2>Terms & Conditions</h2>

        <label class="checkbox">
            <input type="checkbox" x-model="agreed">
            I agree to the terms
        </label>

        <br>
        <button
            :disabled="!agreed"
            @click="localStorage.setItem('termsAccepted','true'); open=false"
        >
            Accept
        </button>
    </div>
</div>



    <div class="wizard-layout">
        <!-- Header with Logo -->
        <header class="wizard-header">
            <div class="wizard-logo-container">
                <img src="{{ asset('assets/images/iuhm_logo.png') }}" alt="Logo" class="wizard-logo">
            </div>
        </header>

        <!-- Main Content -->
        <main class="wizard-content">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="wizard-footer">
            <div class="wizard-footer-content">
                <div class="wizard-logo-footer">
                    <img src="{{ asset('assets/images/iuhm_logo.png') }}" alt="iuhm-logo-footer">
                    <img src="{{ asset('assets/images/indh_logo.png') }}" alt="indh-logo-footer" >
                    <img src="{{ asset('assets/images/logo_zettat.png') }}" alt="zettat-logo-footer" >
                </div>
                <p class="text-center mt-5">&copy; {{ date('Y') }} Tous droits réservés par <a href="www.iuhm.org" style='color:#2f5496'>initiative urbaine hay mohammadi</a></p>
            </div>
        </footer>
    </div>

    @livewireScripts

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>
</body>
</html>