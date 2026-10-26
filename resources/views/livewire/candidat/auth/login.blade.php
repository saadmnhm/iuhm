<!DOCTYPE html>
@php
    $locale = app()->getLocale();
    $isAr   = $locale === 'ar';
    $dir    = $isAr ? 'rtl' : 'ltr';
    $t = [
        'page_title'    => $isAr ? 'تسجيل الدخول' : 'Connexion — IUHM',
        'signin_title'  => $isAr ? 'تسجيل الدخول' : 'Connexion',
        'login_ph'      => $isAr ? 'البريد الإلكتروني أو اسم المستخدم' : 'Email ou Login',
        'password_ph'   => $isAr ? 'كلمة المرور' : 'Mot de passe',
        'forgot'        => $isAr ? 'نسيت كلمة المرور؟' : 'Mot de passe oublié ?',
        'signin_btn'    => $isAr ? 'دخول' : 'Connexion',
        'signup_title'  => $isAr ? 'إنشاء حساب' : 'Créer un compte',
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

        * { box-sizing: border-box; }

        body {
            background: var(--bg-light);
            font-family: "Nunito", sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 1.5rem;
            color: var(--text-dark);
        }

        h1 { font-weight: 800; margin: 0 0 10px 0; font-size: 1.75rem; color: #111827; }
        p { font-size: 14px; font-weight: 400; line-height: 22px; margin: 10px 0 25px; color: #4b5563; }
        a { color: #4b5563; font-size: 14px; text-decoration: none; transition: color 0.2s; }
        a:hover { color: var(--main-color); }

        /* Form & Inputs */
        form {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 35px;
            height: 100%;
            text-align: center;
            width: 100%;
            transform: translateZ(0);
        }

        .input-field {
            width: 100%;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 13px 18px;
            margin: 8px 0;
            border-radius: 9999px;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.25s ease;
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
            padding: 13px 32px;
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
            padding: 13px 35px;
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
            padding: 12px 16px;
            margin-bottom: 15px;
            border-radius: 10px;
            font-size: 13px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            text-align: left;
        }
        [dir="rtl"] .alert { text-align: right; }
        .alert-danger { background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert-success { background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }

        /* Main Container */
        .container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.03);
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            min-height: 520px;
            transform: translateZ(0);
        }

        .form-container {
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            transition: all 0.6s cubic-bezier(0.65, 0, 0.35, 1);
            padding: 2rem 1.5rem;
            will-change: transform, opacity;
            backface-visibility: hidden;
        }

        .sign-in-container { left: 0; z-index: 2; opacity: 1; }
        .sign-up-container { left: 0; z-index: 1; opacity: 0; pointer-events: none; }

        .container.right-panel-active .sign-in-container { opacity: 0; z-index: 1; pointer-events: none; }
        .container.right-panel-active .sign-up-container { opacity: 1; z-index: 5; pointer-events: auto; animation: show 0.6s; }

        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; }
            50%, 100% { opacity: 1; z-index: 5; }
        }

        /* Overlay */
        .overlay-container {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            overflow: hidden;
            transition: transform 0.6s cubic-bezier(0.65, 0, 0.35, 1);
            z-index: 100;
            pointer-events: none;
            will-change: transform;
            backface-visibility: hidden;
        }

        .overlay {
            background: var(--main-color);
            color: #fff;
            position: relative;
            width: 100%;
            height: 100%;
            padding: 2.5rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translateZ(0);
            will-change: transform;
        }

        .overlay-panel { 
            width: 100%; 
            text-align: center; 
            transform: translateZ(0);
            backface-visibility: hidden;
        }
        .overlay-panel h1 { color: #fff; font-size: 1.75rem; margin-bottom: 12px; }
        .overlay-panel p { color: rgba(255, 255, 255, 0.9); margin: 10px 0 25px; }

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
        .password-wrapper { position: relative; width: 100%; margin: 8px 0; }
        .password-wrapper .input-field { margin: 0; padding-right: 45px; }
        .password-toggle {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 5px; color: #9ca3af; font-size: 16px; transition: color 0.2s;
        }
        .password-toggle:hover { color: var(--main-color); }

        /* Desktop View */
        @media (min-width: 768px) {
            body { padding: 20px; }
            .container { width: 850px; max-width: 100%; height: 500px; min-height: auto; }
            .form-container { width: 50%; padding: 2.5rem 3rem; }
            .sign-in-container { left: 0; width: 50%; }
            .container.right-panel-active .sign-in-container { transform: translateX(100%); }
            .sign-up-container { left: 0; width: 50%; }
            .container.right-panel-active .sign-up-container { transform: translateX(100%); }
            .overlay-container { left: 50%; width: 50%; pointer-events: auto; transform: translateX(0); }
            .container.right-panel-active .overlay-container { transform: translateX(-100%); }
            .overlay { left: -100%; width: 200%; padding: 0; transform: translateX(0); transition: transform 0.6s cubic-bezier(0.65, 0, 0.35, 1); }
            .container.right-panel-active .overlay { transform: translateX(50%); }
            .overlay-panel { position: absolute; top: 0; height: 100%; width: 50%; padding: 0 50px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
            .overlay-left {  left: 0; }
            .overlay-right { right: 0; left: 50%; }
            .mobile-toggle { display: none; }
        }

        /* RTL Adjustments */
        [dir="rtl"] .container { direction: ltr; }
        [dir="rtl"] form { direction: rtl; text-align: right; }
        [dir="rtl"] form h1, [dir="rtl"] form span, [dir="rtl"] form .alert, [dir="rtl"] form .mobile-toggle { text-align: center; }
        [dir="rtl"] .password-wrapper .input-field { padding-right: 18px; padding-left: 45px; }
        [dir="rtl"] .password-toggle { right: auto; left: 15px; }
    </style>
</head>

<body>
    {{-- Language Switcher --}}
    <div style="position:fixed;top:20px;{{ $isAr ? 'left' : 'right' }}:20px;z-index:1000;display:flex;gap:8px;">
        <a href="{{ route('lang.switch', 'fr') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-full border-2 text-xs font-bold text-decoration-none transition-all {{ $locale==='fr' ? 'border-[#648454] bg-[#648454] text-white shadow-sm' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300' }}">
            🇫🇷 FR
        </a>
        <a href="{{ route('lang.switch', 'ar') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-full border-2 text-xs font-bold text-decoration-none transition-all {{ $locale==='ar' ? 'border-[#648454] bg-[#648454] text-white shadow-sm' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300' }}">
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
                
                <div style="display: flex; gap: 10px; width: 100%;">
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

                <button type="submit" class="btn-primary" style="margin-top: 15px;">{{ $t['signup_btn'] }}</button>
                
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
                
                <div style="width: 100%; text-align: {{ $isAr ? 'left' : 'right' }}; margin: 5px 0 15px;">
                    <a href="{{ route('user.password.request') }}" style="font-size: 13px; font-weight: 600;">{{ $t['forgot'] }}</a>
                </div>

                <button type="submit" class="btn-primary" style="margin-top: 5px;">{{ $t['signin_btn'] }}</button>
                
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