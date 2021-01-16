<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Entity;

class CategoryImageAttachment
{
    public function __construct(
        private string $src,
        private int $width,
        private int $height,
        private bool $isResized
    ) {
    }

    /**
     * @param int $id
     * @param string|null $size
     * @return static
     */
    public static function fromId(int $id, ?string $size): self
    {
        return new self(...wp_get_attachment_image_src($id, $size));
    }

    /**
     * @return string
     */
    public function src(): string
    {
        return $this->src;
    }

    /**
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * @return bool
     */
    public function isResized(): bool
    {
        return $this->isResized;
    }
}
