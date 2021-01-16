<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Category\Image;

use Brianvarskonst\Nikas\Entity\CategoryImageAttachment;
use Brianvarskonst\Nikas\TermMeta\CategoryImage;

class CategoryImageRenderer
{
    public function __construct(
        private CategoryImage $categoryImageTermMeta,
        private string $placeholderImage
    )
    {}

    public function render(int $id, string $size, $attr = null): string
    {
        $categoryImage = $this->categoryImageTermMeta->get($id);
        $placeholderImage = '<img src="' . $this->placeholderImage . '" loading="auto" />';

        if ($categoryImage === null) {
            return $placeholderImage;
        }

        $attachment = CategoryImageAttachment::fromId(
            $categoryImage?->attachmentId(),
            $size
        );

        if ($attachment === null) {
            return $placeholderImage;
        }

        $image_attr = '';

        if (!empty($attr['class'])) {
            $image_attr .= ' class="' . $attr['class'] . '" ';
        }

        $altText = get_post_meta($categoryImage?->attachmentId(), '_wp_attachment_image_alt', true);

        if (!empty($altText)) {
            $image_attr .= ' alt="' . $altText . '" ';
        }

        $image_attr .= ' title="' . get_the_title($categoryImage?->attachmentId()) . '" ';
        $image_attr .= ' width="' . $attachment->width() . '" ';
        $image_attr .= ' height="' . $attachment->height() . '" ';

        $image_attr .= ' loading="auto" ';

        return '<img src="' . $attachment->src() . '" ' . $image_attr . '/>';
    }
}