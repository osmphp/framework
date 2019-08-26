<?php

namespace Osm\Framework\Profiler\Advices;

use Osm\Core\Profiler;
use Osm\Framework\Http\Advice;
use Symfony\Component\HttpFoundation\Response;

class AddProfileHeader extends Advice
{
    public function around(callable $next) {
        global $osm_profiler; /* @var Profiler $osm_profiler */

        /* @var Response $response */
        $response = $next();

        if ($osm_profiler) {
            $response->headers->set('Performance-Profile', $osm_profiler->getId());
        }

        return $response;
    }
}