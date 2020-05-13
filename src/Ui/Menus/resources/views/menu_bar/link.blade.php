<?php
/* @var \Osm\Ui\Menus\Views\LinkItem $view */
use Osm\Ui\Buttons\Views\Button;
?>
@include(Button::new([
    'alias' => 'button',
    'title' => $view->title,
    'icon' => $view->icon,
    'url' => $view->url,
    'color' => $view->button_color,
    'on_color' => $view->button_on_color,
    'outlined' => $view->button_outlined,
]))
