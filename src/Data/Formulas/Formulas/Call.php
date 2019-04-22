<?php

namespace Manadev\Data\Formulas\Formulas;

/**
 * @property string $function @required @part
 * @property Formula[] $args @required @part
 */
class Call extends Formula
{
    public $type = self::CALL;
}