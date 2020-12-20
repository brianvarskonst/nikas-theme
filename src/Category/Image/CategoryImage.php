<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Category\Image;

class CategoryImage
{
    public function render($term_id = null, $size = 'full', $attr = null, $echo = true)
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
                $taxonomy_image = wp_get_attachment_image($attachment_id, $size, false, $attr);
            } else {
                $image_attr = '';

                if (is_array($attr)) {
                    if (!empty($attr['class'])) {
                        $image_attr .= ' class="' . $attr['class'] . '" ';
                    }

                    if (!empty($attr['alt'])) {
                        $image_attr .= ' alt="' . $attr['alt'] . '" ';
                    }

                    if (!empty($attr['width'])) {
                        $image_attr .= ' width="' . $attr['width'] . '" ';
                    }

                    if (!empty($attr['height'])) {
                        $image_attr .= ' height="' . $attr['height'] . '" ';
                    }

                    if (!empty($attr['title'])) {
                        $image_attr .= ' title="' . $attr['title'] . '" ';
                    }
                }

                $taxonomy_image = '<img src="' . $taxonomy_image_url . '" ' . $image_attr . '/>';
            }
        } else {
            $taxonomy_image = '';
        }

        if ($echo) {
            echo $taxonomy_image;
        } else {
            return $taxonomy_image;
        }
    }

    private function getAttachmentIdByUrl($imageSrc)
    {
        global $wpdb;

        $query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $imageSrc);
        $id = $wpdb->get_var($query);

        return (!empty($id)) ? $id : null;
    }
}
