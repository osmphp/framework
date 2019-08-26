<?php
/* @var \Osm\Ui\PopupMenus\Views\PopupMenu $view */
?>
<a class="popup-menu__link" href="{{ $view->item->url }}">
    @if ($view->item->icon)
        <span class="popup-menu__icon">
            <i class="icon {{ $view->item->icon }}"></i>
        </span>
    @endif

    <span class="popup-menu__title">{{ $view->item->title }}</span>
</a>
