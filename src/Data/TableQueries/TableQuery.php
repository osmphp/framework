<?php

namespace Osm\Data\TableQueries;

use Illuminate\Support\Collection;
use Osm\Core\App;
use Osm\Data\Queries\Part;
use Osm\Data\Queries\Query;
use Osm\Data\TableQueries\Exceptions\InvalidInto;
use Osm\Data\TableQueries\Traits\JoinTrait;
use Osm\Framework\Db\Db;
use Osm\Data\Tables\Table as TableDefinition;
use Osm\Framework\Db\Key;

/**
 * @property string $from @required @part
 * @property Resolver $resolver @required
 * @property Generator $generator @required
 * @property Db $db @required
 * @method TableQuery clone(...$methods)
 * @method TableQuery where(string $formula, ...$parameters)
 * @method TableQuery select($formulas, ...$parameters)
 * @method TableQuery innerJoin(string $table, string $on, ...$parameters)
 * @method TableQuery leftJoin(string $table, string $on, ...$parameters)
 */
class TableQuery extends Query
{
    use JoinTrait;

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'resolver': return $osm_app[Resolver::class];
            case 'generator': return $osm_app[Generator::class];
        }

        return parent::default($property);
    }

    /**
     * @param string|string[] $formulas
     * @param array $parameters
     * @return Collection
     */
    public function get($formulas = [], ...$parameters) {
        $this->select($formulas, ...$parameters);

        $this->generator->reset()->generateSelect($this);
        return collect($this->db->connection->select($this->generator->sql, $this->generator->bindings));
    }

    public function insert($values, $onDuplicateKey = OnDuplicateKey::ERROR) {
        $table = $this->tables['this']->table_;
        $values = $this->partitionValues($table, $values);
        $id = null;

        $this->db->connection->transaction(function() use ($table, $values, $onDuplicateKey, &$id) {
            $this->generator->reset()->generateInsert($this, $table->name, $values[1] ?? [], $onDuplicateKey);
            $this->db->connection->insert($this->generator->sql, $this->generator->bindings);
            $id = $this->db->connection->getPdo()->lastInsertId();
            $id = is_numeric($id) ? (int)$id : $id;
            unset($values[1]);

            foreach ($table->partitions as $partition) {
                $partitionValues = $values[$partition] ?? [];
                $partitionValues['id'] = $id;
                $this->generator->reset()->generateInsert($this, "{$table->name}__{$partition}", $partitionValues,
                    $onDuplicateKey);
                $this->db->connection->insert($this->generator->sql, $this->generator->bindings);
            }
        });

        return $id;
    }

    protected function partitionValues(TableDefinition $table, $values) {
        $result = [];
        foreach ($table->columns as $name => $column) {
            if (!array_key_exists($name, $values)) {
                continue;
            }

            if (!isset($result[$column->partition])) {
                $result[$column->partition] = [];
            }

            $result[$column->partition][$name] = $values[$name];
        }
        return $result;
    }

    public function into($table, $onDuplicateKey = OnDuplicateKey::ERROR) {
        $indexes = array_flip(array_keys($this->columns));
        $table_ = $this->db->temp_tables[$table] ?? $this->db->tables[$table];
        $indexes = $this->partitionValues($table_, $indexes);

        $this->db->connection->transaction(function() use ($table_, $indexes, $onDuplicateKey) {
            // bulk update main partition
            $query = $this->removeAllColumnsFromQueryExcept($indexes[1]);
            $this->generator->reset()->generateInto($query, $table_->name, $onDuplicateKey);
            $this->db->connection->insert($this->generator->sql, $this->generator->bindings);

            // bulk update each additional partition
            foreach ($table_->partitions as $partition) {
                $query = $this->removeAllColumnsFromQueryExcept($indexes[$partition]);
                $this->selectPrimaryKeyInto($query, $table_);
                $this->generator->reset()->generateInto($query, $table_->name, $onDuplicateKey);
                $this->db->connection->insert($this->generator->sql, $this->generator->bindings);
            }
        });
    }

    /**
     * @param int[] $indexes
     * @return TableQuery
     */
    protected function removeAllColumnsFromQueryExcept($indexes) {
        $result = $this->clone(...Part::NOT_COLUMNS);

        foreach ($this->columns as $alias => $column) {
            if (isset($indexes[$alias])) {
                $result->select($column->formula, ...$this->parameter_collector->collect($column));
            }

        }

        return $result;
    }

    protected function selectPrimaryKeyInto(TableQuery $query, TableDefinition $table) {
        $key = $this->findUsedUniqueKey($table);

        $on = '';
        $parameters = [];
        foreach ($key->columns as $column) {
            $formula = $this->columns[$column];

            if ($on) {
                $on .= ' AND ';
            }

            $on .= "__primary__.$column = {$formula->expr}";
            $parameters = array_merge($parameters, $this->parameter_collector->collect($formula));
        }

        $query
            ->leftJoin("{$$table->name} AS __primary__", $on, ...$parameters)
            ->select("__primary__.id");
    }

    protected function findUsedUniqueKey(TableDefinition $table) {
        foreach ($table->keys as $key) {
            if ($key->type != Key::PRIMARY && $key->type != Key::UNIQUE) {
                continue;
            }

            if ($this->isUniqueKeyUsed($key)) {
                return $key;
            }
        }

        throw new InvalidInto(osm_t("Partitioned INTO statement should reference unique key in SELECT columns"));
    }

    /**
     * @param Key $key
     * @return bool
     */
    protected function isUniqueKeyUsed($key) {
        foreach ($key->columns as $column) {
            if (!isset($this->columns[$column])) {
                return false;
            }
        }

        return true;
    }

    public function update($values) {
        $table = $this->tables['this']->table_;
        $values = $this->partitionValues($table, $values);
        $this->db->connection->transaction(function() use ($table, $values) {
            foreach ($values as $partition => $partitionValues) {
                $query = $this->clone(...Part::IDENTITY);
                if ($partition == 1) {
                    $mainAlias = "this";
                }
                else {
                    $tables = $query->tables;

                    unset($tables["this"]);
                    unset($tables["this__{$partition}"]);

                    $query->tables = [];
                    $query->from("{$table->name}__{$partition} AS this__{$partition}")
                        ->leftJoin("$table->name AS this", "this.id = this__{$partition}.id");
                    $query->tables = array_merge($query->tables, $tables);

                    $mainAlias = "this__{$partition}";
                }

                $query->tables[$mainAlias]->no_alias = true;
                try {
                    $this->generator->reset()->generateUpdate($query, $mainAlias, $partitionValues);
                    $this->db->connection->update($this->generator->sql, $this->generator->bindings);
                }
                finally {
                    $query->tables[$mainAlias]->no_alias = null;
                }
            }
        });
    }

    public function delete() {
        $this->tables['this']->no_alias = true;
        try {
            $this->generator->reset()->generateDelete($this);
            $this->db->connection->delete($this->generator->sql, $this->generator->bindings);
        }
        finally {
            $this->tables['this']->no_alias = null;
        }
    }
}