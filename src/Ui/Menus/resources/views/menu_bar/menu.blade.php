<?php
/* @var \Osm\Ui\Menus\Views\MenuBar $view */

use Osm\Ui\Menus\Views\DelimiterItem;

$delimiter = '';

?>
<nav id="{{$view->id_}}" class="menu-bar {{ $view->on_color_ }}
        {{$view->color_}} -{{ $view->horizontal_align }}"
        {!! $view->model('menu-bar') !!}>
    <ul class="menu-bar__items">
        @foreach ($view->items_ as $child)
            @if (!$child->empty)
                <li id="{{$child->id_}}" class="menu-bar__item {{ $child->type }}
                    {{ $child->hidden ? '-hidden' : '' }}
                    {{ $delimiter }}"
                    {!! $view->model('menu-bar__item', $child->model) !!} >
                    @include($child)
                </li>
            @endif
            <?php $delimiter = $child instanceof DelimiterItem ? '-delimiter' : ''; ?>
        @endforeach
    </ul>
    <div class="menu-bar__show-more _hidden">
        @include($view->show_more)
        @include($view->mobile_menu)
    </div>
</nav>