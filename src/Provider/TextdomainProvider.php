<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\Nikas\ThemeProperties;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\BootedOnly;

class TextdomainProvider extends BootedOnly
{
    public function boot(Container $container): bool
    {
        $themeProperties = $container->get(ThemeProperties::class);

        load_theme_textdomain(
            $themeProperties->id(),
            $themeProperties->languagesPath()
        );

        return true;
    }
}
