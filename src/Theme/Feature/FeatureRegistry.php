<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Theme\Feature;

class FeatureRegistry
{
    private array $features;

    public function __construct(Feature ...$features)
    {
        $this->features = $features;
    }

    public static function fromArray(array $features): self
    {
        $instances = [];

        foreach ($features as $feature => $args) {
            $instances[] = new Feature($feature, $args);
        }

        return new self(...$instances);
    }

    public function feature(string $title): ?Feature
    {
        return $this->features[$title] ?? null;
    }

    public function all(): array
    {
        return $this->features;
    }
}
