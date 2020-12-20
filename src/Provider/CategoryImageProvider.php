<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\Nikas\Asset\CategoryImageConfigProcessor;
use Brianvarskonst\Nikas\Asset\ConfigProcessorInterface;
use Brianvarskonst\Nikas\Category\Image\CategoryImagePageChecker;
use Brianvarskonst\Nikas\Category\Image\CategoryImageUrlProvider;
use Brianvarskonst\Nikas\Category\Image\TaxonomyColumn;
use Brianvarskonst\Nikas\Category\Image\TaxonomyField;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\EarlyBooted;

class CategoryImageProvider extends EarlyBooted
{
    public function register(Container $container): bool
    {
        $placeholderImage = get_template_directory_uri() . '/resources/img/placeholder.png';

        $container->addService(
            CategoryImagePageChecker::class,
            static function(Container $container): CategoryImagePageChecker {
                return new CategoryImagePageChecker();
            }
        );

        $container->addService(
            CategoryImageUrlProvider::class,
            static function(Container $container) use ($placeholderImage): CategoryImageUrlProvider {
                return new CategoryImageUrlProvider($placeholderImage);
            }
        );

        $container->addService(
            TaxonomyField::class,
            static function(Container $container): TaxonomyField {
                return new TaxonomyField(
                    $container->get(CategoryImageUrlProvider::class)
                );
            }
        );

        $container->addService(
            TaxonomyColumn::class,
            static function(Container $container): TaxonomyColumn {
                return new TaxonomyColumn(
                    $container->get(CategoryImageUrlProvider::class)
                );
            }
        );

        $container->addService(
            CategoryImageConfigProcessor::class,
            static function (Container $container) use ($placeholderImage): ConfigProcessorInterface {
                $pageChecker = $container->get(CategoryImagePageChecker::class);

                return new CategoryImageConfigProcessor(
                    [
                        'version' => get_bloginfo('version'),
                        'placeholder' => $placeholderImage,
                        'canEnqueue' => static function() use ($pageChecker) {
                            return $pageChecker->checkPage();
                        },
                    ]
                );
            }
        );

        $container->extendService(
            'nikas.config.processors',
            static function ($service, Container $container): array {
                return [
                    ...$service,
                    $container->get(CategoryImageConfigProcessor::class)
                ];
            }
        );

        return true;
    }

    public function boot(Container $container): bool
    {
        $taxonomyColumn = $container->get(TaxonomyColumn::class);

        $taxonomies = get_taxonomies();

        foreach ($taxonomies as $taxonomy) {
            if ($taxonomy !== 'category') {
                continue;
            }

            $taxonomyField = $container->get(TaxonomyField::class);

            add_action("{$taxonomy}_add_form_fields", [$taxonomyField, 'add']);
            add_action("{$taxonomy}_edit_form_fields", [$taxonomyField, 'edit']);

            add_filter("manage_edit-{$taxonomy}_columns", [$taxonomyColumn, 'register']);
            add_filter("manage_{$taxonomy}_custom_column", [ $taxonomyColumn, 'render'], 10, 3);

            // If tax is deleted
            add_action("delete_{$taxonomy}", function ($ttId) {
                delete_option("taxonomy_image{$ttId}");
            });
        }

        $pageChecker = $container->get(CategoryImagePageChecker::class);

        // Register styles and scripts
        if ($pageChecker->checkPage()) {
            add_action(
                'quick_edit_custom_box',
                [$taxonomyColumn, 'renderQuickEdit'],
                10,
                3
            );
        }

        add_action('edit_term', [$taxonomyColumn, 'save']);
        add_action('create_term', [$taxonomyColumn, 'save']);

        return true;
    }
}