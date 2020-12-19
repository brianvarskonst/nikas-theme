<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Asset;

use Inpsyde\Assets\Asset;
use Inpsyde\Assets\Script;

class CategoryImageConfigProcessor implements ConfigProcessorInterface
{
    private string $key;

    private array $config;

    public function __construct(string $key, array $config)
    {
        $this->key = $key;
        $this->config = $config;
    }

    public function accepts(Asset $asset): bool
    {
        return $asset instanceof Script || $asset->handle() === 'theme';
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
                $this->key,
                static function () use ($localize): array {
                    return $localize;
                }
            );
        }

        if (array_key_exists('canEnqueue', $this->config) && is_callable($this->config['canEnqueue'])) {
            $asset->canEnqueue($this->config['canEnqueue']());
        }

        return $asset;
    }
}