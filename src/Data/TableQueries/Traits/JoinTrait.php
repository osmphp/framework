<?php

namespace Osm\Data\TableQueries\Traits;

use Osm\Core\App;
use Osm\Data\Formulas\Parser\Parser;
use Osm\Data\Formulas\Types;
use Osm\Data\Queries\Query;
use Osm\Data\Queries\Resolver;
use Osm\Data\TableQueries\Table;
use Osm\Data\TableQueries\TableQuery;
use Osm\Framework\Data\AliasParser;
use Osm\Framework\Db\Db;

/**
 * @property Parser $parser @required
 * @property Resolver $resolver @required
 * @property Types $types @required
 * @property Db|TableQuery[] $db @required
 */
trait JoinTrait
{
    /**
     * @required @part
     * @var Table[]
     */
    public $tables = [];

    public function db(Db $db) {
        $this->registerMethodCall(__FUNCTION__, $db);
        $this->db = $db;
        return $this;
    }

    public function from($table) {
        $this->registerMethodCall(__FUNCTION__, $table);
        $table_ = $this->createTable($table, 'this');
        $table_->join = Table::JOIN_FROM;
        $this->tables[$table_->alias] = $table_;

        return $this;
    }

    public function innerJoin($table, $on, ...$parameters) {
        $this->registerMethodCall(__FUNCTION__, $table, $on, ...$parameters);
        return $this->join(Table::JOIN_INNER, $table, $on, $parameters);
    }

    public function leftJoin($table, $on, ...$parameters) {
        $this->registerMethodCall(__FUNCTION__, $table, $on, ...$parameters);
        return $this->join(Table::JOIN_LEFT, $table, $on, $parameters);
    }

    protected function join($type, $table, $on, $parameters) {
        $query = $this; /* @var Query $query */

        $table_ = $this->createTable($table);
        $table_->join = $type;
        $this->tables[$table_->alias] = $table_;

        $on_ = $this->parser->parseFormula($on, $parameters);
        $on_->parent = $this;
        $this->resolver->resolve(__FUNCTION__, $query, $on_);
        $on_ = $this->types->cast($on_, Types::BOOL_);
        $table_->on = $on_;

        return $this;
    }

    public function virtualJoin($table, callable $callback) {
        $this->registerMethodCall(__FUNCTION__, $table, $callback);
        $table_ = $this->createTable($table);
        $table_->join = Table::JOIN_VIRTUAL;
        $this->tables[$table_->alias] = $table_;

        $table_->callback = $callback;

        return $this;
    }

    /**
     * @param $table
     * @param $alias
     * @return Table
     */
    protected function createTable($table, $alias = null) {
        $db = $this->db;
        if ($parts = $this->explodeByAsKeyword($table)) {
            list($table, $alias) = $parts;
            return Table::new(compact('table', 'alias', 'db'), null, $this);
        }

        return Table::new(['table' => $table, 'alias' => $alias ?? $table, 'db' => $db], null, $this);
    }

    protected function explodeByAsKeyword($expr) {
        global $m_app; /* @var App $m_app */

        $parser = $m_app[AliasParser::class]; /* @var AliasParser $parser*/
        return $parser->parse($expr);
    }

}