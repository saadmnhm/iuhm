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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;800&display=swap");

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
            border-radius: 10px;
            box-shadow: 0 14px 28px rgba(0,0,0,.25), 0 10px 10px rgba(0,0,0,.22);
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
            margin: 0 auto 1.25rem;
            font-size: 2rem;
            color: var(--main-color);
        }

        h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: .5rem; }

        p { font-size: 14px; color: #555; line-height: 1.6; margin-bottom: 1.5rem; }

        .btn-verify {
            border-radius: 20px;
            border: 1px solid var(--main-color);
            background: var(--main-color);
            color: #fff;
            font-size: 12px;
            font-weight: bold;
            padding: 12px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all .3s ease;
            cursor: pointer;
            width: 100%;
        }

        .btn-verify:hover { opacity: .88; }

        .alert {
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 1rem;
        }

        .logout-link {
            display: block;
            margin-top: 1.25rem;
            font-size: 13px;
            color: #888;
            text-decoration: none;
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
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('user.verification.send') }}">
            @csrf
            <button type="submit" class="btn-verify">{{ $t['resend'] }}</button>
        </form>

        <form method="POST" action="{{ route('user.logout') }}">
            @csrf
            <button type="submit" class="logout-link" style="background:none;border:none;cursor:pointer;">
                {{ $t['logout'] }}
            </button>
        </form>
    </div>
</body>
</html>
