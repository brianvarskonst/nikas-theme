<?php

namespace Brianvarskonst\Nikas\Theme\Thumbnail;

interface ThumbnailInterface
{
    public function width(): int;

    public function height(): int;

    public function isCropped(): bool;
}
