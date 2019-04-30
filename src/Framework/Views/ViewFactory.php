<?php

namespace Manadev\Framework\Views;

use Illuminate\View\Factory;
use Illuminate\View\View as LaravelView;
use Manadev\Core\App;
use Manadev\Core\Profiler;

class ViewFactory extends Factory
{
    public function make($view, $data = [], $mergeData = []) {
        if ($view instanceof View) {
            return parent::make($view->template, array_merge($data, compact('view')), $mergeData);
        }

        return parent::make($view, $data, $mergeData);
    }

    public function render($view, $data = []) {
        global $m_app; /* @var App $m_app */
        global $m_profiler; /* @var Profiler $m_profiler */

        $view_ = null;
        if ($view instanceof View) {
            $data['view'] = $view_ = $view;
            $view = $view->template;
        }
        elseif (isset($data['view'])) {
            $view_ = $data['view'];
        }

        if ($m_profiler) $m_profiler->start($view, 'views');
        try {
            $path = $this->finder->find($this->normalizeName($view));
            $engine = $this->getEngineFromPath($path);

            if (!$view_) {
                return $engine->get($path, $data);
            }

            /* @var Rendering $rendering */
            $rendering = $m_app[Rendering::class];

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
            if ($m_profiler) $m_profiler->stop($view);
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
        global $m_app; /* @var App $m_app */

        return $m_app->createRaw(LaravelView::class, $this,
            $this->getEngineFromPath($path), $view, $path, $data);
    }

}