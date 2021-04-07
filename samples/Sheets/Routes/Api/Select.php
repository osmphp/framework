<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Sheets\Routes\Api;

use Osm\Core\App;
use Osm\Core\Attributes\Name;
use Osm\Framework\Areas\Api;
use Osm\Framework\Areas\Attributes\Area;
use Osm\Framework\Data\Data;
use Osm\Framework\Data\Query;
use Osm\Framework\Data\RequestParser;
use Osm\Framework\Http\Route;
use Symfony\Component\HttpFoundation\Response;
use function Osm\json_response;

/**
 * @property Data $data
 * @property Query $query
 */
#[Area(Api::class), Name('GET /sheets')]
class Select extends Route
{
    public function run(): Response {
        return json_response($this->query->get());
    }

    /** @noinspection PhpUnused */
    protected function get_data(): Data {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->data;
    }

    /** @noinspection PhpUnused */
    protected function get_query(): Query {
        $query = $this->data->sheet('sheets');

        RequestParser::new(['query' => $query])
            ->count()
            ->select()
            ->filters();

        return $query;
    }
}