<?php

declare(strict_types=1);

namespace Osm\Framework\Maintenance\Advices;

use Osm\Core\App;
use Osm\Framework\Areas\Attributes\Area;
use Osm\Framework\Http\Advices\Advice;
use Osm\Framework\Http\Responses;
use Symfony\Component\HttpFoundation\Response;
use function Osm\__;

/**
 * @property string $filename
 * @property Responses $responses
 */
#[Area(null, 5)]
class PreventAccess extends Advice
{
    public function around(callable $next): Response {
        if (!is_file($this->filename)) {
            return $next();
        }

        return $this->responses->maintenance();
    }

    protected function get_filename(): string {
        global $osm_app; /* @var App $osm_app */

        return "{$osm_app->paths->temp}/maintenance.flag";
    }

    protected function get_responses(): Responses {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->http->responses;
    }

}