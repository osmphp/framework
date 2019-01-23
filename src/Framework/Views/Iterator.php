<?php

namespace Manadev\Framework\Views;

use Manadev\Core\Object_;

class Iterator extends Object_
{
    public function iterateData($data) {
        foreach ($data as $property => $value) {
            if ($value instanceof View) {
                yield $property => $value;
                continue;
            }

            if (is_array($value)) {
                foreach ($value as $index => $item) {
                    if ($item instanceof View) {
                        yield $property . '_' . $index => $item;
                    }
                }
            }
        }
    }

    public function iterateProperties(View $view) {
        foreach ($this->iterateData($view) as $property => $value) {
            if ($property == 'parent') {
                continue;
            }

            yield $property => $value;
        }
    }

    public function iterateView(View $view) {
        yield '' => $view;

        foreach ($this->iterateProperties($view) as $property => $value) {
            yield $property => $value;
        }
    }

    public function iterateRecursively(View $view, callable $filter = null) {
        if (!$filter || $filter($view) !== false) {
            yield $view;
        }

        foreach ($this->iterateProperties($view) as $child) {
            foreach ($this->iterateRecursively($child, $filter) as $result) {
                yield $result;
            }
        }
    }
}