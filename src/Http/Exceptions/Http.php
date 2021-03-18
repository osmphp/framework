<?php

declare(strict_types=1);

namespace Osm\Framework\Http\Exceptions;

use Symfony\Component\HttpFoundation\Response;

abstract class Http extends \Exception
{
    abstract public function response(): Response;
}