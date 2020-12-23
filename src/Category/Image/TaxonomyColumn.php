<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Category\Image;

use Brianvarskonst\Nikas\TermMeta\CategoryImage as TermMetaManager;

class TaxonomyColumn
{
    public const KEY = 'image';

    public function __construct(
        private CategoryImageUrlProvider $imageProvider,
        private TermMetaManager $categoryImageTermMeta
    ) {}

    public function register($columns)
    {
        return array_merge(
            $columns,
            [
                self::KEY => __('Image', 'nikas'),
            ]
        );
    }

    public function render($columns, $column, $id)
    {
        if ($column === self::KEY) {
            $columns = '
                <span>
                    <img src="' . $this->imageProvider->provide($id, 'thumbnail') . '" alt="' . __('Thumbnail', 'nikas') . '" class="wp-post-image" />
                </span>
            ';
        }

        return $columns;
    }

    public function renderQuickEdit($columnName, $screen, $name)
    {
        if ($columnName === self::KEY) {
            echo $this->rendeInlineEdit();
        }
    }


    private function rendeInlineEdit(): string
    {
        ob_start(); ?>

        <fieldset>
            <div class="<?php echo self::KEY; ?> inline-edit-col">
                <label>
                    <span class="title">
                        <?php esc_html_e('Image', 'nikas'); ?>
                    </span>

                    <span class="thumbnail-image">
                        <img src="" alt="Thumbnail"/>
                    </span>

                    <span class="input-text-wrap">
                        <input type="text" name="zci_taxonomy_image" value="" class="tax_list" />
                    </span>

                    <span class="input-text-wrap">
                        <button class="z_upload_image_button button">
                            <?php esc_html_e('Upload/Add image', 'nikas'); ?>
                        </button>

                        <button class="z_remove_image_button button">
                            <?php esc_html_e('Remove image', 'categories-images'); ?>
                        </button>
                    </span>
                </label>
            </div>
        </fieldset>

        <?php return ob_get_clean();
    }
}
