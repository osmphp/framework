<?php
/* @var \Manadev\Ui\MenuBars\Views\MenuBar $view */

use Manadev\Ui\Buttons\Views\Button;
?>
<nav class="menu-bar {{$view->modifier}}" id="{{$view->id_}}">
    <ul class="menu-bar__items">
        @foreach ($view->items_ as $item)
            <?php /* @var \Manadev\Ui\Menus\Items\Item $item */ $view->item = $item; ?>
            @include($item->type_->menu_bar_template, ['view' => $view])
        @endforeach
    </ul>
    <div class="menu-bar__show-more">
        @include(Button::new(['alias' => 'show_more', 'icon' => '-menu']))
    </div>
</nav>
{!! $view->view_model_script !!}