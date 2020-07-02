<?php
/* @var \Osm\Ui\SnackBars\Views\SnackBar $view */
?>
<div id="{{ $view->id_ }}" class="snack-bar snack-bar-panel__item
    {{ $view->modifier }}
    {{ $view->on_color_ }} {{ $view->color_ }}
    @if ($side ?? null) -with-sidebar @endif"
>
    @if ($side ?? null)
    <div class="snack-bar__sidebar">
        {{ $side }}
    </div>
    @endif
    <div class="snack-bar__message">@{{ message }}</div>
    @if ($footer ?? null)
        <div class="snack-bar__footer">
            {{ $footer }}
        </div>
    @endif
</div>
