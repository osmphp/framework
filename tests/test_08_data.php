<?php

declare(strict_types=1);

namespace Osm\Framework\Tests;

use Osm\Framework\Samples\App;
use Osm\Framework\Data\Blueprint;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;

class test_08_data extends TestCase
{
    public function test_basics() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN a compiled app with a query class specific to
            // `test_products` sheet

            if ($app->data->exists('test_products')) {
                $app->data->drop('test_products');
            }

            // WHEN you create a simple index and add data to it
            $app->data->create('test_products', function(Blueprint $sheet) {
                $sheet->string('sku');
                $sheet->int('qty');
            });

            $id = $app->data->test_products()->insert([
                'sku' => 'P1',
                'qty' => 5,
            ]);

            // THEN the data is indeed in the search engine
            $value = $app->data->test_products()
                ->whereEquals('sku', 'P1')
                ->value('id');

            $this->assertEquals($id, $value);

            // WHEN you delete an index
            $app->data->drop('test_products');

            // THEN it;s no longer there
            $this->assertFalse($app->data->exists('test_products'));
        });
    }
}