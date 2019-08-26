<?php

namespace Osm\Framework\Views\Traits;

use Osm\Core\App;
use Osm\Core\Profiler;
use Osm\Framework\Views\Rendering;
use Osm\Framework\Views\View;

/**
 * @property array $data
 */
trait LaravelViewTrait
{
    protected function around_getContents(callable $callback) {
        global $osm_app; /* @var App $osm_app */
        global $osm_profiler; /* @var Profiler $osm_profiler */

        if ($osm_profiler) $osm_profiler->start($this->view, 'views');
        try {
            if (!isset($this->data['view'])) {
                return $callback();
            }

            $view = $this->data['view'];
            if (!($view instanceof View)) {
                return $callback();
            }

            /* @var Rendering $rendering */
            $rendering = $osm_app[Rendering::class];

            $currentView = $rendering->current_view;
            $rendering->current_view = $view;

            try {
                return $view->rendering() ?? $view->rendered($callback());
            }
            finally {
                $rendering->current_view = $currentView;
            }
        }
        finally {
            if ($osm_profiler) $osm_profiler->stop($this->view);
        }
    }
}