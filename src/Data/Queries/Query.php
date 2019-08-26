<?php

namespace Osm\Data\Queries;

use Illuminate\Support\Collection;
use Osm\Core\App;
use Osm\Data\Formulas\Exceptions\UnnamedColumn;
use Osm\Data\Formulas\Handlers\ParameterCollector;
use Osm\Data\Formulas\Parser\Parser;
use Osm\Data\Formulas\Parser\Token;
use Osm\Data\Formulas\Types;
use Osm\Core\Object_;
use Osm\Data\Formulas\Formulas;
use Osm\Data\Formulas\Formulas\Formula;
use Osm\Framework\Data\Traits\CloneableTrait;

/**
 * @property int $limit @part
 * @property int $offset @part
 * @property Parser $parser @required
 * @property Resolver $resolver @required
 * @property Types $types @required
 * @property ParameterCollector $parameter_collector @required
 * @method Query clone(...$methods)
 */
abstract class Query extends Object_
{
    use CloneableTrait;

    /**
     * @var Formula
     */
    public $filter;
    /**
     * @var Formulas\SelectExpr[]
     */
    public $columns = [];
    /**
     * @var Formula[]
     */
    public $groups = [];
    /**
     * @var Formula[]
     */
    public $sorts = [];


    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'parser': return $osm_app[Parser::class];
            case 'types': return $osm_app[Types::class];
            case 'parameter_collector': return $osm_app[ParameterCollector::class];
        }

        return parent::default($property);
    }

    public function groupBy($formulas, ...$parameters) {
        $this->registerMethodCall(__FUNCTION__, $formulas, ...$parameters);
        if (!is_array($formulas)) {
            $formulas = [$formulas];
        }

        $formulas_ = $this->parser->parseFormulas($formulas, $parameters, Formula::EXPR);
        foreach ($formulas_ as $formula_) {
            $this->resolver->resolve(__FUNCTION__, $this, $formula_);
            $formula_->parent = $this;
            $this->groups[] = $formula_;
        }

        return $this;
    }

    public function orderBy($formulas, ...$parameters) {
        $this->registerMethodCall(__FUNCTION__, $formulas, ...$parameters);
        if (!is_array($formulas)) {
            $formulas = [$formulas];
        }

        $formulas_ = $this->parser->parseFormulas($formulas, $parameters, Formula::SORT_EXPR);
        foreach ($formulas_ as $formula_) {
            $this->resolver->resolve(__FUNCTION__, $this, $formula_);
            $formula_->parent = $this;
            $this->sorts[] = $formula_;
        }

        return $this;
    }

    /**
     * @param string $formula
     * @param array $parameters
     * @return Query
     */
    public function where($formula, ...$parameters) {
        $this->registerMethodCall(__FUNCTION__, $formula, ...$parameters);
        $formula_ = $this->parser->parseFormula($formula, $parameters);
        $formula_->parent = $this;

        $this->resolver->resolve(__FUNCTION__, $this, $formula_);
        $formula_ = $this->types->cast($formula_, Types::BOOL_);

        if (!$this->filter) {
            $this->filter = $formula_;
        }
        else {
            if ($this->filter->type != Formula::LOGICAL_AND) {
                $operand = $this->filter;

                $this->filter = Formulas\Operator::new([
                    'type' => Formula::LOGICAL_AND,
                    'operands' => [$operand],
                    'operators' => []
                ], null, $this);

                $operand->parent = $this->filter;
            }

            $this->filter->operands[] = $formula_;
            $this->filter->operators[] = Token::AND_;
            $formula_->parent = $this->filter;
        }

        return $this;
    }

    /**
     * @param string|string[] $formulas
     * @param array $parameters
     * @return Query
     */
    public function select($formulas, ...$parameters) {
        $this->registerMethodCall(__FUNCTION__, $formulas, ...$parameters);
        if (!is_array($formulas)) {
            $formulas = [$formulas];
        }

        $formulas_ = $this->parser->parseFormulas($formulas, $parameters, Formula::SELECT_EXPR);
        foreach ($formulas_ as $formula_) {
            $this->resolver->resolve(__FUNCTION__, $this, $formula_);
            if ($formula_ instanceof Formulas\Identifier) {
                $expr = $formula_;

                $formula_ = Formulas\SelectExpr::new(['expr' => $expr, 'alias' => $formula_->column,
                    'data_type' => $expr->data_type]);
                $expr->parent = $formula_;
            }

            if ($formula_->type != Formula::SELECT_EXPR) {
                throw new UnnamedColumn(m_("Unnamed column expression ':expr' are not allowed",
                    ['expr' => $formula_->formula]));
            }

            $formula_->parent = $this;
            $this->columns[$formula_->alias] = $formula_;
        }

        return $this;
    }

    public function limit($limit) {
        $this->registerMethodCall(__FUNCTION__, $limit);

        $this->limit = $limit;
        return $this;
    }

    public function offset($offset) {
        $this->registerMethodCall(__FUNCTION__, $offset);

        $this->offset = $offset;
        return $this;
    }

    /**
     * @param string $source
     * @return Query
     */
    abstract public function from($source);

    /**
     * @param string|string[] $formulas
     * @param array $parameters
     * @return Collection
     */
    abstract public function get($formulas = [], ...$parameters);

    /**
     * @param string|string[] $formulas
     * @param array $parameters
     * @return \stdClass
     */
    public function first($formulas = [], ...$parameters) {
        return $this->get($formulas, ...$parameters)->first();
    }

    /**
     * @param string $formula
     * @param array $parameters
     * @return mixed
     */
    public function value($formula, ...$parameters) {
        $item = $this->first(["$formula AS value"], ...$parameters);

        return $item ? $item->value : null;
    }

    /**
     * @param string $formula
     * @param array $parameters
     * @return Collection
     */
    public function values($formula, ...$parameters) {
        return $this->get(["$formula AS value"], ...$parameters)
            ->map(function($item) {
                $item = (array) $item;
                return count($item) > 0 ? reset($item) : null;
            });
    }

    /**
     * @param string $keyFormula
     * @param string $valueFormula
     * @param array $parameters
     * @return Collection
     */
    public function pairs($keyFormula, $valueFormula, ...$parameters) {
        return $this->get(["$keyFormula AS key", "$valueFormula AS value"], ...$parameters)
            ->keyBy(function($item) {
                return $item->key;
            })
            ->map(function($item) {
                return $item->value;
            });
    }
}