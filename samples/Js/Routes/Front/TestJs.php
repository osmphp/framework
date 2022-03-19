<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Js\Routes\Front;

use Osm\Core\App;
use Osm\Core\Attributes\Name;
use Osm\Framework\Areas\Attributes\Area;
use Osm\Framework\Areas\Front;
use Osm\Framework\Http\Exceptions\NotFound;
use Osm\Framework\Http\Route;
use Symfony\Component\HttpFoundation\Response;
use function Osm\asset;
use function Osm\template;

/**
 * @property string $public_path
 */
#[Area(Front::class), Name('GET /test/js')]
class TestJs extends Route
{
    public function run(): Response {
        if (!isset($this->http->query['file'])) {
            throw new NotFound();
        }

        $filename = str_replace('.', '/',
            $this->http->query['file']) . '.js';

        if (!is_file("{$this->public_path}/files/{$filename}")) {
            throw new NotFound();
        }

        return new Response((string)
            template('sample-js::test-js', [
                'test_url' => asset("files/$filename"),
                'options' => (object)[
                    'ui' => 'bdd',
                    //'reporter' => 'json',
                    'checkLeaks' => true,
                ],
            ]));
    }

    protected function get_public_path(): string {
        global $osm_app; /* @var App $osm_app */

        return "{$osm_app->paths->project}/public/{$osm_app->name}/" .
            $osm_app->theme->name;
    }
}