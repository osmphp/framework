<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Unit;

use Osm\Core\TestCase;

class test_05_db extends TestCase
{
    public string $app_class_name = \Osm\Framework\Samples\App::class;

    public function test_connection() {
        // GIVEN that PhpUnit configures in-memory SqLite connection

        // WHEN you request text translation
        $db = $this->app->db->connection;
        $value = $db->query()->value($db->raw("'test'"));

        // THEN you get it according to the current locale
        $this->assertEquals('test', $value);
    }
}