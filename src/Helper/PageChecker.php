<?php


namespace Brianvarskonst\Nikas\Helper;


class PageChecker
{
    public function checkPage(array $pages): bool
    {
        $filterPageName = basename(
            filter_input(INPUT_SERVER, 'SCRIPT_NAME', FILTER_SANITIZE_STRING),
            '.php'
        );

        return in_array($filterPageName, $pages, true);
    }
}