<?php

namespace Manadev\Framework\Views;

use Manadev\Core\App;
use Manadev\Core\Object_;
use Manadev\Framework\Views\Exceptions\IdCantBeInferred;

/**
 * @property string $template @required @part
 * @property string $modifier @part
 * @property string $id @part
 * @property string $alias @part
 * @property string $id_ @required @part
 * @property string $view_model @required @part
 * @property object $model
 * @property string $view_model_script @required
 *
 * @property \Manadev\Framework\Views\Module $views @required
 * @property ViewFactory $laravel_view @required
 * @property Rendering $rendering @required
 * @property Iterator $iterator @required
 * @property JsConfig $js_config @required
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
    }

    public function set($data) {
        $this->assignSelfAsParentTo($data);

        return parent::set($data);
    }

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'views': return $m_app->modules['Manadev_Framework_Views'];
            case 'laravel_view': return $this->views->laravel_view;
            case 'rendering': return $m_app[Rendering::class];
            case 'iterator': return $m_app[Iterator::class];
            case 'id_': return $this->inferId();
            case 'view_model_script': return $this->getViewModelScript();
            case 'js_config': return $m_app[JsConfig::class];
        }
        return parent::default($property);
    }

    /**
     * Add logic here to prepare view for rendering (prepare data, modify view tree, create child views). Only
     * called if view is part of layered layout.
     */
    public function prepare() {
    }

    public function __toString() {
        global $m_app; /* @var App $m_app */

        try {
            return $this->laravel_view->render($this);
        }
        catch (\Throwable $e) {
            if (!$m_app->pending_exception) {
                $m_app->pending_exception = $e;
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
            throw new IdCantBeInferred(m_("View parent not assigned, id_ can't be rendered."));
        }

        if (!$this->alias) {
            // Views which are not assigned an 'id' or 'html_id' must have an alias. Alias is resolved
            // automatically for all non-root views in layer files. Otherwise, alias should be assigned manually.
            throw new IdCantBeInferred(m_("View alias not assigned, id_ can't be rendered."));
        }

        return (isset($this->parent->id_) ? $this->parent->id_ . '__' : '') . $this->alias;
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
        return "<script>new {$this->view_model}('#{$this->id_}', " . json_encode($this->model) .
            ");</script>";
    }
}