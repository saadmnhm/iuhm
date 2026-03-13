<!DOCTYPE html>
<html lang="{{ $isAr ? 'ar' : 'fr' }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $isAr ? 'إعادة تعيين كلمة المرور' : 'Réinitialisation du mot de passe' }}</title>
</head>
<body style="margin:0;padding:0;background:#f0f4f8;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f4f8;padding:40px 0;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

        {{-- Header --}}
        <tr>
          <td style="background:linear-gradient(135deg,#648454 0%,#8baf74 100%);padding:40px 48px;text-align:center;">
            <div style="display:inline-block;background:rgba(255,255,255,0.2);border-radius:50%;padding:14px;margin-bottom:16px;">
              <span style="font-size:32px;">🔑</span>
            </div>
            <h1 style="margin:0;color:#ffffff;font-size:26px;font-weight:700;letter-spacing:-0.5px;">
              {{ $isAr ? 'إعادة تعيين كلمة المرور' : 'Réinitialisation du mot de passe' }}
            </h1>
            <p style="margin:8px 0 0;color:rgba(255,255,255,0.85);font-size:14px;">
              IUHM — Initiative Urbaine Hors des Murs
            </p>
          </td>
        </tr>

        {{-- Body --}}
        <tr>
          <td style="padding:48px 48px 32px;">
            <p style="margin:0 0 8px;font-size:18px;font-weight:600;color:#1a202c;">
              {{ $isAr ? 'مرحباً' : 'Bonjour' }} {{ $candidat->prenom }} {{ $candidat->nom }} 👋
            </p>
            <p style="margin:0 0 24px;font-size:15px;color:#4a5568;line-height:1.7;">
              @if($isAr)
                تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بحساب <strong>IUHM</strong> المرتبط بهذا البريد الإلكتروني.
              @else
                Nous avons reçu une demande de réinitialisation du mot de passe pour votre compte <strong>IUHM</strong>.
              @endif
            </p>

            {{-- Warning box --}}
            <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:16px 20px;margin-bottom:32px;">
              <p style="margin:0;font-size:14px;color:#92400e;">
                <span style="font-size:16px;">⏱️</span>
                {{ $isAr ? 'هذا الرابط صالح لمدة ' . $expiresIn . ' دقيقة فقط.' : 'Ce lien expire dans ' . $expiresIn . ' minutes.' }}
              </p>
            </div>

            {{-- CTA Button --}}
            <div style="text-align:center;margin-bottom:32px;">
              <a href="{{ $url }}"
                 style="display:inline-block;background:linear-gradient(135deg,#648454,#8baf74);color:#ffffff;text-decoration:none;padding:16px 40px;border-radius:30px;font-size:16px;font-weight:700;letter-spacing:0.5px;box-shadow:0 4px 14px rgba(100,132,84,0.4);">
                {{ $isAr ? '🔑 إعادة تعيين كلمة المرور' : '🔑 Réinitialiser mon mot de passe' }}
              </a>
            </div>

            <p style="font-size:13px;color:#718096;line-height:1.6;">
              {{ $isAr ? 'إذا لم يعمل الزر، انسخ هذا الرابط والصقه في متصفحك:' : 'Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :' }}
            </p>
            <p style="font-size:12px;word-break:break-all;background:#f7fafc;padding:12px;border-radius:8px;color:#4a5568;border:1px solid #e2e8f0;">
              {{ $url }}
            </p>

            <p style="margin:24px 0 0;font-size:13px;color:#a0aec0;">
              {{ $isAr ? 'إذا لم تطلب إعادة تعيين كلمة المرور، يمكنك تجاهل هذا البريد بأمان. حسابك في أمان.' : 'Si vous n\'avez pas demandé cette réinitialisation, ignorez cet email. Votre compte est sécurisé.' }}
            </p>
          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td style="background:#f7fafc;padding:24px 48px;text-align:center;border-top:1px solid #e2e8f0;">
            <p style="margin:0;font-size:13px;color:#718096;">
              © {{ date('Y') }} <strong style="color:#648454;">IUHM</strong> — Initiative Urbaine Hors des Murs
            </p>
            <p style="margin:8px 0 0;font-size:12px;color:#a0aec0;">
              Casablanca, Maroc
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>
