<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Framework\Themes;

use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Paths;
use Osm\Framework\Settings\Hints\Settings;
use Osm\Framework\Themes\Theme;
use Osm\Framework\Cache\Attributes\Cached;
use Osm\Runtime\Apps;
use Osm\Runtime\Compilation\Compiler;
use Osm\Runtime\Compilation\Package;
use function Osm\merge;
use function Osm\sort_by_dependency;

/**
 * @property Package[] $packages
 * @property Theme[] $themes #[Cached('themes')]
 * @property Paths $paths
 */
class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Cache\Module::class,
        \Osm\Framework\Laravel\Module::class,
    ];

    public static array $traits = [
        App::class => Traits\AppTrait::class,
        Settings::class => Traits\SettingsTrait::class,
    ];

    /** @noinspection PhpUnused */
    protected function get_themes(): array {
        global $osm_app; /* @var App $osm_app */

        /* @var Theme[] $themes */
        $themes = [];

        foreach ($this->packages as $package) {
            $path = $package->path
                ? "{$this->paths->project}/$package->path"
                : $this->paths->project;

            foreach (glob("{$path}/themes/*/osm_theme.json") as $filename) {
                $dir = dirname($filename);
                $name = basename($dir);

                if (!isset($themes[$name])) {
                    $themes[$name] = Theme::new(['name' => $name]);
                }

                $themes[$name] = merge($themes[$name],
                    json_decode(file_get_contents($filename)));
                $themes[$name]->paths[] = mb_substr($dir,
                    mb_strlen($this->paths->project) + 1);

                if (is_file("{$dir}/gulpfile.js")) {
                    $themes[$name]->gulpfile = mb_substr("{$dir}/gulpfile.js",
                        mb_strlen($this->paths->project) + 1);
                }
            }
        }

        return sort_by_dependency($themes, 'Themes',
            fn($positions) =>
                fn(Theme $a, Theme $b) =>
                    $positions[$a->name] <=> $positions[$b->name]
        );
    }

    /** @noinspection PhpUnused */
    protected function get_packages(): array {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->packages;
    }

    /** @noinspection PhpUnused */
    protected function get_paths(): Paths {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->paths;
    }
}