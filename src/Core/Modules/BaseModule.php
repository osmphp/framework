<?php

namespace Osm\Core\Modules;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Core\Packages\ComponentPool;
use Osm\Core\Packages\Package;

/**
 * @property string $name @required @part
 * @property string $path @required @part
 * @property string $short_name @part
 * @property string[] $hard_dependencies @part
 * @property string[] $soft_dependencies @part
 * @property string[] $traits @part
 * @property array $setters
 *
 * @property string $namespace @required
 * @property string $package @required @part
 * @property Package $package_ @required
 * @property string $component_pool @required @part
 * @property ComponentPool $component_pool_ @required
 */
class BaseModule extends Object_
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'namespace': return strtr($this->name, '_', '\\');
            case 'package_': return $osm_app->packages[$this->package];
            case 'component_pool_': return $this->package_->component_pools[$this->component_pool];
        }

        return parent::default($property);
    }

    public function boot() {
        global $osm_app; /* @var App $osm_app */

        if ($this->short_name) {
            $osm_app->{$this->short_name} = $this;
        }
    }

    public function terminate() {
    }
}
