<?php

namespace Manadev\Framework\Console;

use Illuminate\Console\OutputStyle;
use Manadev\Core\Exceptions\NotImplemented;
use Manadev\Core\Object_;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @property string $name @required @part
 * @property string $description @required @part
 * @property array $options @part
 * @property array $arguments @part
 * @property Option[] $options_ @required @part
 * @property Argument[] $arguments_ @required @part
 * @property InputInterface $input @temp
 * @property OutputStyle $output @temp
 */
class Command extends Object_
{
    public function default($property) {
        switch ($property) {
            case 'options_': return $this->createOptions($this->unset('options'));
            case 'arguments_': return $this->createArguments($this->unset('arguments'));
        }
        return parent::default($property);
    }

    public function run() {
        throw new NotImplemented();
    }

    protected function createOptions($options) {
        $result = [];
        if (!$options) {
            return $result;
        }

        foreach ($options as $name => $option) {
            $result[$name] = Option::new($option, $name, $this);
        }

        return $result;
    }

    protected function createArguments($arguments) {
        $result = [];
        if (!$arguments) {
            return $result;
        }

        foreach ($arguments as $name => $argument) {
            $result[$name] = Argument::new($argument, $name, $this);
        }

        return $result;
    }
}