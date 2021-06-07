<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Unit;

use Osm\Core\TestCase;
use Osm\Framework\Search\Blueprint;
use Osm\Framework\Search\Where;

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
            $index->string('sku')
                ->filterable();
            $index->int('qty');
        });

        $this->app->search->index('test_products')->insert([
            'id' => 1,
            'sku' => 'P1',
            'qty' => 5,
        ]);

        // THEN the data is indeed in the search engine
        $id = $this->app->search->index('test_products')
            ->where('sku', '=', 'P1')
            ->id();

        $this->assertEquals('1', $id);

        // WHEN you delete an index
        $this->app->search->drop('test_products');

        // THEN it;s no longer there
        $this->assertFalse($this->app->search->exists('test_products'));
    }

    public function test_bulk_operations() {
        // GIVEN that search connection is configured in `search` setting

        $search = $this->app->search;
        if ($search->exists('test_products')) {
            $search->drop('test_products');
        }

        // WHEN you create a simple index and add data to it
        $search->create('test_products', function(Blueprint $index) {
            $index->string('sku')
                ->searchable()
                ->filterable();
            $index->int('qty')
                ->filterable()
                ->faceted();
            $index->float('price')
                ->filterable()
                ->faceted()
                ->sortable();
            $index->bool('in_stock')
                ->filterable()
                ->faceted()
                ->sortable();
            $index->string('tags')
                ->array()
                ->searchable()
                ->filterable()
                ->faceted();
            $index->int('color_ids')
                ->array()
                ->filterable()
                ->faceted();
            $index->float('widths')
                ->array()
                ->filterable()
                ->faceted();
            $index->string('description')
                ->searchable();
        });

        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'id' => $i,
                'sku' => "P{$i}",
                'qty' => $i > 5 ? $i : 0,
                'price' => round($i * 1.23, 2),
                'in_stock' => $i > 5,
                'tags' => array_merge(
                    $i % 2 == 0 ? ['Even'] : [],
                    $i % 3 == 0 ? ['Multiple of three'] : [],
                    $i % 4 == 0? ['Divides by four'] : [],
                ),
                'color_ids' => array_values(array_filter(array_unique(
                    [$i % 2, $i % 3, $i % 4, $i % 5]))),
                'widths' => [$i * 1.0, (11 - $i) * 1.0],
                'description' => implode("\n", array_merge(
                    $i % 2 == 0 ? ['Lorem ipsum dolor sit amet, consectetur adipiscing elit.'] : [],
                    $i % 3 == 0 ? ['Etiam quis nisl even faucibus, molestie elit eu, porta turpis.'] : [],
                    $i % 4 == 0 ? ['Quisque bibendum lectus eget arcu rutrum, vel tristique justo ullamcorper.'] : [],
                )),
            ];
        }
        $search->index('test_products')->bulkInsert($data);

        $ids = $search->index('test_products')->ids();

        $search->index('test_products')->update(4, [
            'description' => 'Phasellus sodales nunc sed quam egestas, vel congue purus tincidunt.',
        ]);
        $search->index('test_products')->update(7, [
            'description' => 'Duis rutrum urna quis faucibus sollicitudin.',
        ]);

        $search->index('test_products')->delete(9);

        // THEN the data is indeed in the search engine
        $this->assertEquals(9, $search->index('test_products')
            ->count());

        $this->assertTrue($search->index('test_products')
            ->search('even three multiple') //
            ->count() > 0);

        $this->assertEquals(5, $search->index('test_products')
            ->where('sku', '=', 'P5')
            ->id());

        $this->assertEquals([2, 4, 6, 8, 10],
            $search->index('test_products')
                ->where('tags', '=', 'Even')
                ->orderBy('id')
                ->ids());

        $this->assertEquals([2, 3, 4, 6, 8, 10],
            $search->index('test_products')
                ->where('tags', 'in', ['Even', 'Multiple of three'])
                ->orderBy('id')
                ->ids());

        $this->assertEquals([5, 6, 7, 8],
            $search->index('test_products')
                ->where('price', '>=', 5)
                ->where('price', '<=', 10)
                ->orderBy('id')
                ->ids());

        $this->assertEquals([1, 5, 6, 7, 8, 10],
            $search->index('test_products')
                ->or(fn(Where $clause) => $clause
                    ->and(fn(Where $clause) => $clause
                        ->where('price', '>=', 1.0)
                        ->where('price', '<', 2.0)
                    )
                    ->and(fn(Where $clause) => $clause
                        ->where('price', '>=', 5.0)
                    )
                )
                ->orderBy('id')
                ->ids());

        $result = $search->index('test_products')
            ->where('price', '>=', 5)
            ->facetBy('tags')
            ->facetBy('color_ids')
            ->facetBy('price', min: true, max: true)
            ->facetBy('widths', min: true, count: false)
            ->offset(2)
            ->limit(2)
            ->orderBy('id')
            ->get();

        $this->assertEquals(5, $result->count);
        $this->assertEquals([7, 8], $result->ids); // out of 5, 6, 7, 8, 10

        $this->assertTrue(isset($result->facets['tags']));
        $this->assertEquals(3,
            $result->facetCount('tags', 'Even'));

        $this->assertTrue(isset($result->facets['color_ids']));
        $this->assertEquals(2,
            $result->facetCount('color_ids', 3));

        $this->assertTrue(isset($result->facets['price']));
        $this->assertCount(5, $result->facets['price']->counts);
        $this->assertEquals(6.15,
            round($result->facets['price']->min, 2));
        $this->assertEquals(12.3,
            round($result->facets['price']->max, 2));

        $this->assertTrue(isset($result->facets['widths']));
        $this->assertEquals(1.0, $result->facets['widths']->min);

        $this->assertEquals(4, $search->index('test_products')
            ->where('price', '<', 5)
            ->orderBy('price', desc: true)
            ->id());

        $search->drop('test_products');
    }
}