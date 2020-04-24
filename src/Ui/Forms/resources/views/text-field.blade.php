<?php
/* @var \Osm\Ui\Forms\Views\TextField $view */
?>
<textarea class="field__value" id="{{ $view->id_ }}__value" rows="1"
    name="{{$view->prefix}}{{$view->name}}">{{ $view->value }}</textarea>
