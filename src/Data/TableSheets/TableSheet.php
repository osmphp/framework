<?php

namespace Osm\Data\TableSheets;

use Osm\Core\App;
use Osm\Core\Exceptions\NotSupported;
use Osm\Data\Sheets\Sheet;
use Osm\Data\Tables\Table;
use Osm\Framework\Db\Db;

/**
 * @property string $table @required @part
 * @property string $operation_class @required @part
 * @property string $row_class @required @part
 * @property Table $table_ @required
 * @property Db $db @required
 */
class TableSheet extends Sheet
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'table': return $this->name;
            case 'db': return $osm_app->db;
            case 'table_': return $this->db->tables[$this->table];
        }

        return parent::default($property);
    }

    /**
     * @param array $data
     * @return TableOperation
     */
    protected function with($data) {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->create($this->operation_class, $data, null, $this);
    }

    protected function getColumnArray() {
        $result = [];

        foreach ($this->table_->columns as $column) {
            $result[$column->name] = [];
        }

        return osm_merge($result, parent::getColumnArray());
    }


    public function search($set = null) {
        return TableSearch::new(['set' => $set], null, $this);
    }

    public function query($set = null) {
        if (!$set) {
            return $this->db[$this->table];
        }

        throw new NotSupported("Row set ':set' not supported in sheet ':sheet'",
            ['set' => $set, 'sheet' => $this->name]);
    }

    public function insert($values, $set = null) {
        return $this->with([
            'values' => $values,
            'set' => $set,
        ])->insert();
    }

    public function update($criteria, $values, $set = null) {
        $this->with([
            'values' => $values,
            'criteria' => $criteria,
            'set' => $set,
        ])->update();
    }

    public function delete($criteria, $set = null) {
        $this->with([
            'criteria' => $criteria,
            'set' => $set,
        ])->delete();
    }
}