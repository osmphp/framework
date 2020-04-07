<?php

namespace Osm\Ui\DataTables\Columns;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Framework\Http\Url;
use Osm\Ui\DataTables\Views\DataTable;

/**
 * @property DataTable $parent @required
 * @property string $name @required @part
 * @property string $type @required @part
 * @property string $title @part
 * @property float $width @required @part
 * @property string $modifier @part
 * @property string $button_icon @part
 * @property string $button_title @part
 * @property string $option_list @required @part
 * @property Url $url @required
 * @property bool $row_link_disabled @part
 */
class Column extends Object_
{
    const STRING = 'string';
    const OPTION = 'option';
    const EDIT_BUTTON = 'edit_button';

    /**
     * @required @part
     *
     * @var string
     */
    public $cell_template = 'Osm_Ui_DataTables.cells.default';

    public function addToSearch() {
        $this->parent->search->select($this->name);
    }

    public function getUrl() {
        return null;
    }

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'width': return $this->getWidth();
            case 'url': return $osm_app->url;
        }

        return parent::default($property);
    }

    protected function getWidth() {
        return 150.0;
    }
}