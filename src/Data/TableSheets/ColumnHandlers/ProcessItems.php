<?php

namespace Osm\Data\TableSheets\ColumnHandlers;

use Illuminate\Support\Collection;
use Osm\Core\Object_;
use Osm\Data\Sheets\Column;
use Osm\Data\TableSheets\TableSearch;

/**
 * Temp properties:
 *
 * @property TableSearch $search @temp
 * @property Collection $items @temp
 * @property Column $column @temp
 */
class ProcessItems extends Object_
{
    /**
     * @param TableSearch $search
     * @param Collection $items
     * @param Column $column
     *
     * @see \Osm\Data\Sheets\Column::$type @handler
     */
    public function process(TableSearch $search, Collection $items,
        Column $column)
    {
        $this->search = $search;
        $this->items = $items;
        $this->column = $column;

        try {
            switch ($this->column->type) {
                case Column::OPTION: $this->processOption(); break;
                default: break; // by default, do nothing
            }
        }
        finally {
            $this->search = null;
            $this->items = null;
            $this->column = null;
        }
    }

    protected function processOption() {
        if ($this->search->for != TableSearch::FOR_DISPLAY) {
            return;
        }

        if ($this->column->option_list_->supports_db_queries) {
            // titles are already in result, as title column
            // has been added to SELECT
            return;
        }

        $this->column->option_list_->addToCollection($this->items,
            $this->column->name, ['title' => "{$this->column->name}__title"]);
    }
}