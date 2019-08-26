<?php

namespace Osm\Ui\DataTables\Columns;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Framework\Http\UrlGenerator;
use Osm\Ui\DataTables\Views\DataTable;

/**
 * @property DataTable $parent @required
 * @property string $name @required @part
 * @property string $type @required @part
 * @property string $title @part
 * @property float $width @required @part
 * @property string $modifier @part
 * @property string $button_title @required @part
 * @property string $option_list @required @part
 * @property UrlGenerator $url_generator @required
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
        $this->parent->search_->select($this->name);
    }

    public function getUrl() {
        return null;
    }

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'width': return $this->getWidth();
            case 'url_generator': return $m_app[UrlGenerator::class];
        }

        return parent::default($property);
    }

    protected function getWidth() {
        return 150.0;
    }
}