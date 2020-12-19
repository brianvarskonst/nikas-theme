<?php

declare(strict_types=1);

namespace Brianvarskonst\WordPress\Term\Meta;

class TermMeta implements
    CreateTermMetaInterface,
    ReadTermMetaInterface,
    UpdateTermMetaInterface,
    DeleteTermMetaInterface,
    EnsureTermMetaInterface
{

    /**
     * @param int $id
     * @param string $key
     * @param $value
     * @param bool $unique
     */
    public function create(int $id, string $key, $value, bool $unique = false)
    {
        add_term_meta($id, $key, $value, $unique);
    }

    /**
     * @param int $id
     * @param string $key
     * @param bool $single
     *
     * @return mixed
     */
    public function get(int $id, string $key, bool $single = false)
    {
        return get_term_meta($id, $key, $single);
    }

    /**
     * @param int $id
     *
     * @return array|false
     */
    public function has(int $id)
    {
        return has_term_meta($id);
    }

    /**
     * @param int $id
     * @param string $key
     * @param $value
     * @param string $prevValue
     *
     * @return bool|int|\WP_Error
     */
    public function update(int $id, string $key, $value, $prevValue = '')
    {
        return update_term_meta($id, $key, $value, $prevValue);
    }

    /**
     * @param int $id
     * @param string $key
     * @param string $value
     *
     * @return bool
     */
    public function delete(int $id, string $key, $value = '')
    {
        return (bool) delete_term_meta($id, $key, $value);
    }
}