<?php

namespace Osm\Data\Tables;

use Osm\Core\App;
use Osm\Data\Formulas\Types;
use Osm\Data\Tables\Commands\Command;
use Osm\Core\Object_;
use Osm\Framework\Db\Db;
use Osm\Framework\Db\Key;
use Osm\Data\Tables\Columns\Column;
use Osm\Data\Tables\Columns\Columns;
use Illuminate\Database\Schema\Blueprint as SchemaBlueprint;
use Osm\Data\Tables\Columns\Schema as ColumnSchema;

/**
 * @property string $name @required @part
 * @property int $id @part
 * @property Db $db @required
 * @property Tables $parent
 * @property Columns|Column[] $columns @required
 * @property int[] $partitions @required @part
 * @property Key[] $keys @required @part
 * @property ColumnSchema $column_schema @required
 * @property Commands\Runner $command_runner @required
 * @property bool $temp @part
 * @property Blueprint $blueprint @temp
 * @property Partition[] $blueprint_partitions @temp
 */
class Table extends Object_
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'columns': return $this->getColumns();
            case 'partitions': return $this->getPartitions();
            case 'keys': return $this->db->getKeys($this);
            case 'column_schema': return $osm_app[ColumnSchema::class];
            case 'command_runner': return $osm_app[Commands\Runner::class];
        }

        return parent::default($property);
    }

    protected function getPartitions() {
        $result = [];

        foreach ($this->columns as $column) {
            if ($column->partition != 1) {
                $result[$column->partition] = true;
            }
        }
        ksort($result);
        return array_keys($result);
    }

    protected function getColumns() {
        global $osm_app; /* @var App $osm_app */

        if (!$this->parent || !$this->parent->cache_key) {
            return null;
        }

        $result = $osm_app->cache->remember("{$this->parent->cache_key}.{$this->name}",
            function($data) {
                return Columns::new($data);
            }, [$this->parent->cache_key]);

        $result->parent = $this;
        return $result;
    }

    public function create() {
        if ($this->temp) {
            $this->createTempTable();
            return;
        }

        $this->blueprint->columns = array_merge([
            'id' => Column::new(['type' => Column::INT_, 'data_type' => Types::INT_], 'id', $this->blueprint)
                ->unsigned()->title("ID")->pinned(),
        ], $this->blueprint->columns);

        $this->blueprint_partitions = $this->db->partition($this->blueprint->name, $this->blueprint);
        $this->createMainPartition();

        foreach (array_keys($this->blueprint_partitions) as $partitionIndex) {
            if ($partitionIndex === 0) {
                continue;
            }

            $this->createAdditionalPartition($partitionIndex);
        }

        $this->db->tables->register($this->blueprint->name, $this->blueprint->columns);
    }

    protected function createMainPartition() {
        $this->db->schema->create($this->blueprint->name, function(SchemaBlueprint $table) {
            foreach ($this->blueprint_partitions[0]->columns as $column) {
                $this->column_schema->create($column, $table);
            }

            foreach ($this->blueprint->commands as $command) {
                $this->command_runner->run($command, $table, $this->blueprint_partitions, 1);
            }
        });
    }

    protected function createAdditionalPartition($partitionIndex) {
        $table = $this->blueprint->name . '__' . ($partitionIndex + 1);
        $this->db->schema->create($table, function(SchemaBlueprint $table) use($partitionIndex) {
            $table->unsignedInteger('id');
            $table->primary('id');
            $table->foreign('id')->references('id')->on($this->blueprint->name)->onDelete('cascade');

            foreach ($this->blueprint_partitions[$partitionIndex]->columns as $column) {
                $this->column_schema->create($column, $table);
            }

            foreach ($this->blueprint->commands as $command) {
                $this->command_runner->run($command, $table, $this->blueprint_partitions, $partitionIndex + 1);
            }
        });
    }

    protected function createTempTable() {
        $this->db->schema->create($this->blueprint->name, function(SchemaBlueprint $table) {
            $table->temporary();

            foreach ($this->blueprint->columns as $column) {
                $column->partition = 1;
                $this->column_schema->create($column, $table);
            }

            $partitions = [Partition::new(['index' => 0, 'columns' => $this->blueprint->columns])];
            foreach ($this->blueprint->commands as $command) {
                $this->command_runner->run($command, $table, $partitions, 1);
            }
        });
    }

    public function alter() {
        $this->blueprint_partitions = $this->db->partition($this->blueprint->name, $this->blueprint);
        $this->alterMainPartition();

        foreach (array_keys($this->blueprint_partitions) as $partitionIndex) {
            if ($partitionIndex === 0) {
                continue;
            }

            if (!in_array($partitionIndex + 1, $this->partitions)) {
                $this->createAdditionalPartition($partitionIndex);
            }
            else {
                $this->alterAdditionalPartition($partitionIndex);
            }
        }

        $this->db->tables->registerColumns($this->id, $this->blueprint->columns);
        $this->db->tables->unregisterColumns($this->id, $this->blueprint->commands);
        $this->db->tables->refresh();
    }

    protected function alterMainPartition() {
        $this->db->schema->table($this->blueprint->name, function(SchemaBlueprint $table) {
            foreach ($this->blueprint_partitions[0]->columns as $column) {
                if (!$column->exists) {
                    $this->column_schema->create($column, $table);
                }
            }

            foreach ($this->blueprint->commands as $command) {
                $this->command_runner->run($command, $table, $this->blueprint_partitions, 1);
            }
        });
    }

    protected function alterAdditionalPartition($partitionIndex) {
        $table = $this->blueprint->name . '__' . ($partitionIndex + 1);
        if ($this->dropAdditionEmptyPartition($partitionIndex)) {
            return;
        }

        $this->db->schema->table($table, function(SchemaBlueprint $table) use($partitionIndex) {
            foreach ($this->blueprint_partitions[$partitionIndex]->columns as $column) {
                if (!$column->exists) {
                    $this->column_schema->create($column, $table);
                }
            }

            foreach ($this->blueprint->commands as $command) {
                $this->command_runner->run($command, $table, $this->blueprint_partitions, $partitionIndex + 1);
            }
        });
    }

    protected function dropAdditionEmptyPartition($partitionIndex) {
        foreach ($this->blueprint_partitions[$partitionIndex]->columns as $column) {
            if (!$column->exists) {
                return false;
            }

            if (!$this->columnBeingDropped($column)) {
                return false;
            }
        }

        $this->db->schema->drop($this->name . '__' . ($partitionIndex + 1));
        return true;
    }

    protected function columnBeingDropped(Column $column) {
        foreach ($this->blueprint->commands as $command) {
            if ($command->type != Command::DROP_COLUMNS) {
                continue;
            }

            if (in_array($column->name, $command->columns)) {
                return true;
            }
        }

        return false;
    }

    public function drop() {
        $partitions = $this->db->partition($this->name);
        array_shift($partitions);

        foreach ($partitions as $partition) {
            $this->db->schema->drop($this->name . '__' . ($partition->index + 1));
        }
        $this->db->schema->drop($this->name);

        $this->db->tables->unregister($this->name);
    }
}