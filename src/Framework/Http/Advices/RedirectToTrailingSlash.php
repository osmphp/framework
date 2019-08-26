<?php

namespace Osm\Framework\Http\Advices;

use Osm\Core\App;
use Osm\Framework\Areas\Area;
use Osm\Framework\Http\Advice;
use Osm\Framework\Http\Exceptions\NotFound;
use Osm\Framework\Http\Request;
use Osm\Framework\Http\Responses;
use Osm\Framework\Http\UrlGenerator;

/**
 * @property Request $request @required
 * @property Responses $responses @required
 * @property Area $area @required
 * @property UrlGenerator $url_generator @required
 */
class RedirectToTrailingSlash extends Advice
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'request': return $m_app->request;
            case 'responses': return $m_app[Responses::class];
            case 'area': return $m_app->area_;
            case 'url_generator': return $m_app->url_generator;
        }
        return parent::default($property);
    }

    public function around(callable $next) {
        try {
            return $next();
        }
        catch (NotFound $e) {
            if ($response = $this->redirect()) {
                return $response;
            }

            throw $e;
        }
    }

    protected function redirect() {
        if ($this->request->method_and_route == 'GET /') {
            return null;
        }

        if (mb_strrpos($this->request->method_and_route, '/') === mb_strlen($this->request->method_and_route)
            - mb_strlen('/'))
        {
            $redirectTo = mb_substr($this->request->method_and_route, 0, mb_strlen($this->request->method_and_route)
                - mb_strlen('/'));
        }
        else {
            $redirectTo = $this->request->method_and_route . '/';
        }

        if (!isset($this->area->controllers[$redirectTo])) {
            return null;
        }

        return $this->responses->redirect($this->url_generator->rawUrl($redirectTo, $this->request->query));
    }
}