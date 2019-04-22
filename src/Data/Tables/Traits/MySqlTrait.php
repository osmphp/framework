<?php

namespace Manadev\Data\Tables\Traits;

use Manadev\Data\Tables\Columns\Column;
use Manadev\Data\Tables\Partition;
use Manadev\Data\Tables\Table;
use Manadev\Data\Tables\Blueprint;
use Manadev\Core\App;
use Manadev\Framework\Db\Db;
use Manadev\Framework\Db\Key;

/**
 * @property App $app
 */
trait MySqlTrait
{
    public function partition($table, Blueprint $blueprint = null) {
        $db = $this; /* @var Db $db */
        $partitions = [];

        if (isset($db->tables[$table])) {
            foreach ($db->tables[$table]->columns as $column) {
                $this->partitionColumn($partitions, $column);
            }
        }

        if ($blueprint) {
            foreach ($blueprint->columns as $column) {
                $this->partitionColumn($partitions, $column);
            }
        }

        return $partitions;
    }

    /**
     * @param Partition[] $partitions
     * @param Column $column
     */
    protected function partitionColumn(&$partitions, $column) {
        if ($column->pinned) {
            $column->partition = 1;
        }

        for ($partition = $column->partition ?: 1; ; $partition++) {
            if (!isset($partitions[$partition - 1])) {
                $partitions[$partition - 1] = Partition::new([
                    'index' => $partition - 1,

                    // in MySql one table row can't be larger than 64Kb. We leave some more reserved space
                    'available' => $partition == 1
                        ? 65535 - 4096
                        : 65535 - 1024,

                    'columns' => [],
                ]);
            }
            /* @var Partition $partition_ */
            $partition_ = $partitions[$partition - 1];
            $columnSize = $this->getColumnSize($column);

            if ($column->partition || $partition_->available - $columnSize >= 0) {
                $column->partition = $partition;
                $partition_->available -= $columnSize;
                $partition_->columns[$column->name] = $column;
                break;
            }
        }
    }

    /**
     * @param Column $column
     * @return int
     */
    protected function getColumnSize($column) {
        if ($column->length) {
            return 4 + 4 * $column->length;
        }

        return 4 + 20;
    }

    public function getKeys(Table $table) {
        /* @var Db $db */
        $db = $this;

        $schema = $db->connection->getDoctrineSchemaManager();

        $result = [];
        foreach ($schema->listTableIndexes($table->name) as $index) {
            $result[$index->getName()] = Key::new([
                'type' => $index->isPrimary() ? Key::PRIMARY
                    : ($index->isUnique() ? Key::UNIQUE
                        : Key::INDEX
                    ),
                'columns' => $index->getColumns(),

            ], $index->getName(), $table);
        }

        return $result;
    }
}