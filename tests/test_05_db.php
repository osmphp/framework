<?php

declare(strict_types=1);

namespace Osm\Framework\Tests;

use Osm\Framework\Samples\App;
use Osm\Runtime\Apps;
use PHPUnit\Framework\TestCase;
use function Osm\__;

class test_05_db extends TestCase
{
    public function test_connection() {
        Apps::run(Apps::create(App::class), function(App $app) {
            // GIVEN that PhpUnit configures in-memory SqLite connection

            // WHEN you request text translation
            $db = $app->db->connection;
            $value = $db->query()->value($db->raw("'test'"));

            // THEN you get it according to the current locale
            $this->assertEquals('test', $value);
        });
    }
}