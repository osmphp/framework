<?php
/* @var \Osm\Ui\Forms\Views\ImageValue $view */
?>
<div id="{{ $view->id_ }}" class="form-section__image-field">
    <div class="form-section__placeholder" style="width: 100px; height: 100px;"></div>
    <img class="form-section__image" width="100" height="100"
        @if ($view->value) src="{{ osm_thumbnail($view->value, 100, 100) }}" @endif>
</div>

