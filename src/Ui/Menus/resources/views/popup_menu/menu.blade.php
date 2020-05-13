<?php
/* @var \Osm\Ui\Menus\Views\PopupMenu $view */

use Osm\Ui\Menus\Views\DelimiterItem;

$delimiter = '';

?>
<nav class="popup-menu {{ $view->on_color_ }} {{ $view->color_ }}" id="{{$view->id_}}">
    <ul class="popup-menu__items">
        @foreach ($view->items_ as $child)
            @if (!$child->empty)
                <li id="{{$child->id_}}" class="popup-menu__item {{ $child->type }}
                        {{ $child->main ? '-main' : '' }}
                        {{ $child->dangerous ? '-dangerous' : '' }}
                        {{ $child->hidden ? '-hidden' : '' }}
                        {{ $delimiter }}">
                    @include ($child)
                </li>
                @if ($child->view_model)
                    {!! $child->view_model_script !!}
                @endif
            @endif
            <?php $delimiter = $child instanceof DelimiterItem ? '-delimiter' : ''; ?>
        @endforeach
    </ul>
</nav>
{!! $view->view_model_script !!}