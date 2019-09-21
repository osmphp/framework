<?php

namespace Osm\Framework\Views;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Framework\Layers\Layout;
use Osm\Framework\Views\Exceptions\IdCantBeInferred;

/**
 * @property string $template @required @part
 * @property string $modifier @part
 * @property string $id @part
 * @property string $alias @part
 * @property string $id_ @required @part
 * @property string $view_model @required @part
 * @property int $sort_order @part // used in Container views
 * @property array|null $model
 * @property string $view_model_script @required
 *
 * @property \Osm\Framework\Views\Module $module @required
 * @property ViewFactory $laravel_view @required
 * @property Rendering $rendering @required
 * @property Iterator $iterator @required
 * @property Layout $layout @required
 */
class View extends Object_
{
    /**
     * @var View
     */
    public $parent = null;

    public function __construct($data = []) {
        parent::__construct($data);

        // if no view is rendered, parent is set to null
        $this->parent = $this->rendering->current_view;

        $this->assignSelfAsParentTo($data);
    }

    public function set($data) {
        $this->assignSelfAsParentTo($data);

        return parent::set($data);
    }

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'module': return $osm_app->modules['Osm_Framework_Views'];
            case 'laravel_view': return $this->module->laravel_view;
            case 'rendering': return $osm_app[Rendering::class];
            case 'iterator': return $osm_app[Iterator::class];
            case 'id_': return $this->inferId();
            case 'view_model_script': return $this->getViewModelScript();
            case 'layout': return $osm_app->layout ?? Layout::new();
        }
        return parent::default($property);
    }

    public function __toString() {
        global $osm_app; /* @var App $osm_app */

        try {
            return $this->laravel_view->render($this);
        }
        catch (\Throwable $e) {
            if (!$osm_app->pending_exception) {
                $osm_app->pending_exception = $e;
            }
            return '';
        }
    }

    public function rendering() {
    }

    public function rendered($result) {
        return $result;
    }

    protected function inferId() {
        if ($this->id) {
            return $this->id;
        }

        if (!$this->parent) {
            // Views which are not assigned an 'id' or 'id_' must have a "parent" view.
            // Parent view is resolved automatically for all non-root views in layer files and for all
            // views created in template files. Otherwise, parent view should be assigned manually.
            throw new IdCantBeInferred(osm_t("View parent not assigned, id_ can't be rendered."));
        }

        if (!$this->alias) {
            // Views which are not assigned an 'id' or 'html_id' must have an alias. Alias is resolved
            // automatically for all non-root views in layer files. Otherwise, alias should be assigned manually.
            throw new IdCantBeInferred(osm_t("View alias not assigned, id_ can't be rendered."));
        }

        $alias = isset($this->parent->content) || isset($this->parent->views)
            ? "_{$this->alias}"
            : $this->alias;

        if (starts_with($alias, '_views_')) {
            $alias = substr($alias, strlen('_views_'));
        }

        if ($this->parent->alias == 'content') {
            return (!empty($this->parent->parent->id_) ? $this->parent->parent->id_ . '__' : '') . $alias;
        }

        return (!empty($this->parent->id_) ? $this->parent->id_ . '__' : '') . $alias;
    }

    public function __isset($key) {
        try {
            return parent::__isset($key);
        }
        catch (IdCantBeInferred $e) {
            return false;
        }
    }

    public function assignSelfAsParentTo($data) {
        foreach ($this->iterator->iterateData($data) as $property => $view) {
            $view->parent = $this;
            $view->alias = $property;
        }
    }

    /**
     * @return string
     */
    protected function getViewModelScript() {
        /** @noinspection BadExpressionStatementJS */
        return "<script>new {$this->view_model}('#{$this->id_}', " .
            json_encode($this->model ? (object)$this->model : null) .
            ")</script>";
    }
}