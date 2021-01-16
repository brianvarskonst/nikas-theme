<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Category\Image;

use Brianvarskonst\Nikas\Entity\CategoryImageAttachment;
use \Brianvarskonst\Nikas\TermMeta\CategoryImage;

class CategoryImageUrlProvider
{
    public function __construct(
        private CategoryImage $categoryImage,
        private string $placeholderImage
    ) {
    }

    public function placeholder(): string
    {
        return $this->placeholderImage;
    }

    /**
     * @param int $id
     * @param string|null $size  small(thumbnail), medium, full
     *
     * @return string|null
     */
    public function provide(int $id, ?string $size = 'full')
    {
        $categoryImage = $this->categoryImage->get($id, true);

        if ($categoryImage !== null) {
            $attachment = CategoryImageAttachment::fromId(
                $categoryImage->attachmentId(),
                $size
            );

            return $attachment->src();
        }

        return $this->placeholderImage;
    }
}
