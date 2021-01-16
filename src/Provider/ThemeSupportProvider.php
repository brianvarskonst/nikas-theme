<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\Nikas\Theme\Feature\FeatureConfig;
use Brianvarskonst\Nikas\Theme\Feature\FeatureCollection;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\EarlyBooted;

class ThemeSupportProvider extends EarlyBooted
{
    public function register(Container $container): bool
    {
        $container->addService(
            FeatureCollection::class,
            static function (): FeatureCollection {
                return FeatureCollection::fromArray(
                    FeatureConfig::LIST
                );
            }
        );

        return true;
    }

    /**
     * @param Container $container
     *
     * @return bool
     *
     * @throws \Throwable
     *
     * phpcs:disable Generic.Metrics.NestingLevel.TooHigh
     * phpcs:disable Inpsyde.CodeQuality.NestingLevel.High
     */
    public function boot(Container $container): bool
    {
        $features = $container->get(FeatureCollection::class);

        add_action(
            'after_setup_theme',
            static function () use ($features) {
                foreach ($features as $feature) {
                    add_theme_support($feature->title(), $feature->args());
                }
            }
        );

        return true;
    }
}
