<?php

namespace Osm\Data\TableSheets;

use Illuminate\Support\Collection;
use Osm\Core\App;
use Osm\Data\Sheets\Search;
use Osm\Data\Sheets\SearchResult;
use Osm\Data\Sheets\Column;
use Osm\Data\TableQueries\TableQuery;
use Osm\Framework\Db\Db;

/**
 * @property TableSheet $parent @required
 * @property Db|TableQuery[] $db @required
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
            $column_ = $this->getColumnDefinition($column);

            $this->selectRawData($column_);
            $this->selectOptionTitle($column_);
        }

        return $this->processItems($this->query->limit($this->limit)->offset($this->offset)->get());
    }

    protected function selectRawData(Column $column) {
        $this->query->select($column->formula ?
            "{$column->formula} AS {$column->name}" :
            $column->name);
    }

    protected function selectOptionTitle(Column $column) {
        if ($this->for != static::FOR_DISPLAY) {
            return;
        }

        if (!$column->option_list_) {
            return;
        }

        if (!$column->option_list_->supports_db_queries) {
            // will add titles later, after retrieving items from database
            return;
        }

        $column->option_list_->addToQuery($this->query, $column->name, ['title' => "{$column->name}__title"]);
    }

    protected function processItems(Collection $items) {
         foreach ($this->columns as $column) {
            $column_ = $this->getColumnDefinition($column);

            $this->addOptionTitleToItems($items, $column_);
        }

        return $items;
    }

    protected function addOptionTitleToItems(Collection $items, Column $column) {
        if ($this->for != static::FOR_DISPLAY) {
            return;
        }

        if (!$column->option_list_) {
            return;
        }

        if ($column->option_list_->supports_db_queries) {
            // titles are already in result, as title column has been added to SELECT
            return;
        }

        $column->option_list_->addToCollection($items, $column->name, ['title' => "{$column->name}__title"]);
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