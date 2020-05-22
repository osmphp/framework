<?php
/* @var \Osm\Ui\Menus\Views\UploadCommandItem $view */
use Osm\Ui\Buttons\Views\UploadButton;
?>
@include(UploadButton::new([
    'alias' => 'button',
    'title' => $view->title,
    'icon' => $view->icon,
    'color' => $view->button_color,
    'on_color' => $view->button_on_color,
    'outlined' => $view->button_outlined,
    'accept' => $view->accept,
    'multi_select' => $view->multi_select,
]))
