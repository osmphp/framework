<?php
/* @var \Osm\Ui\Forms\Views\CheckboxField $view */
?>
<div class="field -checkbox {{ $view->modifier }}" id="{{ $view->id_ }}">
    <div class="field__body">
        <input class="field__value" type="checkbox"
            id="{{ $view->id_ }}__value" @if ($view->value) checked @endif
            name="{{$view->prefix}}{{$view->name}}">
        <label class="field__title"
            for="{{ $view->id_ }}__value">{{ $view->title }}</label>
    </div>
</div>
{!! $view->view_model_script !!}