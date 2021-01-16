<?php

namespace Brianvarskonst\WordPress\Term\Meta;

interface CrudTermMetaInterface extends
    CreateTermMetaInterface,
    ReadTermMetaInterface,
    UpdateTermMetaInterface,
    DeleteTermMetaInterface,
    EnsureTermMetaInterface
{
}