<?php

namespace Osm\Data\TableSheets;

use Illuminate\Support\Collection;
use Osm\Core\App;
use Osm\Data\Sheets\Search;
use Osm\Data\Sheets\SearchResult;
use Osm\Data\Sheets\Column;
use Osm\Data\TableQueries\TableQuery;
use Osm\Data\TableSheets\ColumnHandlers\ProcessItems;
use Osm\Data\TableSheets\ColumnHandlers\SelectColumns;
use Osm\Framework\Db\Db;

/**
 * @property TableSheet $parent @required
 *
 * Dependencies:
 *
 * @property Db|TableQuery[] $db @required
 * @property SelectColumns $select_columns @required
 * @property ProcessItems $process_items @required
 *
 * @method TableQuery query()
 * @property int $count @temp
 * @property Collection $items @temp
 * @property TableQuery $query @temp
 */
class TableSearch extends Search
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'db': return $osm_app->db;
            case 'select_columns': return $osm_app[SelectColumns::class];
            case 'process_items': return $osm_app[ProcessItems::class];

            case 'count': return $this->getCount();
            case 'items': return $this->getItems();
        }
        return parent::default($property);
    }

    public function get() {
        try {
            return SearchResult::new([
                'count' => $this->count,
                'items' => $this->items,
            ]);
        }
        finally {
            unset($this->count);
            unset($this->items);
        }
    }

    protected function getCount() {
        $query = $this->query();
        $this->applyFilters($query);
        return $query->value("DISTINCT_COUNT(id)");
    }

    protected function getItems() {
        $this->query = $this->query();
        $this->applyFilters($this->query);

        $this->query->select('id');

        foreach ($this->columns as $column) {
            $this->select_columns->select($this,
                $this->getColumnDefinition($column));
        }

        $items = $this->query
            ->limit($this->limit)
            ->offset($this->offset)
            ->get();

        foreach ($this->columns as $column) {
            $this->process_items->process($this, $items,
                $this->getColumnDefinition($column));
        }

        return $items;
    }

    /**
     * @param TableQuery $query
     */
    protected function applyFilters($query) {
        if ($this->id) {
            $query->where("id = ?", $this->id);
        }
    }
}