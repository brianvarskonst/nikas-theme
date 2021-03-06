<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Category\Menu;

use Brianvarskonst\Nikas\Category\Image\CategoryImageRenderer;

class CategoryMenu
{
    public function __construct(
        private CategoryImageRenderer $categoryImageRenderer,
        /**
         * @var \WP_Term[]
         */
        private array $categories = [],
    ) {
    }

    public function render(): string
    {
        ob_start();

        foreach ($this->categories as $category) { ?>
            <div class="CategoryMenuContainerItem">
                <a href="<?php echo get_term_link($category->term_id) ?>">
                    <div class="CategoryMenuContainerItemInner">
                       <?php echo wp_kses_post(
                           $this->categoryImageRenderer->render(
                               (int) $category->term_id,
                               'thumbnail'
                           )
                       ); ?>
                    </div>

                    <span class="CategoryMenuItemTitle">
                        <?php esc_html_e($category->name); ?>
                    </span>
                </a>
            </div>

            <?php
        }

        return ob_get_clean();
    }
}