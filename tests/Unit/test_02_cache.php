<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Unit;

use Osm\Framework\Samples\App;
use Osm\Framework\Samples\Cache\ObjectWithId;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;

class test_02_cache extends TestCase
{
    public function test_getting_property_from_cache() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN a ObjectWithId class having a #[Cached] `id` property that,
            // if not #[Cached], would return ever-increasing ID for every
            // new class instance

            // WHEN you create 2 instances
            $object1 = ObjectWithId::new();
            $object2 = ObjectWithId::new();

            // THEN both object have the same ID: the first one generated with
            // the getter, and the second one retrieved from cache
            $this->assertEquals(1, $object1->id);
            $this->assertEquals(1, $object2->id);
        });
    }
}