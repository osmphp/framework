<?php

namespace Manadev\Framework\Http\Advices;

use Manadev\Core\App;
use Manadev\Framework\Areas\Area;
use Manadev\Framework\Http\Advice;
use Manadev\Framework\Http\Exceptions\NotFound;
use Manadev\Framework\Http\Request;

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

        if (isset($m_app->controller)) {
            return $next();
        }

        if (!isset($this->area->controllers["{$this->request->method} {$this->request->route}"])) {
            throw new NotFound(m_("Page not found"));
        }

        $controller = $this->area->controllers["{$this->request->method} {$this->request->route}"];

        if ($controller->seo) {
            throw new NotFound(m_("Page not found"));
        }

        $m_app->controller = $controller;

        return $next();
    }
}