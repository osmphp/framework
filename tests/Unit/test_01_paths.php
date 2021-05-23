<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Unit;

use Osm\Core\TestCase;

class test_01_paths extends TestCase
{
    public string $app_class_name = \Osm\Framework\Samples\App::class;

    public function test_data_path() {
        // GIVEN an app path setup
        $paths = $this->app->paths;

        // WHEN you its data path

        // THEN it is `/sample-data` for this sample application
        $this->assertTrue($paths->data ===
            "{$paths->project}/sample-data");
    }
}