<?php

namespace Osm\Framework\Areas;

use Osm\Core\Object_;
use Osm\Framework\Http\Advices;
use Osm\Framework\Http\Controller;
use Osm\Framework\Http\Controllers;
use Osm\Framework\Http\Parameter;
use Osm\Framework\Http\Parameters;
use Osm\Framework\Http\Url;
use Osm\Framework\Sessions\Session;
use Osm\Framework\Sessions\Stores\Store;

/**
 * @property Areas|Area[] $parent
 * @property string $name @required @part
 * @property string $parent_area @part
 * @property Area $parent_area_
 * @property string $resource_path @part
 * @property bool $abstract @part
 * @property string $title @required @part
 *
 * @property string[] $names @required
 *
 * @see \Osm\Framework\Http\Traits\AreaTrait
 *      @property array $advices @required @part
 *      @property Advices $advices_ @required
 *      @property array $parameters @required @part
 *      @property Parameters|Parameter[] $parameters_ @required @part
 *      @property Controllers|Controller[] $controllers @required
 *      @property array $query @required Parsed area-wide parameters (like _env) in current request
 *      @property Url $url @required
 *      @method void setUrl(Url $url)
 * @see \Osm\Framework\Sessions\Module
 *      @property Store|Session[] $sessions @default
 *      @property Session $session
 */
class Area extends Object_
{
    protected function default($property) {
        switch ($property) {
            case 'parent_area_': return $this->parent_area ? $this->parent[$this->parent_area] : null;
            case 'names': return $this->parent_area_
                ? array_merge($this->parent_area_->names, [$this->name])
                : [$this->name];
        }
        return parent::default($property);
    }
}