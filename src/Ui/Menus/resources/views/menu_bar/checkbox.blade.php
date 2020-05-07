<?php
/* @var \Osm\Ui\Menus\Views\CheckboxItem $view */
use Osm\Ui\Buttons\Views\Button;
?>
@include(Button::new([
    'alias' => 'button',
    'title' => $view->title,
    'icon' => $view->checked ? '-checked' : '-empty',
    'main' => $view->main,
    'dangerous' => $view->dangerous,
]))
