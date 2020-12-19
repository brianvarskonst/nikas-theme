<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Theme\Thumbnail;

class PostThumbnailSize implements ThumbnailInterface
{
    public const WIDTH = 825;

    public const HEIGHT = 510;

    public function width(): int
    {
        return self::WIDTH;
    }

    public function height(): int
    {
        return self::HEIGHT;
    }

    public function isCropped(): bool
    {
        return true;
    }
}