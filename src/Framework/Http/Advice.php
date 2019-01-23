<?php

namespace Manadev\Framework\Http;

use Manadev\Framework\Data\Advice as BaseAdvice;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method Response around(callable $next)
 */
class Advice extends BaseAdvice
{
}