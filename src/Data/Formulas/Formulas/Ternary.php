<?php

namespace Manadev\Data\Formulas\Formulas;

/**
 * @property Formula $condition @required @part
 * @property Formula $then @required @part
 * @property Formula $else_ @required @part
 */
class Ternary extends Formula
{
    public $type = self::TERNARY;
}