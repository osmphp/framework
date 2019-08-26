<?php

namespace Osm\Data\Formulas\Handlers;

use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;
use Osm\Data\Formulas\Formulas\Formula;
use Osm\Data\Formulas\Formulas;

class Handler extends Object_
{
    /**
     * @see \Osm\Data\Formulas\Formulas\Formula::$type @handler
     * @param Formula $formula
     */
    protected function handleFormula(Formula &$formula) {
        switch ($formula->type) {
            case Formula::SORT_EXPR:
                /* @var Formulas\SortExpr $formula */
                $this->handleFormula($formula->expr);
                break;

            case Formula::SELECT_EXPR:
                /* @var Formulas\SelectExpr $formula */
                $this->handleFormula($formula->expr);
                break;

            case Formula::CAST:
                /* @var Formulas\Cast $formula */
                $this->handleFormula($formula->expr);
                break;

            case Formula::LOGICAL_OR:
            case Formula::LOGICAL_XOR:
            case Formula::LOGICAL_AND:
            case Formula::BIT_OR:
            case Formula::BIT_AND:
            case Formula::BIT_SHIFT:
            case Formula::ADD:
            case Formula::MULTIPLY:
            case Formula::BIT_XOR:
            case Formula::COALESCE:
            case Formula::EQUAL:
            case Formula::EQUAL_OR_GREATER:
            case Formula::GREATER:
            case Formula::EQUAL_OR_LESS:
            case Formula::LESS:
            case Formula::NOT_EQUAL:
            case Formula::EQUAL_OR_NULL:
                /* @var Formulas\Operator $formula */
                foreach ($formula->operands as $operand) {
                    $this->handleFormula($operand);
                }
                break;

            case Formula::NOT_IN:
            case Formula::IN_:
                /* @var Formulas\In_ $formula */
                $this->handleFormula($formula->value);
                foreach ($formula->items as $item) {
                    $this->handleFormula($item);
                }
                break;

            case Formula::NOT_BETWEEN:
            case Formula::BETWEEN:
                /* @var Formulas\Between $formula */
                $this->handleFormula($formula->value);
                $this->handleFormula($formula->from);
                $this->handleFormula($formula->to);
                break;

            case Formula::NOT_LIKE:
            case Formula::LIKE:
            case Formula::NOT_REGEXP:
            case Formula::REGEXP:
                /* @var Formulas\Pattern $formula */
                $this->handleFormula($formula->value);
                $this->handleFormula($formula->pattern);
                break;

            case Formula::IS_NULL:
            case Formula::IS_NOT_NULL:
            case Formula::POSITIVE:
            case Formula::NEGATIVE:
            case Formula::BIT_INVERT:
            case Formula::LOGICAL_NOT:
                /* @var Formulas\Unary $formula */
                $this->handleFormula($formula->operand);
                break;

            case Formula::IDENTIFIER:
                /* @var Formulas\Identifier $formula */
                break;

            case Formula::PARAMETER:
                /* @var Formulas\Parameter $formula */
                break;

            case Formula::CALL:
                /* @var Formulas\Call $formula */
                foreach ($formula->args as $arg) {
                    $this->handleFormula($arg);
                }
                break;

            case Formula::LITERAL:
                /* @var Formulas\Literal $formula */
                break;

            case Formula::TERNARY:
                /* @var Formulas\Ternary $formula */
                $this->handleFormula($formula->condition);
                $this->handleFormula($formula->then);
                $this->handleFormula($formula->else_);
                break;

            default:
                throw new NotSupported(m_("Formula type ':type' not supported", ['type' => $formula->type]));
        }
    }
}