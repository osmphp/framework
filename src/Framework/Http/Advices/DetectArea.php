<?php

namespace Manadev\Framework\Http\Advices;

use Manadev\Core\App;
use Manadev\Framework\Http\Advice;

class DetectArea extends Advice
{
    public function around(callable $next) {
        global $m_app; /* @var App $m_app */

        $m_app->area = 'frontend';
        return $next();
    }
}