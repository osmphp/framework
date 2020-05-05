<?php
/* @var \Osm\Ui\Menus\Views\LinkItem $view */
use Osm\Ui\Buttons\Views\Button;
?>
@include(Button::new([
    'alias' => 'button',
    'title' => $view->title,
    'icon' => $view->icon,
    'url' => $view->url,
]))
