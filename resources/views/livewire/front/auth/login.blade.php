<!DOCTYPE html>
@php
    $locale = app()->getLocale();
    $isAr   = $locale === 'ar';
    $dir    = $isAr ? 'rtl' : 'ltr';
    $t = [
        'page_title'    => $isAr ? 'تسجيل الدخول' : 'Connexion — IUHM',
        // Sign-In panel
        'signin_title'  => $isAr ? 'تسجيل الدخول' : 'Connexion',
        'or_account'    => $isAr ? 'أو استخدم حسابك' : 'ou utilisez votre compte',
        'login_ph'      => $isAr ? 'البريد الإلكتروني أو اسم المستخدم' : 'Email ou Login',
        'password_ph'   => $isAr ? 'كلمة المرور' : 'Mot de passe',
        'forgot'        => $isAr ? 'نسيت كلمة المرور؟' : 'Mot de passe oublié ?',
        'signin_btn'    => $isAr ? 'دخول' : 'Connexion',
        // Sign-Up panel
        'signup_title'  => $isAr ? 'إنشاء حساب' : 'Créer un compte',
        'or_email'      => $isAr ? 'أو استخدم بريدك الإلكتروني للتسجيل' : 'ou utilisez votre email pour vous inscrire',
        'nom_ph'        => $isAr ? 'النسب' : 'Nom',
        'prenom_ph'     => $isAr ? 'الاسم' : 'Prénom',
        'login_field_ph'=> $isAr ? 'اسم المستخدم' : 'Login',
        'email_ph'      => $isAr ? 'البريد الإلكتروني' : 'Email',
        'signup_btn'    => $isAr ? 'إنشاء حساب' : 'Inscription',
        // Overlay
        'welcome_back'  => $isAr ? 'أهلاً بعودتك!' : 'Bon retour !',
        'welcome_desc'  => $isAr ? 'يرجى تسجيل الدخول بمعلوماتك الشخصية' : 'Connectez-vous avec vos informations personnelles',
        'overlay_signin'=> $isAr ? 'تسجيل الدخول' : 'Connexion',
        'hello_title'   => $isAr ? 'مرحباً!' : 'Bonjour !',
        'hello_desc'    => $isAr ? 'أدخل بياناتك الشخصية وابدأ رحلتك معنا' : 'Entrez vos informations et commencez votre aventure',
        'overlay_signup'=> $isAr ? 'إنشاء حساب' : 'Inscription',
        // Mobile toggles
        'mobile_have'   => $isAr ? 'لديك حساب بالفعل؟ تسجيل الدخول' : 'Vous avez un compte ? Se connecter',
        'mobile_no'     => $isAr ? 'ليس لديك حساب؟ إنشاء حساب' : 'Pas de compte ? S\'inscrire',
    ];
@endphp
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $t['page_title'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
       @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;800&display=swap");

:root {
  --main-color: #648454;
  --secondary-color: #edffe4 ;
  --gradient: linear-gradient(
    135deg,
    var(--main-color),
    var(--secondary-color)
  );
}

* {
  box-sizing: border-box;
}

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

h1 {
  font-weight: bold;
  margin: 0;
  font-size: 1.5rem;
}

p {
  font-size: 14px;
  font-weight: 100;
  line-height: 20px;
  letter-spacing: 0.5px;
  margin: 20px 0 30px;
}

input{
    outline:none;
}

.social-container {
  margin: 20px 0;
}

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

.social-container a:hover {
  border-color: var(--main-color);
  color: var(--main-color);
}

span {
  font-size: 12px;
}

a {
  color: #333;
  font-size: 14px;
  text-decoration: none;
  margin: 15px 0;
}

a:hover {
  color: var(--main-color);
}

button {
  cursor: pointer;
  border-radius: 20px;
  border: 1px solid var(--main-color);
  background: var(--main-color);
  color: #fff;
  font-size: 12px;
  font-weight: bold;
  padding: 12px 45px;
  letter-spacing: 1px;
  text-transform: uppercase;
  transition: transform 80ms ease-out;
}

button:hover {
  background: var(--secondary-color);
  color: var(--main-color);
}



button:focus {
  outline: none;
}

button.ghost {
  background-color: transparent;
  border-color: #fff;
  color: #fff;
}

button.ghost:hover {
  background: #fff;
  color: var(--main-color);
}

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
  border-radius: 30px;
}

.alert {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border-radius: 5px;
  font-size: 13px;
}

.alert-danger {
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
  color: #721c24;
}

.alert-success {
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
  color: #155724;
}

.container {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
  position: relative;
  overflow: hidden;
  width: 100%;
  max-width: 400px;
  min-height: 600px;
}

.form-container {
  position: absolute;
  top: 0;
  width: 100%;
  height: 100%;
  transition: all 0.6s ease-in-out;
}

.sign-in-container {
  top: 0;
  height: 100%;
  z-index: 2;
  opacity: 1;
}

.container.right-panel-active .sign-in-container {
  opacity: 0;
  z-index: 1;
}

.sign-up-container {
  top: 0;
  height: 100%;
  opacity: 0;
  z-index: 1;
}

.container.right-panel-active .sign-up-container {
  opacity: 1;
  z-index: 5;
  animation: show 0.6s;
}

@keyframes show {
  0%,
  49.99% {
    opacity: 0;
    z-index: 1;
  }
  50%,
  100% {
    opacity: 1;
    z-index: 5;
  }
}

.overlay-container {
  position: absolute;
  left: 0;
  bottom: 0;
  height: auto;
  width: 100%;
  overflow: hidden;
  transition: transform 0.6s ease-in-out;
  z-index: 100;
}

.overlay {
  background: #648454;
  background-repeat: no-repeat;
  background-size: cover;
  background-position: 0 0;
  color: #fff;
  position: relative;
  width: 100%;
  height: 100%;
  padding: 30px 20px;
  text-align: center;
  transition: transform 0.6s ease-in-out;
}

.overlay-panel {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  padding: 0 20px;
  text-align: center;
  width: 100%;
  transition: transform 0.6s ease-in-out;   
}
@media screen and (max-width:767px) {
    .overlay-container{
        display:none;
    }
    .sign-in-container {
        left: 0;
    }
    .sign-up-container{
        left: 0;
    }
    
}
.overlay-panel h1 {
  font-size: 1.3rem;
  margin-bottom: 10px;
}

.overlay-panel p {
  margin: 10px 0 20px;
  font-size: 13px;
}

.mobile-toggle {
  display: block;
  text-align: center;
  padding: 20px;
  color: var(--main-color);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.password-wrapper {
  position: relative;
  width: 100%;
}

.password-wrapper input {
  width: 100%;
  padding-right: 45px;
}

.password-toggle {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  padding: 5px;
  color: #666;
  font-size: 18px;
  transition: color 0.3s ease;
}

.password-toggle:hover {
  color: var(--main-color);
}

.password-toggle:focus {
  outline: none;
}

/* Desktop Styles */
@media (min-width: 768px) {
  body {
    padding: 20px;
  }

  h1 {
    font-size: 2rem;
  }

  form {
    padding: 0 50px;
  }

  .container {
    width: 768px;
    max-width: 100%;
    height: 570px;
    min-height: auto;
  }

  .form-container {
    top: 0;
    height: 100%;
    width: 50%;
  }

  .sign-in-container {
    left: 0;
    width: 50%;
    height: 100%;
    opacity: 1;
  }

  .container.right-panel-active .sign-in-container {
    transform: translateX(100%);
    opacity: 1;
  }

  .sign-up-container {
    left: 0;
    width: 50%;
    height: 100%;
  }

  .container.right-panel-active .sign-up-container {
    transform: translateX(100%);
  }

  .overlay-container {
    left: 50%;
    top: 0;
    bottom: auto;
    height: 100%;
    width: 50%;
  }

  .container.right-panel-active .overlay-container {
    transform: translateX(-100%);
  }

  .overlay {
    left: -100%;
    height: 100%;
    width: 200%;
    transform: translateX(0);
    padding: 0;
  }

  .container.right-panel-active .overlay {
    transform: translateX(50%);
  }

  .overlay-panel {
    position: absolute;
    top: 0;
    height: 100%;
    width: 50%;
    padding: 0 40px;
    transform: translateX(0);
  }

  .overlay-panel h1 {
    font-size: 2rem;
  }

  .overlay-panel p {
    font-size: 14px;
    margin: 20px 0 30px;
  }

  .overlay-left {
    transform: translateX(-20%);
  }

  .container.right-panel-active .overlay-left {
    transform: translateX(0);
  }

  .overlay-right {
    right: 0;
    top: 0;
    left: 50%;
    transform: translateX(0);
  }

  .container.right-panel-active .overlay-right {
    transform: translateX(20%);
  }

  .mobile-toggle {
    display: none;
  }
}

/* Keep panel animation layout stable (LTR), apply RTL only to content */
[dir="rtl"] .container {
  direction: ltr;
}

[dir="rtl"] form {
  direction: rtl;
  text-align: right;
}

[dir="rtl"] form h1,
[dir="rtl"] form span,
[dir="rtl"] form .alert,
[dir="rtl"] form .mobile-toggle {
  text-align: center;
}

[dir="rtl"] .password-wrapper input {
  padding-right: 15px;
  padding-left: 45px;
}

[dir="rtl"] .password-toggle {
  right: auto;
  left: 15px;
}

    </style>
</head>


<body>

{{-- Language Switcher --}}
<div style="position:fixed;top:16px;{{ $isAr ? 'left' : 'right' }}:16px;z-index:1000;display:flex;gap:8px;">
    <a href="{{ route('lang.switch', 'fr') }}"
       style="display:inline-flex;align-items:center;gap:4px;padding:5px 14px;border-radius:20px;border:2px solid {{ $locale==='fr' ? '#648454' : '#ddd' }};background:{{ $locale==='fr' ? '#648454' : '#fff' }};color:{{ $locale==='fr' ? '#fff' : '#666' }};font-size:13px;font-weight:700;text-decoration:none;">
        🇫🇷 FR
    </a>
    <a href="{{ route('lang.switch', 'ar') }}"
       style="display:inline-flex;align-items:center;gap:4px;padding:5px 14px;border-radius:20px;border:2px solid {{ $locale==='ar' ? '#648454' : '#ddd' }};background:{{ $locale==='ar' ? '#648454' : '#fff' }};color:{{ $locale==='ar' ? '#fff' : '#666' }};font-size:13px;font-weight:700;text-decoration:none;">
        🇲🇦 AR
    </a>
</div>

<div class="container" id="container">
    <!-- Sign Up Form -->
    <div class="form-container sign-up-container">
        <form method="POST" action="{{ route('user.register.post') }}">
            @csrf
            <h1>{{ $t['signup_title'] }}</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-apple"></i></a>
                <a href="#" class="social"><i class="fab fa-google"></i></a>
            </div>
            <span>{{ $t['or_email'] }}</span>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            <div style="display: flex; gap: 15px;">
                <input type="text" name="nom" placeholder="{{ $t['nom_ph'] }}" value="{{ old('nom') }}" required />
                <input type="text" name="prenom" placeholder="{{ $t['prenom_ph'] }}" value="{{ old('prenom') }}" required />
            </div>
            <input type="text" name="login" placeholder="{{ $t['login_field_ph'] }}" value="{{ old('login') }}" required />
            <input type="email" name="email" placeholder="{{ $t['email_ph'] }}" value="{{ old('email') }}" required />
            
            <div class="password-wrapper">
                <input type="password" id="signup-password" name="password" placeholder="{{ $t['password_ph'] }}" required />
                <button type="button" class="password-toggle" onclick="togglePassword('signup-password', this)">
                    <i class="ri-eye-line"></i>
                </button>
            </div>

            <button type="submit" class="mt-4">{{ $t['signup_btn'] }}</button>
            
            <div class="mobile-toggle" id="mobileSignIn">
                {{ $t['mobile_have'] }}
            </div>
        </form>
    </div>

    <!-- Sign In Form -->
    <div class="form-container sign-in-container">
        <form method="POST" action="{{ route('user.login.post') }}">
            @csrf
            <h1>{{ $t['signin_title'] }}</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-apple"></i></a>
                <a href="#" class="social"><i class="fab fa-google"></i></a>
            </div>
            <span>{{ $t['or_account'] }}</span>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <input type="text" name="login" placeholder="{{ $t['login_ph'] }}" value="{{ old('login') }}" required />
           
            <div class="password-wrapper">
                <input type="password" id="signin-password" name="password" placeholder="{{ $t['password_ph'] }}" required />
                <button type="button" class="password-toggle" onclick="togglePassword('signin-password', this)">
                    <i class="ri-eye-line"></i>
                </button>
            </div>
            
            <a href="{{ route('user.password.request') }}">{{ $t['forgot'] }}</a>
            <button type="submit">{{ $t['signin_btn'] }}</button>
            
            <div class="mobile-toggle" id="mobileSignUp">
                {{ $t['mobile_no'] }}
            </div>
        </form>
    </div>

    <!-- Overlay (Desktop Only) -->
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>{{ $t['welcome_back'] }}</h1>
                <p>{{ $t['welcome_desc'] }}</p>
                <button class="ghost" id="signIn">{{ $t['overlay_signin'] }}</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>{{ $t['hello_title'] }}</h1>
                <p>{{ $t['hello_desc'] }}</p>
                <button class="ghost" id="signUp">{{ $t['overlay_signup'] }}</button>
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