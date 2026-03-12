@php
    $locale = app()->getLocale();
    $isAr   = $locale === 'ar';
    $dir    = $isAr ? 'rtl' : 'ltr';
    $t = [
        'title'     => $isAr ? 'تعيين كلمة مرور جديدة' : 'Nouveau mot de passe',
        'subtitle'  => $isAr ? 'أدخل كلمة المرور الجديدة لحسابك'
                             : 'Choisissez un nouveau mot de passe pour votre compte',
        'email'     => $isAr ? 'البريد الإلكتروني' : 'Adresse email',
        'password'  => $isAr ? 'كلمة المرور الجديدة' : 'Nouveau mot de passe',
        'confirm'   => $isAr ? 'تأكيد كلمة المرور' : 'Confirmer le mot de passe',
        'submit'    => $isAr ? 'تعيين كلمة المرور' : 'Réinitialiser le mot de passe',
        'back'      => $isAr ? 'العودة إلى تسجيل الدخول' : 'Retour à la connexion',
        'min_chars' => $isAr ? '٦ أحرف على الأقل' : '6 caractères minimum',
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $t['title'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap");
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
            box-shadow: 0 14px 28px rgba(0,0,0,.18), 0 10px 10px rgba(0,0,0,.12);
            max-width: 440px; width: 100%;
            padding: 2.5rem 2rem;
        }
        .icon-ring {
            width: 72px; height: 72px; background: var(--light);
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; font-size: 2rem; margin: 0 auto 1.25rem;
        }
        h1 { font-size: 1.5rem; font-weight: 800; text-align: center; margin-bottom: .5rem; }
        p.sub { font-size: 14px; color: #666; text-align: center; margin-bottom: 1.75rem; line-height: 1.6; }
        .field { margin-bottom: 1rem; }
        .field label { display: block; font-size: 12px; font-weight: 700; color: #555; margin-bottom: 4px; padding-{{ $isAr ? 'right' : 'left' }}: 8px; }
        input[type=email], input[type=password], input[type=text] {
            background: #eee; border: none; border-radius: 30px;
            padding: 13px 18px; width: 100%; font-family: inherit;
            font-size: 14px; outline: none; transition: background .2s;
        }
        input:focus { background: #e0e0e0; }
        .pw-wrap { position: relative; }
        .pw-wrap input { padding-{{ $isAr ? 'left' : 'right' }}: 46px; }
        .pw-toggle {
            position: absolute; {{ $isAr ? 'left' : 'right' }}: 14px; top: 50%;
            transform: translateY(-50%); background: none; border: none;
            cursor: pointer; color: #888; font-size: 18px; padding: 0;
        }
        .btn-main {
            width: 100%; border-radius: 30px; border: 2px solid var(--main);
            background: var(--main); color: #fff; font-size: 14px; font-weight: 700;
            padding: 13px; letter-spacing: .5px; cursor: pointer; transition: all .25s; margin-top: .5rem;
        }
        .btn-main:hover { background: var(--light); color: var(--main); }
        .back-link { display: block; text-align: center; margin-top: 1.25rem; font-size: 13px; color: var(--main); text-decoration: none; font-weight: 600; }
        .back-link:hover { text-decoration: underline; }
        .hint { font-size: 11px; color: #aaa; margin-top: 3px; padding-{{ $isAr ? 'right' : 'left' }}: 8px; }
        .lang-bar { position: fixed; top: 16px; right: 16px; display: flex; gap: 8px; z-index: 999; }
        .lang-btn { padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; text-decoration: none; border: 2px solid #ddd; color: #666; background: #fff; transition: all .2s; }
        .lang-btn.active { border-color: var(--main); background: var(--main); color: #fff; }
    </style>
</head>
<body>

<div class="lang-bar">
    <a href="{{ route('lang.switch', 'fr') }}" class="lang-btn {{ $locale === 'fr' ? 'active' : '' }}">🇫🇷 FR</a>
    <a href="{{ route('lang.switch', 'ar') }}" class="lang-btn {{ $locale === 'ar' ? 'active' : '' }}">🇲🇦 AR</a>
</div>

<div class="card">
    <div class="icon-ring">🔐</div>
    <h1>{{ $t['title'] }}</h1>
    <p class="sub">{{ $t['subtitle'] }}</p>

    @if($errors->any())
        <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;border-radius:10px;padding:12px 16px;font-size:13px;margin-bottom:1rem;">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('user.password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="field">
            <label>{{ $t['email'] }}</label>
            <input type="email" name="email" value="{{ old('email', $email ?? '') }}"
                   placeholder="{{ $t['email'] }}" required dir="ltr">
        </div>

        <div class="field">
            <label>{{ $t['password'] }}</label>
            <div class="pw-wrap">
                <input type="password" id="pw1" name="password" placeholder="{{ $t['password'] }}" required>
                <button type="button" class="pw-toggle" onclick="togglePw('pw1',this)">
                    <i class="ri-eye-line"></i>
                </button>
            </div>
            <p class="hint">{{ $t['min_chars'] }}</p>
        </div>

        <div class="field">
            <label>{{ $t['confirm'] }}</label>
            <div class="pw-wrap">
                <input type="password" id="pw2" name="password_confirmation" placeholder="{{ $t['confirm'] }}" required>
                <button type="button" class="pw-toggle" onclick="togglePw('pw2',this)">
                    <i class="ri-eye-line"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-main">{{ $t['submit'] }}</button>
    </form>

    <a href="{{ route('user.login') }}" class="back-link">
        {{ $isAr ? '→' : '←' }} {{ $t['back'] }}
    </a>
</div>

<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('ri-eye-line');
    icon.classList.toggle('ri-eye-off-line');
}
</script>
</body>
</html>
