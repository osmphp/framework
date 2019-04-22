<?php

namespace Manadev\Data\TableSheets;

use Manadev\Core\App;
use Manadev\Data\Sheets\Sheet;
use Manadev\Data\Tables\Table;

/**
 * @property string $table @required @part
 * @property Table $table_ @required
 */
class TableSheet extends Sheet
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'table': return $this->name;
            case 'table_': return $m_app->db->tables[$this->table];
        }

        return parent::default($property);
    }

    protected function getColumnArray() {
        $result = [];

        foreach ($this->table_->columns as $column) {
            $result[$column->name] = [];
        }

        return m_merge($result, parent::getColumnArray());
    }
}