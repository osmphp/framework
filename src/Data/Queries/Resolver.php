<?php

namespace Osm\Data\Queries;

use Osm\Core\App;
use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Profiler;
use Osm\Data\Formulas\Formulas\Formula;
use Osm\Data\Formulas\Handlers\Handler;
use Osm\Data\Formulas\Types;
use Osm\Data\Formulas\Formulas;
use Osm\Data\Formulas\Functions\Resolver as FunctionResolver;

/**
 * @property Types $types @required
 * @property FunctionResolver $function_resolver @required
 * @property Query $query @temp
 * @property string $part @temp
 */
class Resolver extends Handler
{
    /**
     * @param string $part
     * @param Query $query
     * @param Formula $formula
     */
    public function resolve($part, $query, Formula &$formula) {
        global $osm_profiler; /* @var Profiler $osm_profiler */

        if ($osm_profiler) $osm_profiler->start(__METHOD__, 'formulas');
        $previousQuery = $this->query;
        $previousPart = $this->part;
        $this->query = $query;
        $this->part = $part;
        try {
            $this->handleFormula($formula);
        }
        finally {
            $this->query = $previousQuery;
            $this->part = $previousPart;
            if ($osm_profiler) $osm_profiler->stop(__METHOD__);
        }
    }

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'types': return $osm_app[Types::class];
        }

        return parent::default($property);
    }

    /**
     * @see \Osm\Data\Formulas\Formulas\Formula::$type @handler
     * @param Formula $formula
     */
    protected function handleFormula(Formula &$formula) {
        // first, resolve child nodes
        parent::handleFormula($formula);

        switch ($formula->type) {
            case Formula::SORT_EXPR:
                /* @var Formulas\SortExpr $formula */
                $formula->data_type = $formula->expr->data_type;
                break;

            case Formula::SELECT_EXPR:
                /* @var Formulas\SelectExpr $formula */
                $formula->data_type = $formula->expr->data_type;
                break;

            case Formula::CAST:
                // casts are created by resolver - nothing to do here
                /* @var Formulas\Cast $formula */
                break;

            case Formula::LOGICAL_OR:
            case Formula::LOGICAL_XOR:
            case Formula::LOGICAL_AND:
                /* @var Formulas\Operator $formula */
                $this->cast($formula->operands, Types::BOOL_);
                $formula->data_type = Types::BOOL_;
                break;

            case Formula::EQUAL:
            case Formula::EQUAL_OR_GREATER:
            case Formula::GREATER:
            case Formula::EQUAL_OR_LESS:
            case Formula::LESS:
            case Formula::NOT_EQUAL:
            case Formula::EQUAL_OR_NULL:
                /* @var Formulas\Operator $formula */
                $formula->data_type = Types::BOOL_;
                break;

            case Formula::BIT_OR:
            case Formula::BIT_AND:
            case Formula::BIT_SHIFT:
            case Formula::ADD:
            case Formula::MULTIPLY:
            case Formula::BIT_XOR:
            case Formula::COALESCE:
                /* @var Formulas\Operator $formula */
                $formula->data_type = $this->castToFirstNonNull($formula->operands);
                break;

            case Formula::NOT_IN:
            case Formula::IN_:
                /* @var Formulas\In_ $formula */
                foreach ($formula->items as &$item) {
                    $item = $this->types->cast($item, $formula->value->data_type);
                }
                $formula->data_type = Types::BOOL_;
                break;

            case Formula::IS_NULL:
            case Formula::IS_NOT_NULL:
                /* @var Formulas\Unary $formula */
                $formula->data_type = Types::BOOL_;
                break;

            case Formula::IDENTIFIER:
                /* @var Formulas\Identifier $formula */
                $formula = $this->handleIdentifier($formula);
                break;

            case Formula::PARAMETER:
                /* @var Formulas\Parameter $formula */
                if (is_bool($formula->parameter)) {
                    $formula->data_type = Types::BOOL_;
                }
                elseif (is_int($formula->parameter)) {
                    $formula->data_type = Types::INT_;
                }
                elseif (is_float($formula->parameter)) {
                    $formula->data_type = Types::FLOAT_;
                }
                elseif (is_string($formula->parameter)) {
                    $formula->data_type = Types::STRING_;
                }
                elseif (is_null($formula->parameter)) {
                    $formula->data_type = Types::NULL_;
                }
                else {
                    $formula->data_type = Types::BINARY;
                }
                break;

            case Formula::CALL:
                /* @var Formulas\Call $formula */
                $this->function_resolver->resolve($formula);
                break;

            case Formula::LITERAL:
                /* @var Formulas\Literal $formula */
                $formula->data_type = $this->types->literals[$formula->token];
                break;

            case Formula::TERNARY:
                /* @var Formulas\Ternary $formula */
                $formula->condition = $this->types->cast($formula->condition, Types::BOOL_);

                $formulas = [$formula->then, $formula->else_];
                $formula->data_type = $this->castToFirstNonNull($formulas);

                $formula->then = $formulas[0];
                $formula->else_ = $formulas[1];
                break;

            case Formula::POSITIVE:
            case Formula::NEGATIVE:
            case Formula::BIT_INVERT:
            case Formula::LOGICAL_NOT:
                /* @var Formulas\Unary $formula */

            case Formula::NOT_BETWEEN:
            case Formula::BETWEEN:
                /* @var Formulas\Between $formula */

            case Formula::NOT_LIKE:
            case Formula::LIKE:
            case Formula::NOT_REGEXP:
            case Formula::REGEXP:
                /* @var Formulas\Pattern $formula */

            default:
                throw new NotSupported(m_("Formula type ':type' not supported", ['type' => $formula->type]));
        }
    }

    /**
     * @param Formula[] $formulas
     * @return string
     */
    protected function castToFirstNonNull(&$formulas) {
        $result = Types::NULL_;

        foreach ($formulas as $formula) {
            if ($formula->data_type != Types::NULL_) {
                $result = $formula->data_type;
                break;
            }
        }

        foreach ($formulas as &$formula) {
            if ($formula->data_type != Types::NULL_) {
                $formula = $this->types->cast($formula, $result);
            }
        }

        return $result;
    }

    /**
     * @param Formula[] $formulas
     * @param string $type
     */
    protected function cast(&$formulas, $type) {
        foreach ($formulas as &$formula) {
            if ($formula->data_type != Types::NULL_) {
                $formula = $this->types->cast($formula, $type);
            }
        }
    }

    protected function handleIdentifier(Formulas\Identifier $formula) {
        return $formula;
    }
}