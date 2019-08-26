<?php
/* @var \Osm\Ui\SnackBars\Views\SnackBar $view */
?>
<div class="snack-bar @if ($side ?? null) -with-sidebar @endif snack-bar-panel__item" id="{{ $view->id_ }}">
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
{!! $view->view_model_script !!}