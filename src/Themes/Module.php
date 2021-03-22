<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Framework\Themes;

use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Paths;
use Osm\Framework\Themes\Theme;
use Osm\Framework\Cache\Attributes\Cached;
use Osm\Runtime\Apps;
use Osm\Runtime\Compilation\Compiler;
use Osm\Runtime\Compilation\Package;
use function Osm\merge;

/**
 * @property Package[] $packages
 * @property Theme[] $themes #[Cached('themes')]
 * @property Paths $paths
 */
class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Cache\Module::class,
    ];

    /** @noinspection PhpUnused */
    protected function get_themes(): array {
        global $osm_app; /* @var App $osm_app */

        $themes = [];

        foreach ($this->packages as $package) {
            $path = $package->path
                ? "{$this->paths->project}/$package->path"
                : $this->paths->project;

            foreach (glob("{$path}/themes/*/osm_theme.json") as $filename) {
                $name = basename(dirname($filename));

                if (!isset($themes[$name])) {
                    $themes[$name] = Theme::new(['name' => $name]);
                }

                $themes[$name] = merge($themes[$name],
                    json_decode(file_get_contents($filename)));
                $themes[$name]->paths[] = mb_substr($filename,
                    mb_strlen($this->paths->project) + 1);
            }
        }

        $compiler = Compiler::new(['app_class_name' => $osm_app->__class->name]);

        return Apps::run($compiler, function(Compiler $compiler) use($themes) {
            $app = $compiler->app;
            return $app->sort($themes, 'Themes',
                function($positions) {
                    return function(Theme $a, Theme $b) use ($positions) {
                        return $positions[$a->name] <=> $positions[$b->name];
                    };
                }
            );
        });
    }

    /** @noinspection PhpUnused */
    protected function get_packages(): array {
        global $osm_app; /* @var App $osm_app */

        $compiler = Compiler::new(['app_class_name' => $osm_app->__class->name]);

        return Apps::run($compiler, function(Compiler $compiler) {
            $app = $compiler->app;
            return $app->sort($app->packages, 'Packages',
                function($positions) {
                    return function(Package $a, Package $b) use ($positions) {
                        return $positions[$a->name] <=> $positions[$b->name];
                    };
                }
            );
        });
    }

    /** @noinspection PhpUnused */
    protected function get_paths(): Paths {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->paths;
    }
}