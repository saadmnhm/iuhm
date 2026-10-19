@php
    $locale = app()->getLocale();
    $isAr   = $locale === 'ar';
    $dir    = $isAr ? 'rtl' : 'ltr';
    $t = [
        'title'        => $isAr ? 'نسيت كلمة المرور' : 'Mot de passe oublié',
        'subtitle'     => $isAr ? 'أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة التعيين'
                                : 'Entrez votre email et nous vous enverrons un lien de réinitialisation',
        'email'        => $isAr ? 'البريد الإلكتروني' : 'Adresse email',
        'send'         => $isAr ? 'إرسال رابط إعادة التعيين' : 'Envoyer le lien de réinitialisation',
        'back'         => $isAr ? 'العودة إلى تسجيل الدخول' : 'Retour à la connexion',
        'sent'         => $isAr ? 'تم إرسال رابط إعادة التعيين على بريدك الإلكتروني إن كان موجوداً.'
                                : 'Si un compte existe avec cet email, un lien de réinitialisation a été envoyé.',
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $t['title'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root { --main: #648454; --light: #edffe4; }
        * { box-sizing: border-box; }
        body {
            background: #f6f5f7;
            font-family: "Nunito", sans-serif;
            min-height: 100vh;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 1rem; margin: 0;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,.1), 0 10px 20px rgba(0,0,0,.06);
            max-width: 440px; width: 100%;
            padding: 2.5rem 2rem;
        }
        .icon-ring {
            width: 72px; height: 72px;
            background: var(--light);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin: 0 auto 1.5rem;
        }
        h1 { font-size: 1.5rem; font-weight: 800; text-align: center; margin-bottom: .5rem; }
        p.sub { font-size: 14px; color: #666; text-align: center; margin-bottom: 1.75rem; line-height: 1.6; }
        
        .input-field {
            background: #f3f4f6;
            border: 1px solid transparent;
            border-radius: 9999px;
            padding: 14px 18px;
            width: 100%;
            font-family: inherit;
            font-size: 14px;
            outline: none;
            margin-bottom: 1rem;
            transition: all .2s;
        }
        .input-field:focus { background: white; border-color: var(--main); box-shadow: 0 0 0 3px rgba(100, 132, 84, 0.1); }

        .btn-main {
            width: 100%;
            border-radius: 9999px;
            border: 2px solid var(--main);
            background: var(--main);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            padding: 14px;
            letter-spacing: .5px;
            cursor: pointer;
            transition: all .25s;
            text-transform: uppercase;
        }
        .btn-main:hover { background: var(--light); color: var(--main); }
        .btn-main:focus { outline: none; }
        .btn-main:focus-visible { outline: 2px solid var(--main); outline-offset: 2px; }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.25rem;
            font-size: 13px;
            color: var(--main);
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover { text-decoration: underline; }

        .lang-bar { position: fixed; top: 16px; right: 16px; display: flex; gap: 8px; z-index: 999; }
        [dir="rtl"] .lang-bar { right: auto; left: 16px; }
        .lang-btn {
            padding: 5px 14px; border-radius: 9999px; font-size: 13px; font-weight: 700;
            text-decoration: none; border: 2px solid #ddd; color: #666; background: #fff;
            transition: all .2s;
        }
        .lang-btn.active { border-color: var(--main); background: var(--main); color: #fff; }

        .alert {
            padding: 12px 16px;
            margin-bottom: 1rem;
            border-radius: 8px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-success { background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-danger { background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    </style>
</head>
<body>

{{-- Language Switcher --}}
<div class="lang-bar">
    <a href="{{ route('lang.switch', 'fr') }}" class="lang-btn {{ $locale === 'fr' ? 'active' : '' }}">🇫🇷 FR</a>
    <a href="{{ route('lang.switch', 'ar') }}" class="lang-btn {{ $locale === 'ar' ? 'active' : '' }}">🇲🇦 AR</a>
</div>

<div class="card">
    <div class="icon-ring">
        <i class="ri-lock-unlock-line text-green-600"></i>
    </div>
    <h1>{{ $t['title'] }}</h1>
    <p class="sub">{{ $t['subtitle'] }}</p>

    @if(session('status'))
        <div class="alert alert-success" role="alert">
            <i class="ri-checkbox-circle-line"></i> {{ $t['sent'] }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <i class="ri-error-warning-line"></i>
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('user.password.email') }}">
        @csrf
        <input type="email" name="email" placeholder="{{ $t['email'] }}"
               value="{{ old('email') }}" required autofocus
               dir="{{ $isAr ? 'rtl' : 'ltr' }}" class="input-field">
        <button type="submit" class="btn-main">{{ $t['send'] }}</button>
    </form>

    <a href="{{ route('user.login') }}" class="back-link">
        {{ $isAr ? '→' : '←' }} {{ $t['back'] }}
    </a>
</div>

</body>
</html>