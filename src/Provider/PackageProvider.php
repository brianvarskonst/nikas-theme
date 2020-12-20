<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\WordPress\Term\Meta\TermMeta;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\RegisteredOnly;

class PackageProvider extends RegisteredOnly
{

    public function register(Container $container): bool
    {
        $container->addService(
            TermMeta::class,
            static function (): TermMeta {
                return new TermMeta();
            }
        );

        return true;
    }
}