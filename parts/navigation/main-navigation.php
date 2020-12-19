<?php

declare(strict_types=1);

use Brianvarskonst\Nikas\Navigation\Header;
use Brianvarskonst\Nikas\Theme\Navigation\MainMenuWalker;
use Inpsyde\App\App;

$mainMenu = App::make(Header::class);

if (!has_nav_menu($mainMenu->id())) {
    return;
}

wp_nav_menu(
    [
        'theme_location' => $mainMenu->id(),
        'container'  => false,
        'items_wrap' => '<ul id="" class="%2$s navbar-nav ml-auto mt-1">%3$s</ul>',
        'depth' => 0,
        'fallback_cb' => 'wp_page_menu',
        'walker' => new MainMenuWalker(),
    ]
);
