<?php

namespace Manadev\Framework\Db;

use Manadev\Core\Object_;

/**
 * @property string $name @required @part
 * @property string $type @required @part
 * @property string[] $columns @required @part
 */
class Key extends Object_
{
    const PRIMARY = 'primary';
    const UNIQUE = 'unique';
    const INDEX = 'index';
}