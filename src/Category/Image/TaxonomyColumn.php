<?php


namespace Brianvarskonst\Nikas\Category\Image;


class TaxonomyColumn
{
    private string $placeholderImage;

    public function __construct(string $placeholderImage)
    {
        $this->placeholderImage = $placeholderImage;
    }

    public function register($columns)
    {
        $columns['thumb'] = __('Image', 'nikas');

        return $columns;
    }

    public function render($columns, $column, $id)
    {
        if ($column === 'thumb') {
            $columns = '
                <span>
                    <img src="' . $this->taxonomyImageUrl($id, 'thumbnail', true) . '" alt="' . __('Thumbnail', 'nikas') . '" class="wp-post-image" />
                </span>
            ';
        }

        return $columns;
    }

    public function renderQuickEdit($column_name, $screen, $name)
    {
        if ($column_name === 'thumb')
            echo '
                <fieldset>
                    <div class="thumb inline-edit-col">
                        <label>
                            <span class="title">
                                <img src="" alt="Thumbnail"/>
                            </span>
                            
                            <span class="input-text-wrap">
                                <input type="text" name="zci_taxonomy_image" value="" class="tax_list" />
                            </span>
                            
                            <span class="input-text-wrap">
                                <button class="z_upload_image_button button">' . __('Upload/Add image', 'categories-images') . '</button>
                                <button class="z_remove_image_button button">' . __('Remove image', 'categories-images') . '</button>
                            </span>
                        </label>
                    </div>
                </fieldset>
            ';
    }

    public function save($term_id)
    {
        if (isset($_POST['zci_taxonomy_image'])) {
            update_option('z_taxonomy_image' . $term_id, $_POST['zci_taxonomy_image'], false);
        }
    }

    private  function taxonomyImageUrl($term_id = NULL, $size = 'full', $return_placeholder = false)
    {
        if (!$term_id) {
            if (is_category()) {
                $term_id = get_query_var('cat');
            } elseif (is_tag()) {
                $term_id = get_query_var('tag_id');
            } elseif (is_tax()) {
                $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
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