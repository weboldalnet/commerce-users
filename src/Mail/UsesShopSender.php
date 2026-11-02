<?php

namespace Weboldalnet\CommerceUsers\Mail;

/** A bolt saját feladója, mint a webshop és a core leveleinél (app.shop_email / shop_name). */
trait UsesShopSender
{
    public static function fromAddress(): ?string
    {
        return config('app.shop_email') ?: config('mail.from.address');
    }

    public static function fromName(): ?string
    {
        return config('app.shop_name') ?: config('mail.from.name');
    }
}
