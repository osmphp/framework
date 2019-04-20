<?php

namespace Manadev\Framework\Areas;

use Manadev\Core\Object_;
use Manadev\Framework\Http\Advices;
use Manadev\Framework\Http\Controller;
use Manadev\Framework\Http\Controllers;
use Manadev\Framework\Http\Parameter;
use Manadev\Framework\Http\Parameters;

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
 * @see \Manadev\Framework\Http\Traits\AreaTrait
 *      @property array $advices @required @part
 *      @property Advices $advices_ @required
 *      @property array $parameters @required @part
 *      @property Parameters|Parameter[] $parameters_ @required @part
 *      @property Controllers|Controller[] $controllers @required
 *      @property array $query @required Parsed area-wide parameters (like _env) in current request
 * @see \Manadev\Framework\Sessions\Module
 *      @property string $session_class @part
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