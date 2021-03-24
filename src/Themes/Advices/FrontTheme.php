<?php

declare(strict_types=1);

namespace Osm\Framework\Themes\Advices;

use Osm\Core\App;
use Osm\Framework\Areas\Attributes\Area;
use Osm\Framework\Areas\Front;
use Osm\Framework\Http\Advices\Advice;
use Osm\Framework\Themes\Module;
use Symfony\Component\HttpFoundation\Response;

#[Area(Front::class, sort_order: 10)]
class FrontTheme extends Advice
{
    public function around(callable $next): Response {
        global $osm_app; /* @var App $osm_app */

        /* @var Module $module */
        $module = $osm_app->modules[Module::class];

        $osm_app->theme = $module->themes["_front__{$osm_app->settings->theme}"];

        $response = $next();

        $osm_app->theme = null;

        return $response;
    }

}