<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CandidatVerifyEmail extends VerifyEmail
{
    /**
     * Build a signed verification URL pointing at the candidat-specific route.
     */
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'user.verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 120)),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Build the branded email notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $url        = $this->verificationUrl($notifiable);
        $locale     = session('locale', app()->getLocale());
        $isAr       = $locale === 'ar';
        $expiresIn  = Config::get('auth.verification.expire', 60);

        return (new MailMessage)
            ->subject($isAr ? 'تأكيد البريد الإلكتروني — IUHM' : 'Vérifiez votre adresse email — IUHM')
            ->view('emails.candidat.verify', [
                'url'       => $url,
                'candidat'  => $notifiable,
                'expiresIn' => $expiresIn,
                'isAr'      => $isAr,
            ]);
    }
}
