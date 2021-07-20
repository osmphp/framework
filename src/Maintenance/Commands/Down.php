<?php

declare(strict_types=1);

namespace Osm\Framework\Maintenance\Commands;

use Osm\Core\App;
use Osm\Framework\Console\Command;
use function Osm\make_dir_for;

/**
 * @property string $filename
 */
class Down extends Command
{
    public string $name = 'http:down';

    public function run(): void {
        file_put_contents(make_dir_for($this->filename), '');
    }

    protected function get_filename(): string {
        global $osm_app; /* @var App $osm_app */

        return "{$osm_app->paths->temp}/maintenance.flag";
    }
}