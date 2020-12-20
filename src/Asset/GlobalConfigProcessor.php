<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Asset;

use Inpsyde\Assets\Asset;
use Inpsyde\Assets\Script;
use Inpsyde\Assets\Style;

class GlobalConfigProcessor implements ConfigProcessorInterface
{
    public function accepts(Asset $asset): bool
    {
        return $asset instanceof Style || $asset instanceof Script;
    }

    public function process(Asset $asset): Asset
    {
        $asset->forLocation(Asset::FRONTEND);

        return $asset;
    }
}
