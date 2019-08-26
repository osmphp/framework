<?php

namespace Osm\Data\Formulas\Formulas;

/**
 * @property Formula $expr @required @part
 * @property string $alias @required @part
 */
class SelectExpr extends Formula
{
    public $type = self::SELECT_EXPR;

    protected function default($property) {
        switch ($property) {
            case 'formula': return $this->expr->formula;
        }
        return parent::default($property);
    }
}