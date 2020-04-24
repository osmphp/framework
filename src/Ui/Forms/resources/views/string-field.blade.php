<?php
/* @var \Osm\Ui\Forms\Views\StringField $view */
?>
<input class="field__value" type="text"
    id="{{ $view->id_ }}__value" value="{{ $view->value }}"
    name="{{$view->prefix}}{{$view->name}}"
    @if ($view->placeholder) placeholder="{{ $view->placeholder }}" @endif
    @if ($view->autocomplete) autocomplete="{{ $view->autocomplete }}" @endif
>
