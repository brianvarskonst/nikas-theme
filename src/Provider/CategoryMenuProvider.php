<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\Nikas\Category\Image\CategoryImageRenderer;
use Brianvarskonst\Nikas\Category\Menu\CategoryMenu;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\RegisteredOnly;

class CategoryMenuProvider extends RegisteredOnly
{

    public function register(Container $container): bool
    {
        $container->addService(
            CategoryMenu::class,
            static fn() => new CategoryMenu(
                $container->get(CategoryImageRenderer::class),
                get_categories(['hide_empty' => false])
            )
        );

        return true;
    }
}
