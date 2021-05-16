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

        $imageAttr = '';

        if (!empty($attr['class'])) {
            $imageAttr .= ' class="' . $attr['class'] . '" ';
        }

        $altText = get_post_meta($categoryImage?->attachmentId(), '_wp_attachment_image_alt', true);

        if (!empty($altText)) {
            $imageAttr .= ' alt="' . $altText . '" ';
        }

        $imageAttr .= ' title="' . get_the_title($categoryImage?->attachmentId()) . '" ';
        $imageAttr .= ' width="' . $attachment->width() . '" ';
        $imageAttr .= ' height="' . $attachment->height() . '" ';

        $imageAttr .= ' loading="auto" ';

        return '<img src="' . $attachment->src() . '" ' . $imageAttr . '/>';
    }
}