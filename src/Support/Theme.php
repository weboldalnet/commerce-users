<?php

namespace Weboldalnet\CommerceUsers\Support;

/** A bolt arculati színe a levelek gombjához (a core ContentHelper::THEME-ből, ha van). */
class Theme
{
    public static function buttonColor(): string
    {
        $helper = '\App\Helpers\ContentHelper';
        if (class_exists($helper) && defined($helper . '::THEME')) {
            $theme = constant($helper . '::THEME');
            if (!empty($theme['theme_color'])) {
                return $theme['theme_color'];
            }
        }

        return '#25204F';
    }
}
