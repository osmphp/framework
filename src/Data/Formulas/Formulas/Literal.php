<?php

namespace Osm\Data\Formulas\Formulas;

/**
 * @property string $value @required @part
 * @property int $token @required @part
 */
class Literal extends Formula
{
    public $type = self::LITERAL;
}