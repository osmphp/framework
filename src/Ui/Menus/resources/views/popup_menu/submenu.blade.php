<?php
/* @var \Osm\Ui\Menus\Views\SubmenuItem $view */
?>
@if ($view->icon)
    <span class="popup-menu__icon">
        <i class="icon {{ $view->icon }}"></i>
    </span>
@endif
<span class="popup-menu__title">{{ $view->title }}</span>
<span class="popup-menu__submenu-indicator">
        <i class="icon -submenu -right"></i>
</span>
@include($view->submenu)
