<?php
/* @var \Osm\Ui\PopupMenus\Views\PopupMenu $view */
?>
<span class="popup-menu__title">{{ $view->item->title }}</span>

@if ($view->item->shortcut)
    <span class="popup-menu__shortcut" aria-label=", shortcut: {{ $view->item->shortcut }}">
        {{ $view->item->shortcut }}
    </span>
@endif

