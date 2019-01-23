<?php

namespace Manadev\Framework\Profiler\Advices;

use Manadev\Core\Profiler;
use Manadev\Framework\Http\Advice;
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