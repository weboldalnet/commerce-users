<?php

namespace Weboldalnet\CommerceUsers\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Weboldalnet\CommerceUsers\Models\CommerceUser;
use Weboldalnet\CommerceUsers\Services\ActivationService;

class ActivationMail extends Mailable
{
    use Queueable, SerializesModels, UsesShopSender;

    public function __construct(public CommerceUser $user, public string $activationUrl)
    {
    }

    /** Küldéskor / rendereléskor fut, így a tárgy az akkor érvényes nyelven készül. */
    public function build()
    {
        return $this->from(self::fromAddress(), self::fromName())
            ->subject(__('commerce-users::mail.activation.subject', ['shop' => config('app.shop_name')]))
            ->view('commerce-users::mail.activation', [
                'user' => $this->user,
                'activationUrl' => $this->activationUrl,
                'expireHours' => ActivationService::expireHours(),
            ]);
    }
}
