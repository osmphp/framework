<?php
/* @var \Osm\Ui\PopupMenus\Views\PopupMenu $view */
?>
<nav class="popup-menu {{$view->modifier}}" id="{{$view->id_}}">
    <ul class="popup-menu__items">
        @foreach ($view->items_ as $item)
            <?php /* @var \Osm\Ui\Menus\Items\Item $item */ $view->item = $item; ?>
            <li class="popup-menu__item -{{$view->item->type}} {{$view->item->modifier}}"
                @if($view->item->name) id="{{$view->id_}}__{{$view->item->name}}__item" @endif>
                @if ($view->item->icon)
                    <span class="popup-menu__icon">
                        <i class="icon {{ $view->item->icon }}"></i>
                    </span>
                @elseif ($view->items_can_be_checked)
                    <span class="popup-menu__icon @if (!$item->checked) -hidden @endif">
                        <i class="icon -checked"></i>
                    </span>
                @endif

                @include($item->type_->popup_menu_template, ['view' => $view])
            </li>
        @endforeach
    </ul>
</nav>
{!! $view->view_model_script !!}