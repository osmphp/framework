<?php
/* @var \Osm\Ui\Menus\Views\CommandItem $view */
use Osm\Ui\Buttons\Views\Button;
?>
@include(Button::new([
    'alias' => 'button',
    'title' => $view->title,
    'icon' => $view->icon,
    'main' => $view->main,
    'dangerous' => $view->dangerous,
]))
