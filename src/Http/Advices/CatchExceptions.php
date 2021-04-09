<?php

declare(strict_types=1);

namespace Osm\Framework\Http\Advices;

use Osm\Core\App;
use Osm\Framework\Areas\Attributes\Area;
use Osm\Framework\Http\Exceptions\Http;
use Symfony\Component\HttpFoundation\Response;
use function Osm\exception_response;

#[Area(null, 10)]
class CatchExceptions extends Advice
{
    public function around(callable $next): Response {
        try {
            return $next();
        }
        catch (Http $e) {
            return $e->response();
        }
        catch (\Throwable $e) {
            return exception_response($e);
        }
    }
}