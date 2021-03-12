<?php

declare(strict_types=1);

namespace Osm\Framework\Data;

use Osm\Core\App;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;
use Osm\Framework\Data\Columns\Column;
use Osm\Framework\Data\Exceptions\BlueprintError;
use Osm\Framework\Db\Db;
use Osm\Framework\Search\Search;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Illuminate\Database\Schema\Blueprint as TableBlueprint;
use Osm\Framework\Search\Blueprint as IndexBlueprint;
use function Osm\__;

/**
 * @property TagAwareAdapter $cache
 * @property string $sheet_column_table_name
 * @property Db $db
 */
class Data extends Object_
{
    const MAX_PARTITION_WEIGHT = 80;

    protected array $sheets = [];

    public function create(string $sheetName, callable $callback): void {
        $callback($blueprint = $this->createBlueprint($sheetName));

        if (empty($blueprint->columns)) {
            throw new BlueprintError(__(":sheet must have at least one column",
                ['sheet' => $sheetName]));
        }

        // every sheet has to add a method to this class returning a query.
        // We'll use the query object to determine the DB and search engine
        // the sheet is stored in
        /* @var Query $query */
        $query = $this->$sheetName();

        $partitions = $this->assignPartitions($sheetName, $blueprint->columns);

        $mainPartitionCreated = false;
        $additionalPartitionsCreated = false;
        $indexCreated = false;

        try {
            $this->createMainPartition($sheetName, $query->db,
                $partitions[0] ?? []);
            $mainPartitionCreated = true;

            unset($partitions[0]);

            foreach ($partitions as $partitionNo => $columns) {
                $this->createAdditionalPartition($sheetName, $query->db,
                    $partitionNo, $columns);
            }
            $additionalPartitionsCreated = true;

            $this->createIndex($sheetName, $query->search, $blueprint->columns);
            $indexCreated = true;

            $this->insertColumns($blueprint->columns);

            $this->refreshSheet($sheetName);
        }
        catch (\Throwable $e) {
            if ($indexCreated) {
                $this->dropIndex($sheetName, $query->search);
            }

            if ($additionalPartitionsCreated) {
                foreach ($partitions as $partitionNo => $columns) {
                    $this->dropAdditionalPartition($sheetName, $query->db,
                        $partitionNo);
                }
            }

            if ($mainPartitionCreated) {
                $this->dropMainPartition($sheetName, $query->db);
            }

            throw $e;
        }
    }

    public function alter(string $sheetName, callable $callback): void {
        $callback($blueprint = $this->createBlueprint($sheetName));
        $this->refreshSheet($sheetName);
        throw new NotImplemented();
    }

    public function drop(string $sheetName): void {
        // every sheet has to add a method to this class returning a query.
        // We'll use the query object to determine the DB and search engine
        // the sheet is stored in
        /* @var Query $query */
        $query = $this->$sheetName();

        $this->dropIndex($sheetName, $query->search);

        $additionalPartitions = $this->sheet($sheetName)->additional_partitions;
        foreach ($additionalPartitions as $partitionNo => $columns) {
            $this->dropAdditionalPartition($sheetName, $query->db,
                $partitionNo);
        }

        $this->dropMainPartition($sheetName, $query->db);

        $this->deleteColumns($sheetName);

        $this->refreshSheet($sheetName);
    }

    public function exists(string $sheetName): bool {
        return $this->sheet($sheetName)->exists;
    }

    protected function createBlueprint(string $sheetName): Blueprint {
        return Blueprint::new([
            'sheet_name' => $sheetName,
        ]);
    }

    public function sheet(string $sheetName): Sheet {
        return isset($this->sheets[$sheetName])
            ? $this->sheets[$sheetName]
            : $this->sheets[$sheetName] =
                $this->cache->get("data|sheets|{$sheetName}",
                    function (/*ItemInterface $item*/) use ($sheetName) {
                        return Sheet::new([
                            'columns' => $this->db->table($this->sheet_column_table_name)
                                ->where('sheet_name', $sheetName)
                                ->get()
                                ->keyBy(fn(\stdClass $item) => $item->name)
                                ->map(function (\stdClass $item) {
                                    $new = "{$item->class_name}::new";
                                    unset($item->class_name);

                                    return $new((array)$item);
                                })
                                ->toArray()
                        ]);
                    }
                );
    }

    protected function refreshSheet(string $sheetName): void {
        unset($this->sheets[$sheetName]);

        if ($this->cache->hasItem("data|sheets|{$sheetName}")) {
            $this->cache->deleteItem("data|sheets|{$sheetName}");
        }
    }

    /** @noinspection PhpUnused */
    protected function get_cache(): TagAwareAdapter {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->cache;
    }

    /** @noinspection PhpUnused */
    protected function get_sheet_column_table_name(): string {
        return 'sheet_columns';
    }

    /** @noinspection PhpUnused */
    protected function get_db(): Db {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->db;
    }

    /**
     * @param string $sheetName
     * @param Db $db
     * @param Column[] $columns
     */
    protected function createMainPartition(string $sheetName, Db $db,
        array $columns): void
    {
        $tableName = $this->tableName($sheetName);

        $db->create($tableName, function (TableBlueprint $table)
            use ($columns, $db)
        {
//            $table->increments('id');
//            $table->timestamp('created_at')
//                ->default($db->raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')
//                ->default($db->raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            foreach ($columns as $column) {
                $column->createInTable($table);
            }
        });
    }


    /**
     * @param string $sheetName
     * @param Db $db
     * @param int $partitionNo
     * @param Column[] $columns
     */
    protected function createAdditionalPartition(string $sheetName,
        Db $db, int $partitionNo, array $columns): void
    {
        $tableName = $this->tableName($sheetName, $partitionNo);

        $db->create($tableName, function (TableBlueprint $table)
            use ($sheetName, $columns)
        {
            $table->integer('id')->unsigned()->unique();
            $table->foreign('id')
                ->references('id')
                ->on($this->tableName($sheetName))
                ->onDelete('cascade');

            foreach ($columns as $column) {
                $column->createInTable($table);
            }
        });
    }

    /**
     * @param string $sheetName
     * @param Search $search
     * @param Column[] $columns
     */
    protected function createIndex(string $sheetName,
        Search $search, array $columns): void
    {
        $this->dropIndex($sheetName, $search);

        $search->create($sheetName, function(IndexBlueprint $index)
            use ($columns)
        {
            foreach ($columns as $column) {
                $column->createInIndex($index);
            }
        });
    }

    /**
     * @param string $sheetName
     * @param Column[] $columns
     * @return array
     */
    protected function assignPartitions(string $sheetName, array $columns)
        : array
    {
        $sheet = $this->sheet($sheetName);

        $weights = array_map(function ($columns) {
            $weight = 0;

            foreach ($columns as $column) {
                /* @var Column $column */
                $weight += $column->partition_weight;
            }

            return $weight;
        }, $sheet->partitions);

        $partitions = [];
        $no = 0;

        foreach ($columns as $column) {
            while (($weights[$no] ?? 0) >= static::MAX_PARTITION_WEIGHT) {
                $no++;
            }

            $partitionNo = $column->partition_no ?? $no;
            if (!isset($partitions[$partitionNo])) {
                $partitions[$partitionNo] = [];
            }

            $partitions[$partitionNo][] = $column;
            $column->partition_no = $partitionNo;

            if (!isset($column->partition_weight)) {
                continue;
            }

            if (!isset($weights[$partitionNo])) {
                $weights[$partitionNo] = 0;
            }

            $weights[$partitionNo] += $column->partition_weight;
        }

        return $partitions;
    }

    public function tableName(string $sheetName, int $partitionNo = 0)
        : string
    {
        return $partitionNo
            ? $sheetName . '__' . ($partitionNo + 1)
            : $sheetName;
    }

    protected function dropMainPartition(string $sheetName, Db $db): void {
        $tableName = $this->tableName($sheetName);
        if ($db->exists($tableName)) {
            $db->drop($tableName);
        }
    }

    protected function dropAdditionalPartition(string $sheetName, Db $db,
        int $partitionNo): void
    {
        $tableName = $this->tableName($sheetName, $partitionNo);
        if ($db->exists($tableName)) {
            $db->drop($tableName);
        }
    }

    protected function dropIndex(string $sheetName, Search $search): void {
        if ($search->exists($sheetName)) {
            $search->drop($sheetName);
        }
    }

    /**
     * @param Column[] $columns
     */
    protected function insertColumns(array $columns): void {
        foreach ($columns as $column) {
            $this->db->table($this->sheet_column_table_name)
                ->insert($column->toDbRecord());
        }
    }

    protected function deleteColumns(string $sheetName): void {
        $this->db->table($this->sheet_column_table_name)
            ->where('sheet_name', $sheetName)
            ->delete();
    }
}