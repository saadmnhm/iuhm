<!DOCTYPE html>
@php
    $locale = app()->getLocale();
    $isAr   = $locale === 'ar';
    $dir    = $isAr ? 'rtl' : 'ltr';
    $t = [
        'page_title'    => $isAr ? 'تسجيل الدخول' : 'Connexion — IUHM',
        'signin_title'  => $isAr ? 'تسجيل الدخول' : 'Connexion',
        'or_account'    => $isAr ? 'أو استخدم حسابك' : 'ou utilisez votre compte',
        'login_ph'      => $isAr ? 'البريد الإلكتروني أو اسم المستخدم' : 'Email ou Login',
        'password_ph'   => $isAr ? 'كلمة المرور' : 'Mot de passe',
        'forgot'        => $isAr ? 'نسيت كلمة المرور؟' : 'Mot de passe oublié ?',
        'signin_btn'    => $isAr ? 'دخول' : 'Connexion',
        'signup_title'  => $isAr ? 'إنشاء حساب' : 'Créer un compte',
        'or_email'      => $isAr ? 'أو استخدم بريدك الإلكتروني للتسجيل' : 'ou utilisez votre email pour vous inscrire',
        'nom_ph'        => $isAr ? 'النسب' : 'Nom',
        'prenom_ph'     => $isAr ? 'الاسم' : 'Prénom',
        'login_field_ph'=> $isAr ? 'اسم المستخدم' : 'Login',
        'email_ph'      => $isAr ? 'البريد الإلكتروني' : 'Email',
        'signup_btn'    => $isAr ? 'إنشاء حساب' : 'Inscription',
        'welcome_back'  => $isAr ? 'أهلاً بعودتك!' : 'Bon retour !',
        'welcome_desc'  => $isAr ? 'يرجى تسجيل الدخول بمعلوماتك الشخصية' : 'Connectez-vous avec vos informations personnelles',
        'overlay_signin'=> $isAr ? 'تسجيل الدخول' : 'Connexion',
        'hello_title'   => $isAr ? 'مرحباً!' : 'Bonjour !',
        'hello_desc'    => $isAr ? 'أدخل بياناتك الشخصية وابدأ رحلتك معنا' : 'Entrez vos informations et commencez votre aventure',
        'overlay_signup'=> $isAr ? 'إنشاء حساب' : 'Inscription',
        'mobile_have'   => $isAr ? 'لديك حساب بالفعل؟ تسجيل الدخول' : 'Vous avez un compte ? Se connecter',
        'mobile_no'     => $isAr ? 'ليس لديك حساب؟ إنشاء حساب' : 'Pas de compte ? S\'inscrire',
    ];
@endphp
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $t['page_title'] }}</title>
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

        h1 { font-weight: bold; margin: 0; font-size: 1.5rem; }
        p { font-size: 14px; font-weight: 400; line-height: 20px; letter-spacing: 0.5px; margin: 20px 0 30px; }
        span { font-size: 12px; }
        a { color: #333; font-size: 14px; text-decoration: none; margin: 15px 0; }
        a:hover { color: var(--main-color); }

        button {
            cursor: pointer;
            border-radius: 9999px;
            border: 1px solid var(--main-color);
            background: var(--main-color);
            color: #fff;
            font-size: 12px;
            font-weight: bold;
            padding: 12px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.2s ease;
        }

        button:hover { background: var(--secondary-color); color: var(--main-color); }
        button:focus { outline: none; }
        button:focus-visible { outline: 2px solid var(--main-color); outline-offset: 2px; }

        button.ghost {
            background-color: transparent;
            border-color: #fff;
            color: #fff;
        }
        button.ghost:hover { background: #fff; color: var(--main-color); }

        form {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 30px;
            height: 100%;
            text-align: center;
        }

        input {
            background-color: #eee;
            border: none;
            padding: 12px 15px;
            margin: 8px 0;
            width: 100%;
            font-family: inherit;
            border-radius: 9999px;
        }
        input:focus { outline: 2px solid var(--main-color); outline-offset: 2px; }

        .social-container { margin: 20px 0; }
        .social-container a {
            border: 1px solid #dddddd;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 5px;
            height: 40px;
            width: 40px;
            transition: all 0.3s ease;
        }
        .social-container a:hover { border-color: var(--main-color); color: var(--main-color); }

        .alert {
            width: 100%;
            padding: 12px 16px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-danger { background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert-success { background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }

        .container {
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 10px 20px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            min-height: 580px;
        }

        .form-container {
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            transition: all 0.6s ease-in-out;
            padding: 2rem;
        }

        .sign-in-container { left: 0; z-index: 2; opacity: 1; }
        .sign-up-container { left: 0; z-index: 1; opacity: 0; }

        .container.right-panel-active .sign-in-container { opacity: 0; z-index: 1; }
        .container.right-panel-active .sign-up-container { opacity: 1; z-index: 5; animation: show 0.6s; }

        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; }
            50%, 100% { opacity: 1; z-index: 5; }
        }

        .overlay-container {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .overlay {
            background: var(--main-color);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: #fff;
            position: relative;
            width: 100%;
            height: 100%;
            padding: 2rem;
            text-align: center;
            transition: transform 0.6s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .overlay-panel { width: 100%; text-align: center; }
        .overlay-panel h1 { font-size: 1.5rem; margin-bottom: 10px; }
        .overlay-panel p { margin: 10px 0 20px; font-size: 13px; }

        .mobile-toggle { display: block; text-align: center; padding: 20px; color: var(--main-color); font-weight: 600; cursor: pointer; transition: all 0.3s ease; }

        .password-wrapper { position: relative; width: 100%; }
        .password-wrapper input { padding-right: 45px; }
        .password-toggle {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 5px; color: #666; font-size: 18px; transition: color 0.3s ease;
        }
        .password-toggle:hover { color: var(--main-color); }
        .password-toggle:focus { outline: none; }

        @media (min-width: 768px) {
            body { padding: 20px; }
            h1 { font-size: 2rem; }
            form { padding: 0 50px; }
            .container { width: 768px; max-width: 100%; height: 520px; min-height: auto; }
            .form-container { width: 50%; height: 100%; }
            .sign-in-container { left: 0; width: 50%; }
            .container.right-panel-active .sign-in-container { transform: translateX(100%); opacity: 1; }
            .sign-up-container { left: 0; width: 50%; }
            .container.right-panel-active .sign-up-container { transform: translateX(100%); }
            .overlay-container { left: 50%; width: 50%; }
            .container.right-panel-active .overlay-container { transform: translateX(-100%); }
            .overlay { left: -100%; width: 200%; padding: 0; }
            .container.right-panel-active .overlay { transform: translateX(50%); }
            .overlay-panel { position: absolute; top: 0; height: 100%; width: 50%; padding: 0 40px; }
            .overlay-left { transform: translateX(-20%); }
            .container.right-panel-active .overlay-left { transform: translateX(0); }
            .overlay-right { right: 0; top: 0; left: 50%; transform: translateX(0); }
            .container.right-panel-active .overlay-right { transform: translateX(20%); }
            .mobile-toggle { display: none; }
        }

        /* RTL content adjustments */
        [dir="rtl"] .container { direction: ltr; }
        [dir="rtl"] form { direction: rtl; text-align: right; }
        [dir="rtl"] form h1, [dir="rtl"] form span, [dir="rtl"] form .alert, [dir="rtl"] form .mobile-toggle { text-align: center; }
        [dir="rtl"] .password-wrapper input { padding-right: 15px; padding-left: 45px; }
        [dir="rtl"] .password-toggle { right: auto; left: 15px; }

        /* Custom button styles for auth */
        .btn-primary {
            background: var(--main-color);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
            width: 100%;
        }
        .btn-primary:hover { background: #547246; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(100, 132, 84, 0.3); }
        .btn-primary:focus { outline: none; }
        .btn-primary:focus-visible { outline: 2px solid var(--main-color); outline-offset: 2px; }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .btn-ghost {
            background: transparent;
            color: white;
            border: 2px solid white;
            padding: 14px 32px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
        }
        .btn-ghost:hover { background: white; color: var(--main-color); }

        .input-field {
            width: 100%;
            background: #f3f4f6;
            border: 1px solid transparent;
            padding: 14px 16px;
            margin: 10px 0;
            border-radius: 9999px;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .input-field:focus { outline: none; background: white; border-color: var(--main-color); box-shadow: 0 0 0 3px rgba(100, 132, 84, 0.1); }
    </style>
</head>

<body>
    {{-- Language Switcher --}}
    <div style="position:fixed;top:16px;{{ $isAr ? 'left' : 'right' }}:16px;z-index:1000;display:flex;gap:8px;">
        <a href="{{ route('lang.switch', 'fr') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-full border-2 text-sm font-bold text-decoration-none {{ $locale==='fr' ? 'border-green-600 bg-green-600 text-white' : 'border-gray-300 bg-white text-gray-600' }}">
            🇫🇷 FR
        </a>
        <a href="{{ route('lang.switch', 'ar') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-full border-2 text-sm font-bold text-decoration-none {{ $locale==='ar' ? 'border-green-600 bg-green-600 text-white' : 'border-gray-300 bg-white text-gray-600' }}">
            🇲🇦 AR
        </a>
    </div>

    <div class="container" id="container">
        {{-- Sign Up Form --}}
        <div class="form-container sign-up-container">
            <form method="POST" action="{{ route('user.register.post') }}">
                @csrf
                <h1>{{ $t['signup_title'] }}</h1>
                <div class="social-container">
                    <a href="#" class="social" aria-label="Apple"><i class="fab fa-apple"></i></a>
                    <a href="#" class="social" aria-label="Google"><i class="fab fa-google"></i></a>
                </div>
                <span class="block mb-4 text-gray-500">{{ $t['or_email'] }}</span>
                
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="ri-error-warning-line"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="nom" placeholder="{{ $t['nom_ph'] }}" value="{{ old('nom') }}" required class="input-field" />
                    <input type="text" name="prenom" placeholder="{{ $t['prenom_ph'] }}" value="{{ old('prenom') }}" required class="input-field" />
                </div>
                <input type="text" name="login" placeholder="{{ $t['login_field_ph'] }}" value="{{ old('login') }}" required class="input-field" />
                <input type="email" name="email" placeholder="{{ $t['email_ph'] }}" value="{{ old('email') }}" required class="input-field" />
                
                <div class="password-wrapper">
                    <input type="password" id="signup-password" name="password" placeholder="{{ $t['password_ph'] }}" required class="input-field" />
                    <button type="button" class="password-toggle" onclick="togglePassword('signup-password', this)" aria-label="Afficher/masquer le mot de passe">
                        <i class="ri-eye-line"></i>
                    </button>
                </div>

                <button type="submit" class="btn-primary mt-4">{{ $t['signup_btn'] }}</button>
                
                <div class="mobile-toggle" id="mobileSignIn">{{ $t['mobile_have'] }}</div>
            </form>
        </div>

        {{-- Sign In Form --}}
        <div class="form-container sign-in-container">
            <form method="POST" action="{{ route('user.login.post') }}">
                @csrf
                <h1>{{ $t['signin_title'] }}</h1>
                <div class="social-container">
                    <a href="#" class="social" aria-label="Apple"><i class="fab fa-apple"></i></a>
                    <a href="#" class="social" aria-label="Google"><i class="fab fa-google"></i></a>
                </div>
                <span class="block mb-4 text-gray-500">{{ $t['or_account'] }}</span>
                
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="ri-error-warning-line"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        <i class="ri-checkbox-circle-line"></i> {{ session('success') }}
                    </div>
                @endif

                <input type="text" name="login" placeholder="{{ $t['login_ph'] }}" value="{{ old('login') }}" required class="input-field" />
                
                <div class="password-wrapper">
                    <input type="password" id="signin-password" name="password" placeholder="{{ $t['password_ph'] }}" required class="input-field" />
                    <button type="button" class="password-toggle" onclick="togglePassword('signin-password', this)" aria-label="Afficher/masquer le mot de passe">
                        <i class="ri-eye-line"></i>
                    </button>
                </div>
                
                <a href="{{ route('user.password.request') }}" class="block text-right mb-4 text-sm text-green-600 hover:text-green-700">{{ $t['forgot'] }}</a>
                <button type="submit" class="btn-primary">{{ $t['signin_btn'] }}</button>
                
                <div class="mobile-toggle" id="mobileSignUp">{{ $t['mobile_no'] }}</div>
            </form>
        </div>

        {{-- Overlay (Desktop Only) --}}
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>{{ $t['welcome_back'] }}</h1>
                    <p>{{ $t['welcome_desc'] }}</p>
                    <button class="btn-ghost" id="signIn">{{ $t['overlay_signin'] }}</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>{{ $t['hello_title'] }}</h1>
                    <p>{{ $t['hello_desc'] }}</p>
                    <button class="btn-ghost" id="signUp">{{ $t['overlay_signup'] }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            } else {
                input.type = 'password';
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            }
        }

        const signUpButton = document.getElementById("signUp");
        const signInButton = document.getElementById("signIn");
        const mobileSignUp = document.getElementById("mobileSignUp");
        const mobileSignIn = document.getElementById("mobileSignIn");
        const container = document.getElementById("container");

        // Desktop toggle
        if (signUpButton) {
            signUpButton.addEventListener("click", () => {
                container.classList.add("right-panel-active");
            });
        }

        if (signInButton) {
            signInButton.addEventListener("click", () => {
                container.classList.remove("right-panel-active");
            });
        }

        // Mobile toggle
        if (mobileSignUp) {
            mobileSignUp.addEventListener("click", () => {
                container.classList.add("right-panel-active");
            });
        }

        if (mobileSignIn) {
            mobileSignIn.addEventListener("click", () => {
                container.classList.remove("right-panel-active");
            });
        }
    </script>
</body>
</html>