<?php

declare(strict_types=1);

namespace Osm\Framework\Blade\Components;

use Illuminate\View\DynamicComponent;
use Osm\Core\App;
use Osm\Core\Attributes\Name;
use Osm\Runtime\Traits\ComputedProperties;

#[Name('dynamic-component')]
class Dynamic extends DynamicComponent
{
    use ComputedProperties;

    protected function createBladeViewFromString($factory, $contents)
    {
        global $osm_app; /* @var App $osm_app */

        $factory->addNamespace('__components',
            $directory = "{$osm_app->paths->temp}/view_cache/{$osm_app->theme->name}"
        );

        if (! is_file($viewFile = $directory.'/'.sha1($contents).'.blade.php')) {
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            file_put_contents($viewFile, $contents);
        }

        return '__components::'.basename($viewFile, '.blade.php');
    }

}