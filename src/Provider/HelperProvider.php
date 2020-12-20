<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\Nikas\Helper\PageChecker;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\RegisteredOnly;

class HelperProvider extends RegisteredOnly
{

    public function register(Container $container): bool
    {
        $container->addService(
            PageChecker::class,
            static function(): PageChecker {
                return new PageChecker();
            }
        );

        return true;
    }
}