<?php

declare(strict_types=1);

namespace Osm\Framework\Maintenance\Commands;

use Osm\Core\App;
use Osm\Framework\Console\Command;
use function Osm\make_dir_for;

/**
 * @property string $filename
 */
class Up extends Command
{
    public string $name = 'http:up';

    public function run(): void {
        if (is_file($this->filename)) {
            unlink($this->filename);
        }
    }

    protected function get_filename(): string {
        global $osm_app; /* @var App $osm_app */

        return "{$osm_app->paths->temp}/maintenance.flag";
    }
}