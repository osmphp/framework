<?php

namespace Osm\Framework\Http;

use Osm\Framework\Data\Advice as BaseAdvice;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method Response around(callable $next)
 */
class Advice extends BaseAdvice
{
}