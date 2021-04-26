<?php

declare(strict_types=1);

namespace Osm\Framework\Http\Traits;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Http\Client;
use Osm\Framework\PhpUnit\TestCase;

/**
 * @property bool $use_http
 * @property Client $http
 */
trait TestCaseTrait
{
    protected function around_setUp(callable $proceed): void {
        /* @var TestCase|static $this */

        $proceed();

        if ($this->use_http) {
            $this->http = new Client();
        }
    }

    protected function around_tearDown(callable $proceed): void {
        /* @var TestCase|static $this */

        if ($this->use_http) {
            $this->http = null;
        }

        $proceed();
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