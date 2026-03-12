@php
    $locale = app()->getLocale();
    $isAr   = $locale === 'ar';
    $dir    = $isAr ? 'rtl' : 'ltr';
    $t = [
        'title'      => $isAr ? 'إنشاء حساب' : 'Créer un compte',
        'or_email'   => $isAr ? 'أو استخدم بريدك الإلكتروني' : 'ou utilisez votre email',
        'nom'        => $isAr ? 'النسب' : 'Nom',
        'prenom'     => $isAr ? 'الاسم' : 'Prénom',
        'email'      => $isAr ? 'البريد الإلكتروني' : 'Email',
        'phone'      => $isAr ? 'الهاتف (مثال: 0612345678)' : 'Téléphone (ex: 0612345678)',
        'genre'      => $isAr ? '-- الجنس --' : '-- Genre --',
        'homme'      => $isAr ? 'ذكر' : 'Homme',
        'femme'      => $isAr ? 'أنثى' : 'Femme',
        'address'    => $isAr ? '-- الحي / العنوان --' : '-- Adresse / Quartier --',
        'addr_other' => $isAr ? 'أخرى' : 'Autre (saisir manuellement)',
        'addr_ph'    => $isAr ? 'أدخل عنوانك...' : 'Saisir votre adresse...',
        'password'   => $isAr ? 'كلمة المرور' : 'Mot de passe',
        'submit'     => $isAr ? 'إنشاء حساب' : 'S\'inscrire',
        'have_acct'  => $isAr ? 'لديك حساب؟ تسجيل الدخول' : 'Vous avez un compte ? Se connecter',
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
  transition: all 0.3s ease;
}

button:hover {
  transform: scale(1.05);
}

button:active {
  transform: scale(0.95);
}

button:focus {
  outline: none;
}

button.ghost {
  background-color: transparent;
  border-color: #fff;
}

button.ghost:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

form {
  background-color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  padding: 0 50px;
  height: 100%;
  text-align: center;
}

input {
  background-color: #eee;
  border: none;
  padding: 12px 15px;
  margin: 8px 0;
  width: 100%;
  border-radius: 5px;
  font-size: 14px;
}

.container {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
  position: relative;
  overflow: hidden;
  width: 768px;
  max-width: 100%;
  min-height: 480px;
}

.form-container {
  position: absolute;
  top: 0;
  height: 100%;
  transition: all 0.6s ease-in-out;
}

.sign-up-container {
  left: 0;
  width: 100%;
  opacity: 1;
  z-index: 2;
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
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 5px 10px;
  color: #666;
}

.password-toggle:hover {
  color: var(--main-color);
}

.alert {
  padding: 10px;
  margin: 10px 0;
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

.mt-4 {
  margin-top: 1rem;
}

@media (max-width: 768px) {
  .container {
    min-height: auto;
  }

  form {
    padding: 30px;
  }

  h1 {
    font-size: 1.3rem;
  }
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
            <h1>{{ $t['title'] }}</h1>
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

            <input type="text" name="nom" placeholder="{{ $t['nom'] }}" value="{{ old('nom') }}" required />
            <input type="text" name="prenom" placeholder="{{ $t['prenom'] }}" value="{{ old('prenom') }}" required />
            <input type="email" name="email" placeholder="{{ $t['email'] }}" value="{{ old('email') }}" required />
            <input type="tel" name="phone" placeholder="{{ $t['phone'] }}" value="{{ old('phone') }}" />
            
            <select name="gender" style="background-color:#eee;border:none;padding:12px 15px;margin:8px 0;width:100%;border-radius:5px;font-size:14px;color:#333;">
                <option value="">{{ $t['genre'] }}</option>
                <option value="homme" {{ old('gender') === 'homme' ? 'selected' : '' }}>{{ $t['homme'] }}</option>
                <option value="femme" {{ old('gender') === 'femme' ? 'selected' : '' }}>{{ $t['femme'] }}</option>
            </select>

            <select name="address_id" id="address_id_select" onchange="toggleAddressOther(this)" style="background-color:#eee;border:none;padding:12px 15px;margin:8px 0;width:100%;border-radius:5px;font-size:14px;color:#333;">
                <option value="">{{ $t['address'] }}</option>
                @foreach($addresses ?? [] as $addr)
                <option value="{{ $addr->address_line1 }}" {{ old('address_id') === $addr->address_line1 ? 'selected' : '' }}>{{ $addr->address_line1 }}{{ $addr->city ? ' — '.$addr->city : '' }}</option>
                @endforeach
                <option value="other" {{ old('address_id') === 'other' ? 'selected' : '' }}>{{ $t['addr_other'] }}</option>
            </select>
            <input type="text" id="address_other_input" name="address_other"
                   placeholder="{{ $t['addr_ph'] }}"
                   value="{{ old('address_other') }}"
                   style="display:none;background-color:#eee;border:none;padding:12px 15px;margin:8px 0;width:100%;border-radius:5px;font-size:14px;" />
            
            <div class="password-wrapper">
                <input type="password" id="signup-password" name="password" placeholder="{{ $t['password'] }}" required />
                <button type="button" class="password-toggle" onclick="togglePassword('signup-password', this)">
                    <i class="ri-eye-line"></i>
                </button>
            </div>
            
            <button type="submit" class="mt-4">{{ $t['submit'] }}</button>
            
            <a href="{{ route('user.login') }}">{{ $t['have_acct'] }}</a>
        </form>
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

    function toggleAddressOther(select) {
        const otherInput = document.getElementById('address_other_input');
        if (select.value === 'other') {
            otherInput.style.display = 'block';
            otherInput.required = true;
        } else {
            otherInput.style.display = 'none';
            otherInput.required = false;
            otherInput.value = '';
        }
    }

    // On page load, restore state if old('address_id') === 'other'
    document.addEventListener('DOMContentLoaded', function () {
        const sel = document.getElementById('address_id_select');
        if (sel) toggleAddressOther(sel);
    });
</script>
</body>
</html>
