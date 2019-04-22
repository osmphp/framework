<?php

namespace Manadev\Data\Queries\Functions;

use Manadev\Core\App;
use Manadev\Data\Formulas\Exceptions\InvalidCall;
use Manadev\Data\Formulas\Formulas\Call;
use Manadev\Data\Formulas\Functions\Argument;
use Manadev\Data\Formulas\Functions\Function_;
use Manadev\Data\Formulas\Functions\Functions;
use Manadev\Data\Formulas\Types;
use Manadev\Core\Object_;
use Manadev\Data\Queries\Resolver as QueryResolver;

/**
 * @property QueryResolver $parent @required
 * @property Functions|Function_[] $functions @required
 * @property Types $types @required
 * @property Call $formula @temp
 * @property Function_ $function @temp
 */
class Resolver extends Object_
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'types': return $m_app[Types::class];
        }

        return parent::default($property);
    }

    public function resolve(Call &$formula) {
        $this->formula = $formula;
        $this->function = $this->functions[strtolower($formula->function)];

        $this->validateArgs();
        $formula->data_type = $this->function->return_data_type;

        $this->handleFunction();

        $formula = $this->formula;
    }

    protected function handleFunction() {
        // by default, functions validate arguments and infer returning data type in resolution phase. If your
        // function needs additional handling during resolution phase, advise this method
    }

    protected function validateArgs() {
        $index = -1;
        $definition = null; /* @var Argument $definition */

        foreach ($this->formula->args as &$arg) {
            $this->nextArgDefinition($definition, $index);
            if ($definition->data_type == Types::ANY) {
                continue;
            }

            $arg = $this->types->cast($arg, $definition->data_type);
        }

        $this->checkIfNoRequiredArgumentIsMissing($index);
    }

    /**
     * @param Argument $definition
     * @param int $index
     */
    protected function nextArgDefinition(&$definition, &$index) {
        if ($definition && $definition->array) {
            // array is expected to be last optional argument, so once once we hit array argument definition,
            // we use it to handle all the rest arguments
            return;
        }

        $index++;
        if (!isset($this->function->args_[$index])) {
            throw new InvalidCall(m_("More arguments provided to function :function than actually expected",
                ['function' => strtoupper($this->formula->function)]), $this->formula->formula,
                $this->formula->pos, $this->formula->length);
        }

        $definition = $this->function->args_[$index];
    }

    protected function checkIfNoRequiredArgumentIsMissing($index) {
        for ($i = $index + 1; $i < count($this->function->args_); $i++) {
            $definition = $this->function->args_[$i];
            if ($definition->optional) {
                continue;
            }

            if ($definition->array) {
                continue;
            }

            throw new InvalidCall(m_("One or more required arguments not provided to function :function",
                ['function' => strtoupper($this->formula->function)]), $this->formula->formula,
                $this->formula->pos, $this->formula->length);
        }
    }
}