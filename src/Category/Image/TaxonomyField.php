<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Category\Image;

use Brianvarskonst\Nikas\Entity\CategoryImage as Entity;
use Brianvarskonst\Nikas\TermMeta\CategoryImage as TermMetaManager;

class TaxonomyField
{
    public function __construct(
        private CategoryImageUrlProvider $imageProvider,
        private TermMetaManager $categoryImageTermMeta
    ) {
    }

    public function add()
    {
        wp_enqueue_media();

        echo '<div class="form-field">
            <label for="zci_taxonomy_image">' . __('Image', 'nikas') . '</label>
            <input type="text" name="zci_taxonomy_image" id="zci_taxonomy_image" value="" />
            
            <br/>
            
            <button class="z_upload_image_button button">' . __('Upload/Add image', 'nikas') . '</button>
        </div>';
    }

    public function edit($taxonomy)
    {
        wp_enqueue_media();

        $termId = (int) $taxonomy->term_id;

        $categoryImage = $this->imageProvider->provide($termId, null);
        $categoryImageMedium = $this->imageProvider->provide($termId, 'medium');

        $imageUrl = $categoryImage === $this->imageProvider->placeholder()
            ? '' : $categoryImage;

        $entity = $this->categoryImageTermMeta->get($termId);

        echo $this->renderEditView($categoryImageMedium, $imageUrl, (int) $entity?->attachmentId());
    }

    private function renderEditView(string $mediumImage, string $url, int $id): string
    {
        ob_start(); ?>

        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="zci_taxonomy_image">
                    <?php esc_html_e('Image', 'nikas'); ?>
                </label>
            </th>

            <td>
                <img class="zci-taxonomy-image" src="<?php echo esc_attr($mediumImage); ?>" />

                <br/>

                <input type="text" name="category-image" id="category-image"
                    <?php echo !empty($url) ? ' value="' .  esc_attr($url) . '"' : ''; ?> />

                <input type="hidden" name="category-image-attachment-id" id="category-image-attachment-id"
                    <?php echo $id > 0 ? 'value="' . esc_attr($id) . '"' : ''; ?> />

                <br />

                <button class="z_upload_image_button button">
                    <?php esc_html_e('Upload/Add image', 'nikas'); ?>
                </button>

                <button class="z_remove_image_button button">
                    <?php esc_html_e('Remove image', 'nikas'); ?>
                </button>
            </td>
        </tr>

        <?php return ob_get_clean();
    }

    public function save($termId)
    {
        $categoryImageAttachmentId = filter_input(
            INPUT_POST,
            'category-image-attachment-id',
            FILTER_SANITIZE_NUMBER_INT
        );

        if ((int) $categoryImageAttachmentId > 0) {
            $this->categoryImageTermMeta->create(
                Entity::new(
                    (int) $categoryImageAttachmentId,
                    (int) $termId
                )
            );
        }
    }
}
