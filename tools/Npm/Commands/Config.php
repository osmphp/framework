<?php

declare(strict_types=1);

namespace Osm\Tools\Npm\Commands;

use Osm\Framework\Console\Command;
use Osm\Framework\Console\Exceptions\ConsoleError;
use Osm\Runtime\Apps;
use Osm\Runtime\Compilation\Compiler;
use Osm\Tools\App;
use function Osm\merge;
use function Osm\__;

class Config extends Command
{
    public string $name = 'config:npm';

    public function run(): void {
        $compiler = Compiler::new(['app_class_name' => App::class]);

        $json = new \stdClass();
        Apps::run($compiler, function(Compiler $compiler) use ($json) {
            foreach ($compiler->app->unsorted_modules as $module) {
                $filename = "{$compiler->paths->project}/{$module->path}/package.json";

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

        file_put_contents("{$compiler->paths->project}/package.json",
            json_encode($json, JSON_PRETTY_PRINT));
    }
}