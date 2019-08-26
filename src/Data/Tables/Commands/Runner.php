<?php

namespace Osm\Data\Tables\Commands;

use Illuminate\Database\Schema\Blueprint as SchemaBlueprint;
use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;
use Osm\Data\Tables\Exceptions\InvalidCommand;
use Osm\Data\Tables\Partition;

/**
 * @property Command $command @temp
 * @property SchemaBlueprint $table @temp
 * @property Partition[] $partitions @temp
 * @property int $partition @temp
 */
class Runner extends Object_
{
    /**
     * @see \Osm\Data\Tables\Commands\Command::$type @handler
     *
     * @param Command $command
     * @param SchemaBlueprint $table
     * @param $partitions
     * @param $partition
     */
    public function run(Command $command, SchemaBlueprint $table, $partitions, $partition) {
        $this->command = $command;
        $this->table = $table;
        $this->partitions = $partitions;
        $this->partition = $partition;

        switch ($this->command->type) {
            case Command::UNIQUE: $this->runUnique(); break;
            case Command::DROP_COLUMNS: $this->runDropColumns(); break;
            default:
                throw new NotSupported(osm_t("Command type ':type' not supported", ['type' => $this->command->type]));
        }
    }

    protected function runUnique() {
        if ($this->getColumnGroupPartition($this->command->columns) === $this->partition) {
            $this->table->unique($this->command->columns);
        }
    }

    protected function runDropColumns() {
        foreach ($this->command->columns as $column) {
            if ($this->getColumnPartition($column) === $this->partition) {
                $this->table->dropColumn($column);
            }
        }
    }

    protected function getColumnPartition($column) {
        foreach ($this->partitions as $partition) {
            if (isset($partition->columns[$column])) {
                return $partition->index + 1;
            }
        }

        throw new InvalidCommand(osm_t("':table.:column' not found", ['table' => $this->table->getTable(),
            'column' => $column]));
    }

    protected function getColumnGroupPartition($columns) {
        $result = null;
        foreach ($this->command->columns as $column) {
            if ($result === null) {
                $result = $this->getColumnPartition($column);
                continue;
            }

            if ($this->getColumnPartition($column) !== $result) {
                throw new InvalidCommand(osm_t("'Columns ':columns' in ':table' should belong to same partition",
                    ['table' => $this->table->getTable(), 'columns' => implode(', ', $columns)]));
            }
        }

        return $result;
    }

}