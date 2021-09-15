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

        if (!$osm_app->theme) {
            return $proceed($message);
        }

        if (!$osm_app->theme->views->exists('std-pages::404')) {
            return $proceed($message);
        }

        try {
            return $this->view('std-pages::404',
                ['message' => $message]);
        }
        catch (\Exception) {
            return $proceed($message);
        }
    }
}