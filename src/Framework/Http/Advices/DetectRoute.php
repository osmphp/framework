<?php

namespace Osm\Framework\Http\Advices;

use Osm\Core\App;
use Osm\Framework\Areas\Area;
use Osm\Framework\Http\Advice;
use Osm\Framework\Http\Exceptions\NotFound;
use Osm\Framework\Http\Request;

/**
 * @property Area $area @required
 * @property Request $request @required
 */
class DetectRoute extends Advice
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'request': return $m_app->request;
            case 'area': return $m_app->area_;
        }
        return parent::default($property);
    }

    public function around(callable $next) {
        global $m_app; /* @var App $m_app */

        if (!isset($m_app->controller)) {
            $m_app->controller = $this->findController();
        }

        return $next();
    }

    protected function findController() {
        if (!isset($this->area->controllers["{$this->request->method} {$this->request->route}"])) {
            throw new NotFound(m_("Page not found"));
        }

        $controller = $this->area->controllers["{$this->request->method} {$this->request->route}"];

        if ($controller->abstract) {
            throw new NotFound(m_("Page not found"));
        }

        return $controller;
    }
}