<?php

declare(strict_types=1);

namespace Osm\Framework\Data\Columns;

use Illuminate\Database\Query\Builder as DbQuery;
use Illuminate\Database\Schema\Blueprint as TableBlueprint;
use Osm\Framework\Search\Blueprint as IndexBlueprint;

class Id extends Column
{
    protected function get_partition_weight(): int {
        return 0;
    }

    public function createInTable(TableBlueprint $table): void {
        $table->increments($this->name);
    }

    public function createInIndex(IndexBlueprint $index): void {
    }

    public function save(array &$values, \stdClass $data): void {
    }

    public function index(array &$values, \stdClass $data): void {
        $values[$this->name] = $data->{$this->name};
    }

    public function select(DbQuery $query): void {
        $query->addSelect("this.{$this->name}");
    }
}