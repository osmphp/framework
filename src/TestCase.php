<?php

declare(strict_types=1);

namespace Osm\Framework;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\TestCase as BaseTestCase;
use Osm\Framework\Db\Db;
use Osm\Framework\Http\Client;

class TestCase extends BaseTestCase
{
    public bool $use_db = false;
    public bool $use_http = false;
    public bool $use_chrome = false;

    protected ?Db $db = null;
    protected ?Client $http = null;

    protected function setUp(): void {
        parent::setUp();

        if ($this->use_http) {
            $this->http = new Client();
        }

        if ($this->use_db) {
            $this->db = $this->app->db;
            $this->db->beginTransaction();
        }
    }

    protected function tearDown(): void {
        if ($this->use_db) {
            $this->db->rollback();
            $this->db = null;
        }

        if ($this->use_http) {
            $this->http = null;
        }

        parent::tearDown();
    }

    protected function call(string $request, string $baseUrl = '/api'): mixed {
        /* @var TestCase|static $this */

        $headers = [];
        $method = '';
        $url = '';
        $content = '';

        foreach (explode(PHP_EOL, $request) as $line) {
            $line = trim($line);

            if ($method) {
                $content .= $line . PHP_EOL;
                continue;
            }

            if (preg_match('/(?<method>GET|POST|DELETE) (?<url>.*)/',
                $line, $match))
            {
                $method = $match['method'];
                $url = "{$baseUrl}{$match['url']}";
                continue;
            }

            // parse and send headers
            throw new NotImplemented();
        }

        $this->http->request($method, $url, content: $content ?: null);

        $response = $this->http->getInternalResponse();

        $this->assertEquals(200, $response->getStatusCode(),
            $response->getContent());

        return $response->getContent()
            ? json_decode($response->getContent())
            : null;
    }
}