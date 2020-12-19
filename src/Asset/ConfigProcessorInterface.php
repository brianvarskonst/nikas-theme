<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Asset;

use Inpsyde\Assets\Asset;

interface ConfigProcessorInterface
{
    public function accepts(Asset $asset): bool;

    public function process(Asset $asset): Asset;
}