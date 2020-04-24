<?php

namespace Osm\Ui\Forms\Views;

use Osm\Core\App;
use Osm\Data\Sheets\Search;
use Osm\Data\Sheets\Sheet;
use Osm\Framework\Views\Views\Container;
use Osm\Ui\Forms\FieldHandlers\Assigner;
use Osm\Ui\Forms\FieldHandlers\Preparer;
use Osm\Ui\Forms\FieldHandlers\Fetcher;

/**
 * @property string $sheet @required @part
 * @property Sheet $sheet_ @required
 * @property string $set @part
 * @property Search $search @required
 *
 * @property string $route @required @part
 * @property string $method @required
 * @property string $action @required
 * @property string $submitting_message @part
 * @property int $row_id
 * @property object $data
 * @property string $autocomplete_prefix @part Prefix added to element names to
 *      scope browser auto-completion
 * @property bool $focus @part If set, sets the focus on the first form element
 */
class Form extends Container
{
    public $template = 'Osm_Ui_Forms.form';
    public $view_model = 'Osm_Ui_Forms.Form';

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'empty': return false;

            case 'method': return substr($this->route, 0, strpos($this->route, ' '));
            case 'action': return substr($this->route, strpos($this->route, ' ') + 1);
            case 'sheet_': return $osm_app->sheets[$this->sheet];
            case 'search': return $this->sheet_->search($this->set);
            case 'data': return Fetcher::fetch($this);
        }
        return parent::default($property);
    }

    public function rendering() {
        $this->model = osm_merge([
            'submitting_message' => (string)$this->submitting_message,
        ], $this->model ?: []);

        Preparer::prepare($this, ['prefix' => $this->autocomplete_prefix]);
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
}