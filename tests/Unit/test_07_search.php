<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Unit;

use Osm\Core\TestCase;
use Osm\Framework\Search\Blueprint;

class test_07_search extends TestCase
{
    public string $app_class_name = \Osm\Framework\Samples\App::class;

    public function test_basics() {
        // GIVEN that search connection is configured in `search` setting

        if ($this->app->search->exists('test_products')) {
            $this->app->search->drop('test_products');
        }

        // WHEN you create a simple index and add data to it
        $this->app->search->create('test_products', function(Blueprint $index) {
            $index->string('sku');
            $index->int('qty');
        });

        $this->app->search->index('test_products')->insert([
            'id' => 1,
            'sku' => 'P1',
            'qty' => 5,
        ]);

        // THEN the data is indeed in the search engine
        $id = $this->app->search->index('test_products')
            ->whereEquals('sku', 'P1')
            ->id();

        $this->assertEquals('1', $id);

        // WHEN you delete an index
        $this->app->search->drop('test_products');

        // THEN it;s no longer there
        $this->assertFalse($this->app->search->exists('test_products'));
    }

    public function test_bulk_operations() {
        // GIVEN that search connection is configured in `search` setting

        if ($this->app->search->exists('test_products')) {
            $this->app->search->drop('test_products');
        }

        // WHEN you create a simple index and add data to it
        $this->app->search->create('test_products', function(Blueprint $index) {
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
        $this->app->search->index('test_products')->bulkInsert($data);

        // THEN the data is indeed in the search engine
        $id = $this->app->search->index('test_products')
            ->whereEquals('sku', 'P5')
            ->id();

        $this->assertEquals('5', $id);

        $this->app->search->drop('test_products');
    }
}