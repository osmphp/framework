<?php
/* @var \Osm\Ui\Sidebars\Views\Sidebar $view */
?>
@if (!$view->empty)
    <div id="{{ $view->id_ }}"
        class="sidebar {{ $view->css_class }} {{ $view->on_color_ }} {{ $view->color_ }}"
    >
        @foreach ($view->items_ as $child)
            @if (!$child->empty)
                <aside class="sidebar__item">
                    @include ($child)
                </aside>
            @endif
        @endforeach

    </div>

@endif
