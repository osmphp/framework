<?php

namespace Manadev\Framework\Views\Views;

use Manadev\Framework\Views\View;

class Container extends View
{
    public $template = 'Manadev_Framework_Views.container';

    /**
     * @var View[] @required @part
     */
    public $views = [];
}