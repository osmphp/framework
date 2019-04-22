<?php

namespace Manadev\Data\Formulas\Handlers;

use Manadev\Data\Formulas\Formulas\Formula;
use Manadev\Data\Formulas\Formulas;

/**
 * @property array $parameters @temp
 */
class ParameterFiller extends Handler {
    public function fill(Formula $formula, $parameters) {
        $this->parameters = $parameters;
        $this->handleFormula($formula);
        return $this->parameters;
    }

    /**
     * @see \Manadev\Data\Formulas\Formulas\Formula::$type @handler
     * @param Formula $formula
     */
    protected function handleFormula(Formula &$formula) {
        switch ($formula->type) {
            case Formula::PARAMETER:
                /* @var Formulas\Parameter $formula */
                $formula->parameter = $this->parameters[$formula->index];
                break;

            default:
                parent::handleFormula($formula);
                break;
        }
    }
}