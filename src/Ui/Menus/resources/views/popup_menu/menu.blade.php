<?php
/* @var \Osm\Ui\Menus\Views\PopupMenu $view */
?>
<nav class="popup-menu {{ $view->color }}" id="{{$view->id_}}">
    <ul class="popup-menu__items">
        @foreach ($view->items_ as $child)
            @if (!$child->empty)
                <li id="{{$child->id_}}" class="popup-menu__item {{ $child->type }}
                        {{ $child->main ? '-main' : '' }}
                        {{ $child->dangerous ? '-dangerous' : '' }}">
                    @include ($child)
                </li>
                @if ($child->view_model)
                    {!! $child->view_model_script !!}
                @endif
            @endif
        @endforeach
    </ul>
</nav>
{!! $view->view_model_script !!}