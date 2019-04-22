<?php

namespace Manadev\Data\TableQueries;

use Manadev\Core\App;
use Manadev\Data\Formulas\Exceptions\UnknownColumn;
use Manadev\Data\Formulas\Formulas;
use Manadev\Data\Formulas\Parser\Parser;
use Manadev\Data\Queries\Part;
use Manadev\Data\Queries\Resolver as BaseResolver;
use Manadev\Data\TableQueries\Exceptions\CircularDependency;
use Manadev\Data\TableQueries\Functions\Resolver as FunctionResolver;

/**
 * @property Relations $relations @required
 * @property Parser $parser @required
 * @property TableQuery $query @temp
 */
class Resolver extends BaseResolver
{
    protected $columns_being_virtually_joined = [];

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'relations': return $m_app[Relations::class];
            case 'function_resolver': return FunctionResolver::new([], null, $this);
            case 'parser': return $m_app[Parser::class];
        }
        return parent::default($property);
    }

    protected function handleIdentifier(Formulas\Identifier $formula) {
        $parts = $formula->parts;
        $column = array_pop($parts);

        if (empty($parts)) {
            if (!isset($this->query->tables['this'])) {
                throw new UnknownColumn(m_("':column' means 'this.:column' but there is no 'this' table alias",
                    ['column' => $column]), $formula->formula, $formula->pos, $formula->length);
            }

            if ($result = $this->handleColumn($formula, 'this', $column)) {
                return $result;
            }

            throw new UnknownColumn(m_("':column' not found in table ':table'",
                ['column' => $column, 'table' => $this->query->tables['this']->table]),
                $formula->formula, $formula->pos, $formula->length);
        }

        if (isset($this->query->tables[$parts[0]])) {
            $table = $parts[0];
            foreach (array_slice($parts, 1) as $part) {
                $table = $this->handleRelation($table, $part);
            }

            if ($result = $this->handleColumn($formula, $table, $column)) {
                return $result;
            }
        }

        if (isset($this->query->tables['this'])) {
            $table = 'this';
            foreach ($parts as $part) {
                $table = $this->handleRelation($table, $part);
            }

            if ($result = $this->handleColumn($formula, $table, $column)) {
                return $result;
            }
        }

        throw new UnknownColumn(m_("Unknown column ':column'", ['column' => implode('.', $formula->parts)]),
            $formula->formula, $formula->pos, $formula->length);
    }

    protected function handleColumn(Formulas\Identifier $formula, $table, $column) {
        if (!isset($this->query->tables[$table])) {
            return null;
        }

        if ($this->query->tables[$table]->join == Table::JOIN_VIRTUAL) {
            return $this->handleVirtuallyJoinedColumn($this->query->tables[$table], $column);
        }

        $table_ = $this->query->tables[$table]->table_;

        if (!isset($table_->columns[$column])) {
            return null;
        }
        $column_ = $table_->columns[$column];

        if ($column_->partition != 1) {
            if (!isset($this->query->tables["{$table}__{$column_->partition}"])) {
                $prevent = $this->query->prevent_registering_method_calls;
                $this->query->prevent_registering_method_calls = true;
                try {
                    $this->query->innerJoin(
                        "{$table_->name}__{$column_->partition} AS {$table}__{$column_->partition}",
                        "{$table}__{$column_->partition}.id = {$table}.id");
                }
                finally {
                    $this->query->prevent_registering_method_calls = $prevent;
                }
            }
            $table = "{$table}__{$column_->partition}";
        }

        $formula->table = $table;
        $formula->column = $column;
        $formula->data_type = $column_->data_type;

        return $formula;
    }

    /**
     * Each relation "table.relation" should be defined in trait applied to Relations class as a
     * method named "table__relation". This method should LEFT JOIN related table, it changed to
     * INNER JOIN if needed.
     *
     * @see \Manadev\Samples\Tables\Traits\RelationsTrait for example.
     *
     * @param $table
     * @param $part
     * @return string
     */
    protected function handleRelation($table, $part) {
        $relatedTable = "{$table}__$part";
        if (!isset($this->query->tables[$relatedTable])) {
            $prevent = $this->query->prevent_registering_method_calls;
            $this->query->prevent_registering_method_calls = true;
            try {
                $table_ = $this->query->tables[$table]->table_;
                $this->relations->{"{$table_->name}__{$part}"}($this->query, $table, $relatedTable);
            }
            finally {
                $this->query->prevent_registering_method_calls = $prevent;
            }
        }

        $table_ = $this->query->tables[$relatedTable];
        if ($table_->join != Table::JOIN_INNER && in_array($this->part, [Part::JOIN, Part::WHERE])) {
            $table_->join = Table::JOIN_INNER;
        }
        return $relatedTable;
    }

    protected function handleVirtuallyJoinedColumn(Table $table, $column) {
        $key = "{$table->alias}.{$column}";
        if (isset($this->columns_being_virtually_joined[$key])) {
            throw new CircularDependency(m_("Formulas have circular dependencies: :formulas",
                ['formulas' => implode(', ', $this->columns_being_virtually_joined[$key])]));
        }
        $this->columns_being_virtually_joined[$key] = true;

        try {
            $formula_ = $this->parser->parseFormula(call_user_func($table->callback, $column), []);
            $this->resolve($this->part, $this->query, $formula_);
            return $formula_;
        }
        finally {
            unset($this->columns_being_virtually_joined[$key]);
        }
    }
}