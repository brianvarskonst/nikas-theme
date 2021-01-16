<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Entity;

class CategoryImage
{
    public function __construct(
        private ?int $attachmentId,
        private ?int $termId
    ) {
    }

    public static function new(int $attachmentId, int $termId): self
    {
        return new self($attachmentId, $termId);
    }

    public function attachmentId(): ?int
    {
        return $this->attachmentId;
    }

    public function termId(): ?int
    {
        return $this->termId;
    }
}
