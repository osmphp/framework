<?php

namespace Osm\Framework\Views;

use Illuminate\View\Factory;
use Illuminate\View\View as LaravelView;
use Osm\Core\App;
use Osm\Core\Profiler;

class ViewFactory extends Factory
{
    public function make($view, $data = [], $mergeData = []) {
        if ($view instanceof View) {
            return parent::make($view->template, array_merge($data, compact('view')), $mergeData);
        }

        return parent::make($view, $data, $mergeData);
    }

    public function render($view, $data = []) {
        global $osm_app; /* @var App $osm_app */
        global $osm_profiler; /* @var Profiler $osm_profiler */

        $view_ = null;
        if ($view instanceof View) {
            $data['view'] = $view_ = $view;
            $view = $view->template;
        }
        elseif (isset($data['view'])) {
            $view_ = $data['view'];
        }

        if ($osm_profiler) $osm_profiler->start($view, 'views');
        try {
            $path = $this->finder->find($this->normalizeName($view));
            $engine = $this->getEngineFromPath($path);

            if (!$view_) {
                return $engine->get($path, $data);
            }

            /* @var Rendering $rendering */
            $rendering = $osm_app[Rendering::class];

            $currentView = $rendering->current_view;
            $rendering->current_view = $view_;

            try {
                return $view_->rendering() ?? $view_->rendered($engine->get($path, $data));
            }
            finally {
                $rendering->current_view = $currentView;
            }
        }
        finally {
            if ($osm_profiler) $osm_profiler->stop($view);
        }
    }

    public function exists($view) {
        if (!$view) {
            return false;
        }

        if ($view instanceof View) {
            return parent::exists($view->template);
        }

        return parent::exists($view);
    }

    protected function viewInstance($view, $path, $data)
    {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->createRaw(LaravelView::class, $this,
            $this->getEngineFromPath($path), $view, $path, $data);
    }

}