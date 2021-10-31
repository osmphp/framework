<?php

declare(strict_types=1);

namespace Osm\Framework\Pages\Components\Admin;

use Osm\Core\App;
use Osm\Framework\Blade\Component;

/**
 * @property string $version
 */
class Layout extends Component
{
    public string $__template = 'std-pages::layout';

    public function __construct(public string $title,
        public ?string $description = null,
        public ?string $canonicalUrl = null)
    {
    }

    public function asset($filename): string {
        global $osm_app; /* @var App $osm_app */

        return "{$osm_app->http->base_url}/{$osm_app->theme->name}/{$filename}" .
            "?v={$this->version}";
    }

    protected function get_version(): string {
        global $osm_app; /* @var App $osm_app */

        return file_get_contents("{$osm_app->paths->project}/public/" .
            "{$osm_app->name}/{$osm_app->theme->name}/version.txt");
    }
}