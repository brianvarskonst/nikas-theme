<?php

namespace Brianvarskonst\WordPress\Term\Meta;

interface CreateTermMetaInterface
{
    /**
     * @param int $id
     * @param string $key
     * @param $value
     * @param bool $unique
     */
    public function create(int $id, string $key, $value, bool $unique = false);
}