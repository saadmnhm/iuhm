<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class CandidatResetPassword extends Notification
{
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $locale = session('locale', app()->getLocale());
        $isAr   = $locale === 'ar';

        $url = url(route('user.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $expiresIn = Config::get('auth.passwords.candidats.expire', 60);

        return (new MailMessage)
            ->subject($isAr ? 'إعادة تعيين كلمة المرور — IUHM' : 'Réinitialisation de votre mot de passe — IUHM')
            ->view('emails.candidat.reset-password', [
                'url'       => $url,
                'candidat'  => $notifiable,
                'expiresIn' => $expiresIn,
                'isAr'      => $isAr,
            ]);
    }
}
