<?php

namespace Manadev\Data\Formulas\Handlers;

use Manadev\Data\Formulas\Formulas\Formula;
use Manadev\Data\Formulas\Formulas;

/**
 * @property array $parameters @temp
 */
class ParameterCollector extends Handler {
    public function collect(Formula $formula) {
        $this->parameters = [];
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
                $this->parameters[] = $formula->parameter;
                break;

            default:
                parent::handleFormula($formula);
                break;
        }
    }
}