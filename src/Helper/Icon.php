<?php

declare(strict_types=1);

namespace Brianvarskonst\Nikas\Helper;

// phpcs:disable Inpsyde.CodeQuality.NoAccessors.NoGetter
class Icon
{
    private string $path;
    /**
     * @var string[]
     */
    private array $registeredIcons;

    public function __construct(string $path, array $registeredIcons)
    {
        $this->path = untrailingslashit($path);
        $this->registeredIcons = $registeredIcons;
    }

    public function getTagsConfiguration(): array
    {
        return array_merge(
            wp_kses_allowed_html('post'),
            $this->defaultAllowedSVGTags()
        );
    }

    public function get(string $name): string
    {
        return $this->getSvg($name);
    }

    private function getSvg(string $fileName): string
    {
        $file = "{$this->path}/{$fileName}.svg";

        if (!is_readable($file) && !in_array($fileName, $this->registeredIcons, true)) {
            return '';
        }

        return $this->escapeSvgIcon(file_get_contents($file));
    }

    private function escapeSvgIcon(string $fileContent): string
    {
        return wp_kses(
            $fileContent,
            $this->getTagsConfiguration()
        );
    }

    protected function defaultAllowedSVGTags(): array
    {
        return [
            'svg' => [
                'class' => true,
                'aria-hidden' => true,
                'aria-labelledby' => true,
                'role' => true,
                'xmlns' => true,
                'width' => true,
                'height' => true,
                'viewbox' => true,
            ],
            'g' => [
                'fill' => true,
                'clip-path' => true,
                'style' => true,
                'class' => true,
            ],
            'path' => [
                'd' => true,
                'fill' => true,
                'id' => true,
                'clip-rule' => true,
                'class' => true,
            ],
            'rect' => [
                'x' => true,
                'y' => true,
                'width' => true,
                'height' => true,
                'fill' => true,
                'class' => true,
            ],
            'circle' => [
                'cx' => true,
                'cy' => true,
                'r' => true,
                'style' => true,
            ],
            'use' => [
                'fill' => true,
                'fill-rule' => true,
                'transform' => true,
                'xlink:href' => true,
            ],
            'style' => true,
        ];
    }
}