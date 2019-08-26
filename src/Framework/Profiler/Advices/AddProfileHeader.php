<?php

namespace Osm\Framework\Profiler\Advices;

use Osm\Core\Profiler;
use Osm\Framework\Http\Advice;
use Symfony\Component\HttpFoundation\Response;

class AddProfileHeader extends Advice
{
    public function around(callable $next) {
        global $m_profiler; /* @var Profiler $m_profiler */

        /* @var Response $response */
        $response = $next();

        if ($m_profiler) {
            $response->headers->set('Performance-Profile', $m_profiler->getId());
        }

        return $response;
    }
}