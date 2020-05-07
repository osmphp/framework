<?php
/* @var \Osm\Ui\Menus\Views\MenuBar $view */

use Osm\Ui\Buttons\Views\Button;use Osm\Ui\Menus\Items\Item;
?>
<nav class="menu-bar {{$view->modifier}}" id="{{$view->id_}}">
    <ul class="menu-bar__items">
        @foreach ($view->items_ as $child)
            @if (!$child->empty)
                <li class="menu-bar__item {{ $child->type }}" id="{{$child->id_}}">
                    @include($child)
                </li>
                @if ($child->view_model)
                    {!! $child->view_model_script !!}
                @endif
            @endif
        @endforeach
    </ul>
    <div class="menu-bar__show-more">
        @include($view->show_more)
        @include($view->mobile_menu)
    </div>
</nav>
{!! $view->view_model_script !!}