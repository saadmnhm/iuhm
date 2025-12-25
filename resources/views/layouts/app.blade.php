<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    <div class="wizard-layout">
        <!-- Header with Logo -->
        <header class="wizard-header">
            <div class="wizard-logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="wizard-logo">
                <h1 style="margin-top: 1rem; font-size: 1.5rem; font-weight: 700; color: #1e293b;">Formulaire de Projet</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="wizard-content">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="wizard-footer">
            <div class="wizard-footer-content">
                <p>&copy; {{ date('Y') }} Tous droits réservés.</p>
                <div class="wizard-footer-links">
                    <a href="#">Politique de confidentialité</a>
                    <a href="#">Conditions d'utilisation</a>
                    <a href="#">Contact</a>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
    function autoResize(textarea) {
        textarea.style.height = 'auto'; 
        textarea.style.height = textarea.scrollHeight + 'px'; 
    }

    function initAllTextareas() {
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(autoResize);
    }

    // Auto-resize on input
    document.addEventListener('input', function (event) {
        if (event.target.tagName.toLowerCase() !== 'textarea') return;
        autoResize(event.target);
    }, false);

    // Initialize on page load
    window.addEventListener('load', initAllTextareas, false);

    // Reinitialize after Livewire updates the DOM
    document.addEventListener('livewire:navigated', initAllTextareas);
    document.addEventListener('livewire:update', initAllTextareas);
    
    // For Livewire v3 - hook into morphing lifecycle
    Livewire.hook('morph.updated', ({ el, component }) => {
        initAllTextareas();
    });

    // Alternative: Use MutationObserver to detect new textareas
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // Element node
                    // Check if the added node is a textarea
                    if (node.tagName && node.tagName.toLowerCase() === 'textarea') {
                        autoResize(node);
                    }
                    // Check if the added node contains textareas
                    const textareas = node.querySelectorAll ? node.querySelectorAll('textarea') : [];
                    textareas.forEach(autoResize);
                }
            });
        });
    });

    // Start observing the document body for added nodes
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
</script>
</body>
</html>