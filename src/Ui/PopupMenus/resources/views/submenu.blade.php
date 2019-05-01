<?php
/* @var \Manadev\Ui\PopupMenus\Views\PopupMenu $view */
use Manadev\Ui\PopupMenus\Views\PopupMenu;
?>
@include(PopupMenu::new(['id_' => "{$view->id_}__{$view->item->name}",
    'items' => $view->item->items, 'modifier' => $view->modifier]))
<span class="popup-menu__title">{{ $view->item->title }}</span>