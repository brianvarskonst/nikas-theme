<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Theme\Feature;

class Feature
{
    private string $title;

    /**
     * @var array<string, array>
     */
    private array $args;

    public function __construct(string $title, array $args = [])
    {
        $this->title = $title;
        $this->args = $args;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function args(): array
    {
        return $this->args;
    }
}
