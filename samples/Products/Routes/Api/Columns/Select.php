<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Products\Routes\Api\Columns;

use Osm\Core\App;
use Osm\Core\Attributes\Name;
use Osm\Framework\Areas\Api;
use Osm\Framework\Areas\Attributes\Area;
use Osm\Framework\Data\Data;
use Osm\Framework\Http\Route;
use Symfony\Component\HttpFoundation\Response;
use function Osm\json_response;

/**
 * @property Data $data
 */
#[Area(Api::class), Name('GET /products/columns')]
class Select extends Route
{
    public function run(): Response {
        return json_response($this->data->sheet('products')->columns);
    }

    /** @noinspection PhpUnused */
    protected function get_data(): Data {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->data;
    }
}