<?php
/* @var \Osm\Ui\Tables\Views\Table $view */

use Osm\Core\Exceptions\NotSupported;
use Osm\Ui\Buttons\Views\Button;

if (!$view->edit_route) {
    throw new NotSupported(osm_t("To use edit button column, fill in data table 'edit_url' property"));
}
?>
@include(Button::new(['alias' => "edit__{$view->item->id}",
    'icon' => $view->column->button_icon,
    'title' => $view->column->button_title,
    'url' => osm_url($view->edit_route, ['id' => $view->item->id])]))

