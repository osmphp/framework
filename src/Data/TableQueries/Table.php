<?php

namespace Manadev\Data\TableQueries;

use Manadev\Data\Formulas\Formulas\Formula;
use Manadev\Data\Tables\Table as TableDefinition;
use Manadev\Core\Object_;
use Manadev\Framework\Db\Db;

/**
 * @property TableQuery $parent @required
 * @property string $alias @required @part
 * @property string $table @required @part
 * @property string $main_table @required @part
 * @property int $partition @required @part
 * @property TableDefinition $table_ @required
 * @property string $join @required @part
 * @property callable $callback @required Avoid caching queries having virtual joins as callbacks are not
 *      serializable. If it really will be of value, refactor this into promise.
 * @property Formula $on @part
 * @property bool $no_alias @temp
 * @property Db $db @required
 */
class Table extends Object_
{
    const JOIN_FROM = 'from';
    const JOIN_INNER = 'inner_join';
    const JOIN_LEFT = 'left_join';
    const JOIN_VIRTUAL = 'virtual_join';

    protected function default($property) {
        switch ($property) {
            case 'main_table':
                return preg_match('/(?<main_table>.+)__(?<partition>\d+)/', $this->table, $matches)
                    ? $matches['main_table']
                    : $this->table;
            case 'partition':
                return preg_match('/(?<main_table>.+)__(?<partition>\d+)/', $this->table, $matches)
                    ? (int)$matches['partition']
                    : 1;
            case 'table_': return $this->db->temp_tables[$this->table] ?? $this->db->tables[$this->main_table];
            case 'db': return $this->parent->db;
        }

        return parent::default($property);
    }
}