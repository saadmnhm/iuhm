@php
    $locale = app()->getLocale();
    $isAr   = $locale === 'ar';
    $dir    = $isAr ? 'rtl' : 'ltr';
    $t = [
        'title'      => $isAr ? 'إنشاء حساب' : 'Créer un compte',
        'or_email'   => $isAr ? 'أو استخدم بريدك الإلكتروني' : 'ou utilisez votre email',
        'nom'        => $isAr ? 'النسب' : 'Nom',
        'prenom'     => $isAr ? 'الاسم' : 'Prénom',
        'cin'        => $isAr ? 'رقم البطاقة الوطنية (CIN)' : 'Numéro CIN',
        'date_naissance' => $isAr ? 'تاريخ الازدياد' : 'Date de naissance',
        'niveau_etude' => $isAr ? 'المستوى الدراسي' : 'Niveau d\'étude',
        'specialite' => $isAr ? 'التخصص' : 'Spécialité',
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

        h1 { font-weight: bold; margin: 0 0 1.5rem; font-size: 1.5rem; }
        p { font-size: 14px; font-weight: 400; line-height: 20px; letter-spacing: 0.5px; margin: 20px 0 30px; }
        span { font-size: 12px; }
        a { color: #333; font-size: 14px; text-decoration: none; margin: 15px 0; }
        a:hover { color: var(--main-color); }

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

        .container {
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 10px 20px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 480px;
        }

        .form-container {
            padding: 2.5rem;
        }

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

        .select-field {
            width: 100%;
            background: #f3f4f6;
            border: 1px solid transparent;
            padding: 14px 16px;
            margin: 10px 0;
            border-radius: 9999px;
            font-family: inherit;
            font-size: 14px;
            color: #333;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z'/%3E%3C/svg%3E");
            background-position: right 1rem center;
            background-repeat: no-repeat;
            background-size: 1.25rem;
            padding-right: 3rem;
            transition: all 0.2s ease;
        }
        .select-field:focus { outline: none; background: white; border-color: var(--main-color); box-shadow: 0 0 0 3px rgba(100, 132, 84, 0.1); }
        [dir="rtl"] .select-field { background-position: left 1rem center; padding-right: 1rem; padding-left: 3rem; }

        .password-wrapper { position: relative; width: 100%; }
        .password-wrapper .input-field { padding-right: 45px; }
        [dir="rtl"] .password-wrapper .input-field { padding-right: 16px; padding-left: 45px; }
        .password-toggle {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 5px; color: #666; font-size: 18px; transition: color 0.3s ease;
        }
        [dir="rtl"] .password-toggle { right: auto; left: 15px; }
        .password-toggle:hover { color: var(--main-color); }
        .password-toggle:focus { outline: none; }

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

        .auth-link { color: var(--main-color); font-weight: 600; text-decoration: none; }
        .auth-link:hover { text-decoration: underline; }

        .hidden-input { display: none; }
        .hidden-input.show { display: block; }
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
        <div class="form-container sign-up-container">
            <form method="POST" action="{{ route('user.register.post') }}">
                @csrf
                <h1>{{ $t['title'] }}</h1>
                <div class="social-container">
                    <a href="#" class="social" aria-label="Apple"><i class="fab fa-apple"></i></a>
                    <a href="#" class="social" aria-label="Google"><i class="fab fa-google"></i></a>
                </div>
                <span class="block mb-6 text-gray-500">{{ $t['or_email'] }}</span>
                
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
                    <input type="text" name="nom" placeholder="{{ $t['nom'] }}" value="{{ old('nom') }}" required class="input-field" />
                    <input type="text" name="prenom" placeholder="{{ $t['prenom'] }}" value="{{ old('prenom') }}" required class="input-field" />
                </div>
                <input type="number" name="cin" placeholder="{{ $t['cin'] }}" value="{{ old('cin') }}" min="0" step="1" inputmode="numeric" oninput="this.value=this.value.replace(/\D/g,'')" class="input-field" />
                <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" class="input-field" />
                <input type="text" name="niveau_etude" placeholder="{{ $t['niveau_etude'] }}" value="{{ old('niveau_etude') }}" class="input-field" />
                <input type="text" name="specialite" placeholder="{{ $t['specialite'] }}" value="{{ old('specialite') }}" class="input-field" />
                <input type="email" name="email" placeholder="{{ $t['email'] }}" value="{{ old('email') }}" required class="input-field" />
                <input type="number" name="phone" placeholder="{{ $t['phone'] }}" value="{{ old('phone') }}" min="0" step="1" inputmode="numeric" oninput="this.value=this.value.replace(/\D/g,'')" class="input-field" />
                
                <select name="gender" class="select-field">
                    <option value="">{{ $t['genre'] }}</option>
                    <option value="homme" {{ old('gender') === 'homme' ? 'selected' : '' }}>{{ $t['homme'] }}</option>
                    <option value="femme" {{ old('gender') === 'femme' ? 'selected' : '' }}>{{ $t['femme'] }}</option>
                </select>

                <select name="address_id" id="address_id_select" onchange="toggleAddressOther(this)" class="select-field">
                    <option value="">{{ $t['address'] }}</option>
                    @foreach($addresses ?? [] as $addr)
                    <option value="{{ $addr->address_line1 }}" {{ old('address_id') === $addr->address_line1 ? 'selected' : '' }}>{{ $addr->address_line1 }}{{ $addr->city ? ' — '.$addr->city : '' }}</option>
                    @endforeach
                    <option value="other" {{ old('address_id') === 'other' ? 'selected' : '' }}>{{ $t['addr_other'] }}</option>
                </select>
                <input type="text" id="address_other_input" name="address_other"
                       placeholder="{{ $t['addr_ph'] }}"
                       value="{{ old('address_other') }}"
                       class="input-field hidden-input" />
                
                <div class="password-wrapper">
                    <input type="password" id="signup-password" name="password" placeholder="{{ $t['password'] }}" required class="input-field" />
                    <button type="button" class="password-toggle" onclick="togglePassword('signup-password', this)" aria-label="Afficher/masquer le mot de passe">
                        <i class="ri-eye-line"></i>
                    </button>
                </div>
                
                <button type="submit" class="btn-primary mt-4">{{ $t['submit'] }}</button>
                
                <a href="{{ route('user.login') }}" class="auth-link mt-4 block">{{ $t['have_acct'] }}</a>
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
                otherInput.classList.add('show');
                otherInput.required = true;
            } else {
                otherInput.classList.remove('show');
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