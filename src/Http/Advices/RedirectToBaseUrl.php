<?php

namespace Osm\Framework\Http\Advices;

use Osm\Core\App;
use Osm\Framework\Http\Advice;
use Osm\Framework\Http\Request;
use Osm\Framework\Http\Responses;
use Osm\Framework\Http\Url;

/**
 * @property Url $url @required
 * @property Responses $responses @required
 * @property Request $request @required
 */
class RedirectToBaseUrl extends Advice
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'url': return $osm_app->area_->url;
            case 'responses': return $osm_app[Responses::class];
            case 'request': return $osm_app->request;
        }
        return parent::default($property);
    }

    public function around(callable $next) {
        if ($this->url->base_url === $this->url->request_base_url) {
            return $next();
        }

        return $this->responses->redirect($this->url->to(
            $this->request->method_and_route,
            $this->request->query
        ));
    }
}