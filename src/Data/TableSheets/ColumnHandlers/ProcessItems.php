<?php

namespace Osm\Data\TableSheets\ColumnHandlers;

use Illuminate\Support\Collection;
use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Data\Files\File;
use Osm\Data\Files\Files;
use Osm\Data\Sheets\Column;
use Osm\Data\TableSheets\TableSearch;
use Osm\Ui\Images\Hints\SizeHint;
use Osm\Ui\Images\Thumbnails;

/**
 * Dependencies:
 *
 * @property Files $files @required
 * @property Thumbnails $thumbnails @required
 *
 * Temp properties:
 *
 * @property TableSearch $search @temp
 * @property Collection $items @temp
 * @property Column $column @temp
 */
class ProcessItems extends Object_
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'files': return $osm_app[Files::class];
            case 'thumbnails': return $osm_app[Thumbnails::class];
        }

        return parent::default($property);
    }

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
                case Column::FILE: $this->processFile(); break;
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

    protected function processFile() {
        if ($this->search->for != TableSearch::FOR_DISPLAY) {
            return;
        }

        if (!isset($this->search->column_extras[$this->column->name])) {
            return;
        }

        /* @var object|SizeHint $column */
        $column = (object)$this->search->column_extras[$this->column->name];

        /* @var int[] $ids */
        $ids = $this->items
            ->map(function ($item) { return $item->{$this->column->name};})
            ->filter()
            ->toArray();

        /* @var object[]|SizeHint[] $sizes */
        $sizes = $this->thumbnails->sizes($column->width, $column->height);

        /* @var File[] $thumbnails */
        $thumbnails = $this->thumbnails->get($ids, $sizes);

        foreach ($this->items as $item) {
            $data = ['id' => $item->{$this->column->name}];

            foreach ($this->files->data_columns as $column) {
                $data[$column] = $item->{"{$this->column->name}__{$column}"};
            }

            $item->{"{$this->column->name}__file"} = File::new($data);
            $item->{"{$this->column->name}__thumbnails"} =
                array_filter($thumbnails, function (File $thumbnail) use ($data) {
                    return $thumbnail->original_file === $data['id'];
                });
        }
    }
}