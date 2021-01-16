<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas;

use Brianvarskonst\Nikas\Provider\AssetProvider;
use Brianvarskonst\Nikas\Provider\BackwardCompatibilityProvider;
use Brianvarskonst\Nikas\Provider\CategoryImageProvider;
use Brianvarskonst\Nikas\Provider\CategoryMenuProvider;
use Brianvarskonst\Nikas\Provider\DisableCoreFunctionalityProvider;
use Brianvarskonst\Nikas\Provider\HelperProvider;
use Brianvarskonst\Nikas\Provider\NavigationProvider;
use Brianvarskonst\Nikas\Provider\PackageProvider;
use Brianvarskonst\Nikas\Provider\TextdomainProvider;
use Brianvarskonst\Nikas\Provider\ThemeProvider;
use Brianvarskonst\Nikas\Provider\ThemeSupportProvider;
use Brianvarskonst\Nikas\Provider\ThumbnailProvider;
use Inpsyde\App\App;
use Inpsyde\App\Container;

if (is_file(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}

App::new(new Container())->boot();

add_action(
    App::ACTION_ADD_PROVIDERS,
    static function (App $app) {
        $placeholder = get_template_directory_uri() . '/../resources/img/placeholder.png';

        $app->addProvider(new PackageProvider());
        $app->addProvider(new HelperProvider());
        $app->addProvider(new BackwardCompatibilityProvider());
        $app->addProvider(new ThemeProvider());
        $app->addProvider(new AssetProvider());
        $app->addProvider(new ThemeSupportProvider());
        $app->addProvider(new DisableCoreFunctionalityProvider());
        $app->addProvider(new TextdomainProvider());
        $app->addProvider(new NavigationProvider());
        $app->addProvider(new ThumbnailProvider());
        $app->addProvider(new CategoryImageProvider($placeholder));
        $app->addProvider(new CategoryMenuProvider());
    }
);