<?php

namespace Osm\Data\TableSheets\ColumnHandlers;

use Osm\Core\App;
use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;
use Osm\Data\Files\Files;
use Osm\Data\Sheets\Column;
use Osm\Data\TableSheets\TableSearch;

/**
 * Dependencies:
 *
 * @property Files $files @required
 *
 * Temps:
 *
 * @property TableSearch $search @temp
 * @property Column $column @temp
 */
class SelectColumns extends Object_
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'files': return $osm_app[Files::class];
        }

        return parent::default($property);
    }

    /**
     * @param TableSearch $search
     * @param Column $column
     *
     * @see \Osm\Data\Sheets\Column::$type @handler
     */
    public function select(TableSearch $search, Column $column) {
        $this->search = $search;
        $this->column = $column;

        try {
            switch ($this->column->type) {
                case Column::SECRET: $this->selectSecret(); break;
                case Column::OPTION: $this->selectOption(); break;
                case Column::FILE: $this->selectFile(); break;
                default: $this->selectColumn(); break;
            }
        }
        finally {
            $this->search = null;
            $this->column = null;
        }
    }

    protected function selectSecret() {
        // columns containing secrets are not selected
    }

    protected function selectOption() {
        $this->selectColumn();

        if ($this->search->for != TableSearch::FOR_DISPLAY) {
            return;
        }

        if (!$this->column->option_list_->supports_db_queries) {
            // will add titles later, after retrieving items from database
            return;
        }

        $this->column->option_list_->addToQuery($this->search->query,
            $this->column->name, ['title' => "{$this->column->name}__title"]);
    }

    protected function selectFile() {
        if ($this->column->formula) {
            throw new NotSupported(
                osm_t("':column' image column formula are not supported", [
                    'column' => $this->column->name,
                ]));
        }

        $this->selectColumn();
        foreach ($this->files->data_columns as $column) {
            $this->selectRelationColumn($column);
        }
    }

    protected function selectColumn() {
        $this->search->query->select($this->column->formula
            ? "{$this->column->formula} AS {$this->column->name}"
            : $this->column->name);
    }

    protected function selectRelationColumn($column) {
        $this->search->query->select("{$this->column->name}.{$column}" .
            " AS {$this->column->name}__{$column}");
    }
}