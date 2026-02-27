<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Association Initiative Urbaine | Admin Panel</title>
    <link rel="shortcut icon" href="{{ asset('assets/admin/image/favicon.png') }}" type="image/x-icon">
    <meta name="description" content="Association Initiative Urbaine | Casablanca">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        /* Global select styling */
        select {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem 2rem 0.5rem 0.75rem !important;
            background-color: #fff !important;
            font-size: 0.875rem !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.5rem center !important;
            background-size: 1.25rem !important;
            outline: none !important;
            transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
        }
        select:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('livewire.admin.aside')


    </div>




@livewireScripts
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('alert', (data) => {
                Swal.fire({
                    title: data.title || 'Success',
                    text: data.message,
                    icon: data.type || 'success',
                    confirmButtonColor: '#648454',
                });
            });

            Livewire.hook('request', ({ fail }) => {
                fail(({ status, preventDefault }) => {
                    if (status === 419) { // CSRF token mismatch / session expired
                        preventDefault();
                        Swal.fire({
                            title: 'Session Expirée',
                            text: 'Votre session a expiré. La page va être actualisée.',
                            icon: 'warning',
                            confirmButtonColor: '#648454',
                            confirmButtonText: 'Actualiser',
                            allowOutsideClick: false,
                            timer: 5000,
                            timerProgressBar: true,
                        }).then(() => window.location.reload());
                    }
                });
            });
        });
    </script>
</body>
</html>
