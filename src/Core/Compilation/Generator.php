<?php

namespace Manadev\Core\Compilation;

use Manadev\Core\Object_;

/**
 * @property Class_ $class_ @temp
 */
class Generator extends Object_
{
    const WIDTH = 120;

    /**
     * @return string
     */
    public function generate() {
        if ($this->class_->abstract) {
            return '';
        }

        $envNamespace = "Generated\\" . ucfirst(env('APP_ENV'));
        return <<<EOT

namespace $envNamespace\\{$this->class_->namespace} {
    class {$this->class_->short_name} extends \\{$this->class_->name}{
{$this->generateUseStatements()}
{$this->generateMethods()}
    }
}
EOT;
    }

    /**
     * @return string
     */
    protected function generateUseStatements() {
        $output = '';
        $length = 8;

        foreach ($this->class_->traits as $trait) {
            if (!$output) {
                $output .= '        use ';
                $length += strlen('use ');
            }
            else {
                $output .= ', ';
                $length += strlen(', ');
                if ($length + strlen($trait) >= static::WIDTH - strlen(', ')) {
                    $output .= "\n            ";
                    $length = 12;
                }
            }
            $output .= "\\" . $trait;
            $length += strlen("\\" . $trait);
        }

        if (empty($this->class_->aliases)) {
            $output .= ';';
        }
        else {
            $output .= "\n        {{$this->generateAliases()}\n        }";
        }

        return $output;
    }

    /**
     * @return string
     */
    protected function generateAliases() {
        $output = '';

        /* @var string[] $defaultClasses */
        $defaultClasses = [];
        foreach ($this->class_->aliases as $alias) {
            if (isset($defaultClasses[$alias->member])) {
                $defaultClassName = $defaultClasses[$alias->member];
                $output .= "\n            \\{$defaultClassName}::{$alias->member} insteadof \\{$alias->class_};";
            }
            else {
                $defaultClasses[$alias->member] = $alias->class_;
            }
        }

        foreach ($this->class_->aliases as $alias) {
            $output .= "\n            \\{$alias->class_}::{$alias->member} as {$alias->name};";
        }

        return $output;
    }

    /**
     * @return string
     */
    protected function generateMethods() {
        $output = '';

        foreach ($this->class_->methods as $method) {
            if ($method->uses_func_get_args) {
                if (!$method->trait) {
                    $output .= "\n\n        {$method->access} function __parent_{$method->name} (...\$args) {" .
                        "\n            return parent::{$method->name}(...\$args);\n        }";
                }
                $output .= "\n\n        {$method->access} function {$method->name} ({$method->parameters}) {" .
                    "\n            \$args = func_get_args();" .
                    "{$this->generateOpenParameterListTraitCall($method, 0)}\n        }";
            }
            else {
                if (!$method->trait) {
                    $output .= "\n\n        {$method->access} function __parent_{$method->name} ({$method->parameters}) {" .
                        "\n            return parent::{$method->name}({$method->arguments});\n        }";
                }
                $output .= "\n\n        {$method->access} function {$method->name} ({$method->parameters}) {" .
                    "{$this->generateTraitCall($method, 0)}\n        }";
            }
        }

        return $output;
    }

    /**
     * @param Method $method
     * @param int $adviceIndex
     * @return string
     */
    protected function generateTraitCall($method, $adviceIndex) {
        $indent = str_repeat(' ', ($adviceIndex + 3) * 4);

        if ($adviceIndex >= count($method->advices)) {
            return $method->trait
                ? "\n{$indent}return \$this->" .
                    str_replace('\\', '_', $method->trait) . '_' . $method->name .
                    "({$method->arguments});"
                : "\n{$indent}return \$this->__parent_{$method->name}({$method->arguments});";
        }

        $advice = $method->advices[count($method->advices) - $adviceIndex - 1];
        $comma = $method->arguments ? ', ' : '';

        $output = "\n{$indent}return \$this->{$advice} (function({$method->parameters}) {";
        $output .= $this->generateTraitCall($method, $adviceIndex + 1);
        $output .= "\n{$indent}}{$comma}{$method->arguments});";

        return $output;
    }

    /**
     * @param Method $method
     * @param int $adviceIndex
     * @return string
     */
    protected function generateOpenParameterListTraitCall($method, $adviceIndex) {
        $indent = str_repeat(' ', ($adviceIndex + 3) * 4);

        if ($adviceIndex >= count($method->advices)) {
            return $method->trait
                ? "\n{$indent}return \$this->" .
                    str_replace('\\', '_', $method->trait) . '_' . $method->name .
                    "(...\$args);"
                : "\n{$indent}return \$this->__parent_{$method->name}(...\$args);";
        }

        $advice = $method->advices[count($method->advices) - $adviceIndex - 1];
        $comma = $method->arguments ? ', ' : '';

        $output = "\n{$indent}return \$this->{$advice} (function({$method->parameters}) use (\$args){";
        $output .= $this->generateOpenParameterListTraitCall($method, $adviceIndex + 1);
        $output .= "\n{$indent}}{$comma}{$method->arguments});";

        return $output;
    }
}