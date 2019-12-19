<?php

namespace Osm\Data\TableQueries\Functions;

use Osm\Core\Exceptions\NotSupported;
use Osm\Data\Formulas\Formulas\Call;
use Osm\Core\Object_;
use Osm\Data\TableQueries\Generator as FormulaGenerator;

/**
 * @property FormulaGenerator $parent @required
 */
class Generator extends Object_
{
    public function generate(Call $formula) {
        switch ($formula->function) {
            case 'count':
                $this->parent->sql .= "COUNT(";
                $this->parent->handleFormula($formula->args[0]);
                $this->parent->sql .= ")";
                break;
            case 'distinct_count':
                $this->parent->sql .= "COUNT(DISTINCT ";
                $this->parent->handleFormula($formula->args[0]);
                $this->parent->sql .= ")";
                break;
            case 'sha1':
                $this->parent->sql .= "SHA1(";
                $this->parent->handleFormula($formula->args[0]);
                $this->parent->sql .= ")";
                break;
            case 'now':
                $this->parent->sql .= "NOW()";
                break;
            default:
                throw new NotSupported(osm_t("Table function ':function' not supported",
                    ['function' => $formula->function]));
        }
    }
}