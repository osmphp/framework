<?php

declare(strict_types=1);

namespace Osm\Framework\Tests;

use Osm\Framework\Data\Module;
use Osm\Framework\Samples\App;
use Osm\Framework\Data\Blueprint;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;

class test_08_data extends TestCase
{
    public function test_basics() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // uncomment when the structure of sheet_column changes
            $app->migrations()->fresh();

            // GIVEN the `Osm_Framework_Data` is migrated
            $app->migrations()->up(Module::class);

            if ($app->data->exists('test_products')) {
                $app->data->drop('test_products');
            }

            // WHEN you create a simple index and add data to it
            $app->data->create('test_products', function(Blueprint $sheet) {
                $sheet->id();
                $sheet->string('sku')->filterable();
                $sheet->int('qty')->partition_no(1);
            });

            $id = $app->data->test_products()->insert((object)[
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