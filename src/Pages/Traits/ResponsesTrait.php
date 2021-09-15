<?php

namespace Osm\Framework\Pages\Traits;

use Osm\Core\App;
use Osm\Framework\Http\Responses;
use Symfony\Component\HttpFoundation\Response;

trait ResponsesTrait
{
    protected function around_notFound(callable $proceed, string $message)
        : Response
    {
        global $osm_app; /* @var App $osm_app */
        /* @var Responses $this */

        if (!$osm_app->theme?->views->exists('std-pages::404')) {
            return $proceed($message);
        }

        try {
            return $this->view('std-pages::404',
                ['message' => $message], status: 404);
        }
        catch (\Exception) {
            return $proceed($message);
        }
    }

    protected function around_renderException(callable $proceed,
        ?string $content): Response
    {
        global $osm_app; /* @var App $osm_app */
        /* @var Responses $this */

        if (!$osm_app->theme?->views->exists('std-pages::500')) {
            return $proceed($content);
        }

        try {
            return $this->view('std-pages::500',
                ['content' => $content], status: 500);
        }
        catch (\Exception) {
            return $proceed($content);
        }
    }

    protected function around_maintenance(callable $proceed)
        : Response
    {
        global $osm_app; /* @var App $osm_app */
        /* @var Responses $this */

        if (!$osm_app->theme?->views->exists('std-pages::503')) {
            return $proceed();
        }

        try {
            return $this->view('std-pages::503', status: 503);
        }
        catch (\Exception) {
            return $proceed();
        }
    }
}