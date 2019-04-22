<?php

namespace Manadev\Data\Formulas\Formulas;

/**
 * @property string[] $parts @required @part
 *
 * Resolved properties:
 *
 * @property string $column @required @part
 * @property string $table @required @part
 */
class Identifier extends Formula
{
    public $type = self::IDENTIFIER;
}