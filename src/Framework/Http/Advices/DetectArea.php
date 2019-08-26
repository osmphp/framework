<?php

namespace Osm\Framework\Http\Advices;

use Osm\Core\App;
use Osm\Framework\Http\Advice;

class DetectArea extends Advice
{
    public function around(callable $next) {
        global $m_app; /* @var App $m_app */

        $m_app->area = 'frontend';
        return $next();
    }
}