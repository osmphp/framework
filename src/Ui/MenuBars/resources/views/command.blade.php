<?php
/* @var \Manadev\Ui\MenuBars\Views\MenuBar $view */
use Manadev\Ui\Buttons\Views\Button;
?>
<li class="menu-bar__item -command">
    @include(Button::new(['id_' => "{$view->id_}__{$view->item->name}", 'title' => $view->item->title,
        'modifier' => $view->item->modifier]))
</li>