<?php

declare(strict_types=1);

namespace Osm\Framework\Blade;

use Illuminate\View\Compilers\ComponentTagCompiler as BaseComponentTagCompiler;

class ComponentTagCompiler extends BaseComponentTagCompiler
{
    /**
     * Guess the class name for the given component.
     *
     * @param  string  $component
     * @return string
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function guessClassName(string $component)
    {
        return '';
    }

}