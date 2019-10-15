<?php
/* @var \Osm\Ui\MenuBars\Views\MenuBar $view */

use Osm\Ui\Buttons\Views\Button;use Osm\Ui\Menus\Items\Item;
?>
<nav class="menu-bar {{$view->modifier}}" id="{{$view->id_}}">
    <ul class="menu-bar__items">
        @foreach ($view->items_ as $item)
            <?php /* @var Item $item */ $view->item = $item; ?>
            @if (!$item->deleted)
                @include($item->type_->menu_bar_template, ['view' => $view])
            @endif
        @endforeach
    </ul>
    <div class="menu-bar__show-more">
        @include(Button::new(['alias' => 'show_more', 'icon' => '-menu']))
    </div>
</nav>
{!! $view->view_model_script !!}