<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailCustom extends VerifyEmail
{
    public function toMail($notifiable)
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('メールアドレス確認のお願い')
            ->view('mails.verify_email_min', [
                'url'  => $url,
                'user' => $notifiable, // ← Userモデルがそのまま入る
            ]);
    }
}
