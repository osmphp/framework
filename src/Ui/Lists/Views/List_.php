<?php

namespace Osm\Ui\Lists\Views;

use Illuminate\Support\Collection;
use Osm\Core\App;
use Osm\Data\Sheets\Search;
use Osm\Data\Sheets\SearchResult;
use Osm\Data\Sheets\Sheet;
use Osm\Framework\Views\View;

/**
 * Constructor arguments:
 *
 * @property string $sheet @required @part
 * @property string[] $sheet_columns @required @part
 * @property string $set @part
 *
 * View template properties:
 *
 * @property string $placeholder_template @part
 * @property string $item_template @required @part
 *
 * Computed properties:
 *
 * @property Sheet $sheet_ @required
 * @property Search $search @required
 * @property array $sections @required
 * @property SearchResult $data @required Data for the ordinary rows
 * @property Collection|object[] $unrevealed_items @required
 * @property Collection|object[] $filtered_out_items @required
 * @property Collection|object[] $new_items @required
 *
 * Other configuration properties:
 *
 * @property bool $refreshing Set it to true in the AJAX refresh routes
 *
 * Temp properties:
 *
 * @property object $item @temp
 */
class List_ extends View
{
    public $template = 'Osm_Ui_Lists.list';

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'sheet_': return $osm_app->sheets[$this->sheet];
            case 'search': return $this->sheet_->search($this->set);
            case 'sections': return [
                'data' => $this->data->items,
                'unrevealed' => $this->unrevealed_items,
                'filtered-out' => $this->filtered_out_items,
                'new' => $this->new_items,
            ];
            case 'data': return $this->getData();
            case 'unrevealed_items': return $this->getUnrevealedItems();
            case 'filtered_out_items': return $this->getFilteredOutItems();
            case 'new_items': return $this->getNewItems();
        }
        return parent::default($property);
    }

    protected function getData() {
        $this->search->forDisplay();

        $this->addSearchColumns();

        return $this->search->get();
    }

    protected function getUnrevealedItems() {
        return [];
    }

    protected function getFilteredOutItems() {
        return [];
    }

    protected function getNewItems() {
        return [];
    }

    protected function addSearchColumns() {
        $this->search->select(...$this->sheet_columns);
    }
}