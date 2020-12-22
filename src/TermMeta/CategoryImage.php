<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\TermMeta;

use Brianvarskonst\WordPress\Term\Meta\TermMeta;
use \Brianvarskonst\Nikas\Entity\CategoryImage as Entity;

class CategoryImage
{
    public const KEY = 'category-image';

    public function __construct(
        private TermMeta $termMeta
    )
    {}

    public function create(Entity $categoryImage, bool $unique = false)
    {
        $this->termMeta->create(
            $categoryImage->termId(),
            self::KEY,
            $categoryImage->attachmentId(),
            $unique
        );
    }

    public function delete(Entity $categoryImage, $value = '')
    {
        $this->termMeta->delete(
            $categoryImage->termId(),
            self::KEY,
            $value
        );
    }

    public function has(int $id)
    {
        return $this->termMeta->has($id);
    }

    public function get(int $id, bool $single = true): ?Entity
    {
        $attachmentId = (int) $this->termMeta->get(
            $id,
            self::KEY,
            $single
        );

        if (empty($attachmentId)) {
            return null;
        }

        return Entity::new($attachmentId, $id);
    }

    public function update(Entity $categoryImage, $prevValue = ''): Entity
    {
        $this->termMeta->update(
            $categoryImage->termId(),
            self::KEY,
            $categoryImage->attachmentId(),
            $prevValue
        );

        return $categoryImage;
    }
}