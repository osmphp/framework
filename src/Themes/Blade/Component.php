<?php

declare(strict_types=1);

namespace Osm\Framework\Themes\Blade;

use Illuminate\View\Component as BaseComponent;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Runtime\Traits\ComputedProperties;
use function Osm\view;

/**
 * @property string $template
 */
class Component extends BaseComponent
{
    use ComputedProperties;

    /** @noinspection PhpMissingReturnTypeInspection */
    public function render() {
        return view($this->template);
    }

    /** @noinspection PhpUnused */
    protected function get_template(): string {
        throw new NotImplemented();
    }
}