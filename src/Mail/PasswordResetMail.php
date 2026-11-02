<?php

namespace Weboldalnet\CommerceUsers\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Weboldalnet\CommerceUsers\Models\CommerceUser;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels, UsesShopSender;

    public function __construct(public CommerceUser $user, public string $token)
    {
    }

    public function build()
    {
        return $this->from(self::fromAddress(), self::fromName())
            ->subject('Új jelszó beállítása – ' . config('app.shop_name'))
            ->view('commerce-users::mail.password-reset', [
                'user' => $this->user,
                'resetUrl' => route('commerce-users.password.reset', ['token' => $this->token, 'email' => $this->user->email]),
                'expireMinutes' => (int) config('commerce-users.password_reset_expire_minutes', 60) ?: 60,
            ]);
    }
}
