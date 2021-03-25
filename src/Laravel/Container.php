<?php

declare(strict_types=1);

namespace Osm\Framework\Laravel;

use Illuminate\Container\Container as BaseContainer;
use Osm\Core\App;

class Container extends BaseContainer
{
    public function build($concrete) {
        global $osm_app; /* @var App $osm_app */

        if (is_string($concrete)) {
            $concrete = $osm_app->classes[$concrete]->generated_name ?? $concrete;
        }

        return parent::build($concrete);
    }
}