<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Inpsyde\App\Container;
use Inpsyde\App\Provider\BootedOnly;
use Inpsyde\Assets\Asset;
use Inpsyde\Assets\AssetManager;
use Inpsyde\Assets\Loader\WebpackManifestLoader;

class AssetProvider extends BootedOnly
{

    public function boot(Container $container): bool
    {
        add_action(
            AssetManager::ACTION_SETUP,
            static function (AssetManager $assetManager) {
                $webpackManifestLoader = new WebpackManifestLoader();

                $assets = $webpackManifestLoader
                    ->withDirectoryUrl(get_template_directory_uri() . '/assets/')
                    ->load(get_template_directory() . '/assets/manifest.json');

                $assets = array_map(
                    static function (Asset $asset): Asset {
                        return $asset->forLocation(Asset::FRONTEND);
                    },
                    $assets
                );

                $assetManager->register(...$assets);
            }
        );

        return true;
    }
}
