<?php

namespace Osm\Framework\Db;

use Illuminate\Database\Connection;
use Osm\Data\Tables\Partition;
use Osm\Data\Tables\Table;
use Osm\Data\Tables\Blueprint as TableBlueprint;
use Osm\Data\Tables\Tables;
use Osm\Core\Object_;
use Illuminate\Database\Schema;

/**
 * @property string $name @required @part
 * @property Connection $connection @required
 * @property Schema\Builder $schema @required
 *
 * @see \Osm\Data\Tables\Module:
 *      @property Tables|Table[] $tables @required @default
 * @see \Osm\Data\Tables\Traits\DbTrait:
 *      @method void create(string $table, callable $callback)
 *      @method string temp(callable $callback)
 *      @method void alter(string $table, callable $callback)
 *      @method void drop(string $table)
 *
 * @see \Osm\Data\Tables\Traits\MySqlTrait:
 *      @method Partition[] partition(string $table, TableBlueprint $blueprint = null)
 *      @method Key[] getKeys(Table $table)
 *
 * @see \Osm\Data\Indexing\Traits\MySqlTrait:
 *      @method void createIndexingTriggers(int $id, string $source, string[] $events = [], string[] $columns = [])
 *      @method void dropIndexingTriggers(int $id, string $source, string[] $events = [])
 */
class Db extends Object_
{
    /**
     * @required
     * @var Table[]
     */
    public $temp_tables = [];

    public function default($property) {
        switch ($property) {
            case 'schema': return $this->connection->getSchemaBuilder();
        }
        return parent::default($property);
    }

    public function wrapTable($identifier) {
        return $identifier;
    }

    public function wrap($identifier) {
        return $identifier;
    }
}