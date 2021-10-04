<?php

declare(strict_types=1);

namespace Osm\Framework\Settings\Traits;

use Osm\Core\App;
use Osm\Core\Attributes\UseIn;
use Osm\Core\Paths;
use Osm\Framework\Env\Attributes\Env;

/**
 * @property string $settings
 */
#[UseIn(Paths::class)]
trait PathsTrait
{
    /** @noinspection PhpUnused */
    #[Env('SETTINGS', 'settings filename', 'settings.{app_name}.php')]
    protected function get_settings(): string {
        global $osm_app; /* @var App $osm_app */

        /* @var Paths $this */
        $path = $_ENV['SETTINGS'] ?? "settings.{$osm_app->name}.php";

        return "{$this->project}/$path";
    }
}