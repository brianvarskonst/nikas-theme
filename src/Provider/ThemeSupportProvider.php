<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\Nikas\Theme\Feature\FeatureConfig;
use Brianvarskonst\Nikas\Theme\Feature\FeatureRegistry;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\EarlyBooted;

class ThemeSupportProvider extends EarlyBooted
{
    public function register(Container $container): bool
    {
        $container->addService(
            FeatureRegistry::class,
            static function (): FeatureRegistry {
                return FeatureRegistry::fromArray(
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
        $features = $container->get(FeatureRegistry::class);

        add_action(
            'after_setup_theme',
            static function () use ($features) {
                foreach ($features as $feature) {
                    if (empty($feature->args())) {
                        add_theme_support($feature->title());

                        continue;
                    }

                    add_theme_support(
                        $feature->title(),
                        $feature->args()
                    );
                }
            }
        );

        return true;
    }
}
