<?php

namespace Osm\Data\Formulas\Formulas;

/**
 * @property mixed $parameter @required
 * @property int $index @required @part
 */
class Parameter extends Formula
{
    public $type = self::PARAMETER;
}