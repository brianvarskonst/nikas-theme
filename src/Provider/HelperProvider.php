<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\Nikas\Helper\Icon;
use Brianvarskonst\Nikas\Helper\PageChecker;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\RegisteredOnly;

class HelperProvider extends RegisteredOnly
{

    public function register(Container $container): bool
    {
        $container->addService(
            PageChecker::class,
            static function (): PageChecker {
                return new PageChecker();
            }
        );

        $container->addService(
            Icon::class,
            static fn(): Icon => new Icon(
                $container->config()->locations()->themesDir('nikas') . '/assets/img',
                [
                    'icon/search',
                    'icon/more'
                ]
            )
        );

        return true;
    }
}
