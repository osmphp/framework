<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Unit;

use Osm\Framework\Samples\App;
use Osm\Framework\Search\Blueprint;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;

class test_07_search extends TestCase
{
    public function test_basics() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN that search connection is configured in `search` setting

            if ($app->search->exists('test_products')) {
                $app->search->drop('test_products');
            }

            // WHEN you create a simple index and add data to it
            $app->search->create('test_products', function(Blueprint $index) {
                $index->string('sku');
                $index->int('qty');
            });

            $app->search->index('test_products')->insert([
                'id' => 1,
                'sku' => 'P1',
                'qty' => 5,
            ]);

            // THEN the data is indeed in the search engine
            $id = $app->search->index('test_products')
                ->whereEquals('sku', 'P1')
                ->id();

            $this->assertEquals('1', $id);

            // WHEN you delete an index
            $app->search->drop('test_products');

            // THEN it;s no longer there
            $this->assertFalse($app->search->exists('test_products'));
        });
    }

    public function test_bulk_operations() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN that search connection is configured in `search` setting

            if ($app->search->exists('test_products')) {
                $app->search->drop('test_products');
            }

            // WHEN you create a simple index and add data to it
            $app->search->create('test_products', function(Blueprint $index) {
                $index->string('sku');
                $index->int('qty');
            });

            $data = [];
            for ($i = 1; $i <= 10; $i++) {
                $data[] = [
                    'id' => $i,
                    'sku' => "P{$i}",
                    'qty' => $i,
                ];
            }
            $app->search->index('test_products')->bulkInsert($data);

            // THEN the data is indeed in the search engine
            $id = $app->search->index('test_products')
                ->whereEquals('sku', 'P5')
                ->id();

            $this->assertEquals('5', $id);

            $app->search->drop('test_products');
        });
    }
}