<?php

namespace Brianvarskonst\WordPress\Term\Meta;

interface EnsureTermMetaInterface
{
    /**
     * @param int $id
     *
     * @return array|false
     */
    public function has(int $id);
}