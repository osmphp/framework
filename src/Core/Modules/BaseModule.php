<?php

namespace Manadev\Core\Modules;

use Manadev\Core\App;
use Manadev\Core\Object_;

/**
 * @property string $name @required @part
 * @property string $path @required @part
 * @property string $short_name @part
 * @property string[] $hard_dependencies @part
 * @property string[] $soft_dependencies @part
 * @property string[] $traits @part
 * @property array $setters
 */
class BaseModule extends Object_
{
    public function boot() {
        global $m_app; /* @var App $m_app */

        if ($this->short_name) {
            $m_app->{$this->short_name} = $this;
        }
    }

    public function terminate() {
    }
}
