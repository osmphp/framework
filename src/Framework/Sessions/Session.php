<?php

namespace Osm\Framework\Sessions;

use Illuminate\Support\Str;
use Osm\Framework\Areas\Area;
use Osm\Core\Object_;

/**
 * Constructor arguments:
 *
 * @property Area $area @required
 *
 * Calculated properties:
 *
 * @property string $id @required @part
 */
class Session extends Object_
{
    const REFERERS_REMEMBERED = 100;

    /**
     * @required @part
     * @var string[]
     */
    public $referers = [];

    protected function default($property) {
        switch ($property) {
            case 'id': return Str::random(40);
        }

        return parent::default($property);
    }

    public function registerReferer($referer) {
        $exceeded = count($this->referers) - static::REFERERS_REMEMBERED;
        for ($i = 0; $i < $exceeded; $i++) {
            array_shift($this->referers);
        }

        $key = md5($referer);
        $this->referers[$key] = $referer;
        return $key;
    }
}