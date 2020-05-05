<?php
/* @var \Osm\Ui\Menus\Views\CheckboxItem $view */
use Osm\Ui\Buttons\Views\Button;
?>
@include(Button::new([
    'alias' => 'button',
    'title' => $view->title,
    'style' => $view->checked ? '-filled': '',
]))
