<?php
/* @var \Osm\Ui\DataTables\Views\DataTable $view */

use Osm\Core\Exceptions\NotSupported;
use Osm\Ui\Buttons\Views\Button;

if (!$view->edit_route) {
    throw new NotSupported(m_("To use edit button column, fill in data table 'edit_url' property"));
}
?>
@include(Button::new(['alias' => "edit__{$view->item->id}", 'title' => $view->column->button_title,
    'url' => m_url($view->edit_route, ['id' => $view->item->id])]))

