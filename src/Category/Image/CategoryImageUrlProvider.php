<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Category\Image;

use Brianvarskonst\WordPress\Term\Meta\TermMeta;

class CategoryImageUrlProvider
{
    private TermMeta $termMeta;

    private string $placeholderImage;

    public function __construct(
        TermMeta $termMeta,
        string $placeholderImage
    ) {

        $this->termMeta = $termMeta;
        $this->placeholderImage = $placeholderImage;
    }

    public function placeholder(): string
    {
        return $this->placeholderImage;
    }

    /**
     * @param int $id
     * @param string|null $size  small(thumbnail), medium, full
     *
     * @return false|mixed|string|void
     */
    public function provide(int $id, ?string $size = 'full')
    {
        return $this->taxonomyImageUrl($id, $size);
    }

    /**
     * @param int $id
     * @param string|null $size
     *
     * @return false|mixed|string|void
     */
    private function taxonomyImageUrl(int $id, ?string $size = 'full')
    {
        $imageUrl = $this->termMeta->get($id, 'category-image', true);;

        if (!empty($imageUrl)) {
            $attachmentId = $this->getAttachmentIdByUrl($imageUrl);

            if (!empty($attachmentId)) {
                $imageUrl = wp_get_attachment_image_src($attachmentId, $size);
                $imageUrl = $imageUrl[0];
            }
        }

        return $imageUrl ?: $this->placeholderImage;
    }

    /**
     * @param string $url
     *
     * @return int
     */
    private function getAttachmentIdByUrl(string $url): int
    {
        global $wpdb;

        $query = $wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE guid = %s",
            $url
        );

        $id = $wpdb->get_var($query);

        return (!empty($id)) ? $id : 0;
    }
}
