<?php

namespace Manadev\Data\Tables\Commands;

use Manadev\Core\Object_;

/**
 * @property string $type @required @part
 * @property string[] $columns @part
 */
class Command extends Object_
{
    const UNIQUE = 'unique';
    const DROP_COLUMNS = 'drop_columns';
}