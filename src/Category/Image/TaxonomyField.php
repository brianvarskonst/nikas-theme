<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Category\Image;

class TaxonomyField
{
    private CategoryImageUrlProvider $imageProvider;

    public function __construct(CategoryImageUrlProvider $imageProvider)
    {
        $this->imageProvider = $imageProvider;
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

        $taxonomyImage = $this->imageProvider->provide($taxonomy->term_id, null);

        if ($taxonomyImage === $this->imageProvider->placeholder()) {
            $image_url = "";
        } else {
            $image_url = $taxonomyImage;
        }

        echo '
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="zci_taxonomy_image">' . __('Image', 'categories-images') . '</label>
            </th>
            <td>
                <img class="zci-taxonomy-image" src="' . $this->imageProvider->provide($taxonomy->term_id, 'medium') . '"/>
                
                <br/>
                
                <input type="text" name="zci_taxonomy_image" id="zci_taxonomy_image" value="' . $image_url . '" />
                
                <br />
                
                <button class="z_upload_image_button button">' . __('Upload/Add image', 'categories-images') . '</button>
                
                <button class="z_remove_image_button button">' . __('Remove image', 'categories-images') . '</button>
            </td>
        </tr>';
    }
}
