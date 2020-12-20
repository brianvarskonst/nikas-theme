<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Asset;

use Inpsyde\Assets\Asset;
use Inpsyde\Assets\Script;

class CategoryImageConfigProcessor implements ConfigProcessorInterface
{
    public const LOCALIZE_KEY = 'nikasCategoryImage';

    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function accepts(Asset $asset): bool
    {
        return $asset instanceof Script && $asset->handle() === 'category-image';
    }

    public function process(Asset $asset): Asset
    {
        assert($asset instanceof Script);

        $localize = [];

        if (array_key_exists('version', $this->config)) {
            $localize['version'] = $this->config['version'];
        }

        if (array_key_exists('placeholder', $this->config)) {
            $localize['placeholder'] = $this->config['placeholder'];
        }

        if (!empty($localize)) {
            $asset->withLocalize(
                self::LOCALIZE_KEY,
                static function () use ($localize): array {
                    return $localize;
                }
            );
        }

        if (
            array_key_exists('canEnqueue', $this->config)
            && is_callable($this->config['canEnqueue'])
        ) {
            $asset->canEnqueue($this->config['canEnqueue']());
        }

        if (array_key_exists('location', $this->config)) {
            $asset->forLocation($this->config['location']);
        }

        return $asset;
    }
}
