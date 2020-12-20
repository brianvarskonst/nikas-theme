<?php


namespace Brianvarskonst\Nikas\Category\Image;


class CategoryImagePageChecker
{
    public const supportedPages = [
        'edit-tags',
        'term'
    ];

    public function checkPage(): bool
    {
        $filterPageName = basename(
            filter_input(INPUT_SERVER, 'SCRIPT_NAME', FILTER_SANITIZE_STRING),
            '.php'
        );

        return in_array($filterPageName, self::supportedPages, true);
    }
}