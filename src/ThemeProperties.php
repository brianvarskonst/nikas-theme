<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas;

class ThemeProperties
{
    public const ID = 'nikas';

    private string $languagePath;

    public function __construct(string $languagePath)
    {
        $this->languagePath = $languagePath;
    }

    public function id(): string
    {
        return self::ID;
    }

    public function languagesPath(): string
    {
        return $this->languagePath;
    }
}
