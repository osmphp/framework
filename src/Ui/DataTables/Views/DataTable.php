<?php

namespace Osm\Ui\DataTables\Views;

use Osm\Core\App;
use Osm\Data\Search\Search;
use Osm\Data\Search\SearchResult;
use Osm\Framework\Views\View;
use Osm\Ui\DataTables\Columns\Column;
use Osm\Ui\DataTables\Exceptions\LimitExceeded;
use Osm\Ui\DataTables\Module;

/**
 * @property string $search @required @part
 * @property Search $search_ @required
 * @property array $columns @required @part
 * @property string $not_found_message @required @part
 * @property string $edit_route @part
 * @property string $main_column @part
 * @property int $rows_per_page @required @part
 * @property string $load_route @required @part
 * @property bool $render_rows @part
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
            case 'search_': return $osm_app->create($this->search);
            case 'columns_': return $this->getColumns();
            case 'model': return $this->getModel();
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
        $this->search_->forDisplay();

        foreach ($this->columns_ as $column) {
            $column->addToSearch();
        }

        if ($this->limit > $this->rows_per_page) {
            throw new LimitExceeded(osm_t("Can't load more than :limit rows in one request", [
                'limit' => $this->rows_per_page,
            ]));
        }

        return $this->search_->offset($this->offset)->limit($this->limit)->get();
    }

    public function getCellUrl() {
        return $this->column->getUrl();
    }

    /**
     * @return string
     */
    public function getCellTemplate() {
        return $this->column->cell_template;
    }

    protected function getModel() {
        return (object)[
            'main_column' => $this->main_column,
            'count' => $this->data->count,
            'rows_per_page' => $this->rows_per_page,
            'load_route' => $this->load_route,
            'columns' => array_map([$this, 'getColumnModel'], $this->columns_),
        ];
    }

    protected function getColumnModel(Column $column) {
        return (object)[
            'width' => $column->width,
        ];
    }
}