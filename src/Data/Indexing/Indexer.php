<?php

namespace Osm\Data\Indexing;

use Osm\Core\App;
use Osm\Data\TableQueries\Table;
use Osm\Data\TableQueries\TableQuery;
use Osm\Core\Object_;
use Osm\Framework\Db\Db;

/**
 * @property Target $parent @required
 * @property Target $target @required
 * @property string $name @required @part
 * @property string $title @required @part
 * @property Db|TableQuery[] $db @required
 * @property Scope $scope @required
 * @property bool $partial @required
 * @property string $save_table @required
 * @property string $delete_table @required
 */
abstract class Indexer extends Object_
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'db': return $osm_app->db;
            case 'target': return $this->parent;
            case 'partial': return $this->scope->mode == Mode::PARTIAL;
            case 'save_table': return $this->partial
                ? "{$this->name}__n{$this->scope->sources[$this->name]}"
                : null;
            case 'delete_table': return $this->partial
                ? "{$this->name}__d{$this->scope->sources[$this->name]}"
                : null;
        }

        return parent::default($property);
    }

    abstract public function index();

    /**
     * Returns false if no indexing should be done
     *
     * @param TableQuery $query
     * @return bool
     */
    protected function handlePartialMode(TableQuery $query) {
        if ($this->scope->mode != Mode::PARTIAL) {
            return true;
        }

        $sources = $this->getSources($query);
        if (!($sourceCount = count($sources))) {
            return false;
        }

        if ($sourceCount == 1) {
            foreach ($sources as $indexer => $table) {
                $notificationTable = "{$table->table}__n{$indexer}";
                $query->innerJoin("{$notificationTable} AS {$table->alias}__n",
                    "{$table->alias}__n.id = {$table->alias}.id");
            }

            return true;
        }

        $condition = "";
        foreach ($sources as $indexer => $table) {
            $notificationTable = "{$table->table}__n{$indexer}";
            $query->leftJoin("{$notificationTable} AS {$table->alias}__n",
                "{$table->alias}__n.id = {$table->alias}.id");

            if ($condition) {
                $condition .= " OR ";
            }

            $condition .= "{$table->alias}__n.id IS NOT NULL";
        }
        $query->where($condition);
        return true;
    }

    /**
     * @param TableQuery $query
     * @return Table[]
     */
    protected function getSources(TableQuery $query) {
        $result = [];

        foreach ($query->tables as $table) {
            if (!isset($this->scope->sources[$table->table])) {
                continue;
            }

            $result[$this->scope->sources[$table->table]] = $table;
        }

        return $result;
    }

}