<?php

namespace Brianvarskonst\WordPress\Term\Meta;

interface ReadTermMetaInterface
{
    /**
     * @param int $id
     * @param string $key
     * @param bool $single
     *
     * @return mixed
     */
    public function get(int $id, string $key, bool $single = false);
}