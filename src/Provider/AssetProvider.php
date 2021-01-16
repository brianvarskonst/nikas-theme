<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\Nikas\Asset\AdminConfigProcessor;
use Brianvarskonst\Nikas\Asset\ConfigProcessorInterface;
use Brianvarskonst\Nikas\Asset\GlobalConfigProcessor;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\Booted;
use Inpsyde\Assets\Asset;
use Inpsyde\Assets\AssetManager;
use Inpsyde\Assets\Loader\WebpackManifestLoader;

class AssetProvider extends Booted
{

    public function register(Container $container): bool
    {
        $container->addService(
            GlobalConfigProcessor::class,
            static function (): ConfigProcessorInterface {
                return new GlobalConfigProcessor();
            }
        );

        $container->addService(
            'nikas.config.processors',
            static fn ($container): array => [
                $container->get(GlobalConfigProcessor::class),
            ]
        );

        return true;
    }

    public function boot(Container $container): bool
    {
        $configLoaders = $container->get('nikas.config.processors');

        // phpcs:disable Inpsyde.CodeQuality.NestingLevel.High
        add_action(
            AssetManager::ACTION_SETUP,
            static function (AssetManager $assetManager) use ($configLoaders) {
                $webpackManifestLoader = new WebpackManifestLoader();

                $assets = $webpackManifestLoader
                    ->withDirectoryUrl(get_template_directory_uri() . '/assets/')
                    ->load(get_template_directory() . '/assets/manifest.json');

                $assets = array_map(
                    static function (Asset $asset) use ($configLoaders): Asset {
                        foreach ($configLoaders as $configLoader) {
                            if ($configLoader->accepts($asset)) {
                                $asset = $configLoader->process($asset);
                            }
                        }

                        return $asset;
                    },
                    $assets
                );
                $assetManager->register(...$assets);
            }
        );

        return true;
    }
}
