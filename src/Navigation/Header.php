<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Navigation;

class Header implements NavigationInterface
{
    public const ID = 'header';

    public function id(): string
    {
        return self::ID;
    }

    public function name(): string
    {
        return esc_html__(
            'Main Menu',
            'nikas'
        );
    }
}
