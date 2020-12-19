<?php

namespace Brianvarskonst\WordPress\Term\Meta;

interface DeleteTermMetaInterface
{
    /**
     * @param int $id
     * @param string $key
     * @param string $value
     *
     * @return bool
     */
    public function delete(int $id, string $key, $value = '');
}