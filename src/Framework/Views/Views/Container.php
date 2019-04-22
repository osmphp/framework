<?php

namespace Manadev\Framework\Views\Views;

use Manadev\Framework\Views\View;

/**
 * @property string $element @part
 */
class Container extends View
{
    public $template = 'Manadev_Framework_Views.container';

    /**
     * @required @part
     * @var View[]
     */
    public $views = [];
}