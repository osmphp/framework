<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Framework\Settings\Traits;

use Osm\Core\App;
use Osm\Framework\Settings\Hints\Settings;
use Osm\Framework\Cache\Attributes\Cached;
use function Osm\merge;

/**
 * @property Settings|\stdClass $settings #[Cached('settings')]
 */
trait AppTrait
{
    /** @noinspection PhpUnused */
    protected function get_settings(): \stdClass {
        /* @var App $this */

        $settings = new \stdClass();
        foreach ($this->modules as $module) {
            $filename = "{$this->paths->project}/{$module->path}/settings.php";
            if (is_file($filename)) {
                /** @noinspection PhpIncludeInspection */
                $settings = merge($settings, include $filename);
            }
        }

        if (is_file($this->paths->settings)) {
            /** @noinspection PhpIncludeInspection */
            $settings = merge($settings, include $this->paths->settings);
        }

        return $settings;
    }
}