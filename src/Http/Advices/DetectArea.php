<?php

namespace Osm\Framework\Http\Advices;

use Osm\Core\App;
use Osm\Framework\Http\Advice;
use Osm\Framework\Http\Url;

class DetectArea extends Advice
{
    public function around(callable $next) {
        global $osm_app; /* @var App $osm_app */

        $osm_app->area = 'frontend';
        $osm_app->area_->setUrl(Url::new());

        return $next();
    }
}