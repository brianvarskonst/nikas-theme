<?php

namespace Brianvarskonst\WordPress\Term\Meta;

interface UpdateTermMetaInterface
{
    /**
     * @param int $id
     * @param string $key
     * @param $value
     * @param string $prevValue
     *
     * @return bool|int|\WP_Error
     */
    public function update(int $id, string $key, $value, $prevValue = '');
}