<?php

namespace Manadev\Ui\Buttons\Views;


use Manadev\Framework\Views\View;

/**
 * @property string $title @part
 * @property string $icon @part
 * @property string $url @part
 */
class Button extends View
{
    public $template = 'Manadev_Ui_Buttons.button';
}