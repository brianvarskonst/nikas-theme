<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Category\Image;

use Brianvarskonst\WordPress\Term\Meta\TermMeta;

class TaxonomyField
{
    private CategoryImageUrlProvider $imageProvider;

    private TermMeta $termMeta;

    public function __construct(
        CategoryImageUrlProvider $imageProvider,
        TermMeta $termMeta
    ) {
        $this->imageProvider = $imageProvider;
        $this->termMeta = $termMeta;
    }

    public function add()
    {
        if (get_bloginfo('version') >= 3.5) {
            wp_enqueue_media();
        } else {
            wp_enqueue_style('thickbox');
            wp_enqueue_script('thickbox');
        }

        echo '<div class="form-field">
            <label for="zci_taxonomy_image">' . __('Image', 'nikas') . '</label>
            <input type="text" name="zci_taxonomy_image" id="zci_taxonomy_image" value="" />
            
            <br/>
            
            <button class="z_upload_image_button button">' . __('Upload/Add image', 'nikas') . '</button>
        </div>';
    }

    public function edit($taxonomy)
    {
        if (get_bloginfo('version') >= 3.5) {
            wp_enqueue_media();
        } else {
            wp_enqueue_style('thickbox');
            wp_enqueue_script('thickbox');
        }

        $termId = (int) $taxonomy->term_id;

        $categoryImage = $this->imageProvider->provide($termId, null);

        $categoryImageMedium = $this->imageProvider->provide($termId, 'medium');

        $imageUrl =  $categoryImage === $this->imageProvider->placeholder()
            ? "" : $categoryImage;

        echo $this->renderEditView($categoryImageMedium, $imageUrl);
    }

    private function renderEditView(string $mediumImage, string $url): string
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
                    <?php echo !empty($url) ? ' value="' .  esc_attr($url) . '"' : ""; ?> />

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

    public function save($term_id)
    {
        $categoryImage = filter_input(
            INPUT_POST,
            'category-image'
        );

        if (!empty($categoryImage)) {
            $this->termMeta->create($term_id, 'category-image', $categoryImage);
        }
    }
}
