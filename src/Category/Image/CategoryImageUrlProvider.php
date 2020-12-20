<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Category\Image;

class CategoryImageUrlProvider
{
    private string $placeholderImage;

    public function __construct(string $placeholderImage)
    {
        $this->placeholderImage = $placeholderImage;
    }

    public function placeholder(): string
    {
        return $this->placeholderImage;
    }

    public function provide($id = null, $size = 'full')
    {
        return $this->taxonomyImageUrl($id, $size);
    }

    private function taxonomyImageUrl($term_id = null, $size = 'full')
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
