<?php

namespace Manadev\Data\Formulas\Formulas;

/**
 * @property Formula $expr @required @part
 */
class Cast extends Formula
{
    protected function default($property) {
        switch ($property) {
            case 'formula': return $this->expr->formula;
            case 'pos': return $this->expr->pos;
            case 'length': return $this->expr->length;
        }
        return parent::default($property);
    }

    public $type = self::CAST;
}