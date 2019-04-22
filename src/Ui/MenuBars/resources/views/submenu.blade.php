<?php
/* @var \Manadev\Ui\MenuBars\Views\MenuBar $view */
use Manadev\Ui\Buttons\Views\Button;
use Manadev\Ui\PopupMenus\Views\PopupMenu;
?>
<li class="menu-bar__item -submenu" id="{{ "{$view->id_}__{$view->item->name}___item" }}">
    @include(PopupMenu::new(['id_' => "{$view->id_}__{$view->item->name}",
        'items' => $view->item->items, 'modifier' => $view->modifier]))
    @include(Button::new(['id_' => "{$view->id_}__{$view->item->name}___button", 'title' => $view->item->title,
        'modifier' => $view->item->modifier]))
</li>