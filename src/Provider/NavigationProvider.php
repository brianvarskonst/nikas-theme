<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\Nikas\Navigation\Header;
use Brianvarskonst\Nikas\Navigation\NavigationInterface;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\EarlyBooted;

class NavigationProvider extends EarlyBooted
{
    public function register(Container $container): bool
    {
        $container->addService(
            Header::class,
            static function(): NavigationInterface {
                return new Header();
            }
        );

        return true;
    }

    public function boot(Container $container): bool
    {
        $navigations = [
            $container->get(Header::class)
        ];

        foreach ($navigations as $navigation) {
            register_nav_menus(
                [
                    $navigation->id() => $navigation->name(),
                ]
            );
        }

        return true;
    }
}
