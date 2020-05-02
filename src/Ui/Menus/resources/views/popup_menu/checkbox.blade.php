<?php
/* @var \Osm\Ui\Menus\Views\CheckboxItem $view */
?>
<span class="popup-menu__icon">
    <i class="icon @if($view->checked)-checked @endif"></i>
</span>
<span class="popup-menu__title">{{ $view->title }}</span>

@if ($view->shortcut)
    <span class="popup-menu__shortcut" aria-label=", shortcut: {{ $view->shortcut }}">
        {{ $view->shortcut }}
    </span>
@endif


