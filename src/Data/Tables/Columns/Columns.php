<?php

namespace Osm\Data\Tables\Columns;

use Osm\Data\Tables\Hints\ColumnHint;
use Osm\Data\Tables\Table;
use Osm\Framework\Data\CollectionRegistry;
use Osm\Framework\Db\Db;

/**
 * @property Table $parent @required
 * @property Db $db @required
 * @property ColumnTypes|ColumnType[] $column_types @required
 */
class Columns extends CollectionRegistry
{
    protected function default($property) {
        switch ($property) {
            case 'not_found_message': return m_("Column ':table.:name' not found",
                ['table' => $this->parent->name]);
            case 'db': return $this->parent->parent->parent;
            case 'column_types': return $this->parent->parent->column_types;
        }
        return parent::default($property);
    }

    protected function get() {
        $result = [];

        $records = $this->db->connection->table('table_columns')
            ->where('table', '=', $this->parent->id)
            ->select(['id', 'name', 'partition', 'pinned', 'type', 'required', 'unsigned', 'length',
                'title__translate'])
            ->selectRaw("COALESCE(title, title__default) AS title")
            ->get();

        foreach ($records as $data) {
            /* @var ColumnHint $data */
            $data->data_type = $this->column_types[$data->type]->data_type;
            if ($data->title__translate) {
                $data->title = m_($data->title);
            }
            unset($data->title__translate);

            $result[$data->name] = Column::new((array)$data, null, $this);
        }

        $this->modified();

        return $result;
    }
}