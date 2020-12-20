<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\Nikas\Asset\CategoryImageConfigProcessor;
use Brianvarskonst\Nikas\Asset\ConfigProcessorInterface;
use Brianvarskonst\Nikas\Category\Image\CategoryImageUrlProvider;
use Brianvarskonst\Nikas\Category\Image\TaxonomyColumn;
use Brianvarskonst\Nikas\Category\Image\TaxonomyField;
use Brianvarskonst\Nikas\Helper\PageChecker;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\EarlyBooted;

class CategoryImageProvider extends EarlyBooted
{
    public const SUPPORTED_PAGES = [
        'edit-tags',
        'term',
    ];

    public const SUPPORTED_TAXONOMY = 'category';

    private string $placeholder;

    public function __construct(string $placeholder)
    {
        $this->placeholder = $placeholder;
    }

    /**
     * @param Container $container
     * @return bool
     *
     * @throws \Throwable
     *
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    public function register(Container $container): bool
    {
        $placeholderImage = $this->placeholder;
        $supportedPages = self::SUPPORTED_PAGES;

        $container->addService(
            CategoryImageUrlProvider::class,
            static function (Container $container) use ($placeholderImage): CategoryImageUrlProvider {
                return new CategoryImageUrlProvider($placeholderImage);
            }
        );

        $container->addService(
            TaxonomyField::class,
            static function (Container $container): TaxonomyField {
                return new TaxonomyField(
                    $container->get(CategoryImageUrlProvider::class)
                );
            }
        );

        $container->addService(
            TaxonomyColumn::class,
            static function (Container $container): TaxonomyColumn {
                return new TaxonomyColumn(
                    $container->get(CategoryImageUrlProvider::class)
                );
            }
        );

        $container->addService(
            CategoryImageConfigProcessor::class,
            static function (
                Container $container
            ) use (
                $placeholderImage,
                $supportedPages
            ): ConfigProcessorInterface {
                $pageChecker = $container->get(PageChecker::class);

                return new CategoryImageConfigProcessor(
                    [
                        'version' => get_bloginfo('version'),
                        'placeholder' => $placeholderImage,
                        'canEnqueue' =>
                            static function () use ($pageChecker, $supportedPages): bool {
                                return $pageChecker->checkPage($supportedPages);
                            },
                    ]
                );
            }
        );

        $container->extendService(
            'nikas.config.processors',
            static function (array $service, Container $container): array {
                return [
                    ...$service,
                    $container->get(CategoryImageConfigProcessor::class),
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
            if ($taxonomy !== self::SUPPORTED_TAXONOMY) {
                continue;
            }

            $taxonomyField = $container->get(TaxonomyField::class);

            add_action("{$taxonomy}_add_form_fields", [$taxonomyField, 'add']);
            add_action("{$taxonomy}_edit_form_fields", [$taxonomyField, 'edit']);

            add_filter("manage_edit-{$taxonomy}_columns", [$taxonomyColumn, 'register']);
            add_filter("manage_{$taxonomy}_custom_column", [ $taxonomyColumn, 'render'], 10, 3);

            // If tax is deleted
            add_action("delete_{$taxonomy}", static function ($ttId): void {
                delete_option("taxonomy_image{$ttId}");
            });
        }

        $pageChecker = $container->get(PageChecker::class);

        // Register styles and scripts
        if ($pageChecker->checkPage(self::SUPPORTED_PAGES)) {
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
