<?php

namespace Osm\Data\Formulas\Functions;

use Osm\Core\Object_;

/**
 * @property string $name @required @part
 * @property array $args @part
 * @property Argument[] $args_ @required @part
 * @property string $return_data_type @required @part
 */
class Function_ extends Object_
{
    protected function default($property) {
        switch ($property) {
            case 'args_': return $this->getArgs();
        }
        return parent::default($property);
    }

    protected function getArgs() {
        $result = [];
        if (!$this->args) {
            return $result;
        }

        foreach ($this->args as $data) {
            $result[] = Argument::new($data, null, $this);
        }
        return $result;
    }
}