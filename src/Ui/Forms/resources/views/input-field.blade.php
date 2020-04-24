<?php
/* @var \Osm\Ui\Forms\Views\InputField $view */
?>
<input class="field__value" type="{{ $view->type }}"
    id="{{ $view->id_ }}__value" value="{{ $view->value }}"
    name="{{$view->prefix}}{{$view->name}}"
    @if ($view->placeholder) placeholder="{{ $view->placeholder }}" @endif
    @if ($view->autocomplete) autocomplete="{{ $view->autocomplete }}" @endif
    @if ($view->step) step="{{ $view->step }}" @endif
>
