<!DOCTYPE html>
@php
    $locale = app()->getLocale();
    $isAr   = $locale === 'ar';
    $dir    = $isAr ? 'rtl' : 'ltr';
    $t = [
        'page_title'     => $isAr ? 'تسجيل الدخول' : 'Connexion — IUHM',
        'signin_title'   => $isAr ? 'تسجيل الدخول' : 'Connexion',
        'login_ph'       => $isAr ? 'البريد الإلكتروني أو اسم المستخدم' : 'Email ou Login',
        'password_ph'    => $isAr ? 'كلمة المرور' : 'Mot de passe',
        'forgot'         => $isAr ? 'نسيت كلمة المرور؟' : 'Mot de passe oublié ?',
        'signin_btn'     => $isAr ? 'دخول' : 'Connexion',
        'signup_title'   => $isAr ? 'إنشاء حساب' : 'Créer un compte',
        'nom_ph'         => $isAr ? 'النسب' : 'Nom',
        'prenom_ph'      => $isAr ? 'الاسم' : 'Prénom',
        'login_field_ph' => $isAr ? 'اسم المستخدم' : 'Login',
        'email_ph'       => $isAr ? 'البريد الإلكتروني' : 'Email',
        'signup_btn'     => $isAr ? 'إنشاء حساب' : 'Inscription',
        'welcome_back'   => $isAr ? 'أهلاً بعودتك!' : 'Bon retour !',
        'welcome_desc'   => $isAr ? 'يرجى تسجيل الدخول بمعلوماتك الشخصية' : 'Connectez-vous avec vos informations personnelles',
        'overlay_signin' => $isAr ? 'تسجيل الدخول' : 'Connexion',
        'hello_title'    => $isAr ? 'مرحباً!' : 'Bonjour !',
        'hello_desc'     => $isAr ? 'أدخل بياناتك الشخصية وابدأ رحلتك معنا' : 'Entrez vos informations et commencez votre aventure',
        'overlay_signup' => $isAr ? 'إنشاء حساب' : 'Inscription',
        'mobile_have'    => $isAr ? 'لديك حساب بالفعل؟ تسجيل الدخول' : 'Vous avez un compte ? Se connecter',
        'mobile_no'      => $isAr ? 'ليس لديك حساب؟ إنشاء حساب' : 'Pas de compte ? S\'inscrire',
    ];
@endphp
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $t['page_title'] }}</title>
    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --main-color: #648454;
            --main-hover: #547246;
            --secondary-color: #edffe4;
            --bg-light: #f8fafc;
            --text-dark: #1e293b;
        }

        * { 
            box-sizing: border-box; 
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg-light);
            font-family: "Nunito", sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            min-height: 100dvh;
            padding: 1.25rem;
            color: var(--text-dark);
        }

        /* Language Switcher Position */
        .lang-switcher {
            position: fixed;
            top: 1rem;
            z-index: 1000;
            display: flex;
            gap: 8px;
        }
        [dir="ltr"] .lang-switcher { right: 1rem; }
        [dir="rtl"] .lang-switcher { left: 1rem; }

        h1 { font-weight: 800; margin-bottom: 8px; font-size: 1.5rem; color: #111827; }
        p { font-size: 14px; font-weight: 400; line-height: 20px; margin: 8px 0 20px; color: #4b5563; }
        a { color: #4b5563; font-size: 14px; text-decoration: none; transition: color 0.2s; }
        a:hover { color: var(--main-color); }

        /* Form & Inputs */
        form {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 2rem 1.25rem;
            width: 100%;
            text-align: center;
        }

        .input-group-row {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }

        .input-field {
            width: 100%;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 12px 18px;
            margin: 6px 0;
            border-radius: 9999px;
            font-family: inherit;
            font-size: 16px; /* Prevents auto-zoom on iOS */
            transition: all 0.25s ease;
        }
        @media (min-width: 768px) {
            .input-field { font-size: 14px; }
            .input-group-row { flex-direction: row; gap: 10px; }
        }

        .input-field:focus {
            outline: none;
            background: #fff;
            border-color: var(--main-color);
            box-shadow: 0 0 0 4px rgba(100, 132, 84, 0.12);
        }

        /* Buttons */
        .btn-primary {
            cursor: pointer;
            border-radius: 9999px;
            border: none;
            background: var(--main-color);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            padding: 14px 28px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            transition: all 0.25s ease;
            width: 100%;
            box-shadow: 0 4px 12px rgba(100, 132, 84, 0.25);
        }
        .btn-primary:hover {
            background: var(--main-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(100, 132, 84, 0.35);
        }
        .btn-primary:active { transform: translateY(0); }

        .btn-ghost {
            cursor: pointer;
            border-radius: 9999px;
            background: transparent;
            border: 2px solid #fff;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            padding: 12px 30px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            transition: all 0.25s ease;
        }
        .btn-ghost:hover {
            background: #fff;
            color: var(--main-color);
            transform: translateY(-1px);
        }

        /* Alerts */
        .alert {
            width: 100%;
            padding: 10px 14px;
            margin-bottom: 12px;
            border-radius: 10px;
            font-size: 13px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            text-align: left;
        }
        [dir="rtl"] .alert { text-align: right; }
        .alert-danger { background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert-success { background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }

        /* Main Container - Mobile First Layout */
        .container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.03);
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 440px;
            margin-top: 3rem;
            min-height: auto;
        }

        .form-container {
            width: 100%;
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        /* Mobile form switching via visibility/display control */
        .sign-in-container { display: block; }
        .sign-up-container { display: none; }

        .container.right-panel-active .sign-in-container { display: none; }
        .container.right-panel-active .sign-up-container { display: block; }

        .overlay-container { display: none; }

        .mobile-toggle {
            display: block;
            text-align: center;
            padding: 15px 0 5px;
            color: var(--main-color);
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: color 0.2s;
        }
        .mobile-toggle:hover { color: var(--main-hover); text-decoration: underline; }

        /* Password Wrapper */
        .password-wrapper { position: relative; width: 100%; margin: 4px 0 8px; }
        .password-wrapper .input-field { margin: 0; padding-right: 45px; }
        .password-toggle {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 5px; color: #9ca3af; font-size: 18px; transition: color 0.2s;
        }
        .password-toggle:hover { color: var(--main-color); }

        /* Desktop View Styles */
        @media (min-width: 768px) {
            body { padding: 20px; }
            .container {
                max-width: 850px;
                height: 520px;
                margin-top: 0;
            }

            .form-container {
                position: absolute;
                top: 0;
                height: 100%;
                width: 50%;
                padding: 2.5rem 3rem;
                transition: transform 0.6s cubic-bezier(0.65, 0, 0.35, 1), opacity 0.6s cubic-bezier(0.65, 0, 0.35, 1);
                will-change: transform, opacity;
                backface-visibility: hidden;
            }

            form { height: 100%; padding: 0 35px; }

            .sign-in-container { left: 0; z-index: 2; opacity: 1; display: block; }
            .sign-up-container { left: 0; z-index: 1; opacity: 0; pointer-events: none; display: block; }

            .container.right-panel-active .sign-in-container { opacity: 0; z-index: 1; pointer-events: none; transform: translateX(100%); display: block; }
            .container.right-panel-active .sign-up-container { opacity: 1; z-index: 5; pointer-events: auto; transform: translateX(100%); display: block; }

            /* Overlay Styling */
            .overlay-container {
                display: block;
                position: absolute;
                left: 50%;
                top: 0;
                height: 100%;
                width: 50%;
                overflow: hidden;
                transition: transform 0.6s cubic-bezier(0.65, 0, 0.35, 1);
                z-index: 100;
                pointer-events: auto;
                will-change: transform;
                backface-visibility: hidden;
            }

            .container.right-panel-active .overlay-container { transform: translateX(-100%); }

            .overlay {
                background: var(--main-color);
                color: #fff;
                position: relative;
                left: -100%;
                width: 200%;
                height: 100%;
                transform: translateX(0);
                transition: transform 0.6s cubic-bezier(0.65, 0, 0.35, 1);
                will-change: transform;
            }

            .container.right-panel-active .overlay { transform: translateX(50%); }

            .overlay-panel { 
                position: absolute; 
                top: 0; 
                height: 100%; 
                width: 50%; 
                padding: 0 50px; 
                display: flex; 
                flex-direction: column; 
                align-items: center; 
                justify-content: center;
                text-align: center;
                backface-visibility: hidden;
            }
            .overlay-panel h1 { color: #fff; font-size: 1.75rem; margin-bottom: 12px; }
            .overlay-panel p { color: rgba(255, 255, 255, 0.9); margin: 10px 0 25px; }

            .overlay-left { left: 0; }
            .overlay-right { right: 0; left: 50%; }

            .mobile-toggle { display: none; }
        }

        /* RTL Adjustments */
        [dir="rtl"] .container { direction: ltr; }
        [dir="rtl"] form { direction: rtl; text-align: right; }
        [dir="rtl"] form h1, [dir="rtl"] form p, [dir="rtl"] form .alert, [dir="rtl"] form .mobile-toggle { text-align: center; }
        [dir="rtl"] .password-wrapper .input-field { padding-right: 18px; padding-left: 45px; }
        [dir="rtl"] .password-toggle { right: auto; left: 15px; }
    </style>
</head>

<body>
    {{-- Language Switcher --}}
    <div class="lang-switcher">
        <a href="{{ route('lang.switch', 'fr') }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border-2 text-xs font-bold text-decoration-none transition-all {{ $locale==='fr' ? 'border-[#648454] bg-[#648454] text-white shadow-sm' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300' }}">
            🇫🇷 FR
        </a>
        <a href="{{ route('lang.switch', 'ar') }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border-2 text-xs font-bold text-decoration-none transition-all {{ $locale==='ar' ? 'border-[#648454] bg-[#648454] text-white shadow-sm' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300' }}">
            🇲🇦 AR
        </a>
    </div>

    <div class="container" id="container">
        {{-- Sign Up Form --}}
        <div class="form-container sign-up-container">
            <form method="POST" action="{{ route('user.register.post') }}">
                @csrf
                <h1>{{ $t['signup_title'] }}</h1>
                
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="ri-error-warning-line text-lg mt-0.5"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <div class="input-group-row">
                    <input type="text" name="nom" placeholder="{{ $t['nom_ph'] }}" value="{{ old('nom') }}" required class="input-field" />
                    <input type="text" name="prenom" placeholder="{{ $t['prenom_ph'] }}" value="{{ old('prenom') }}" required class="input-field" />
                </div>
                
                <input type="email" name="email" placeholder="{{ $t['email_ph'] }}" value="{{ old('email') }}" required class="input-field" />
                
                <div class="password-wrapper">
                    <input type="password" id="signup-password" name="password" placeholder="{{ $t['password_ph'] }}" required class="input-field" />
                    <button type="button" class="password-toggle" onclick="togglePassword('signup-password', this)" aria-label="Afficher/masquer le mot de passe">
                        <i class="ri-eye-line"></i>
                    </button>
                </div>

                <button type="submit" class="btn-primary" style="margin-top: 10px;">{{ $t['signup_btn'] }}</button>
                
                <div class="mobile-toggle" id="mobileSignIn">{{ $t['mobile_have'] }}</div>
            </form>
        </div>

        {{-- Sign In Form --}}
        <div class="form-container sign-in-container">
            <form method="POST" action="{{ route('user.login.post') }}">
                @csrf
                <h1>{{ $t['signin_title'] }}</h1>
                
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="ri-error-warning-line text-lg mt-0.5"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        <i class="ri-checkbox-circle-line text-lg mt-0.5"></i> 
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <input type="text" name="login" placeholder="{{ $t['login_ph'] }}" value="{{ old('login') }}" required class="input-field" />
                
                <div class="password-wrapper">
                    <input type="password" id="signin-password" name="password" placeholder="{{ $t['password_ph'] }}" required class="input-field" />
                    <button type="button" class="password-toggle" onclick="togglePassword('signin-password', this)" aria-label="Afficher/masquer le mot de passe">
                        <i class="ri-eye-line"></i>
                    </button>
                </div>
                
                <div style="width: 100%; text-align: {{ $isAr ? 'left' : 'right' }}; margin: 4px 0 14px;">
                    <a href="{{ route('user.password.request') }}" style="font-size: 13px; font-weight: 600;">{{ $t['forgot'] }}</a>
                </div>

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
                    <button type="button" class="btn-ghost" id="signIn">{{ $t['overlay_signin'] }}</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>{{ $t['hello_title'] }}</h1>
                    <p>{{ $t['hello_desc'] }}</p>
                    <button type="button" class="btn-ghost" id="signUp">{{ $t['overlay_signup'] }}</button>
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