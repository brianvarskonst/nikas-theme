<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Category\Image;

class TaxonomyField
{
    private string $placeholderImage;

    public function __construct(string $placeholderImage)
    {
        $this->placeholderImage = $placeholderImage;
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
        if (get_bloginfo('version') >= 3.5)
            wp_enqueue_media();
        else {
            wp_enqueue_style('thickbox');
            wp_enqueue_script('thickbox');
        }

        if ($this->taxonomyImageUrl($taxonomy->term_id, null, true) === $this->placeholderImage) {
            $image_url = "";
        } else {
            $image_url = $this->taxonomyImageUrl($taxonomy->term_id, null, true);
        }

        echo '
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="zci_taxonomy_image">' . __('Image', 'categories-images') . '</label>
            </th>
            <td>
                <img class="zci-taxonomy-image" src="' . $this->taxonomyImageUrl($taxonomy->term_id, 'medium', true) . '"/>
                
                <br/>
                
                <input type="text" name="zci_taxonomy_image" id="zci_taxonomy_image" value="' . $image_url . '" />
                
                <br />
                
                <button class="z_upload_image_button button">' . __('Upload/Add image', 'categories-images') . '</button>
                
                <button class="z_remove_image_button button">' . __('Remove image', 'categories-images') . '</button>
            </td>
        </tr>';
    }

    private  function taxonomyImageUrl($term_id = NULL, $size = 'full', $return_placeholder = false)
    {
        if (!$term_id) {
            if (is_category()) {
                $term_id = get_query_var('cat');
            } elseif (is_tag()) {
                $term_id = get_query_var('tag_id');
            } elseif (is_tax()) {
                $current_term = get_term_by(
                    'slug',
                    get_query_var('term'),
                    get_query_var('taxonomy')
                );

                $term_id = $current_term->term_id;
            }
        }

        $taxonomy_image_url = get_option("taxonomy_image{$term_id}");

        if (!empty($taxonomy_image_url)) {
            $attachment_id = $this->getAttachmentIdByUrl($taxonomy_image_url);

            if (!empty($attachment_id)) {
                $taxonomy_image_url = wp_get_attachment_image_src($attachment_id, $size);
                $taxonomy_image_url = $taxonomy_image_url[0];
            }
        }

        return $taxonomy_image_url ?: $this->placeholderImage;
    }

    private function getAttachmentIdByUrl($image_src)
    {
        global $wpdb;

        $query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $image_src);
        $id = $wpdb->get_var($query);

        return (!empty($id)) ? $id : null;
    }
}