<?php

namespace Osm\Framework\Pages\Traits;

use Osm\Core\App;
use Osm\Core\Attributes\UseIn;

/**
 * @property string $asset_version
 */
#[UseIn(App::class)]
trait AppTrait
{
    public function asset($filename): string {
        /* @var App|static $this */

        return "{$this->http->base_url}/{$this->theme->name}/{$filename}" .
            "?v={$this->asset_version}";
    }

    protected function get_asset_version(): string {
        /* @var App|static $this */

        return file_get_contents("{$this->paths->project}/public/" .
            "{$this->name}/{$this->theme->name}/version.txt");
    }

}