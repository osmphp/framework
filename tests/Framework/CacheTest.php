<?php

namespace Osm\Tests\Framework;

use Osm\Core\App;
use Osm\Framework\Testing\Tests\UnitTestCase;
use Osm\Samples\Cache\Container;

class CacheTest extends UnitTestCase
{
    public function test_incrementally_cached_property() {
        global $osm_app; /* @var App $osm_app */

        /* @var Container $container */
        $container = Container::new();

        try {
            // incremental_property is not touched, hence not calculated
            $this->assertArrayNotHasKey('incremental_property', get_object_vars($container->item));

            // if we get same object from cache again, its incremental_property is not there yet
            $container = Container::new();
            $this->assertArrayNotHasKey('incremental_property', get_object_vars($container->item));

            // now we touch the property
            $container->item->incremental_property;
            $this->assertArrayHasKey('incremental_property', get_object_vars($container->item));

            // we manually commit incremental changes - normally it is done automatically during app termination
            $osm_app->cache->terminate();

            // if we get same object from cache again, its incremental_property is already set
            $container = Container::new();
            $this->assertArrayHasKey('incremental_property', get_object_vars($container->item));

            $osm_app->cache->forget('test_incremental_object');
        }
        finally {
            $osm_app->cache->forget('t_key');
        }

    }
}