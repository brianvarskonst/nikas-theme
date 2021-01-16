<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\Nikas\ThemeProperties;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\RegisteredOnly;

class ThemeProvider extends RegisteredOnly
{

    public function register(Container $container): bool
    {
        $container->addService(
            ThemeProperties::class,
            static function (): ThemeProperties {
                return new ThemeProperties(
                    get_template_directory() . 'resources/languages'
                );
            }
        );

        return true;
    }
}
