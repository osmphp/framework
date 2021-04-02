<?php

/** @noinspection PhpUnused */
declare(strict_types=1);

namespace Osm\Framework\Samples\Products\Migrations;

use Osm\Core\App;
use Osm\Framework\Data\Blueprint;
use Osm\Framework\Data\Data;
use Osm\Framework\Migrations\Migration;

/**
 * @property Data $data
 */
class M01_products extends Migration
{
    protected function get_data(): Data {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->data;
    }

    public function create(): void {
        $this->data->create('products', function(Blueprint $sheet) {
            $sheet->id();
            $sheet->string('title')->filterable();
        });
    }

    public function drop(): void {
        $this->data->drop('products');
    }
}