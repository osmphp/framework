<?php

namespace Osm\Ui\Forms\Views;

use Osm\Core\App;
use Osm\Data\Search\Search;
use Osm\Framework\Views\View;
use Osm\Framework\Views\Views\Container;
use Osm\Ui\Forms\Assigner;
use Osm\Ui\Forms\Loader;
use Osm\Ui\Forms\Validator;

/**
 * @property View $header @part
 * @property View $footer @part
 * @property string $route @required @part
 * @property string $method @required
 * @property string $action @required
 * @property string $submitting_message @part
 * @property string $search @required @part
 * @property Search $search_ @required
 * @property int $row_id
 * @property object $data
 */
class Form extends Container
{
    public $template = 'Osm_Ui_Forms.form';
    public $view_model = 'Osm_Ui_Forms.Form';

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'method': return substr($this->route, 0, strpos($this->route, ' '));
            case 'action': return substr($this->route, strpos($this->route, ' ') + 1);
            case 'search_': return $osm_app->create($this->search);
            case 'data': return Loader::load($this);
        }
        return parent::default($property);
    }

    public function rendering() {
        $this->model = osm_merge([
            'submitting_message' => (string)$this->submitting_message,
        ], $this->model ?: []);
    }

    public function load() {
        if (!$this->data) {
            return $this->row_id === null;
        }

        $this->assign($this->data);

        return true;
    }

    public function assign($data) {
        Assigner::assign($this, $data);
    }

    public function validate() {
        return Validator::validate($this);
    }
}