<?php

namespace Manadev\Framework\Db;

use Illuminate\Database\Connection;
use Manadev\Data\Tables\Partition;
use Manadev\Data\Tables\Table;
use Manadev\Data\Tables\Blueprint as TableBlueprint;
use Manadev\Data\Tables\Tables;
use Manadev\Core\Object_;
use Illuminate\Database\Schema;

/**
 * @property string $name @required @part
 * @property Connection $connection @required
 * @property Schema\Builder $schema @required
 *
 * @see \Manadev\Data\Tables\Module:
 *      @property Tables|Table[] $tables @required @default
 * @see \Manadev\Data\Tables\Traits\DbTrait:
 *      @method void create(string $table, callable $callback)
 *      @method string temp(callable $callback)
 *      @method void alter(string $table, callable $callback)
 *      @method void drop(string $table)
 *
 * @see \Manadev\Data\Tables\Traits\MySqlTrait:
 *      @method Partition[] partition(string $table, TableBlueprint $blueprint = null)
 *      @method Key[] getKeys(Table $table)
 *
 * @see \Manadev\Data\Indexing\Traits\MySqlTrait:
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