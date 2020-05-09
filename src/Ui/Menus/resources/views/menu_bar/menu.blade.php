<?php
/* @var \Osm\Ui\Menus\Views\MenuBar $view */

use Osm\Ui\Menus\Views\DelimiterItem;

$delimiter = '';

?>
<nav id="{{$view->id_}}" class="menu-bar {{$view->color}} {{$view->main ? '-main' : ''}}">
    <ul class="menu-bar__items">
        @foreach ($view->items_ as $child)
            @if (!$child->empty)
                <li id="{{$child->id_}}" class="menu-bar__item {{ $child->type }}
                    {{ $child->hidden ? '-hidden' : '' }}
                    {{ $delimiter }}" >
                    @include($child)
                </li>
                @if ($child->view_model)
                    {!! $child->view_model_script !!}
                @endif
            @endif
            <?php $delimiter = $child instanceof DelimiterItem ? '-delimiter' : ''; ?>
        @endforeach
    </ul>
    <div class="menu-bar__show-more -hidden">
        @include($view->show_more)
        @include($view->mobile_menu)
    </div>
</nav>
{!! $view->view_model_script !!}