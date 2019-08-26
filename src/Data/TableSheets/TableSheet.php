<?php

namespace Osm\Data\TableSheets;

use Osm\Core\App;
use Osm\Data\Sheets\Sheet;
use Osm\Data\Tables\Table;

/**
 * @property string $table @required @part
 * @property Table $table_ @required
 */
class TableSheet extends Sheet
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'table': return $this->name;
            case 'table_': return $osm_app->db->tables[$this->table];
        }

        return parent::default($property);
    }

    protected function getColumnArray() {
        $result = [];

        foreach ($this->table_->columns as $column) {
            $result[$column->name] = [];
        }

        return osm_merge($result, parent::getColumnArray());
    }
}