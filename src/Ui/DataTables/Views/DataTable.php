<?php

namespace Osm\Ui\DataTables\Views;

use Osm\Core\App;
use Osm\Data\Sheets\Search;
use Osm\Data\Sheets\SearchResult;
use Osm\Data\Sheets\Sheet;
use Osm\Framework\Views\View;
use Osm\Ui\DataTables\Columns\Column;
use Osm\Ui\DataTables\Exceptions\LimitExceeded;
use Osm\Ui\DataTables\Module;

/**
 * @property string $sheet @required @part
 * @property Sheet $sheet_ @required
 * @property string $set @part
 * @property Search $search @required
 * @property array $columns @required @part
 * @property string $not_found_message @required @part
 * @property string $edit_route @part
 * @property string $main_column @part
 * @property int $rows_per_page @required @part
 * @property string $load_route @required @part
 * @property bool $render_rows @part
 * @property bool $row_link_disabled @part
 *
 * @property Column[] $columns_ @required
 * @property int $offset @required
 * @property int $limit @required
 * @property SearchResult $data @required
 * @property Module $data_table_module @required
 *
 * @property Column $column @temp
 * @property object $item @temp
 */
class DataTable extends View
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'sheet_': return $osm_app->sheets[$this->sheet];
            case 'search': return $this->sheet_->search($this->set);
            case 'columns_': return $this->getColumns();
            case 'rows_per_page': return $osm_app->settings->data_table_rows_per_page;
            case 'template': return $this->render_rows ? $this->rows_template : $this->full_template;
            case 'data_table_module': return $osm_app->modules['Osm_Ui_DataTables'];

            case 'offset': return 0;
            case 'limit': return $this->rows_per_page;
            case 'data': return $this->getData();
        }
        return parent::default($property);
    }

    /**
     * @required @part
     * @var string
     */
    public $rows_template = 'Osm_Ui_DataTables.rows';

    /**
     * @required @part
     * @var string
     */
    public $full_template = 'Osm_Ui_DataTables.data_table';

    public $view_model = 'Osm_Ui_DataTables.DataTable';

    protected function getColumns() {
        $result = [];

        foreach ($this->columns as $name => $data) {
            if (!isset($data['class'])) {
                $data['class'] = $this->data_table_module->column_types[$data['type']];
            }
            $result[$name] = Column::new($data, $name, $this);
        }

        return $result;
    }

    protected function getData() {
        $this->search->forDisplay();

        foreach ($this->columns_ as $column) {
            $column->addToSearch();
        }

        if ($this->limit > $this->rows_per_page) {
            throw new LimitExceeded(osm_t("Can't load more than :limit rows in one request", [
                'limit' => $this->rows_per_page,
            ]));
        }

        return $this->search->offset($this->offset)->limit($this->limit)->get();
    }

    public function getCellUrl() {
        if ($result = $this->column->getUrl()) {
            // if column generates link for the cell, render it
            return $result;
        }

        if ($this->row_link_disabled) {
            // if row edit links are disabled on data table level, don't render
            return null;
        }

        if ($this->column->row_link_disabled) {
            // if row edit links is disabled in the column, don't render
            return null;
        }

        if (!$this->edit_route) {
            return null;
        }

        // otherwise, render edit link
        return osm_url($this->edit_route, ['id' => $this->item->id]);
    }

    /**
     * @return string
     */
    public function getCellTemplate() {
        return $this->column->cell_template;
    }

    public function rendering() {
        $this->model = osm_merge([
            'main_column' => $this->main_column,
            'count' => $this->data->count,
            'rows_per_page' => $this->rows_per_page,
            'load_route' => $this->load_route,
            'columns' => array_map([$this, 'getColumnModel'], $this->columns_),
        ], $this->model ?: []);
    }

    protected function getColumnModel(Column $column) {
        return (object)[
            'width' => $column->width,
        ];
    }
}