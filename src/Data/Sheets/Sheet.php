<?php

namespace Osm\Data\Sheets;

use Osm\Core\App;
use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;
use Osm\Data\Queries\Query;

/**
 * @property string $name @required @part
 * @property array $columns @required @part
 * @property Column[] $columns_ @required @part
 * @property string $search_class @required @part
 */
class Sheet extends Object_
{
    protected function default($property) {
        switch ($property) {
            case 'columns': return [];
            case 'columns_': return $this->getColumns();
        }
        return parent::default($property);
    }

    /**
     * Override this method in specialized sheet classes to inject predefined columns into the sheet
     *
     * @return array
     */
    protected function getColumnArray() {
        return $this->columns;
    }

    protected function getColumns() {
        $result = [];

        foreach ($this->getColumnArray() as $name => $data) {
            $result[$name] = Column::new($data, $name, $this);
        }

        return $result;
    }

    /**
     * @param null $set
     * @return Search
     */
    public function search($set = null) {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->create($this->search_class, ['set' => $set],
            null, $this);
    }

    /**
     * @param null $set
     * @return Query
     */
    public function query($set = null) {
        throw new NotSupported("Row set ':set' not supported in sheet ':sheet'",
            ['set' => $set, 'sheet' => $this->name]);
    }
}