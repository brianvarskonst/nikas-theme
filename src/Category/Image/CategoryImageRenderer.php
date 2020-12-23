<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Category\Image;

use Brianvarskonst\Nikas\Entity\CategoryImageAttachment;
use Brianvarskonst\Nikas\TermMeta\CategoryImage;

class CategoryImageRenderer
{
    public function __construct(
        private CategoryImage $categoryImageTermMeta
    )
    {}

    public function render(int $id, string $size, $attr = null): string
    {
        $categoryImage = $this->categoryImageTermMeta->get($id);
        $attachment = CategoryImageAttachment::fromId(
            $categoryImage?->attachmentId(),
            $size
        );

        if ($attachment === null) {
            return '';
        }

        $image_attr = '';

        if (is_array($attr)) {
            if (!empty($attr['class'])) {
                $image_attr .= ' class="' . $attr['class'] . '" ';
            }

            if (!empty($attr['alt'])) {
                $image_attr .= ' alt="' . $attr['alt'] . '" ';
            }

            if (!empty($attr['title'])) {
                $image_attr .= ' title="' . $attr['title'] . '" ';
            }
        }

        $image_attr .= ' width="' . $attachment->width() . '" ';
        $image_attr .= ' height="' . $attachment->height() . '" ';

        return '<img src="' . $attachment->src() . '" ' . $image_attr . '/>';
    }
}