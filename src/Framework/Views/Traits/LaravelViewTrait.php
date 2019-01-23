<?php

namespace Manadev\Framework\Views\Traits;

use Manadev\Core\App;
use Manadev\Core\Profiler;
use Manadev\Framework\Views\Rendering;
use Manadev\Framework\Views\View;

/**
 * @property array $data
 */
trait LaravelViewTrait
{
    protected function around_getContents(callable $callback) {
        global $m_app; /* @var App $m_app */
        global $m_profiler; /* @var Profiler $m_profiler */

        if ($m_profiler) $m_profiler->start($this->view, 'views');
        try {
            if (!isset($this->data['view'])) {
                return $callback();
            }

            $view = $this->data['view'];
            if (!($view instanceof View)) {
                return $callback();
            }

            /* @var Rendering $rendering */
            $rendering = $m_app[Rendering::class];

            $currentView = $rendering->current_view;
            $rendering->current_view = $view;

            try {
                $view->rendering();
                return $view->rendered($callback());
            }
            finally {
                $rendering->current_view = $currentView;
            }
        }
        finally {
            if ($m_profiler) $m_profiler->stop($this->view);
        }
    }
}