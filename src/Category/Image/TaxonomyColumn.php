<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Category\Image;

use Brianvarskonst\Nikas\TermMeta\CategoryImage;
use Brianvarskonst\Nikas\TermMeta\CategoryImage as TermMetaManager;

class TaxonomyColumn
{
    public const KEY = 'image';

    public function __construct(
        private CategoryImageUrlProvider $imageProvider,
        private CategoryImage $categoryImage
    ) {
    }

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
            ob_start();
            $categoryImage = $this->categoryImage->get($id, true);
            $attachmentId = ($categoryImage !== null) ? $categoryImage->attachmentId() : null;
            ?>

            <span>
                <img src="<?php echo $this->imageProvider->provide($id, 'thumbnail') ?>"
                     alt="<?php esc_attr_e('Thumbnail', 'nikas') ?>"
                     class="wp-post-image"
                />

                <input
                    type="hidden"
                    name="category-image-attachment-id"
                    class="CategoryImageAttachmentIdTaxonomyListing"
                    <?php echo $attachmentId !== null ? 'value="' . esc_attr($attachmentId) . '"' : ''; ?>
                />
            </span>

            <?php $columns = (string) ob_get_clean();
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
                        <input type="text" name="category-image" value="" class="tax_list" />
                    </span>

                    <input type="hidden" name="category-image-attachment-id" id="category-image-attachment-id" class="taxListAttachmentId"/>
                    <br />

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
