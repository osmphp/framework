<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Framework\Data;

use Osm\Core\Object_;
use Osm\Core\Attributes\Serialized;

/**
 * @property bool $exists
 * @property Column[] $columns #[Serialized]
 * @property array $partitions
 * @property Column[] $main_partition
 * @property array $additional_partitions
 */
class Sheet extends Object_
{
    /** @noinspection PhpUnused */
    protected function get_exists(): bool {
        return count($this->columns) > 0;
    }

    /** @noinspection PhpUnused */
    protected function get_partitions(): array {
        $partitions = [];

        foreach ($this->columns as $column) {
            if (!isset($partitions[$column->partition_no])) {
                $partitions[$column->partition_no] = [];
            }

            $partitions[$column->partition_no][$column->name] = $column;
        }

        return $partitions;
    }

    /** @noinspection PhpUnused */
    protected function get_main_partition(): array {
        return $this->partitions[0];
    }

    /** @noinspection PhpUnused */
    protected function get_additional_partitions(): array {
        $partitions = $this->partitions;
        unset($partitions[0]);

        return $partitions;
    }
}