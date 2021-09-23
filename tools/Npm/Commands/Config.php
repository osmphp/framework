<?php

declare(strict_types=1);

namespace Osm\Tools\Npm\Commands;

use Osm\Framework\Console\Command;
use Osm\Framework\Console\Exceptions\ConsoleError;
use Osm\Runtime\Apps;
use Osm\Runtime\Compilation\Compiler;
use Osm\Project\App;
use function Osm\merge;
use function Osm\__;

class Config extends Command
{
    public string $name = 'config:npm';

    public function run(): void {
        global $osm_app; /* @var App $osm_app */

        $json = new \stdClass();
        Apps::run(Apps::create(App::class), function(App $app) use ($json) {
            foreach ($app->modules as $module) {
                $filename = "{$app->paths->project}/{$module->path}/package.json";

                if (!is_file($filename)) {
                    continue;
                }

                if (!$moduleJson = json_decode(file_get_contents($filename))) {
                    throw new ConsoleError(__("':filename' is not a valid JSON file",
                        ['filename' => $filename]));
                }

                $json = merge($json, $moduleJson);
            }
        });

        file_put_contents("{$osm_app->paths->project}/package.json",
            json_encode($json, JSON_PRETTY_PRINT));
    }
}