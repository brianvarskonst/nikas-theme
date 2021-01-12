<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Provider;

use Brianvarskonst\Nikas\Asset\AdminConfigProcessor;
use Brianvarskonst\Nikas\Asset\ConfigProcessorInterface;
use Brianvarskonst\Nikas\Category\Image\CategoryImageRenderer;
use Brianvarskonst\Nikas\Category\Image\CategoryImageUrlProvider;
use Brianvarskonst\Nikas\Category\Image\TaxonomyColumn;
use Brianvarskonst\Nikas\Category\Image\TaxonomyField;
use Brianvarskonst\Nikas\Helper\PageChecker;
use Brianvarskonst\Nikas\TermMeta\CategoryImage;
use Brianvarskonst\WordPress\Term\Meta\TermMeta;
use Inpsyde\App\Container;
use Inpsyde\App\Provider\EarlyBooted;
use Inpsyde\Assets\Asset;

class CategoryImageProvider extends EarlyBooted
{
    public const SUPPORTED_PAGES = [
        'edit-tags',
        'term',
    ];

    public const SUPPORTED_TAXONOMY = 'category';

    public function __construct(private string $placeholder)
    {
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
            CategoryImage::class,
            static fn() =>
                new CategoryImage(
                    $container->get(TermMeta::class)
                )
        );

        $container->addService(
            CategoryImageUrlProvider::class,
            static function (Container $container) use ($placeholderImage): CategoryImageUrlProvider {
                return new CategoryImageUrlProvider(
                    $container->get(CategoryImage::class),
                    $placeholderImage
                );
            }
        );

        $container->addService(
            TaxonomyField::class,
            static fn (Container $container): TaxonomyField =>
                new TaxonomyField(
                    $container->get(CategoryImageUrlProvider::class),
                    $container->get(CategoryImage::class)
                )
        );

        $container->addService(
            TaxonomyColumn::class,
            static fn(Container $container): TaxonomyColumn =>
                new TaxonomyColumn(
                    $container->get(CategoryImageUrlProvider::class),
                    $container->get(CategoryImage::class)
                )
        );

        $container->addService(
            AdminConfigProcessor::class,
            static function (
                Container $container
            ) use (
                $placeholderImage,
                $supportedPages
            ): ConfigProcessorInterface {
                $pageChecker = $container->get(PageChecker::class);

                return new AdminConfigProcessor(
                    [
                        'version' => get_bloginfo('version'),
                        'placeholder' => $placeholderImage,
                        'location' => Asset::BACKEND,
                        'canEnqueue' =>
                            static function () use ($pageChecker, $supportedPages): bool {
                                return $pageChecker->checkPage($supportedPages);
                            },
                    ]
                );
            }
        );

        $container->addService(
            CategoryImageRenderer::class,
            static fn(Container $container) =>
                new CategoryImageRenderer(
                    $container->get(CategoryImage::class),
                    $placeholderImage
                )
        );

        $container->extendService(
            'nikas.config.processors',
            static fn (array $service, Container $container): array =>
                [
                    ...$service,
                    $container->get(AdminConfigProcessor::class),
                ]
        );

        return true;
    }

    public function boot(Container $container): bool
    {
        $taxonomyColumn = $container->get(TaxonomyColumn::class);
        $taxonomyField = $container->get(TaxonomyField::class);

        $taxonomies = get_taxonomies();

        foreach ($taxonomies as $taxonomy) {
            if ($taxonomy !== self::SUPPORTED_TAXONOMY) {
                continue;
            }

            add_action("{$taxonomy}_add_form_fields", [$taxonomyField, 'add']);
            add_action("{$taxonomy}_edit_form_fields", [$taxonomyField, 'edit']);

            add_filter("manage_edit-{$taxonomy}_columns", [$taxonomyColumn, 'register']);
            add_filter("manage_{$taxonomy}_custom_column", [$taxonomyColumn, 'render'], 10, 3);

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

        add_action('edit_term', [$taxonomyField, 'save']);
        add_action('create_term', [$taxonomyField, 'save']);

        return true;
    }
}
