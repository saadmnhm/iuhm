<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Expirée - IUHM</title>
    <link rel="shortcut icon" href="{{ asset('assets/admin/image/favicon.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #e8f5e9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            padding: 60px 50px;
            max-width: 520px;
            width: 100%;
            text-align: center;
        }

        .logo-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 16px;
            margin-bottom: 36px;
        }

        .logo-wrapper img {
            height: 50px;
            object-fit: contain;
        }

        .icon-circle {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
        }

        .icon-circle svg {
            width: 48px;
            height: 48px;
            color: #f57c00;
        }

        .error-code {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #f57c00;
            margin-bottom: 12px;
        }

        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 14px;
            line-height: 1.3;
        }

        p {
            font-size: 15px;
            color: #6b7280;
            line-height: 1.7;
            margin-bottom: 36px;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2f5496 0%, #1e3a6e 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(47, 84, 150, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(47, 84, 150, 0.4);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }

        .divider {
            border: none;
            border-top: 1px solid #f3f4f6;
            margin: 32px 0 20px;
        }

        .footer-text {
            font-size: 12px;
            color: #9ca3af;
            margin: 0;
        }

        @media (max-width: 480px) {
            .container { padding: 40px 24px; }
            h1 { font-size: 22px; }
            .btn-group { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
        }

        /* Auto-reload countdown */
        .countdown-bar {
            height: 3px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 20px;
        }

        .countdown-fill {
            height: 100%;
            background: linear-gradient(90deg, #2f5496, #4a7bd4);
            border-radius: 3px;
            width: 100%;
            animation: shrink 10s linear forwards;
        }

        .countdown-text {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 8px;
        }

        @keyframes shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logos -->
        <div class="logo-wrapper">
            <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="IUHM Logo"
                 onerror="this.style.display='none'">
            <img src="{{ asset('assets/admin/image/logo.png') }}" alt="IUHM Logo"
                 onerror="this.style.display='none'">
        </div>

        <!-- Icon -->
        <div class="icon-circle">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <div class="error-code">Erreur 419</div>
        <h1>Page Expirée</h1>
        <p>
            Votre session a expiré ou le jeton de sécurité n'est plus valide.<br>
            Veuillez actualiser la page et réessayer.
        </p>

        <div class="btn-group">
            <button onclick="window.location.reload()" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Actualiser la page
            </button>
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>

        <!-- Auto-reload bar -->
        <div class="countdown-bar">
            <div class="countdown-fill" id="progressBar"></div>
        </div>
        <p class="countdown-text" id="countdownText">Actualisation automatique dans <strong id="countdownNum">10</strong>s…</p>

        <hr class="divider">
        <p class="footer-text">&copy; {{ date('Y') }} Initiative Urbaine Hay Mohammadi — Tous droits réservés</p>
    </div>

    <script>
        let seconds = 10;
        const countdownNum = document.getElementById('countdownNum');

        const timer = setInterval(() => {
            seconds--;
            countdownNum.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(timer);
                window.location.reload();
            }
        }, 1000);
    </script>
</body>
</html>
