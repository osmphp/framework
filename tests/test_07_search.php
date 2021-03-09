<?php

declare(strict_types=1);

namespace Osm\Framework\Tests;

use Osm\Framework\Samples\App;
use Osm\Framework\Search\Blueprint;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;

class test_07_search extends TestCase
{
    public function test_basics() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN that search connection is configured in `search` setting

            if (!$app->search->hasIndex('test_products')) {
                // WHEN you create a simple index and add data to it
                $app->search->create('test_products', function(Blueprint $index) {
                    $index->int('id');
                    $index->string('sku');
                });
                $app->search->index('test_products')->insert([
                    'id' => 1,
                    'sku' => 'P1',
                ]);

                // THEN the data is indeed in the search engine
                $id = $app->search->index('t_products')
                    ->where('sku', 'P1')
                    ->value('id');

                $this->assertEquals(1, $id);
            }

            // WHEN you delete an index
            $app->search->drop('test_products');

            // THEN it;s no longer there
            $this->assertFalse($app->search->hasIndex('test_products'));
        });
    }
}