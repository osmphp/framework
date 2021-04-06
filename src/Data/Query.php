<?php

declare(strict_types=1);

namespace Osm\Framework\Data;

use Illuminate\Database\Query\Builder as DbQuery;
use Osm\Core\App;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Osm\Framework\Data\Column;
use Osm\Framework\Data\Hints\Result;
use Osm\Framework\Db\Db;
use Osm\Framework\Search\Search;
use function Osm\__;
use Osm\Framework\Data\Exceptions\QueryError;

/**
 * @property string $sheet_name
 * @property Filters\Filter $filter
 * @property Db $db
 * @property Search $search
 * @property Data $data
 * @property Sheet $sheet
 * @property bool $count
 */
class Query extends Object_
{
    /**
     * @var string[]
     */
    public array $select = [];

    public function insert(\stdClass $data): int {
        return $this->db->transaction(function() use ($data) {
            $data->id = $this->insertIntoMainPartition($data);

            foreach ($this->sheet->additional_partitions as $no => $columns) {
                $this->insertIntoAdditionalPartition($no, $columns, $data);
            }

            $this->insertIntoChildSheets($data);

            //$this->insertIntoIndex($data);

            return $data->id;
        });
    }

    protected function insertIntoMainPartition(\stdClass $data): int {
        $values = [];

        foreach ($this->sheet->main_partition as $column) {
            $column->save($values, $data);
        }

        return $this->db->table($this->data->tableName($this->sheet_name))
            ->insertGetId($values);
    }

    /**
     * @param int $no
     * @param Column[] $columns
     * @param \stdClass $data
     */
    protected function insertIntoAdditionalPartition(int $no, array $columns,
        \stdClass $data): void
    {
        $values = ['id' => $data->id];

        foreach ($columns as $column) {
            $column->save($values, $data);
        }

        $this->db->table($this->data->tableName($this->sheet_name, $no))
            ->insert($values);
    }

    protected function insertIntoChildSheets(\stdClass $data): void {
        foreach ($this->sheet->columns as $column) {
            $column->insertIntoChildSheet($data);
        }
    }

    public function update(\stdClass $data): int {
        $count = $this->updateMainPartition($data);

        foreach ($this->sheet->additional_partitions as $no => $columns) {
            $this->updateAdditionalPartition($no, $columns, $data);
        }

        return $count;
    }

    protected function updateMainPartition(\stdClass $data): int {
        $values = [];

        foreach ($this->sheet->main_partition as $column) {
            $column->save($values, $data);
        }

        if (empty($values)) {
            return 0;
        }

        $mainTableName = $this->data->tableName($this->sheet_name);
        $dbQuery = $this->db->table($mainTableName, 'this');
        $this->filter->applyToDbQuery($dbQuery);

        return $dbQuery->update($values);
    }

    protected function updateAdditionalPartition(int $no, array $columns,
        \stdClass $data): void
    {

    }

    public function delete(): int {
        $mainTableName = $this->data->tableName($this->sheet_name);
        $dbQuery = $this->db->table($mainTableName, 'this');
        $this->filter->applyToDbQuery($dbQuery);

        return $dbQuery->delete();
    }

    public function bulkInsert(\stdClass $data): void {
        throw new NotImplemented();
    }

    public function whereEquals(string $columnName, mixed $value): static
    {
//        if (!$this->sheet->columns[$columnName]->filterable) {
//            throw new QueryError(__(
//                "Make ':sheet.:column' column filterable before trying to filter by it.",
//                ['sheet' => $this->sheet_name, 'column' => $columnName]));
//        }
        
        $this->filter->filters[] = Filters\Equals::new([
            'column_name' => $columnName,
            'value' => $value,
        ]);

        return $this;
    }

    public function get(string ...$columnNames): array|\stdClass|Result {
        $this->select(...$columnNames);

//        $searchQuery = $this->search->index($this->sheet_name);
//        $this->filter->apply($searchQuery);
//        $searchResult = $searchQuery->get();

        $mainTableName = $this->data->tableName($this->sheet_name);
        $dbQuery = $this->db->table($mainTableName, 'this');
        $this->filter->applyToDbQuery($dbQuery);

        if (!empty($this->select)) {
            foreach ($this->select as $columnName) {
                $this->sheet->columns[$columnName]->select($dbQuery);
            }

            $items = $dbQuery->get()->toArray();
        }
        else {
            $items = $dbQuery->pluck('id')->toArray();
        }

        if (!$this->count) {
            return $items;
        }

        $dbQuery = $this->db->table($mainTableName, 'this');
        $this->filter->applyToDbQuery($dbQuery);
        $count = $dbQuery->value($this->db->raw('COUNT(this.id)'));

        return (object)[
            'count' => $count,
            'items' => $items,
        ];
    }

    public function count(bool $count = true): static {
        $this->count = $count;
        return $this;
    }

    public function rows(string ...$columnNames): array {
        return $this->get(...$columnNames)->rows;
    }

    public function first(string ...$columnNames): ?\stdClass {
        return $this->rows(...$columnNames)[0] ?? null;
    }

    public function value($columnName): mixed {
        if (($row = $this->first($columnName)) === null) {
            return null;
        }

        foreach ($row as $property => $value) {
            return $value;
        }

        return null;
    }

    /** @noinspection PhpUnused */
    protected function get_filter(): Filters\LogicalFilter {
        return Filters\And_::new();
    }

    /** @noinspection PhpUnused */
    protected function get_db(): Db {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->db;
    }

    /** @noinspection PhpUnused */
    protected function get_search(): Search {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->search;
    }

    /** @noinspection PhpUnused */
    protected function get_data(): Data {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->data;
    }

    /** @noinspection PhpUnused */
    protected function get_sheet(): Sheet {
        return $this->data->get($this->sheet_name);
    }

    protected function insertIntoIndex(\stdClass $data): void {
        $values = [];

        foreach ($this->sheet->columns as $column) {
            $column->index($values, $data);
        }

        $this->search->index($this->sheet_name)->insert($values);
    }

    public function select(...$columnNames): static {
        $this->select = array_unique(array_merge($this->select, $columnNames));

        return $this;
    }
}