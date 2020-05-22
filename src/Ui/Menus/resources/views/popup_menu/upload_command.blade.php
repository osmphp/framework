<?php
/* @var \Osm\Ui\Menus\Views\UploadCommandItem $view */
?>
@if ($view->icon)
    <span class="popup-menu__icon">
        <i class="icon {{ $view->icon }}"></i>
    </span>
@endif
<span class="popup-menu__title">{{ $view->title }}</span>

@if ($view->shortcut)
    <span class="popup-menu__shortcut" aria-label=", shortcut: {{ $view->shortcut }}">
        {{ $view->shortcut }}
    </span>
@endif
<input type="file" class="popup-menu__file-input"
       @if ($view->accept) accept="{{ $view->accept }}" @endif
       @if ($view->multi_select) multiple @endif
>


