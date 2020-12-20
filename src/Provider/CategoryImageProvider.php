<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\Nikas\Asset\CategoryImageConfigProcessor;
use Brianvarskonst\Nikas\Asset\ConfigProcessorInterface;
use Brianvarskonst\Nikas\Category\Image\CategoryImageUrlProvider;
use Brianvarskonst\Nikas\Category\Image\TaxonomyColumn;
use Brianvarskonst\Nikas\Category\Image\TaxonomyField;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\EarlyBooted;

class CategoryImageProvider extends EarlyBooted
{
    public const excludedTaxonomies = [
        'nav_menu',
        'link_category',
        'post_format'
    ];

    public const supportedPages = [
        'edit-tags',
        'term'
    ];

    public const LOCALIZE_KEY = 'nikasCategoryImage';

    public function register(Container $container): bool
    {
        $placeholderImage = get_template_directory_uri() . '/resources/img/placeholder.png';

        $container->addService(
            CategoryImageUrlProvider::class,
            static function(Container $container) use ($placeholderImage): CategoryImageUrlProvider {
                return new CategoryImageUrlProvider($placeholderImage);
            }
        );

        $container->addService(
            TaxonomyField::class,
            static function(Container $container) use ($placeholderImage): TaxonomyField {
                return new TaxonomyField(
                    $container->get(CategoryImageUrlProvider::class)
                );
            }
        );

        $container->addService(
            TaxonomyColumn::class,
            static function(Container $container) use ($placeholderImage): TaxonomyColumn {
                return new TaxonomyColumn(
                    $container->get(CategoryImageUrlProvider::class)
                );
            }
        );

        $container->addService(
            CategoryImageConfigProcessor::class,
            static function () use ($placeholderImage): ConfigProcessorInterface {
                return new CategoryImageConfigProcessor(
                    self::LOCALIZE_KEY,
                    [
                        'version' => get_bloginfo('version'),
                        'placeholder' => $placeholderImage,
                        'canEnqueue' => static function (): bool {
                            $filterPageName = basename(
                                filter_input(INPUT_SERVER, 'SCRIPT_NAME', FILTER_SANITIZE_STRING),
                                '.php'
                            );

                            return in_array($filterPageName, ['edit-tags', 'term'], true);
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

        add_action(
            'admin_init',
            function () use ($container, $taxonomyColumn) {
                $taxonomies = get_taxonomies();

                if (is_array($taxonomies)) {
                    foreach ($taxonomies as $taxonomy) {
                        if (in_array($taxonomy, self::excludedTaxonomies, true)) {
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
                }

                $filterPageName = basename(
                    filter_input(INPUT_SERVER, 'SCRIPT_NAME', FILTER_SANITIZE_STRING),
                    '.php'
                );

                // Register styles and scripts
                if (in_array($filterPageName, self::supportedPages, true)) {
                    add_action('quick_edit_custom_box', [$taxonomyColumn, 'renderQuickEdit'], 10, 3);
                }
            }
        );

        // save our taxonomy image while edit or create term
        add_action('edit_term', [$taxonomyColumn, 'save']);
        add_action('create_term', [$taxonomyColumn, 'save']);

        return true;
    }
}