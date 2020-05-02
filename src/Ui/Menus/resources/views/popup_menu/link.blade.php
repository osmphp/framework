<?php
/* @var \Osm\Ui\Menus\Views\LinkItem $view */
?>
<a class="popup-menu__link" href="{{ $view->url }}">
    @if ($view->icon)
        <span class="popup-menu__icon">
            <i class="icon {{ $view->icon }}"></i>
        </span>
    @endif
    <span class="popup-menu__title">{{ $view->title }}</span>
</a>
