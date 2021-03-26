<?php

declare(strict_types=1);

namespace Osm\Framework\Http\Advices;

use Osm\Core\App;
use Osm\Framework\Areas\Attributes\Area;
use Osm\Framework\Http\Exceptions\Http;
use Symfony\Component\HttpFoundation\Response;

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
            $content = '';
            for (; $e; $e = $e->getPrevious()) {
                $content = "{$e->getMessage()}\n\n{$e->getTraceAsString()}" .
                    "\n\n{$content}";
            }

            return new Response($content, 500, [
                'Content-Type' => 'text/plain',
            ]);
        }
    }
}