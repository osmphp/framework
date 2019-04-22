<?php

namespace Manadev\Data\Formulas\Formulas;

/**
 * @property Formula $expr @required @part
 * @property bool $ascending @required @part
 */
class SortExpr extends Formula
{
    public $type = self::SORT_EXPR;
}