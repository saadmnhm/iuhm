<!DOCTYPE html>
@php
    $locale = app()->getLocale();
    $isAr = str_starts_with($locale, 'ar');
    $t = [
        'title' => $isAr ? 'تأكيد البريد الإلكتروني - لوحة التحكم' : 'Vérification de l\'email - Tableau de bord',
        'heading' => $isAr ? 'تحقق من بريدك الإلكتروني' : 'Vérifiez votre adresse email',
        'desc' => $isAr
            ? 'شكرًا على التسجيل! قبل البدء، يرجى التحقق من عنوان بريدك الإلكتروني بالنقر على الرابط الذي أرسلناه للتو إلى'
            : 'Merci pour votre inscription ! Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons d\'envoyer à',
        'resend' => $isAr ? 'إعادة إرسال رابط التحقق' : 'Renvoyer l\'email de vérification',
        'logout' => $isAr ? 'تسجيل الخروج' : 'Déconnexion',
    ];
@endphp
<html lang="{{ $locale }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $t['title'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --main-color: #648454;
            --secondary-color: #edffe4;
        }

        * { box-sizing: border-box; }

        body {
            background: #f6f5f7;
            font-family: "Nunito", sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
        }

        .card {
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,.1), 0 10px 20px rgba(0,0,0,.06);
            max-width: 480px;
            width: 100%;
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .icon-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--secondary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: var(--main-color);
        }

        h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: .5rem; color: #1e293b; }

        p { font-size: 14px; color: #555; line-height: 1.6; margin-bottom: 1.5rem; }

        .btn-verify {
            border-radius: 9999px;
            border: 2px solid var(--main-color);
            background: var(--main-color);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            padding: 14px 45px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all .25s ease;
            cursor: pointer;
            width: 100%;
        }

        .btn-verify:hover { background: var(--secondary-color); color: var(--main-color); border-color: var(--main-color); }
        .btn-verify:focus { outline: none; }
        .btn-verify:focus-visible { outline: 2px solid var(--main-color); outline-offset: 2px; }

        .alert {
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 1rem;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success { background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-danger { background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

        .logout-link {
            display: block;
            margin-top: 1.25rem;
            font-size: 13px;
            color: #888;
            text-decoration: none;
            font-weight: 500;
        }

        .logout-link:hover { color: var(--main-color); }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-circle">
            <i class="ri-mail-check-line"></i>
        </div>

        <h1>{{ $t['heading'] }}</h1>
        <p>
            {{ $t['desc'] }} <strong>{{ Auth::guard('candidat')->user()->email }}</strong>.
        </p>

        @if (session('message'))
            <div class="alert alert-success" role="alert">
                <i class="ri-checkbox-circle-line"></i> {{ session('message') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <i class="ri-error-warning-line"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('user.verification.send') }}">
            @csrf
            <button type="submit" class="btn-verify">{{ $t['resend'] }}</button>
        </form>

        <form method="POST" action="{{ route('user.logout') }}">
            @csrf
            <button type="submit" class="logout-link" style="background:none;border:none;cursor:pointer;padding:0;">
                {{ $t['logout'] }}
            </button>
        </form>
    </div>
</body>
</html>